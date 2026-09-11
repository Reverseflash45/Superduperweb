<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class TermsController extends Controller
{
    public function show(): View
    {
        return view('terms', [
            'content' => setting('terms_content'),
        ]);
    }
}
