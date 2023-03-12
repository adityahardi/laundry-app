<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('outlets')->insert([
            [
                'nama' => 'Asep Outlet Laundry',
                'alamat' => 'Bandung',
                'tlp' => '991928390192'
            ],
            [
                'nama' => 'Joget Outlet Laundry',
                'alamat' => 'Cibaduyut',
                'tlp' => '019238928971'
            ],
        ]);

        Db::table('users')->insert([
            [
                'nama' => 'Rusdi',
                'username' => 'admin',
                'password' => bcrypt('admin'),
                'role' => 'admin',
                'outlet_id' => 1,
            ],
            [
                'nama' => 'Rehan',
                'username' => 'kasir',
                'password' => bcrypt('kasir'),
                'role' => 'kasir',
                'outlet_id' => 1,
            ],

            [
                'nama' => 'Bajigur',
                'username' => 'owner',
                'password' => bcrypt('owner'),
                'role' => 'owner',
                'outlet_id' => 1,
            ]
        ]);

        Db::table('pakets')->insert([
            [
                'nama_paket' => 'Reguler',
                'harga' => 7000,
                'jenis' => 'kiloan',
                'harga_akhir' => 7000,
                'outlet_id' => 1,
            ],
            [
                'nama_paket' => 'Bed Cover',
                'harga' => 5000,
                'jenis' => 'bed_cover',
                'harga_akhir' => 5000,
                'outlet_id' => 1,
            ]
        ]);

        Db::table('members')->insert([
            [
                'nama' => 'Asep',
                'jenis_kelamin' => 'L',
                'alamat' => 'Bandung Jawa Tengah',
                'tlp' => '991928390192',
            ],
            [
                'nama' => 'Marimas',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jogja Jawa Barat',
                'tlp' => '019238928971',
            ],
            [
                'nama' => 'Roger',
                'jenis_kelamin' => 'L',
                'alamat' => 'Asgard Blok M ',
                'tlp' => '019231023898',
            ]
        ]);

        DB::table('tambahans')->insert([
            'nama' => 'Ongkir',
            'harga' => 10000,
            'outlet_id' => 1,
        ]);
    }
}
