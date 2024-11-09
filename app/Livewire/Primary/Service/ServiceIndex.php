<?php

namespace App\Livewire\Primary\Service;

use App\Models\ServiceCategory;
use App\Models\Service AS ServiceModel;
use App\Models\ServiceCategoryType;
use Livewire\Component;

class ServiceIndex extends Component
{
    public $selectServiceType, $selectCategory, $selectStatusService, $search;
    public $readyToLoadTables = false;


    public function loadTables()
    {
        $this->readyToLoadTables = true;
    }

    public function render()
    {
        $status = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $serviceTypes = ServiceCategoryType::query()
            ->whereIn('is_active', [1])
            ->select('slug')
            ->orderBy('position', 'ASC')
            ->get();

        $serviceCategories = $this->readyToLoadTables ? ServiceCategory::query()
            ->select('id', 'name', 'service_type')
            ->when($this->selectServiceType <> '', function ($query) {
                $query
                    ->where('service_type', $this->selectServiceType);
            })
            ->active()
            ->orderBy('name', 'asc')
            ->get()
            ->toArray() : [];

        $serviceCategory = $this->selectCategory ? ServiceCategory::query()
            ->active()
            ->select('id', 'name', 'service_type')
            ->when($this->selectServiceType <> '', function ($query) {
                $query
                    ->where('service_type', $this->selectServiceType);
            })
            ->when($this->selectCategory, function ($query) {
                return $query->where('id', $this->selectCategory);
            })
            ->orderBy('name', 'asc')
            ->get()
            ->toArray() : [];

        $services = [];
        foreach ($serviceCategory as $key => $value) {
            $services[$key] = $this->selectCategory ? ServiceModel::query()
                ->select('id', 'name', 'price', 'is_active')
                ->when($this->selectServiceType <> '', function ($query) {
                    $query
                        ->where('service_type', $this->selectServiceType);
                })
                ->when($this->search <> '', function ($query) {
                    $query->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->selectStatusService <> '', function ($query) {
                    $query->where('is_active', $this->selectStatusService);
                })
                ->where('service_category_id', $value['id'])
                ->orderByRaw("CAST(JSON_EXTRACT(price,'$.public') AS UNSIGNED) ASC")
                ->get()
                ->toArray() : [];
        }
        return view('livewire.primary.service.service-index', compact('services', 'serviceCategories', 'serviceCategory', 'status'), [
            'serviceCategory' => $serviceCategory,
            'services' => $services,
            'serviceCategories' => $serviceCategories,
            'status' => $status,
        ]);
    }
}
