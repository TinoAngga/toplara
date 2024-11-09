<?php

namespace App\Http\Controllers\Primary;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\ServiceCategoryType;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request('type') === 'getPopularCategory') {
            $data = '';
            foreach (getPopularCategory() as $key => $value) {
                $data .= '
                    <div class="col-md-12">
                        <a href="'.url('product/' . $value->service_type . '/category/' . $value->slug).'" target="_blank" role="link" style="font-size: 14px; text-decoration: none;">
                            <i class="mdi mdi-circle" style="font-size: 10px;"></i> ' .$value->name. '
                        </a>
                    </div>
                ';
            }
            return $data;
        }
        $banners = Banner::query()->get();
        $serviceCategoryType = ServiceCategoryType::query()
            ->whereIn('is_active', [1])
            ->select('slug', 'name', 'icon')
            ->orderBy('position', 'ASC')
            ->get();

        $populers = getPopularCategory(8);

        $page = [
            'type' => 'top-up-game',
            'title' => getConfig('bartitle'),
            'breadcrumb' => [
                'first' => 'Top Up Game'
            ]
        ];
        return view('primary.home.index', compact('page', 'banners', 'serviceCategoryType', 'populers'));
    }
}
