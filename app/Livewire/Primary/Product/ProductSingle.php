<?php

namespace App\Livewire\Primary\Product;

use App\Models\PaymentMethod;
use App\Models\Review;
use App\Models\Service;
use Livewire\Component;
use App\Models\ServiceCategory;
use App\Models\ServiceSubCategory;

class ProductSingle extends Component
{
    public $category, $serviceType;
    public $services = [];
    public $payments = [];
    public $reviews = [];
    public $readyToLoadServices = false;
    public $whatsappNumber;
    public $whatsappNumber1;

    public function mount($serviceType, $category)
    {
        $this->serviceType = $serviceType;
        $this->category = $category;
        $this->whatsappNumber = $this->getWhatsAppNumber(); // Panggil fungsi saat mount
        $this->whatsappNumber1 = $this -> getWhatsappNumber1();
    }

    public function getWhatsAppNumber()
    {
        $hour = now()->hour; // Dapatkan jam saat ini dari server

        // Logika pemilihan nomor WhatsApp berdasarkan jam
        if ($hour >= 6 && $hour < 17) {
            return '6289518595626'; // WhatsApp jam 06:00 - 17:00
        } else {
            return '6289518595626'; // WhatsApp jam 17:00 - 06:00 (sama dengan jam sebelumnya)
        }
    }
    
    
public function getWhatsAppNumber1() 
{
    $hour = now()->hour; // Dapatkan jam saat ini dari server

    // Logika pemilihan nomor WhatsApp berdasarkan jam
    if ($hour >= 6 && $hour < 14) {
        return '6288228616759'; // WhatsApp jam 06:00 - 14:00
    } elseif ($hour >= 14 && $hour < 22) {
        return '6285718405734'; // WhatsApp jam 14:00 - 22:00
    } else {
        return '6285180760077'; // WhatsApp jam 22:00 - 06:00
    }
}


    public function loadServices()
    {
        $this->readyToLoadServices = true;
    }

    public function render()
    {
        $category = $this->category;
        $this->services =  $this->readyToLoadServices ? ServiceSubCategory::query()
            ->select('id', 'name', 'slug')
            ->with([
                'service' => function ($query) use ($category) {
                    $query
                        ->where('service_category_id', $category->id)
                        ->where('is_active', 1)
                        ->select('id', 'sub_category', 'name', 'price')
                        ->orderByRaw("CAST(JSON_EXTRACT(price,'$.public') AS UNSIGNED) ASC");
                }
            ])
            ->whereHas('service', null, '>', 0)
            ->orderBy('position', 'ASC')
            ->get()
            ->toArray() : [];

        $this->payments =  $this->readyToLoadServices ? PaymentMethod::select('id', 'img', 'name', 'type')
            ->where(function ($query) {
                $query
                    ->where('is_active', 1)
                    ->whereNotIn('type', ['saldo'])
                    ->when(user() === false, function ($query) {
                        $query
                            ->where('is_public', 1);
                    });
            })
            ->get()
            ->toArray() : [];

        $this->reviews =  $this->readyToLoadServices ? Review::query()
            ->where('service_category_id', $category->id)
            ->where('is_published', 1)
            ->with('service:id,name,service_category_id', 'order:id,service_id,invoice,whatsapp_order,email_order')
            ->limit(5)
            ->get()
            ->toArray() : [];

        return view('livewire.primary.product.product-single', [
            'category' => $this->category,
            'whatsappNumber' => $this->whatsappNumber, // Kirim nomor WhatsApp ke view
        ]);
    }
}
