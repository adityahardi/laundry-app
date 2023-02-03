@extends('layouts.main', ['title' => 'User'])
@section('content')
    <x-content :title="['name' => 'User','icon' => 'fas fa-user']">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('user.store') }}" method="post" class="card card-primary">
                    <div class="card-header">
                        Buat User
                    </div>
                    <div class="card-body">
                        @csrf
                        <x-input label="Nama" name="nama"/>
                        <x-input label="Username" name="username"/>
                        <x-select label="Role" name="role" :data-option="[
                            ['value' => 'kasir', 'option' => 'Kasir'],
                            ['value' => 'owner', 'option' => 'Pemilik'],
                            ['value' => 'admin', 'option' => 'Administrator'],
                        ]" />

                        <x-select label="Outlet" name="outlet_id" :data-option="$outlets" />
                        <x-input label="Password" name="password" type="password" />
                        <x-input label="Password Confirmation" name="password_confirmation" type="password" />
                    </div>
                    <div class="card-footer">
                        <x-btn-submit />
                    </div>
                </form>
            </div>
        </div>
    </x-content>
@endsection
