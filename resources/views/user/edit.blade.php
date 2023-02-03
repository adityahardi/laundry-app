@extends('layouts.main', ['title' => 'User'])
@section('content')
    <x-content :title="[
        'name' => 'User',
        'icon' => 'fas fa-user'
    ]">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <form action="{{ route('user.update', ['user' => $user->id]) }}" method="post" class="card card-primary">
                    <div class="card-header">
                        Edit User
                    </div>
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <x-input label="Nama" name="nama" :value="$user->nama"/>
                        <x-input label="Username" name="username" :value="$user->username"/>
                        <x-select label="Role" name="role" :value="$user->role" :data-option="[
                            ['value' => 'kasir', 'option' => 'Kasir'],
                            ['value' => 'owner', 'option' => 'Pemilik'],
                            ['value' => 'admin', 'option' => 'Administrator'],
                        ]" />
                        <x-select label="Outlet" name="outlet_id" :value="$user->outlet_id"  :data-option="$outlets" />
                        <p class="text-muted mt-5">
                            Apabila tidak melakukan perubahan pada Password, pada inputan Password kosongkan.
                        </p>

                        <x-input label="Password" name="password" type="password" />
                        <x-input label="Password Confirmation" name="password_confirmation" type="password" />
                    </div>
                    <div class="card-footer">
                        <x-btn-update />
                    </div>
                </form>
            </div>
        </div>
    </x-content>
@endsection
