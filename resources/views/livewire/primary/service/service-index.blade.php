
<div>
    <style>
        .bg-primary {
            background-color: var(--theme-color-5) !important;
        }
    </style>
    <div class="container my-5 py-5"  wire:init="loadTables">
        <div class="row justify-content-center">
            <div class="col-md-12 mb-3">
                <form method="GET" id="filter-form">
                    <div class="row">
                        <div class="form-group col-md-6 col-6">
                            <label>Filter Kategori</label>
                            <div class="input-group">
                                <select wire:loading.attr="disabled" class="form-control" wire:model.change="selectCategory">

                                    <option value="" selected>Pilih salah satu...</option>
                                    @foreach($serviceCategories as $key => $value)
                                        <option value="{{ $value['id'] }}">{{ $value['name'] . ' - ' . ucwords(str_replace('-', ' ', $value['service_type'])) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" disabled type="button"><i class="fa fa-filter"></i></button>
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-6">
                            <label>Filter Status</label>
                            <div class="input-group">
                                <select wire:loading.attr="disabled" class="form-control" wire:model.change="selectStatusService">
                                    <option value="" selected>Semua...</option>
                                    @foreach($status as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" disabled type="button"><i class="fa fa-filter"></i></button>
                            </div>
                        </div>
                        {{-- <div class="form-group col-md-12">
                            <label>Cari</label>
                            <div class="input-group">
                                <input type="text" class="form-control" wire:model="search" placeholder="Ketik sesuatu..." value="{{ old('search') }}">
                                <button class="btn btn-primary" disabled type="button"><i class="fa fa-search"></i></button>
                            </div>
                        </div> --}}
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="pt-3 pb-4">
                    <h5><i class="mdi mdi-format-list-bulleted"></i> Daftar Layanan</h5>
                    <span class="strip-primary"></span>
                </div>
                <div class="section">
                    <div class="card-body">
                        <div wire:loading>
                            <h5 class="text-center mb-2 mt-2 ">Loading...</h5>
                        </div>
                        <div class="table-responsive" wire:loading.class="d-none">
                            <table class="table table-bordered mb-0">
                                @forelse ($serviceCategory as $key => $value)
                                    <thead>
                                        <tr>
                                            <th colspan="8" class="bg-primary text-white text-center">{{ $value['name'] . ' - ' . str_replace('-', ' ', $value['service_type']) }}</th>
                                        </tr>
                                        <tr>
                                            <th title="ID" width="20" rowspan="2" class="text-center align-middle text-white text-nowrap">ID</th>
                                            <th title="LAYANAN" width="140" rowspan="2" class="text-center align-middle text-white text-nowrap">LAYANAN</th>
                                            <th title="HARGA" width="150" colspan="3" class="text-center text-white">HARGA</th>
                                            <th title="STATUS" class="text-center align-middle text-white" width="50" rowspan="2">STATUS</th>
                                        </tr>
                                        <tr>
                                            <th title="PUBLIK" width="150" class="text-center text-white">PUBLIK</th>
                                            <th title="RESELLER" width="150" class="text-center text-white">RESELLER</th>
                                            <th title="H2H" width="150" class="text-center text-white">H2H</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($services[$key]))
                                            @forelse ($services[$key] as $service)
                                                @php
                                                    $service['price'] = (array) $service['price']
                                                @endphp
                                                <tr>
                                                    <td class="text-white  text-nowrap">{{ $service['id'] }}</td>
                                                    <td class="text-white  text-nowrap">{{ $value['name'] . ' - ' . $service['name'] }}</td>
                                                    <td class="text-center text-white">{{ 'Rp ' . currency($service['price']['public']) }}</td>
                                                    <td class="text-center text-white">{{ 'Rp ' . currency($service['price']['reseller']) }}</td>
                                                    <td class="text-center text-white">{{ 'Rp ' . currency($service['price']['h2h']) }}</td>
                                                    <td class="text-center text-white">
                                                        @if ($service['is_active'] == 1)
                                                        <span class="badge badge-success">Aktif</span>
                                                        @else
                                                        <span class="badge badge-danger">Tidak Aktif</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    flush();
                                                @endphp
                                            @empty
                                                <tbody>
                                                    <tr>
                                                        <td colspan="6" class="text-center text-white"> Layanan tidak tersedia...</td>
                                                    </tr>
                                                </tbody>
                                            @endforelse
                                        @endif
                                    </tbody>
                                    @php
                                        flush();
                                    @endphp
                                @empty
                                    <tbody>
                                        <tr>
                                            <td rowspan="6" class="text-center text-white"> Harap pilih kategori terlebih dahulu...</td>
                                        </tr>
                                    </tbody>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
