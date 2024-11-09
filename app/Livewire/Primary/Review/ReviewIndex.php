<?php

namespace App\Livewire\Primary\Review;

use Livewire\Component;
use App\Models\Review; // Assuming you have a Review model
use Livewire\WithPagination;

class ReviewIndex extends Component
{
    use WithPagination;

    public $perPage = 6;
    protected $listeners = [
        'load-more' => 'loadMore'
    ];
    public function loadMore()
    {
        $this->perPage = $this->perPage + 6;
    }

    public function render()
    {
        // Load paginated reviews
        $reviews = Review::with('serviceCategory:id,name', 'service:id,name,service_category_id', 'order:id,created_at,whatsapp_order')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.primary.review.review-index', [
            'reviews' => $reviews
        ]);
    }

}
