<div class="col-md-12">
    <form method="GET" id="filter-form">
        <div class="row">
            <div class="form-group col-md-3">
                <label>Filter Pengguna</label>
                <select class="form-control" name="filter_user" id="filter_user" style="width: 100%">
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Filter Layanan</label>
                <select class="form-control" name="filter_service" id="filter_service" style="width: 100%">
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Filter Provider</label>
                <div class="input-group">
                    <select class="form-control" name="filter_provider" id="filter_provider">
                        <option value="" selected>Semua...</option>
                        @foreach($providers as $key => $value)
                            <option value="{{ $value->id }}">{{ ucfirst($value->name) }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Filter Tipe Pemesanan</label>
                <div class="input-group">
                    <select class="form-control" name="filter_order_type" id="filter_order_type">
                        <option value="" selected>Semua...</option>
                        @foreach($orderType as $key => $value)
                            <option value="{{ $key }}">{{ ucfirst($value) }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Filter Pembayaran</label>
                <div class="input-group">
                    <select class="form-control" name="filter_payment" id="filter_payment">
                        <option value="" selected>Semua...</option>
                        @foreach($payments as $key => $value)
                            <option value="{{ $value->id }}">{{ ucfirst($value->name) }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Filter Status</label>
                <div class="input-group">
                    <select class="form-control" name="filter_status" id="filter_status">
                        <option value="" selected>Semua...</option>
                        @foreach($status as $key => $value)
                            <option value="{{ $value }}">{{ ucfirst($value) }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Filter Lunas</label>
                <div class="input-group">
                    <select class="form-control" name="filter_paid" id="filter_paid">
                        <option value="" selected>Semua...</option>
                        @foreach($paid as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Cari</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="search" id="search" placeholder="Ketik sesuatu..." value="{{ old('search') }}">
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
        </div>
    </form>
</div>
