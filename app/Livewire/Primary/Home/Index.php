<?php

namespace App\Livewire\Primary\Home;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $search, $page, $serviceCategoryType, $serviceCategories;

    public function mount($page, $serviceCategoryType)
    {
        $this->page = $page;
        $this->serviceCategoryType = $serviceCategoryType;
    }

    public function render()
    {
        dump($this->search);
        $this->serviceCategories = DB::table('service_categories')
            ->where('is_active', 1)
            // ->where('service_type', $this->page)
            ->select('service_type', 'name', 'slug', 'brand', 'img')
            ->when($this->search <> '', function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name', 'ASC')
            ->get();

        return view('livewire.primary.home.index');
    }

    public function like($name)
    {
        $this->search = $name;
        dd($this->search);
    }

    public function test()
    {

    }
}
