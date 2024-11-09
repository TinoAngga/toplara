<div>
    <div class="row">
        @forelse ($reviews as $key => $value)
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <article class="d-flex flex-column mt-3"> <!-- Loop -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-bold">{{ hideString($value['order']['whatsapp_order']) }}</p>
                                <small>{{ $value['service']['name'] }}</small>
                                <p>
                                    <i>{{ $value['comment'] }}</i>
                                </p>
                            </div>
                            <div>
                                <div class="d-flex justify-content-end fs-5">
                                    @for ($i = 0; $i < $value['rating']; $i++)
                                    <span class="mdi mdi-star text-warning"></span>
                                    @endfor
                                </div>
                                <small>{{ format_datetime($value['created_at']) }}</small>
                            </div>
                        </div>
                        <hr class="border-3" style="opacity: 10%">
                    </article> <!-- Loop End -->
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">Belum ada ulasan</p>
        @endforelse
    </div>

    @if ($reviews->hasMorePages())
    <div class="d-flex justify-content-center">
        <button wire:click="loadMore" class="btn btn-primary">Load More</button>
    </div>
@endif
</div>
