<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Http\Requests\StorePaymentProofRequest;
use App\Mail\NewPaymentProofMail;
use App\Models\Payment;
use App\Models\Project;
use App\Support\SiteSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function show(Project $project, SiteSettings $site): View
    {
        return view('invoice.show', $this->invoiceData($project, $site) + [
            'qrisUrl' => $site->qrisUrl(),
            'bankAccounts' => $site->activeBankAccounts(),
        ]);
    }

    public function pdf(Project $project, SiteSettings $site): Response
    {
        $qrisPath = $site->get('qris_image');

        $pdf = Pdf::loadView('invoice.pdf', $this->invoiceData($project, $site) + [
            'bankAccounts' => $site->activeBankAccounts(),
            // dompdf membaca gambar dari berkas lokal, bukan URL, agar tidak perlu akses jaringan.
            'qrisFile' => $qrisPath && Storage::disk('public')->exists($qrisPath)
                ? Storage::disk('public')->path($qrisPath)
                : null,
        ])
            ->setPaper('a4')
            ->setOption([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'chroot' => storage_path('app/public'),
                'defaultFont' => 'DejaVu Sans',
            ]);

        $filename = 'Invoice-'.Str::slug($project->title).'-'.substr($project->invoice_code, 0, 8).'.pdf';

        return $pdf->download($filename);
    }

    public function storeProof(StorePaymentProofRequest $request, Project $project): RedirectResponse
    {
        // Bukti pembayaran disimpan di disk privat, tidak pernah bisa diakses lewat URL publik.
        $path = $request->file('proof')->store('bukti-pembayaran', 'private');

        $payment = $project->payments()->create([
            'type' => $this->resolveType($project, (int) $request->validated('amount')),
            'amount' => (int) $request->validated('amount'),
            'method' => $request->validated('method'),
            'bank_account_id' => $request->validated('bank_account_id'),
            'paid_at' => $request->validated('paid_at'),
            'note' => $request->validated('note'),
            'proof_path' => $path,
            'status' => PaymentStatus::Pending,
        ]);

        $this->notifyAdmin($payment);

        return redirect()
            ->route('invoice.show', $project->invoice_code)
            ->with('proof_uploaded', true);
    }

    /**
     * @return array{project: Project, payments: Collection, site: SiteSettings}
     */
    private function invoiceData(Project $project, SiteSettings $site): array
    {
        $project->load(['client', 'package']);

        return [
            'project' => $project,
            'payments' => $project->payments()
                ->with('bankAccount')
                ->orderByDesc('paid_at')
                ->orderByDesc('id')
                ->get(),
            'site' => $site,
        ];
    }

    /** Jenis pembayaran ditentukan otomatis supaya klien tidak perlu memilih istilah teknis. */
    private function resolveType(Project $project, int $amount): PaymentType
    {
        if ($project->total_paid <= 0) {
            return PaymentType::DownPayment;
        }

        return $amount >= $project->remaining
            ? PaymentType::Settlement
            : PaymentType::Instalment;
    }

    /** Kegagalan kirim email tidak boleh membatalkan bukti yang sudah tersimpan. */
    private function notifyAdmin(Payment $payment): void
    {
        $recipient = config('superduperweb.admin_email');

        if (blank($recipient)) {
            return;
        }

        try {
            Mail::to($recipient)->send(new NewPaymentProofMail($payment));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim notifikasi bukti pembayaran.', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
