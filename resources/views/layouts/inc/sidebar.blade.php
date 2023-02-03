<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/" class="brand-link">
        <img src="/adminlte/dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
            <x-nav-item :href="route('dashboard')" :title="[
                'name' => 'Dashboard',
                'icon' => 'fas fa-home',
                'active' => ['dashboard'],
            ]"/>
            @can('admin-kasir')
            <x-nav-item :href="route('member.index')" :title="[
                'name' => 'Member',
                'icon' => 'fas fa-users',
                'active' => ['member.index', 'member.create', 'member.edit'],
            ]"/>
            @endcan
            @can('admin')
                <x-nav-item :href="route('user.index')" :title="[
                    'name' => 'User',
                    'icon' => 'fas fa-user',
                    'active' => ['user.index', 'user.create', 'user.edit'],
                ]"/>
                <x-nav-item :href="route('outlet.index')" :title="[
                    'name' => 'Outlet',
                    'icon' => 'fas fa-store-alt',
                    'active' => ['outlet.index', 'outlet.create', 'outlet.edit'],
                ]"/>
                <x-nav-item :href="route('paket.index')" :title="[
                    'name' => 'Paket',
                    'icon' => 'fas fa-cubes',
                    'active' => ['paket.index', 'paket.create', 'paket.edit'],
                ]"/>
            @endcan
        </nav>
    </div>
</aside>
