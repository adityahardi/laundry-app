@extends('layouts.main', ['title' => 'Dashboard'])
@section('content')
<x-content :title="[
    'name' => 'Dashboard',
    'icon' => 'fas fa-home',
]">
<div class="card">
    <div class="card-header">
        Dashboard
    </div>
    <div class="card-body">
        Halaman Dashboard
    </div>
</div>
</x-content>
@endsection
