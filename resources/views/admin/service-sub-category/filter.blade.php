<div class="col-md-12 mb-3">
    <form method="GET" id="filter-form">
        <div class="row">
            <div class="form-group col-md-6">
                <label>Filter Kategori</label>
                <div class="input-group">
                    <select class="form-control" name="filter_category" id="filter_category">
                        <option value="" selected>Semua...</option>
                        @foreach($category as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-prepend">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i></button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-6">
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