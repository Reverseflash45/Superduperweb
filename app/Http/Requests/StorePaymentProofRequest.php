<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use App\Models\BankAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1000', 'max:9999999999'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'bank_account_id' => [
                'nullable',
                'required_if:method,'.PaymentMethod::Transfer->value,
                Rule::exists(BankAccount::class, 'id')->where('is_active', true),
            ],
            'paid_at' => ['required', 'date', 'before_or_equal:today'],
            'note' => ['nullable', 'string', 'max:500'],
            'proof' => [
                'required',
                'file',
                'mimes:'.implode(',', config('superduperweb.proof_mimes')),
                'max:'.config('superduperweb.proof_max_kb'),
            ],

            // Honeypot.
            'website' => ['prohibited'],
        ];
    }

    public function attributes(): array
    {
        return [
            'amount' => 'nominal',
            'method' => 'metode pembayaran',
            'bank_account_id' => 'bank tujuan',
            'paid_at' => 'tanggal bayar',
            'note' => 'catatan',
            'proof' => 'bukti pembayaran',
        ];
    }

    public function messages(): array
    {
        return [
            'proof.mimes' => 'Bukti pembayaran harus berupa file JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran bukti pembayaran maksimal 2MB.',
            'bank_account_id.required_if' => 'Pilih bank tujuan transfer.',
            'paid_at.before_or_equal' => 'Tanggal bayar tidak boleh di masa depan.',
            'website.prohibited' => 'Pengiriman ditolak.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Klien biasanya mengetik "1.750.000"; simpan sebagai integer rupiah.
        $this->merge([
            'amount' => (int) preg_replace('/\D+/', '', (string) $this->input('amount')),
        ]);
    }
}
