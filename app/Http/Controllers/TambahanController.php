<?php

namespace App\Http\Controllers;

use App\Models\Tambahan;
use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Models\LogActivity;

class TambahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $tambahans = Tambahan::join('outlets', 'outlets.id', 'tambahans.id')
        ->when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                ->orwhere('harga', 'like', "%{$search}%");
        })
        ->select(
            'tambahans.id',
            'tambahans.nama as nama_tambahan',
            'harga',
            'outlets.nama as nama_outlet'
        )
        ->orderBy('nama_tambahan', 'ASC')
        ->paginate(10);

        if ($search) {
            $tambahans->appends(['search' => $search]);
        }

        // return $tambahans;

        return view('tambahan.index', [
            'tambahans' => $tambahans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $outlets = Outlet::select('id as value', 'nama as option')->get();
        return view('tambahan.create', [
            'outlets' => $outlets
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required','regex:/^[a-zA-Z\s]*$/','min:3'],
            'harga' => 'required|numeric|min:0',
            'outlet_id' => 'required|exists:outlets,id',
        ], [], [
            'outlet_id' => 'Outlet',
        ]);

        Tambahan::create($request->all());

        LogActivity::add('menambahkan Biaya Tambahan baru : ' . $request->nama );

        return redirect()->route('tambahan.index')->with('message', 'success store');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tambahan  $tambahan
     * @return \Illuminate\Http\Response
     */
    public function show(Tambahan $tambahan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tambahan  $tambahan
     * @return \Illuminate\Http\Response
     */
    public function edit(Tambahan $tambahan)
    {
        $outlets = Outlet::select('id as value', 'nama as option')->get();
        return view('tambahan.edit', [
            'tambahan' => $tambahan,
            'outlets' => $outlets
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tambahan  $tambahan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tambahan $tambahan)
    {
        $request->validate([
            'nama' => ['required','regex:/^[a-zA-Z\s]*$/','min:3'],
            'harga' => 'required|numeric|min:0',
            'outlet_id' => 'required|exists:outlets,id',
        ], [], [
            'outlet_id' => 'Outlet',
        ]);

        $tambahan->update($request->all());

        LogActivity::add('berhasil mengedit data Biaya Tambahan');

        return redirect()->route('tambahan.index')
            ->with('message', 'success update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tambahan  $tambahan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tambahan $tambahan)
    {
        $tambahan->delete();

        LogActivity::add('berhasil menghapus data Biaya Tambahan');

        return back()->with('message', 'success delete');
    }
}
