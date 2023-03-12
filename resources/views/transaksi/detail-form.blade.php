<form action="{{ route('transaksi.update', ['transaksi' => $transaksi->id]) }}" class="card-body border-top"
    method="post">
    @csrf
    @method('put')
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label>Tanggal</label>
                <span class="col"> :
                    {{ date('d/m/Y H:i:s', strtotime($transaksi->tgl)) }}</span>
            </div>
            <div class="form-group">
                @if ($transaksi->tgl_bayar == null)
                    <label>Tanggal Bayar</label>
                    <span class="col"> : Belum Dibayar</span>
                @else
                    <label>Tanggal Bayar</label>
                    <span class="col"> :
                        {{ date('d/m/Y H:i:s', strtotime($transaksi->tgl_bayar)) }}</span>
                @endif
            </div>
            @if ($transaksi->tgl_selesai == null)
                <label>Tanggal Selesai</label>
                <span class="col"> : Belum Selesai</span>
            @else
                <label>Tanggal Selesai</label>
                <span class="col"> :
                    {{ date('d/m/Y H:i:s', strtotime($transaksi->tgl_selesai)) }}</span>
            @endif
            <div class="form-group">
            </div>
            <div class="form-group">
                <label>Batas Waktu</label>
                <span> :
                    {{ date('d/m/Y H:i:s', strtotime($transaksi->batas_waktu)) }}</span>
            </div>
            <div class="form-group">
                <label>Status</label>
                <span> : {{ ucwords($transaksi->status) }}</span>
            </div>
            <div class="form-group">
                <label>Status Dibayar</label>
                <span> : {{ ucwords(str_replace('_', '  ', $transaksi->dibayar)) }}</span>
            </div>
        </div>
        @if ($transaksi->status == 'batal')
        <div class="col-2"></div>
        <div class="col">
            <div class="form-group row">
                <label class="col">Total</label>
                <div class="col">
                    <x-input-transaksi name="total" id="total" :value="$transaksi->sub_total" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Diskon Tambahan (Optional)</label>
                <div class="col">
                    <x-input-transaksi name="diskon" id="diskon" :value="$transaksi->diskon" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Biaya Tambahan (Optional)</label>
                <div class="col">
                    <x-input-transaksi name="biaya_tambahan" id="biaya_tambahan" :value="$transaksi->biaya_tambahan" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Pajak (10%)</label>
                <div class="col">
                    <x-input-transaksi name="pajak" id="pajak" :value="$transaksi->pajak" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Total Bayar</label>
                <div class="col">
                    <x-input-transaksi name="total_bayar" id="total_bayar" :value="$transaksi->total_bayar" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Uang Tunai / Cash (Optional)</label>
                <div class="col">
                    <x-input-transaksi name="uang_tunai" disabled />
                </div>
            </div>
            <div class="form-group row">
                <div class="col form-inline">
                    <a href="{{ route('transaksi.index') }}" class="btn btn-default mr-2">Kembali</a>
                </div>
            </div>
        </div>
        @else
        <div class="col-2"></div>
        <div class="col">
            <div class="form-group row">
                <label class="col">Total</label>
                <div class="col">
                    <x-input-transaksi name="total" id="total" :value="$transaksi->sub_total" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Diskon Tambahan (Optional)</label>
                <div class="col">
                    <x-input-transaksi name="diskon" id="diskon" :value="$transaksi->diskon" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Biaya Tambahan (Optional)</label>
                <div class="col">
                    <x-select-2 name="biaya_tambahan" select="" :opt="$tambahans" :value="$tambahan_details" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Pajak (10%)</label>
                <div class="col">
                    <x-input-transaksi name="pajak" id="pajak" :value="$transaksi->pajak" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Total Bayar</label>
                <div class="col">
                    <x-input-transaksi name="total_bayar" id="total_bayar" :value="$transaksi->total_bayar" disabled />
                </div>
            </div>
            <div class="form-group row">
                <label class="col">Uang Tunai / Cash (Optional)</label>
                <div class="col">
                    <x-input-transaksi name="uang_tunai" />
                </div>
            </div>
            <div class="form-group row">
                <div class="col form-inline">
                    <a href="{{ route('transaksi.index') }}" class="btn btn-default mr-2">Kembali</a>
                    <div class="dropdown">
                        @if ($transaksi->status != 'diambil' && $transaksi->dibayar == 'belum_dibayar')
                            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">
                                Pilih Status Menjadi
                            </button>
                            <div class="dropdown-menu">
                                <?php
                                $status = [['Proses', 'proses'], ['Selesai', 'selesai'], ['Batal', 'batal']];
                                ?>
                                @if ($transaksi->status == 'baru')
                                    <a href="{{ route('transaksi.status', ['transaksi' => $transaksi->id, 'status' => 'proses']) }}"
                                        class="dropdown-item">
                                        Proses
                                    </a>
                                    <a href="{{ route('transaksi.status', ['transaksi' => $transaksi->id, 'status' => 'batal']) }}"
                                        class="dropdown-item">
                                        Batal
                                    </a>
                                @elseif ($transaksi->status == 'proses')
                                    <a href="{{ route('transaksi.status', ['transaksi' => $transaksi->id, 'status' => 'selesai']) }}"
                                        class="dropdown-item">
                                        Selesai
                                    </a>
                                @elseif ($transaksi->status == 'selesai')
                                    <button disabled class="dropdown-item">
                                        Silahkan Bayar
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <button id="btn-submit" type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-database mr-2"></i> Update Pembayaran
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</form>

@push('css')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $('#diskon, #biaya_tambahan').keyup(function(e) {
            let t = parseInt($('#total').val());
            let d = parseInt($('#diskon').val());
            let bt = parseInt($('#biaya_tambahan').val());
            d = isNaN(d) ? 0 : d;
            bt = isNaN(bt) ? 0 : bt;
            let total = t - d + bt;
            let pajak = Math.round(total * 10 / 100);
            let total_bayar = total + pajak;
            $("#pajak").val(pajak);
            $("#total_bayar").val(total_bayar);
            if (total_bayar < 0) {
                $('#btn-submit').prop('disabled', true);
                toastr.error('Total bayar tidak boleh minus!');
            } else {
                $('#btn-submit').prop('disabled', false);
            }
        });
    </script>
@endpush
