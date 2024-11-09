@extends('admin.layouts.app')

@section('content')
<!-- Start row -->
<div class="row">
    <div class="col-lg-4 col-sm-6 col-12">
        <div class="card">
            <div class="card-header align-items-start pb-2">
                <div>
                    <h2 class="font-weight-bolder">{{ 'Rp ' . currency($widget['orders']['sum']) }}</h2>
                    <p class="card-text">{{ currency($widget['orders']['count']) }} Pesanan</p>
                </div>
                <div class="avatar bg-light-primary p-50">
                    <div class="avatar-content">
                        <i data-feather="shopping-cart" class="font-medium-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6 col-12">
        <div class="card">
            <div class="card-header align-items-start pb-2">
                <div>
                    <h2 class="font-weight-bolder">{{ 'Rp ' . currency($widget['deposits']['sum']) }}</h2>
                    <p class="card-text">{{ currency($widget['deposits']['count']) }} Deposit</p>
                </div>
                <div class="avatar bg-light-primary p-50">
                    <div class="avatar-content">
                        <i class="mdi mdi-credit-card-outline font-medium-5"> </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6 col-12">
        <div class="card">
            <div class="card-header align-items-start pb-2">
                <div>
                    <h2 class="font-weight-bolder">{{ 'Rp ' . currency($widget['users']['sum']) }}</h2>
                    <p class="card-text">Total Saldo Member</p>
                </div>
                <div class="avatar bg-light-primary p-50">
                    <div class="avatar-content">
                        <i class="mdi mdi-account-multiple-outline font-medium-5"> </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-body">
    <form method="GET" action="{{ route('admin.dashboard') }}">
        <div class="row">
            <div class="col-md-3">
                <label for="end_date">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="end_time">Jam Akhir (HH:MM:SS)</label>
                <input type="time" id="end_time" name="end_time" class="form-control" value="{{ $endTime ?? '' }}" step="1">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
</div>


<!-- Display the paid services in a table -->
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">DATA PENJUALAN HDI ONLY</h4>
            </div>
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <!--<th>Service Code</th>-->
                                    <th>Name</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($widget['serviceCounts'] as $serviceCode => $data)
                                    <tr>
                                        <!--<td>{{ $serviceCode }}</td>-->
                                        <td style="font-weight: bold;">{{ $data['name'] }}</td>
                                        <td style="font-weight: bold;">{{ $data['count'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td  class="text-end" style="font-weight: bold;">Total</td>
                                    <td style="font-weight: bold;">{{ $widget['totalCount'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">DATA PENJUALAN RD ONLY</h4>
            </div>
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <!--<th>Service Code</th>-->
                                    <th>Name</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($widget['serviceCountss'] as $serviceCode => $data)
                                    <tr>
                                        <!--<td>{{ $serviceCode }}</td>-->
                                        <td style="font-weight: bold;">{{ $data['name'] }}</td>
                                        <td style="font-weight: bold;">{{ $data['count'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td  class="text-end" style="font-weight: bold;">Total</td>
                                    <td style="font-weight: bold;">{{ $widget['totalCounts'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">DATA PENJUALAN HDI KONVERSI</h4>
            </div>
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <!--<th>Service Code</th>-->
                                    <th>Name</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($widget['serviceCountsss'] as $serviceCode => $data)
                                    <tr>
                                        <!--<td>{{ $serviceCode }}</td>-->
                                        <td style="font-weight: bold;">{{ $data['name'] }}</td>
                                        <td style="font-weight: bold;">{{ $data['count'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td  class="text-end" style="font-weight: bold;">Total Penjualan HDI (M)</td>
                                    <td style="font-weight: bold;">{{ $widget['totalCountss'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">DATA PENJUALAN RD KONVERSI</h4>
            </div>
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <!--<th>Service Code</th>-->
                                    <th>Name</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($widget['sserviceCount'] as $serviceCode => $data)
                                    <tr>
                                        <!--<td>{{ $serviceCode }}</td>-->
                                        <td style="font-weight: bold;">{{ $data['name'] }}</td>
                                        <td style="font-weight: bold;">{{ $data['count'] }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td  class="text-end" style="font-weight: bold;">Total Penjualan RD (M)</td>
                                    <td style="font-weight: bold;">{{ $widget['ttotalCount'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- End row -->

@endsection
