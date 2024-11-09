<?php

namespace App\Http\Controllers\Primary;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function sitemap($slug)
    {
        $pages = Page::query()
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();
        if ($pages == null ) abort(404);
        $page = [
            'title' => $pages->title,
            'breadcrumb' => [
                'first' => 'Sitemap',
                'second' => ucfirst(strtolower($pages->title))
            ]
        ];
        return view('primary.page', compact('page', 'pages'));
    }
}
