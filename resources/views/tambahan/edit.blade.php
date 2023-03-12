@extends('layouts.main', ['title' => 'Biaya Tambahan'])
@section('content')
    <x-content :title="[
        'name' => 'Biaya Tambahan',
        'icon' => 'fas fa-store-alt',
    ]">

        <div class="row">
            <div class="co-lg-4 col-md-6">
                <form class="card card-primary" method="POST" action="{{ route('tambahan.update', ['tambahan' => $tambahan->id]) }}">
                    <div class="card-header">
                        Edit Biaya Tambahan
                    </div>
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <x-input label="Nama" name="nama" :value="$tambahan->nama" />
                        <x-input label="Harga" name="harga" type="numeric" :value="$tambahan->harga" />
                        <x-select label="Outlet" name="outlet_id" :data-option="$outlets" :value="$tambahan->outlet_id" />
                    </div>
                    <div class="card-footer">
                        <x-btn-submit />
                    </div>
                </form>
            </div>
        </div>
    </x-content>
@endsection