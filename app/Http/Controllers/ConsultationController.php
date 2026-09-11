<?php

namespace App\Http\Controllers;

use App\Enums\InquiryStatus;
use App\Http\Requests\StoreInquiryRequest;
use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use App\Models\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
{
    public function create(Request $request): View
    {
        return view('consultation.create', [
            'serviceTypes' => StoreInquiryRequest::SERVICE_TYPES,
            'budgetRanges' => StoreInquiryRequest::BUDGET_RANGES,
            'deadlines' => StoreInquiryRequest::DEADLINES,
            // Nama paket bisa dikirim lewat query string dari section Paket & Harga.
            'presetService' => $this->resolvePresetService($request->query('paket')),
        ]);
    }

    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        $inquiry = Inquiry::query()->create(
            $request->safe()->except(['website', 'rendered_at']) + [
                'status' => InquiryStatus::New,
                'ip_address' => $request->ip(),
            ],
        );

        $this->notifyAdmin($inquiry);

        // Brief sudah tersimpan; klien langsung diarahkan ke WhatsApp berisi ringkasannya.
        return redirect()
            ->route('consultation.create')
            ->with('inquiry_sent', wa_link($inquiry->toWhatsappMessage()));
    }

    private function resolvePresetService(?string $slug): ?string
    {
        if (blank($slug)) {
            return null;
        }

        $name = Package::query()->where('slug', $slug)->value('name');

        return in_array($name, StoreInquiryRequest::SERVICE_TYPES, true) ? $name : null;
    }

    /** Kegagalan kirim email tidak boleh membatalkan brief yang sudah tersimpan. */
    private function notifyAdmin(Inquiry $inquiry): void
    {
        $recipient = config('superduperweb.admin_email');

        if (blank($recipient)) {
            return;
        }

        try {
            Mail::to($recipient)->send(new NewInquiryMail($inquiry));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim notifikasi brief baru.', [
                'inquiry_id' => $inquiry->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
