<div class="col-md-12 mb-3">
    <form method="GET" id="filter-form">
        <div class="row">
            <div class="form-group col-md-3">
                <label>Filter Status</label>
                <div class="input-group">
                    <select class="form-control" name="filter_status" id="filter_status">
                        <option value="" selected>Semua...</option>
                        @foreach($status as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Filter Tipe</label>
                <div class="input-group">
                    <select class="form-control" name="filter_type" id="filter_type">
                        <option value="" selected>Semua...</option>
                        @foreach($type as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Filter Pembayaran Public</label>
                <div class="input-group">
                    <select class="form-control" name="filter_is_public" id="filter_is_public">
                        <option value="" selected>Semua...</option>
                        @foreach($is_public as $key => $value)
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
