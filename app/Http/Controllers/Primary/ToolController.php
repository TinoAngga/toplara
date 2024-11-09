<?php

namespace App\Http\Controllers\Primary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index(Request $request, string $type)
    {
        if (!in_array($type, ['mobile-legends-win-rate', 'mobile-legends-win-lose', 'mobile-legends-magic-wheel', 'mobile-legends-point-zodiac'])) abort(404);
        if ($type == 'mobile-legends-win-rate') {
            $title = 'Hitung Win Rate Mobile Legends';
            $view = 'primary.tool.partials.mobile-legends.win-rate';
        } else if ($type == 'mobile-legends-win-lose') {
            $title = 'Hitung Win Lose Mobile Legends';
            $view = 'primary.tool.partials.mobile-legends.win-lose';
        } else if ($type == 'mobile-legends-magic-wheel') {
            $title = 'Hitung Magic Wheel Mobile Legends';
            $view = 'primary.tool.partials.mobile-legends.magic-wheel';
        } else if ($type == 'mobile-legends-point-zodiac') {
            $title = 'Hitung Poin Zodiac Mobile Legends';
            $view = 'primary.tool.partials.mobile-legends.point-zodiac';
        } else {
            abort(404);
        }
        $page = [
            'title' => $title,
            'breadcrumb' => [
                'first' => 'Tool',
                'second' => $title
            ]
        ];
        return view($view, compact('page', 'type'));
    }
}
