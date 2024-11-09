                <!-- My Wallet Start -->
                <div class="tab-pane fade" id="v-pills-wallet" role="tabpanel" aria-labelledby="v-pills-wallet-tab">
                    <div class="card m-b-30 shadow">
                        <div class="card-header bg-primary">
                            <h5 class="card-title-custom mb-0 text-white"><i class="mdi mdi-credit-card-outline mr-2"></i> Saldo</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-6 col-md-6 col-lg-4">
                                    <img src="{{ asset('cdn/wallet.svg') }}" class="img-fluid" alt="wallet">
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-sm-6">
                                    <h4 class="text-primary"><i class="mdi mdi-credit-card-outline mr-5"></i> {{ 'Rp ' . currency(Auth::user()->balance) }} </h4>
                                </div>
                                <div class="col-sm-6 d-flex justify-content-end">
                                    <p class="mb-0"><a href="{{ route('deposit.index') }}" class="btn btn-primary font-weight-bold font-16"><i class="feather icon-plus mr-2"></i>Deposit</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card m-b-30 shadow">
                        <div class="card-header bg-primary">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h5 class="card-title-custom mb-0 text-white"><i class="mdi mdi-scale-balance"></i> Mutasi Saldo</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="wallet-transaction-box">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-bordered">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th scope="col" class="text-white">#</th>
                                                <th scope="col" class="text-white">Waktu / Tanggal</th>
                                                <th scope="col" class="text-white">Tipe</th>
                                                <th scope="col" class="text-white">Kategori</th>
                                                <th scope="col" class="text-white">Deskripsi</th>
                                                <th scope="col" class="text-white">Nominal</th>
                                                <th scope="col" class="text-white">Sebelum</th>
                                                <th scope="col" class="text-white">Sesudah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($mutationHistory as $key => $value)
                                            <tr>
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>{{ format_datetime($value->created_at) }}</td>
                                                <td>
                                                    @if ($value->type == 'credit')
                                                        <span class="badge bg-danger">{{ strtoupper($value->type) }}</span>
                                                    @else
                                                        <span class="badge bg-success">{{ strtoupper($value->type) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ strtoupper(str_replace('-', ' ', $value->category)) }}</td>
                                                <td><a href="javascript:;" onclick="info('{{ $value->description }}')" data-toggle="tooltip" title="Detail">{{ \Str::limit($value->description, 30, '...') }}</a></td>
                                                <td>{{ 'Rp ' . currency($value->amount) }}</td>
                                                <td>{{ 'Rp ' . currency($value->beginning_balance) }}</td>
                                                <td>{{ 'Rp ' . currency($value->last_balance) }}</td>
                                            </tr>
                                            @php flush() @endphp
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-white">Tidak ada data</td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <center class="mt-3"><a href="{{ route('account.mutation') }}" class="btn btn-primary">Lihat semua mutasi saldo</a></center>
                        </div>
                    </div>
                </div>
                <!-- My Wallet End -->
