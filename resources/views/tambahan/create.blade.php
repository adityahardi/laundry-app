@extends('layouts.main', ['title' => 'Biaya Tambahan'])
@section('content')
    <x-content :title="[
        'name' => 'Biaya Tambahan',
        'icon' => 'fas fa-store-alt',
    ]">

        <div class="row">
            <div class="co-lg-4 col-md-6">
                <form class="card card-primary" method="POST" action="{{ route('tambahan.store') }}">
                    <div class="card-header">
                        Buat Biaya Tambahan
                    </div>
                    <div class="card-body">
                        @csrf
                        <x-input label="Nama" name="nama" />

                        <x-input label="Harga" name="harga" type="numeric" />

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