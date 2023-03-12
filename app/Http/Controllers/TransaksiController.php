<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Outlet;
use App\Models\Paket;
use App\Models\Tambahan;
use App\Models\TambahanDetail;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Cart;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::select('id', 'nama')->get();

        $search = $request->search;
        $user = Auth::user();
        $outlet_id = $user->role != 'admin' ? $user->outlet_id : null;

        $transaksis = Transaksi::join('members', 'members.id', 'transaksis.member_id')
            ->join('users', 'users.id', 'transaksis.user_id')
            ->join('outlets', 'outlets.id', 'users.outlet_id')
            ->where('members.nama', 'like', "%{$search}%")
            ->when($outlet_id, function ($query, $outlet_id) {
                return $query->where('transaksis.outlet_id', $outlet_id);
            })
            ->orderBy('tgl', 'DESC')
            ->select(
                'transaksis.id as id',
                'members.nama as nama',
                'members.tlp as tlp',
                'qty_total',
                'status',
                'dibayar',
                'tgl',
                'batas_waktu',
                'kode_invoice',
                'total_bayar',
                'outlets.nama as outlet'
            )
            ->paginate();

        $transaksis->map(function ($row) {
            $row->tgl = date('d/m/Y H:i:s', strtotime($row->tgl));
            $row->batas_waktu = date('d/m/Y H:i:s', strtotime($row->batas_waktu));
            $row->status = ucwords($row->status);
            $row->dibayar = ucwords(str_replace('_', ' ', $row->dibayar));
            $row->total_bayar = number_format($row->total_bayar, 0, ',', '.');
        });

        return view('transaksi.index', [
            'members' => $members,
            'transaksis' => $transaksis
        ]);
    }

    public function create(Request $request, Member $member)
    {
        $user = Auth::user();
        $outlet = Outlet::find($user->outlet_id);
        $pakets = Paket::where('outlet_id', $outlet->id)
            ->select('id as value', DB::raw("CONCAT(nama_paket, ' - Rp ', harga) as option"))
            ->get();
        $tambahans = Tambahan::where('tambahans.outlet_id', $outlet->id)
            ->select('tambahans.id as value', DB::raw("CONCAT(tambahans.nama, ' - Rp ', tambahans.harga) as option"))
            ->distinct()
            ->get();

        $items = Cart::session($member->id)->getContent();

        // $total = Cart::session($member->id)->getTotal();

        // $diskon = 0;
        // foreach ($items as $item) {
        //     $totalQPrice = $item->quantity * $item->price;
        //     $totalQDiscount = $item->quantity * $item->attributes->diskon;
        //     $subTotalDiscount = $totalQPrice - $totalQDiscount;
        //     $qty = $item->quantity;
        //     $diskon += $qty * ($item->attributes->diskon ?? 0);
        //     $item->subTotalDiscount = $subTotalDiscount;
        // }

        $subTotal = Cart::session($member->id)->getTotal();
        // $getDiscountTotal = Cart::session($member->id)->getTotal() - $subTotal;

        // return $items;

        return view('transaksi.create', [
            'member' => $member,
            'outlet' => $outlet,
            'pakets' => $pakets,
            'tambahans' => $tambahans,
            'items' => $items,
            'total' => $subTotal,
        ]);
    }

    public function add(Request $request, Member $member)
    {
        $request->validate([
            'paket' => 'required|exists:pakets,id',
            'quantity' => 'required|numeric|min:1',
            'keterangan' => 'nullable|max:200',
        ]);

        $paket = Paket::find($request->paket);

        Cart::session($member->id)->add(array(
            'id' => $paket->id,
            'name' => $paket->nama_paket,
            'price' => $paket->harga_akhir,
            'quantity' => $request->quantity,
            'attributes' => [
                'harga_awal' => $paket->harga,
                'keterangan' => $request->keterangan,
                'diskon' => $paket->diskon,
            ]
        ));

        return back();
    }

    public function delete(Member $member, Paket $paket)
    {
        Cart::session($member->id)->remove($paket->id);
        return back();
    }

    public function clear(Member $member)
    {
        Cart::session($member->id)->clear();
        return back();
    }

    public function store(Request $request, Member $member)
    {
        $request->validate([
            'batas_waktu' => 'required|after:' . Carbon::now()->addHours(3)->toDateTimeString(),
            'diskon' => 'nullable|numeric',
            'biaya_tambahan' => 'nullable|',
            'uang_tunai' => 'nullable|numeric',
        ]);

        if (Cart::session($member->id)->isEmpty()) {
            return back()->withErrors([
                'paket' => 'Paket tidak boleh kosong.'
            ]);
        }

        $subtotal = Cart::session($member->id)->getTotal();
        $diskon = $request->diskon;
        $biaya_tambahan_ids = $request->biaya_tambahan;
        $biaya_tambahan_harga = 0;

        if ($biaya_tambahan_ids) {
            foreach ($biaya_tambahan_ids as $biaya_tambahan_id) {
                $biaya_tambahan_harga += Tambahan::find($biaya_tambahan_id)->harga;
            }
        }
        $total = $subtotal - $diskon + $biaya_tambahan_harga;
        $pajak = round($total * 10 / 100);
        $total_bayar = $total + $pajak;
        $uang_tunai = $request->uang_tunai;
        $kembalian = $uang_tunai - $total_bayar;

        if ($uang_tunai && ($kembalian < 0)) {
            return back()->withErrors([
                'uang_tunai' => 'Uang tunai kurang dari total bayar'
            ]);
        }

        $user = Auth::user();
        $qty_total = Cart::session($member->id)->getTotalQuantity();

        $last_transaksi = Transaksi::orderBy('id', 'desc')->select('id')->first();
        $last_id = $last_transaksi ? $last_transaksi->id : 0;
        $id = sprintf("%04s", $last_id + 1);
        $invoice = date('Ymd') . $id;

        $query_transaksi = [
            'outlet_id' => $user->outlet_id,
            'member_id' => $member->id,
            'user_id' => $user->id,
            'kode_invoice' => $invoice,
            'tgl' => date('Y-m-d H:i:s'),
            'batas_waktu' => date('Y-m-d H:i:s', strtotime($request->batas_waktu)),
            'tgl_bayar' => $uang_tunai ? date('Y-m-d H:i:s') : null,
            'biaya_tambahan' => $biaya_tambahan_harga,
            'diskon' => $diskon,
            'pajak' => $pajak,
            'sub_total' => $subtotal,
            'qty_total' => $qty_total,
            'total_bayar' => $total_bayar,
            'cash' => $uang_tunai ? $uang_tunai : null,
            'kembalian' => $uang_tunai ? $kembalian : null,
            'status' => 'baru',
            'dibayar' => $uang_tunai ? 'dibayar' : 'belum_dibayar',
        ];

        $transaksi = Transaksi::create($query_transaksi);

        $items = Cart::session($member->id)->getContent();

        foreach ($items as $item) {
            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'paket_id' => $item->id,
                'harga' => $item->attributes->harga_awal,
                'diskon_paket' => $item->attributes->diskon,
                'qty' => $item->quantity,
                'sub_total' => $item->price * $item->quantity,
                'keterangan' => $item->attributes->keterangan,
            ]);
        }

        LogActivity::add('membuat transaksi baru. invoice :' . $invoice);

        $tambahan_ids = $request->biaya_tambahan;

        if ($tambahan_ids) {
            foreach ($tambahan_ids as $tambahan_id) {
                TambahanDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'tambahan_id' => $tambahan_id,
                ]);
            }
        }

        Cart::session($member->id)->clear();

        return redirect()->route('transaksi.detail', ['transaksi' => $transaksi->id]);
    }

    public function detail(Transaksi $transaksi)
    {
        $user = User::find($transaksi->user_id);
        $member = Member::find($transaksi->member_id);
        $outlet = Outlet::find($transaksi->outlet_id);
        $tambahans = Tambahan::where('outlet_id', $outlet->id)
            ->select('id as value', DB::raw("CONCAT(nama, ' - Rp ', harga) as option"))
            ->get();
        $items = TransaksiDetail::join('pakets', 'pakets.id', 'transaksi_details.paket_id')
            ->where('transaksi_id', $transaksi->id)
            ->select(
                'pakets.id as id',
                'nama_paket',
                'qty',
                'transaksi_details.harga as harga',
                'sub_total',
                'diskon_paket',
                'keterangan',
            )
            ->get();

        return view('transaksi.detail', [
            'items' => $items,
            'member' => $member,
            'user' => $user,
            'tambahans' => $tambahans,
            'outlet' => $outlet,
            'transaksi' => $transaksi,
        ]);
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'diskon' => 'nullable|numeric',
            'biaya_tambahan' => 'nullable|numeric',
            'uang_tunai' => 'nullable|numeric',
        ]);

        $subtotal = $transaksi->sub_total;
        $diskon = $request->diskon;
        $biaya_tambahan = $request->biaya_tambahan;
        $total = $subtotal - $diskon + $biaya_tambahan;
        $pajak = round($total * 10 / 100);
        $total_bayar = $total + $pajak;
        $uang_tunai = $request->uang_tunai;
        $kembalian = $uang_tunai - $total_bayar;

        if ($uang_tunai && ($kembalian < 0)) {
            return back()->withErrors([
                'uang_tunai' => 'Uang tunai kurang dari total bayar'
            ]);
        }

        $query_transaksi = [
            'tgl_bayar' => $uang_tunai ? date('Y-m-d H:i:s') : null,
            'biaya_tambahan' => $biaya_tambahan,
            'diskon' => $diskon,
            'pajak' => $pajak,
            'sub_total' => $subtotal,
            'total_bayar' => $total_bayar,
            'cash' => $uang_tunai ? $uang_tunai : null,
            'kembalian' => $uang_tunai ? $kembalian : null,
            'dibayar' => $uang_tunai ? 'dibayar' : 'belum_dibayar',
        ];

        LogActivity::add('mengupdate transaksi. Invoice : ' . $transaksi->kode_invoice);

        $transaksi->update($query_transaksi);

        return back()->with('message', 'success update');
    }

    public function qUpdate($member, $paket, $type)
    {

        $member = Cart::session($member);

        if (!$member->get($paket)) {
            return back();
        }

        // if ($type == 'plus') {
        //     $member->update($paket, [
        //         'quantity' => 1,
        //     ]);
        // } else {
        //     if ($member->get($paket)->quantity > 1) {
        //         $member->update($paket, [
        //             'quantity' => -1,
        //         ]);
        //     } else
        //         $member->remove($paket);
        // }

        switch ($type) {
            case 'plus':
                $member->update($paket, [
                    'quantity' => 1,
                ]);
                break;
            case 'min':
                if ($member->get($paket)->quantity > 1) {
                    $member->update($paket, [
                        'quantity' => -1,
                    ]);
                } else
                    $member->remove($paket);
                break;
        }



        // update only quantity using cart darryldecode
        // switch ($type) {
        //     case 'plus':
        //         Cart::session($member)->update($member, [
        //             'quantity' => 1,
        //         ]);
        //         break;
        //     case 'min':
        //         if (Cart::session($member)->get($id)->quantity > 1) {
        //             Cart::session($member)->update($id, [
        //                 'quantity' => - 1,
        //             ]);
        //         } else
        //             Cart::session($member)->remove($id);
        //         break;
        // }


        return back();
    }

    const ALLOWED_VALUES = ['baru', 'proses', 'selesai', 'diambil', 'batal'];

    public function status(Transaksi $transaksi, $status)
    {
        // Check if the status is valid and the transaction can be updated
        if (!in_array($status, self::ALLOWED_VALUES) || !$this->canUpdateStatus($transaksi, $status)) {
            return back()->with('message', 'fail store');
        }

        // Update the status and the date if needed
        $transaksi->update(['status' => $status]);
        if ($status == 'selesai' || $status == 'diambil') {
            $transaksi->update(['tgl_' . $status => Carbon::now()]);
        }

        // Log the activity
        LogActivity::add('mengupdate status transaksi ke status ' . $status . '.' . ' Invoice : ' . $transaksi->kode_invoice);

        return back()->with('message', 'success update');
    }

    // Helper function to check if the transaction can be updated to a given status
    private function canUpdateStatus(Transaksi $transaksi, $status)
    {
        // The transaction cannot be updated if it is already diambil or batal
        if ($transaksi->status == 'diambil' || $transaksi->status == 'batal') {
            return false;
        }

        // The next possible status for each current status
        $nextStatus = [
            'baru' => ['proses', 'batal'],
            'proses' => ['selesai'],
            'selesai' => ['diambil']
        ];

        // The transaction can be updated only if the given status is in the next possible status array
        return in_array($status, $nextStatus[$transaksi->status]);
    }

    public function invoice(Transaksi $transaksi)
    {
        $user = User::find($transaksi->user_id);
        $member = Member::find($transaksi->member_id);
        $outlet = Outlet::find($transaksi->outlet_id);
        $items = TransaksiDetail::join('pakets', 'pakets.id', 'transaksi_details.paket_id')
            ->where('transaksi_id', $transaksi->id)
            ->select(
                'pakets.id as id',
                'nama_paket',
                'qty',
                'transaksi_details.harga as harga',
                'sub_total',
                'diskon_paket',
                'keterangan'
            )
            ->get();

        return view('transaksi.invoice', [
            'items' => $items,
            'member' => $member,
            'user' => $user,
            'outlet' => $outlet,
            'transaksi' => $transaksi,
        ]);
    }
}
