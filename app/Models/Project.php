<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'deal_price' => 'integer',
            'dp_percentage' => 'integer',
            'start_date' => 'date',
            'deadline' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            $project->invoice_code ??= static::generateInvoiceCode();
        });
    }

    /** Kode acak 40 karakter, dipakai sebagai URL invoice publik tanpa login. */
    public static function generateInvoiceCode(): string
    {
        do {
            $code = Str::lower(Str::random(40));
        } while (static::query()->where('invoice_code', $code)->exists());

        return $code;
    }

    public function getRouteKeyName(): string
    {
        return 'invoice_code';
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(ProjectExpense::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', array_column(ProjectStatus::activeStatuses(), 'value'));
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', ProjectStatus::Completed);
    }

    /**
     * Memo agregat per instance. Satu halaman invoice membaca total_paid lewat
     * remaining, margin, dan is_paid_off sekaligus; tanpa memo ini setiap
     * pembacaan menjalankan SUM() baru ke database.
     *
     * @var array<string, int>
     */
    protected array $totalsMemo = [];

    /** Buang memo agar agregat dihitung ulang setelah data pembayaran/biaya berubah. */
    public function forgetTotals(): static
    {
        $this->totalsMemo = [];

        return $this;
    }

    public function refresh(): static
    {
        return parent::refresh()->forgetTotals();
    }

    /** Total pembayaran terverifikasi — satu-satunya yang dihitung sebagai omzet. */
    public function getTotalPaidAttribute(): int
    {
        return $this->totalsMemo['paid'] ??= (int) $this->payments()
            ->where('status', PaymentStatus::Verified)
            ->sum('amount');
    }

    public function getTotalPendingAttribute(): int
    {
        return $this->totalsMemo['pending'] ??= (int) $this->payments()
            ->where('status', PaymentStatus::Pending)
            ->sum('amount');
    }

    public function getTotalExpenseAttribute(): int
    {
        return $this->totalsMemo['expense'] ??= (int) $this->expenses()->sum('amount');
    }

    public function getRemainingAttribute(): int
    {
        return max(0, $this->deal_price - $this->total_paid);
    }

    /** Margin = total pembayaran terverifikasi - total biaya. */
    public function getMarginAttribute(): int
    {
        return $this->total_paid - $this->total_expense;
    }

    public function getDpAmountAttribute(): int
    {
        return (int) round($this->deal_price * $this->dp_percentage / 100);
    }

    public function getIsPaidOffAttribute(): bool
    {
        return $this->deal_price > 0 && $this->total_paid >= $this->deal_price;
    }
}
