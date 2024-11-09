<?php

namespace App\Http\Controllers\Primary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $page = [
            'title' => 'Reviews',
            'breadcrumb' => [
                'first' => 'Reviews',
            ]
        ];

        return view('primary.review.index', compact('page'));
    }
}
