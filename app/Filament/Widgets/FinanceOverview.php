<?php

namespace App\Filament\Widgets;

use App\Enums\InquiryStatus;
use App\Enums\PaymentStatus;
use App\Models\Inquiry;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectExpense;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class FinanceOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $previousStart = $start->copy()->subMonthNoOverflow();
        $previousEnd = $previousStart->copy()->endOfMonth();

        $revenue = $this->revenueBetween($start, $end);
        $previousRevenue = $this->revenueBetween($previousStart, $previousEnd);

        $expense = $this->expenseBetween($start, $end);
        $margin = $revenue - $expense;

        $activeProjects = Project::query()->active()->count();
        $newInquiries = Inquiry::query()->where('status', InquiryStatus::New)->count();
        $pendingPayments = Payment::query()->where('status', PaymentStatus::Pending)->count();
        $pendingAmount = (int) Payment::query()->where('status', PaymentStatus::Pending)->sum('amount');

        return [
            Stat::make('Omzet bulan ini', rupiah($revenue))
                ->description($this->growthLabel($revenue, $previousRevenue))
                ->descriptionIcon($revenue >= $previousRevenue ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenue >= $previousRevenue ? 'success' : 'danger')
                ->chart($this->sparkline(fn (Carbon $from, Carbon $to) => $this->revenueBetween($from, $to))),

            Stat::make('Total biaya bulan ini', rupiah($expense))
                ->description('Domain, hosting, lisensi, outsource')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('warning')
                ->chart($this->sparkline(fn (Carbon $from, Carbon $to) => $this->expenseBetween($from, $to))),

            Stat::make('Margin bersih bulan ini', rupiah($margin))
                ->description('Omzet terverifikasi dikurangi biaya')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($margin >= 0 ? 'success' : 'danger')
                ->chart($this->sparkline(fn (Carbon $from, Carbon $to) => $this->revenueBetween($from, $to) - $this->expenseBetween($from, $to))),

            Stat::make('Proyek aktif', $activeProjects)
                ->description('Antrian, pengerjaan, revisi, menunggu pelunasan')
                ->descriptionIcon('heroicon-m-folder-open')
                ->color('primary'),

            Stat::make('Brief baru', $newInquiries)
                ->description($newInquiries > 0 ? 'Perlu ditindaklanjuti' : 'Semua sudah ditindaklanjuti')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($newInquiries > 0 ? 'warning' : 'gray'),

            Stat::make('Menunggu verifikasi', $pendingPayments)
                ->description($pendingPayments > 0 ? rupiah($pendingAmount).' perlu dicek' : 'Tidak ada antrean')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingPayments > 0 ? 'warning' : 'gray'),
        ];
    }

    /** Hanya pembayaran terverifikasi yang dihitung sebagai omzet. */
    private function revenueBetween(Carbon $from, Carbon $to): int
    {
        return (int) Payment::query()
            ->where('status', PaymentStatus::Verified)
            ->whereBetween('paid_at', [$from->toDateString(), $to->toDateString()])
            ->sum('amount');
    }

    private function expenseBetween(Carbon $from, Carbon $to): int
    {
        return (int) ProjectExpense::query()
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->sum('amount');
    }

    private function growthLabel(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? 'Bulan lalu belum ada omzet' : 'Belum ada omzet bulan ini';
        }

        $percentage = round((($current - $previous) / $previous) * 100);

        return ($percentage >= 0 ? 'Naik ' : 'Turun ').abs($percentage).'% dari bulan lalu';
    }

    /**
     * Deret 6 bulan terakhir untuk grafik mini di kartu statistik.
     *
     * @param  callable(Carbon, Carbon): int  $resolver
     * @return array<int, int>
     */
    private function sparkline(callable $resolver): array
    {
        $points = [];

        for ($i = 5; $i >= 0; $i--) {
            $from = Carbon::now()->startOfMonth()->subMonthsNoOverflow($i);
            $points[] = $resolver($from, $from->copy()->endOfMonth());
        }

        return $points;
    }
}
