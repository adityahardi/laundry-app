@extends('layouts.main', ['title' => 'Member'])
@section('content')
    <x-content :title="['name' => 'Member','icon' => 'fas fa-store-alt']">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('member.store') }}" method="post" class="card card-primary">
                    <div class="card-header">
                        Buat Member
                    </div>
                    <div class="card-body">
                        @csrf
                        <x-input label="Nama" name="nama"/>
                        <x-select label="Jenis Kelamin" name="jenis_kelamin" :data-option="[
                            ['option' => 'Laki-laki', 'value' => 'L'],
                            ['option' => 'Perempuan', 'value' => 'P'],
                        ]" />
                        <x-input label="Telepon" name="tlp"/>
                        <x-textarea label="Alamat" name="alamat"/>
                    </div>
                    <div class="card-footer">
                        <x-btn-submit />
                    </div>
                </form>
            </div>
        </div>
    </x-content>
@endsection
