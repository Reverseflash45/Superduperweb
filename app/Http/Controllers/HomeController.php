<?php

namespace App\Http\Controllers;

use App\Enums\PackageType;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Support\SiteSettings;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(SiteSettings $site): View
    {
        $packages = Package::query()->active()->ordered()->get();

        return view('home', [
            'projectPackages' => $packages->where('type', PackageType::Project)->values(),
            'maintenancePackages' => $packages->where('type', PackageType::Maintenance)->values(),
            'portfolios' => Portfolio::query()->published()->ordered()->limit(6)->get(),
            'testimonials' => Testimonial::query()->published()->ordered()->get(),
            'faqs' => Faq::query()->published()->ordered()->get(),
            'statistics' => $site->statistics(),
            'bankAccounts' => $site->activeBankAccounts(),
        ]);
    }
}
