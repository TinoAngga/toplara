<?php

namespace App\Livewire\Primary\Home;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\ServiceCategoryType;

class HomeIndex extends Component
{
    public $search, $serviceCategoryTypeMenus, $serviceCategoryTypes, $serviceCategories, $selectServiceCategoryType = 'all';

    public function mount()
    {
        $this->init();
    }

    public function render()
    {

        return view('livewire.primary.home.home-index');
    }

    public function init()
    {
        $this->serviceCategoryTypeMenus = ServiceCategoryType::query()
            ->whereIn('is_active', [1])
            ->select('slug', 'name', 'id')
            ->orderBy('position', 'ASC')
            ->get();

        $this->serviceCategoryTypes = ServiceCategoryType::query()
            ->with(['categories' => function ($query) {
                $query
                    ->select('id', 'name', 'slug', 'service_type', 'img')
                    ->where('is_active', 1)
                    ->orderBy('name', 'ASC');
            }])
            ->whereIn('is_active', [1])
            ->select('slug', 'name', 'id')
            ->orderBy('position', 'ASC')
            ->get()
            ->toArray();
    }
}
