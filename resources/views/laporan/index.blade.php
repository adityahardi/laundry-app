@extends('layouts.main', ['title' => 'Laporan'])

@section('content')
<x-content :title="['name' => 'Laporan','icon' => 'fas fa-print']">
    <div class="row">
        <div class="col-md-4">
            <form action="{{ route('laporan.harian') }}" target="_blank" class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Laporan Harian</h3>
                </div>
                <div class="card-body">
                    @csrf
                    <x-input label="Tanggal" name="tanggal" type="date" />
                    <x-select label="Outlet" name="outlet_id" :data-option="$outlets"/>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-print mr-2"></i>
                        Generate Laporan
                    </button>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <form action="{{ route('laporan.perbulan') }}" target="_blank" class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Laporan Per-Bulan</h3>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <x-select label="Bulan" name="bulan" :data-option="$bulan"/>
                        </div>
                        <div class="col">
                            <x-select label="Tahun" name="tahun" :data-option="$tahun"/>
                        </div>
                    </div>
                    <x-select label="Outlet" name="outlet_id" :data-option="$outlets"/>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-print mr-2"></i>
                        Generate Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-content>
@endsection
