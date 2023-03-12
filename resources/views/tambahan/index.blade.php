@extends('layouts.main', ['title' => 'Biaya Tambahan'])
@section('content')
    <x-content :title="[
        'name' => 'Biaya Tambahan',
        'icon' => 'fas fa-cubes',
    ]">
        @if (session('message') == 'success store')
            <x-alert-success />
        @endif
        @if (session('message') == 'success update')
            <x-alert-success type="update" />
        @endif
        @if (session('message') == 'success delete')
            <x-alert-success type="delete" />
        @endif

        <div class="card card-outline card-primary">
            <div class="card-header form-inline">
                <x-btn-add :href="route('tambahan.create')" nama="Biaya Tambahan" />
                <x-search />
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-striped ">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Biaya Tambahan</th>
                            <th>Harga</th>
                            <th>Outlet</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $tambahans->firstItem();
                        ?>
                        @forelse ($tambahans as $tambahan)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $tambahan->nama_tambahan }}</td>
                                <td>{{ $tambahan->harga }}</td>
                                <td>{{ $tambahan->nama_outlet }}</td>
                                <td class="text-center">
                                    <x-edit :href="route('tambahan.edit', ['tambahan' => $tambahan->id])" />
                                    <x-delete data-name="{{ $tambahan->nama }}" :data-url="route('tambahan.destroy', ['tambahan' => $tambahan->id])" />
                                </td>
                            </tr>
                        @empty
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $tambahans->links('page') }}
            </div>
        </div>
    </x-content>
@endsection
@push('modal')
    <x-modal-delete />
@endpush
