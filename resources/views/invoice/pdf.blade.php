@php
    use App\Enums\PaymentStatus;

    $totalPaid = $project->total_paid;
    $remaining = $project->remaining;
    $isPaidOff = $project->is_paid_off;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $project->title }}</title>
    <style>
        /* dompdf tidak mendukung flexbox/grid, jadi tata letak memakai tabel. */
        @page { margin: 28px 32px; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #0F172A;
            line-height: 1.5;
            margin: 0;
        }

        table { width: 100%; border-collapse: collapse; }
        td, th { vertical-align: top; }

        .muted { color: #64748B; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .small { font-size: 9.5px; }

        .brand { font-size: 20px; font-weight: bold; color: #0F172A; }
        .brand span { color: #2563EB; }

        .header { border-bottom: 2px solid #2563EB; padding-bottom: 14px; }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-paid { background-color: #D1FAE5; color: #065F46; }
        .badge-unpaid { background-color: #FEF9C3; color: #854D0E; }
        .badge-verified { background-color: #D1FAE5; color: #065F46; }
        .badge-pending { background-color: #FEF9C3; color: #854D0E; }
        .badge-rejected { background-color: #FEE2E2; color: #991B1B; }

        .section { margin-top: 22px; }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
            margin-bottom: 7px;
        }

        .box { border: 1px solid #E2E8F0; border-radius: 8px; padding: 14px; }

        .lines td { padding: 7px 0; border-bottom: 1px solid #F1F5F9; }
        .lines tr:last-child td { border-bottom: none; }

        .total-row td {
            border-top: 2px solid #0F172A;
            padding-top: 10px;
            font-size: 15px;
            font-weight: bold;
        }

        .history th {
            background-color: #F8FAFC;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748B;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
        }
        .history td { padding: 9px 10px; border-bottom: 1px solid #F1F5F9; }

        .footer {
            margin-top: 26px;
            padding-top: 12px;
            border-top: 1px solid #E2E8F0;
            color: #64748B;
            font-size: 9.5px;
            text-align: center;
        }
    </style>
</head>
<body>

<table class="header">
    <tr>
        <td style="width: 55%;">
            <p class="brand" style="margin: 0;">Super<span>Duper</span>Web</p>
            <p class="muted small" style="margin: 4px 0 0;">
                Jasa Pembuatan Website &amp; Aplikasi<br>
                @if ($site->email()){{ $site->email() }}<br>@endif
                @if ($site->whatsappNumber())WhatsApp: +{{ $site->whatsappNumber() }}@endif
            </p>
        </td>
        <td class="right" style="width: 45%;">
            <p style="margin: 0; font-size: 17px; font-weight: bold;">INVOICE</p>
            <p class="muted small" style="margin: 4px 0 0;">
                No. INV-{{ Str::upper(Str::substr($project->invoice_code, 0, 8)) }}<br>
                Dicetak {{ now()->translatedFormat('d F Y') }}
            </p>
            <p style="margin: 8px 0 0;">
                <span class="badge {{ $isPaidOff ? 'badge-paid' : 'badge-unpaid' }}">
                    {{ $isPaidOff ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
            </p>
        </td>
    </tr>
</table>

<table class="section">
    <tr>
        <td style="width: 50%; padding-right: 12px;">
            <p class="section-title">Ditagihkan kepada</p>
            <div class="box">
                <p class="bold" style="margin: 0;">{{ $project->client?->name }}</p>
                @if ($project->client?->business_name)
                    <p style="margin: 2px 0 0;">{{ $project->client->business_name }}</p>
                @endif
                @if ($project->client?->whatsapp)
                    <p class="muted small" style="margin: 4px 0 0;">WhatsApp: {{ $project->client->whatsapp }}</p>
                @endif
                @if ($project->client?->email)
                    <p class="muted small" style="margin: 2px 0 0;">{{ $project->client->email }}</p>
                @endif
                @if ($project->client?->address)
                    <p class="muted small" style="margin: 4px 0 0;">{{ $project->client->address }}</p>
                @endif
            </div>
        </td>
        <td style="width: 50%; padding-left: 12px;">
            <p class="section-title">Detail proyek</p>
            <div class="box">
                <p class="bold" style="margin: 0;">{{ $project->title }}</p>
                <table class="small" style="margin-top: 6px;">
                    @if ($project->package)
                        <tr><td class="muted" style="width: 45%;">Paket</td><td>{{ $project->package->name }}</td></tr>
                    @endif
                    <tr><td class="muted">Status</td><td>{{ $project->status->getLabel() }}</td></tr>
                    @if ($project->start_date)
                        <tr><td class="muted">Tanggal mulai</td><td>{{ $project->start_date->translatedFormat('d F Y') }}</td></tr>
                    @endif
                    @if ($project->deadline)
                        <tr><td class="muted">Target selesai</td><td>{{ $project->deadline->translatedFormat('d F Y') }}</td></tr>
                    @endif
                </table>
            </div>
        </td>
    </tr>
</table>

<div class="section">
    <p class="section-title">Rincian tagihan</p>
    <table class="lines">
        <tr>
            <td>Total nilai proyek</td>
            <td class="right bold">{{ rupiah($project->deal_price) }}</td>
        </tr>
        <tr>
            <td>Sudah dibayar (terverifikasi)</td>
            <td class="right" style="color: #047857;">&minus; {{ rupiah($totalPaid) }}</td>
        </tr>
        @if ($project->total_pending > 0)
            <tr>
                <td>Menunggu verifikasi</td>
                <td class="right" style="color: #B45309;">{{ rupiah($project->total_pending) }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td>Sisa tagihan</td>
            <td class="right">{{ rupiah($remaining) }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <p class="section-title">Riwayat pembayaran</p>
    @if ($payments->isEmpty())
        <div class="box muted">Belum ada pembayaran yang tercatat.</div>
    @else
        <table class="history">
            <thead>
                <tr>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 20%;">Jenis</th>
                    <th style="width: 27%;">Metode</th>
                    <th style="width: 18%;">Status</th>
                    <th style="width: 15%;" class="right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->paid_at?->translatedFormat('d M Y') ?: '-' }}</td>
                        <td>{{ $payment->type->getLabel() }}</td>
                        <td>
                            {{ $payment->method->getLabel() }}
                            @if ($payment->bankAccount)
                                <span class="muted small">({{ $payment->bankAccount->bank_name }})</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ match ($payment->status) {
                                PaymentStatus::Verified => 'badge-verified',
                                PaymentStatus::Pending => 'badge-pending',
                                PaymentStatus::Rejected => 'badge-rejected',
                            } }}">{{ $payment->status->getLabel() }}</span>
                        </td>
                        <td class="right bold">{{ rupiah($payment->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@if ($remaining > 0)
    <div class="section">
        <p class="section-title">Cara membayar</p>
        <table>
            <tr>
                @if ($qrisFile)
                    <td style="width: 32%; padding-right: 12px;">
                        <div class="box" style="text-align: center;">
                            <p class="bold small" style="margin: 0 0 6px;">Scan QRIS</p>
                            <img src="{{ $qrisFile }}" alt="QRIS" style="width: 130px;">
                        </div>
                    </td>
                @endif
                <td style="width: {{ $qrisFile ? '68%' : '100%' }};">
                    @if ($bankAccounts->isNotEmpty())
                        <div class="box">
                            <p class="bold small" style="margin: 0 0 8px;">Transfer bank</p>
                            <table class="small">
                                @foreach ($bankAccounts as $bank)
                                    <tr>
                                        <td style="width: 22%; padding: 4px 0;" class="bold">{{ $bank->bank_name }}</td>
                                        <td style="padding: 4px 0;">
                                            {{ $bank->account_number }}
                                            <span class="muted">a.n. {{ $bank->account_holder }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <p class="muted small" style="margin-top: 10px;">
            Setelah membayar, unggah bukti transfer lewat halaman invoice online kamu agar kami bisa segera memverifikasi.
        </p>
    </div>
@endif

<div class="footer">
    Invoice ini dibuat otomatis oleh sistem {{ $site->name() }} dan sah tanpa tanda tangan basah.<br>
    Ketentuan layanan berlaku sesuai Syarat &amp; Ketentuan yang tercantum di {{ route('terms') }}
</div>

</body>
</html>
