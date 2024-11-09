
<div class="col-md-12 mb-3">
    <form method="GET" id="filter-form">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Filter Waktu</label>
                <div class="input-group">
                    <input type="date" class="form-control text-white" name="filter_start_date" value="">
                    <div class="input-group-prepend">
                        <span class="input-group-text">sampai</span>
                    </div>
                    <input type="date" class="form-control" name="filter_end_date" value="">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label>Filter Kategori</label>
                <div class="input-group">
                    <select class="form-control" name="filter_category" id="filter_category">
                        <option value="" selected>Semua...</option>
                        @foreach($category as $key => $value)
                            <option value="{{ $key }}">{{ str_replace('-', ' ', ucfirst($value)) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label>Filter Tipe</label>
                <div class="input-group">
                    <select class="form-control" name="filter_type" id="filter_type">
                        <option value="" selected>Semua...</option>
                        @foreach($type as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label>Cari</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="search" id="search" placeholder="Ketik sesuatu..." value="{{ old('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>
