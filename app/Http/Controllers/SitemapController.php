<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sitemap hanya memuat halaman publik.
     * Halaman invoice sengaja tidak pernah dimasukkan karena bersifat privat per klien.
     */
    public function index(): Response
    {
        $urls = [
            ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('portfolio.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('consultation.create'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('terms'), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        foreach (Portfolio::query()->published()->ordered()->get() as $portfolio) {
            $urls[] = [
                'loc' => route('portfolio.show', $portfolio->slug),
                'lastmod' => $portfolio->updated_at?->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
