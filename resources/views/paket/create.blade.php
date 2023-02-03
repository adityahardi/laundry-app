@extends('layouts.main', ['title' => 'Paket'])
@section('content')
    <x-content :title="['name' => 'Paket','icon' => 'fas fa-cubes']">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('paket.store') }}" method="post" class="card card-primary">
                    <div class="card-header">
                        Buat Paket
                    </div>
                    <div class="card-body">
                        @csrf
                        <x-input label="Nama Paket" name="nama_paket"/>
                        <x-input label="Harga" name="harga"/>
                        <x-select label="Jenis" name="jenis" :data-option="[
                            ['option' => 'Kiloan', 'value' => 'kiloan'],
                            ['option' => 'T-Shirt/Kaos', 'value' => 'kaos'],
                            ['option' => 'Bed Cover', 'value' => 'bed_cover'],
                            ['option' => 'Selimut', 'value' => 'selimut'],
                            ['option' => 'Lainnya', 'value' => 'lain'],
                        ]" />
                        <x-select label="Outlet" name="outlet_id" :data-option="$outlets" />
                    </div>
                    <div class="card-footer">
                        <x-btn-submit />
                    </div>
                </form>
            </div>
        </div>
    </x-content>
@endsection
