<?php

namespace App\Http\Controllers;

use App\Enums\PortfolioCategory;
use App\Models\Portfolio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request): View
    {
        $category = PortfolioCategory::tryFrom((string) $request->query('kategori'));

        $portfolios = Portfolio::query()
            ->published()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        // Jumlah proyek per kategori dipakai sebagai badge di tombol filter.
        $counts = Portfolio::query()
            ->published()
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('portfolio.index', [
            'portfolios' => $portfolios,
            'activeCategory' => $category,
            'counts' => $counts,
            'totalCount' => $counts->sum(),
        ]);
    }

    public function show(Portfolio $portfolio): View
    {
        abort_unless($portfolio->is_published, 404);

        $portfolio->load('images');

        $related = Portfolio::query()
            ->published()
            ->where('id', '!=', $portfolio->id)
            ->where('category', $portfolio->category)
            ->ordered()
            ->limit(3)
            ->get();

        return view('portfolio.show', [
            'portfolio' => $portfolio,
            'related' => $related,
        ]);
    }
}
