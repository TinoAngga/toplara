<!-- My Orders Start -->
<div class="tab-pane fade" id="v-pills-order" role="tabpanel" aria-labelledby="v-pills-order-tab">
    <div class="card m-b-30 shadow">
        <div class="card-header bg-primary">
            <h5 class="card-title-custom mb-0 text-white"><i class="mdi mdi-cart-outline mr-2"></i> Pesanan Saya</h5>
        </div>
        <div class="card-body">
            @foreach ($orderHistory as $key => $value)
            <div class="order-box">
                <div class="card border m-b-30 shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5 class="float-start">INVOICE : #{{ $value->invoice }}</h5>
                            </div>
                            <div class="col-sm-6">
                                <h6 class="mb-0 float-end">Total : <strong>Rp {{ currency($value->price) }}</strong></h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Layanan</th>
                                        <th scope="col">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ format_datetime($value->created_at) }}</td>
                                        <td>{{ $value->service->name }}</td>
                                        <td>Rp {{ currency($value->price) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-theme-2 border-white">
                        <div class="row">
                            <div class="col-sm-6">
                                <h6 class="mb-0 float-start"><a class="btn btn-primary font-16" href="{{ route('order.invoice', $value->invoice) }}"><i class="mdi mdi-file-document-box-multiple-outline mr-2"></i>Invoice</a></h6>
                            </div>
                            <div class="col-sm-6">
                                <h5 class="mb-0 float-end">Status : {!! badgeStatus($value->status, $value->is_paid) !!}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <center class="mt-3"><a href="{{ route('order.history') }}" class="btn btn-primary shadow">Lihat semua pesanan</a></center>

        </div>
    </div>
</div>
<!-- My Orders End -->
