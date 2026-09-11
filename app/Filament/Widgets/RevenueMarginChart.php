<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\ProjectExpense;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueMarginChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Omzet vs Margin 12 Bulan Terakhir';

    protected ?string $description = 'Omzet dihitung dari pembayaran terverifikasi. Margin = omzet dikurangi biaya proyek.';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 'full';

    public function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $labels = [];
        $revenues = [];
        $expenses = [];
        $margins = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonthsNoOverflow($i);
            $from = $month->toDateString();
            $to = $month->copy()->endOfMonth()->toDateString();

            $revenue = (int) Payment::query()
                ->where('status', PaymentStatus::Verified)
                ->whereBetween('paid_at', [$from, $to])
                ->sum('amount');

            $expense = (int) ProjectExpense::query()
                ->whereBetween('expense_date', [$from, $to])
                ->sum('amount');

            $labels[] = $month->translatedFormat('M Y');
            $revenues[] = $revenue;
            $expenses[] = $expense;
            $margins[] = $revenue - $expense;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omzet',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(37, 99, 235, 0.85)',
                    'borderColor' => '#2563EB',
                    'borderRadius' => 6,
                    'order' => 2,
                ],
                [
                    'label' => 'Biaya',
                    'data' => $expenses,
                    'backgroundColor' => 'rgba(248, 113, 113, 0.75)',
                    'borderColor' => '#EF4444',
                    'borderRadius' => 6,
                    'order' => 3,
                ],
                [
                    'label' => 'Margin',
                    'type' => 'line',
                    'data' => $margins,
                    'backgroundColor' => 'rgba(250, 204, 21, 0.25)',
                    'borderColor' => '#FACC15',
                    'borderWidth' => 3,
                    'tension' => 0.35,
                    'fill' => false,
                    'order' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'bottom'],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        // Skala dipadatkan ke satuan juta rupiah agar sumbu tetap terbaca di layar HP.
                        'callback' => new RawJs('function (value) { return "Rp " + (value / 1000000).toLocaleString("id-ID") + " jt"; }'),
                    ],
                ],
            ],
        ];
    }
}
