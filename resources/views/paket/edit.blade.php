@extends('layouts.main', ['title' => 'Paket'])
@section('content')
    <x-content :title="[
        'name' => 'Paket',
        'icon' => 'fas fa-store-alt'
    ]">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <form action="{{ route('paket.update', ['paket' => $paket->id]) }}" method="post" class="card card-primary">
                    <div class="card-header">
                        Edit Paket
                    </div>
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <x-input label="Nama Paket" name="nama_paket" :value="$paket->nama_paket"/>
                        <x-input label="Harga" name="harga" :value="$paket->harga"/>
                        <x-select label="Jenis" name="jenis" :data-option="[
                            ['option' => 'Kiloan', 'value' => 'kiloan'],
                            ['option' => 'T-Shirt/Kaos', 'value' => 'kaos'],
                            ['option' => 'Bed Cover', 'value' => 'bed_cover'],
                            ['option' => 'Selimut', 'value' => 'selimut'],
                            ['option' => 'Lainnya', 'value' => 'lain'],
                        ]" :value="$paket->jenis"/>
                        <x-select label="Outlet" name="outlet_id" :data-option="$outlets" :value="$paket->outlet_id" />
                    </div>
                    <div class="card-footer">
                        <x-btn-update />
                    </div>
                </form>
            </div>
        </div>
    </x-content>
@endsection
