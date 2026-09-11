@php
    use App\Enums\PaymentMethod;
    use App\Enums\PaymentStatus;

    $totalPaid = $project->total_paid;
    $remaining = $project->remaining;
    $pending = $project->total_pending;
    $progress = $project->deal_price > 0 ? min(100, (int) round($totalPaid / $project->deal_price * 100)) : 0;
    $isPaidOff = $project->is_paid_off;

    $statusSteps = ['antrian', 'pengerjaan', 'revisi', 'menunggu-pelunasan', 'selesai'];
    $currentStep = array_search($project->status->value, $statusSteps, true);
@endphp

<x-layouts.public
    :title="'Invoice '.$project->title"
    description="Halaman invoice pribadi untuk klien SuperDuperWeb."
    :noindex="true"
    :show-header="false">

    <div class="container-page py-10 sm:py-14">
        <div class="mx-auto max-w-4xl">

            {{-- Kepala invoice --}}
            <div class="flex flex-wrap items-start justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('favicon.svg') }}" alt="" class="h-10 w-10" width="40" height="40">
                    <span class="text-xl font-extrabold tracking-tight text-ink">
                        Super<span class="text-brand-600">Duper</span>Web
                    </span>
                </a>

                <a href="{{ route('invoice.pdf', $project->invoice_code) }}"
                   class="inline-flex items-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-ink transition hover:border-brand-300 hover:text-brand-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Unduh PDF
                </a>
            </div>

            @if (session('proof_uploaded'))
                <div role="status" class="mt-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-6 w-6 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <h2 class="text-base font-extrabold text-emerald-900">Bukti pembayaran terkirim</h2>
                            <p class="mt-1.5 text-sm leading-relaxed text-emerald-800">
                                Terima kasih. Kami akan cek dan verifikasi, biasanya dalam 1x24 jam pada hari kerja.
                                Status pembayaran di bawah akan berubah otomatis setelah diverifikasi.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-5">
                    <h2 class="text-sm font-bold text-red-900">Bukti pembayaran belum bisa dikirim:</h2>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Ringkasan tagihan --}}
            <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 sm:px-8">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Invoice</p>
                            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">{{ $project->title }}</h1>
                            <p class="mt-1.5 text-sm text-slate-500">
                                No. INV-{{ Str::upper(Str::substr($project->invoice_code, 0, 8)) }}
                            </p>
                        </div>

                        <span @class([
                            'inline-flex items-center rounded-full px-3.5 py-1.5 text-sm font-bold',
                            'bg-emerald-100 text-emerald-800' => $isPaidOff,
                            'bg-accent-100 text-amber-900' => ! $isPaidOff,
                        ])>
                            {{ $isPaidOff ? 'LUNAS' : 'Belum Lunas' }}
                        </span>
                    </div>
                </div>

                <div class="grid gap-6 px-6 py-6 sm:grid-cols-2 sm:px-8">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Ditagihkan kepada</h2>
                        <p class="mt-2 text-base font-bold text-ink">{{ $project->client?->name }}</p>
                        @if ($project->client?->business_name)
                            <p class="text-sm text-slate-600">{{ $project->client->business_name }}</p>
                        @endif
                        @if ($project->client?->address)
                            <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $project->client->address }}</p>
                        @endif
                    </div>

                    <div class="sm:text-right">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Detail proyek</h2>
                        <dl class="mt-2 space-y-1 text-sm">
                            @if ($project->package)
                                <div class="flex gap-2 sm:justify-end">
                                    <dt class="text-slate-500">Paket:</dt>
                                    <dd class="font-semibold text-ink">{{ $project->package->name }}</dd>
                                </div>
                            @endif
                            @if ($project->start_date)
                                <div class="flex gap-2 sm:justify-end">
                                    <dt class="text-slate-500">Mulai:</dt>
                                    <dd class="font-semibold text-ink">{{ $project->start_date->translatedFormat('d F Y') }}</dd>
                                </div>
                            @endif
                            @if ($project->deadline)
                                <div class="flex gap-2 sm:justify-end">
                                    <dt class="text-slate-500">Target selesai:</dt>
                                    <dd class="font-semibold text-ink">{{ $project->deadline->translatedFormat('d F Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Status pengerjaan --}}
                <div class="border-t border-slate-200 px-6 py-6 sm:px-8">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Status pengerjaan</h2>

                    @if ($project->status->value === 'batal')
                        <p class="mt-3 inline-flex items-center rounded-lg bg-red-50 px-3 py-2 text-sm font-bold text-red-700">
                            Proyek dibatalkan
                        </p>
                    @else
                        <ol class="mt-4 grid grid-cols-5 gap-1.5">
                            @foreach ($statusSteps as $index => $step)
                                @php
                                    $label = \App\Enums\ProjectStatus::from($step)->getLabel();
                                    $done = $currentStep !== false && $index <= $currentStep;
                                @endphp
                                <li class="flex flex-col gap-2">
                                    <span @class([
                                        'h-1.5 rounded-full',
                                        'bg-brand-600' => $done,
                                        'bg-slate-200' => ! $done,
                                    ])></span>
                                    <span @class([
                                        'text-[11px] font-semibold leading-tight sm:text-xs',
                                        'text-brand-700' => $done,
                                        'text-slate-400' => ! $done,
                                    ])>{{ $label }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>

                {{-- Angka tagihan --}}
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-6 sm:px-8">
                    <dl class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <dt class="text-slate-600">Total nilai proyek</dt>
                            <dd class="font-bold text-ink">{{ rupiah($project->deal_price) }}</dd>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <dt class="text-slate-600">Sudah dibayar (terverifikasi)</dt>
                            <dd class="font-bold text-emerald-700">− {{ rupiah($totalPaid) }}</dd>
                        </div>
                        @if ($pending > 0)
                            <div class="flex items-center justify-between text-sm">
                                <dt class="text-slate-600">Menunggu verifikasi</dt>
                                <dd class="font-bold text-amber-700">{{ rupiah($pending) }}</dd>
                            </div>
                        @endif
                        <div class="flex items-center justify-between border-t border-slate-200 pt-3">
                            <dt class="text-base font-bold text-ink">Sisa tagihan</dt>
                            <dd class="text-2xl font-extrabold tracking-tight {{ $remaining > 0 ? 'text-ink' : 'text-emerald-700' }}">
                                {{ rupiah($remaining) }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-4">
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-200" role="progressbar"
                             aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"
                             aria-label="Progres pembayaran">
                            <div class="h-full rounded-full bg-brand-600 transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="mt-2 text-xs font-semibold text-slate-500">{{ $progress }}% dari total tagihan sudah lunas</p>
                    </div>
                </div>
            </section>

            {{-- Riwayat pembayaran --}}
            <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <h2 class="border-b border-slate-200 px-6 py-4 text-base font-extrabold text-ink sm:px-8">
                    Riwayat pembayaran
                </h2>

                @if ($payments->isEmpty())
                    <p class="px-6 py-8 text-center text-sm text-slate-500 sm:px-8">
                        Belum ada pembayaran yang tercatat.
                    </p>
                @else
                    <ul role="list" class="divide-y divide-slate-100">
                        @foreach ($payments as $payment)
                            <li class="flex flex-wrap items-start justify-between gap-3 px-6 py-4 sm:px-8">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-ink">
                                        {{ $payment->type->getLabel() }} · {{ $payment->method->getLabel() }}
                                        @if ($payment->bankAccount)
                                            <span class="font-semibold text-slate-500">({{ $payment->bankAccount->bank_name }})</span>
                                        @endif
                                    </p>
                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ $payment->paid_at?->translatedFormat('d F Y') ?: '-' }}
                                    </p>
                                    @if ($payment->status === PaymentStatus::Rejected && $payment->rejection_reason)
                                        <p class="mt-1.5 rounded-lg bg-red-50 px-2.5 py-1.5 text-xs leading-relaxed text-red-700">
                                            Ditolak: {{ $payment->rejection_reason }}
                                        </p>
                                    @endif
                                </div>

                                <div class="text-right">
                                    <p class="text-base font-extrabold text-ink">{{ rupiah($payment->amount) }}</p>
                                    <span @class([
                                        'mt-1 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold',
                                        'bg-emerald-100 text-emerald-800' => $payment->status === PaymentStatus::Verified,
                                        'bg-accent-100 text-amber-900' => $payment->status === PaymentStatus::Pending,
                                        'bg-red-100 text-red-800' => $payment->status === PaymentStatus::Rejected,
                                    ])>{{ $payment->status->getLabel() }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            @if ($remaining > 0 && $project->status->value !== 'batal')
                {{-- Detail pembayaran: hanya muncul di halaman invoice, tidak pernah di landing page. --}}
                <section class="mt-8 grid gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
                        <h2 class="text-base font-extrabold text-ink">Cara membayar</h2>

                        @if ($qrisUrl)
                            <div class="mt-5">
                                <h3 class="text-sm font-bold text-slate-700">Scan QRIS</h3>
                                <div class="mt-3 inline-block rounded-xl border border-slate-200 bg-white p-3">
                                    <img src="{{ $qrisUrl }}" alt="Kode QRIS untuk pembayaran"
                                         class="h-52 w-52 object-contain">
                                </div>
                                <p class="mt-2 text-xs text-slate-500">
                                    Bisa dipindai lewat semua aplikasi e-wallet dan mobile banking.
                                </p>
                            </div>
                        @endif

                        @if ($bankAccounts->isNotEmpty())
                            <div class="mt-6">
                                <h3 class="text-sm font-bold text-slate-700">Transfer bank</h3>
                                <ul role="list" class="mt-3 space-y-3">
                                    @foreach ($bankAccounts as $bank)
                                        <li class="rounded-xl border border-slate-200 p-4">
                                            <div class="flex items-center gap-3">
                                                @if ($bank->logo)
                                                    <img src="{{ Storage::disk('public')->url($bank->logo) }}"
                                                         alt="{{ $bank->bank_name }}" class="h-7 w-auto object-contain">
                                                @else
                                                    <span class="text-sm font-extrabold text-slate-700">{{ $bank->bank_name }}</span>
                                                @endif
                                            </div>
                                            <p class="mt-2 font-mono text-lg font-bold tracking-wide text-ink">{{ $bank->account_number }}</p>
                                            <p class="text-sm text-slate-600">a.n. {{ $bank->account_holder }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <p class="mt-6 rounded-xl bg-brand-50 px-4 py-3 text-xs leading-relaxed text-slate-600">
                            Setelah transfer, unggah buktinya lewat form di samping supaya kami bisa segera verifikasi.
                            Bukti yang kamu unggah hanya bisa dilihat admin SuperDuperWeb.
                        </p>
                    </div>

                    {{-- Form unggah bukti --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">
                        <h2 class="text-base font-extrabold text-ink">Unggah bukti pembayaran</h2>

                        <form method="POST" action="{{ route('invoice.proof', $project->invoice_code) }}"
                              enctype="multipart/form-data" class="mt-5" x-data="{ method: @js(old('method', PaymentMethod::Transfer->value)) }">
                            @csrf

                            <div class="absolute left-[-9999px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                                <label for="proof-website">Jangan isi kolom ini</label>
                                <input type="text" id="proof-website" name="website" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label for="amount" class="block text-sm font-bold text-ink">
                                        Nominal yang ditransfer <span class="text-red-600" aria-hidden="true">*</span>
                                    </label>
                                    <div class="relative mt-2">
                                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-base font-semibold text-slate-500">Rp</span>
                                        <input type="text" id="amount" name="amount" required inputmode="numeric"
                                               value="{{ old('amount', $remaining) }}"
                                               class="block w-full rounded-xl border border-slate-300 py-3 pl-12 pr-4 text-base text-ink shadow-sm transition focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                                    </div>
                                    <p class="mt-1.5 text-xs text-slate-500">Sisa tagihan saat ini {{ rupiah($remaining) }}.</p>
                                    @error('amount')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <fieldset>
                                    <legend class="block text-sm font-bold text-ink">
                                        Metode pembayaran <span class="text-red-600" aria-hidden="true">*</span>
                                    </legend>
                                    <div class="mt-2 grid grid-cols-2 gap-3">
                                        @foreach (PaymentMethod::cases() as $methodOption)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="method" value="{{ $methodOption->value }}"
                                                       x-model="method" class="peer sr-only" required>
                                                <span class="flex items-center justify-center rounded-xl border-2 border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition peer-checked:border-brand-600 peer-checked:bg-brand-50 peer-checked:text-brand-700">
                                                    {{ $methodOption->getLabel() }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('method')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </fieldset>

                                @if ($bankAccounts->isNotEmpty())
                                    <div x-show="method === @js(PaymentMethod::Transfer->value)" x-cloak x-collapse>
                                        <label for="bank_account_id" class="block text-sm font-bold text-ink">
                                            Bank tujuan <span class="text-red-600" aria-hidden="true">*</span>
                                        </label>
                                        <select id="bank_account_id" name="bank_account_id"
                                                class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-base text-ink shadow-sm transition focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                                            <option value="">Pilih bank tujuan</option>
                                            @foreach ($bankAccounts as $bank)
                                                <option value="{{ $bank->id }}" @selected((int) old('bank_account_id') === $bank->id)>
                                                    {{ $bank->bank_name }} — {{ $bank->account_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bank_account_id')
                                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                <div>
                                    <label for="paid_at" class="block text-sm font-bold text-ink">
                                        Tanggal bayar <span class="text-red-600" aria-hidden="true">*</span>
                                    </label>
                                    <input type="date" id="paid_at" name="paid_at" required
                                           max="{{ now()->toDateString() }}"
                                           value="{{ old('paid_at', now()->toDateString()) }}"
                                           class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-base text-ink shadow-sm transition focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                                    @error('paid_at')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="proof" class="block text-sm font-bold text-ink">
                                        Bukti pembayaran <span class="text-red-600" aria-hidden="true">*</span>
                                    </label>
                                    <input type="file" id="proof" name="proof" required accept=".jpg,.jpeg,.png,.pdf"
                                           class="mt-2 block w-full rounded-xl border border-slate-300 text-sm text-slate-600 shadow-sm file:mr-4 file:rounded-l-xl file:border-0 file:bg-brand-50 file:px-4 file:py-3 file:text-sm file:font-bold file:text-brand-700 hover:file:bg-brand-100 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                                    <p class="mt-1.5 text-xs text-slate-500">Format JPG, PNG, atau PDF. Maksimal 2MB.</p>
                                    @error('proof')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="note" class="block text-sm font-bold text-ink">Catatan</label>
                                    <textarea id="note" name="note" rows="2"
                                              class="mt-2 block w-full rounded-xl border border-slate-300 px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200"
                                              placeholder="Opsional">{{ old('note') }}</textarea>
                                </div>
                            </div>

                            <button type="submit"
                                    class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 py-4 text-base font-extrabold text-white transition hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                                Kirim Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </section>
            @elseif ($isPaidOff)
                <section class="mt-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-8 text-center">
                    <h2 class="text-xl font-extrabold text-emerald-900">Tagihan sudah lunas</h2>
                    <p class="mx-auto mt-2 max-w-lg text-sm leading-relaxed text-emerald-800">
                        Terima kasih atas kepercayaannya. Source code, akses hosting, dan domain sepenuhnya
                        menjadi milik kamu. Kalau ada kendala teknis, kami siap bantu.
                    </p>
                    <a href="{{ wa_link('Halo SuperDuperWeb, saya mau tanya soal proyek '.$project->title.'.') }}"
                       target="_blank" rel="noopener"
                       class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">
                        <x-icon.whatsapp class="h-4 w-4" />
                        Hubungi kami
                    </a>
                </section>
            @endif

            <p class="mt-8 text-center text-xs leading-relaxed text-slate-500">
                Halaman ini bersifat pribadi. Jangan bagikan tautannya ke siapa pun selain pihak yang berkepentingan.<br>
                Ada pertanyaan soal tagihan?
                <a href="{{ wa_link('Halo SuperDuperWeb, saya mau tanya soal invoice proyek '.$project->title.'.') }}"
                   target="_blank" rel="noopener" class="font-bold text-brand-700 underline underline-offset-2">
                    Hubungi kami lewat WhatsApp
                </a>
            </p>
        </div>
    </div>
</x-layouts.public>
