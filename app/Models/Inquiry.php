<?php

namespace App\Models;

use App\Enums\InquiryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inquiry extends Model
{
    use HasFactory;

    protected $table = 'inquiries';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['status' => InquiryStatus::class];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /** Ringkasan brief untuk dikirim sebagai pesan WhatsApp otomatis. */
    public function toWhatsappMessage(): string
    {
        $lines = [
            'Halo SuperDuperWeb, saya mau konsultasi. Berikut brief saya:',
            '',
            "Nama: {$this->name}",
        ];

        if ($this->business_name) {
            $lines[] = "Nama usaha: {$this->business_name}";
        }

        $lines[] = "Jenis layanan: {$this->service_type}";

        if ($this->budget_range) {
            $lines[] = "Kisaran budget: {$this->budget_range}";
        }

        if ($this->deadline) {
            $lines[] = "Target selesai: {$this->deadline}";
        }

        $lines[] = '';
        $lines[] = 'Kebutuhan:';
        $lines[] = $this->description;

        return implode("\n", $lines);
    }
}
