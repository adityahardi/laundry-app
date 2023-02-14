<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivity;
use Gate;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        $data = Gate::allows('admin') ? $this->all($request) : $this->kasirOwner($request);
        return view('log-activity.index',[
            'data' => $data
        ]);
    }

    public function all(Request $request)
    {
        return LogActivity::list($request->nama);
    }

    public function kasirOwner(Request $request)
    {
        return LogActivity::list($request->nama, ['kasir', 'owner']);
    }

    public function clear()
    {
        LogActivity::truncate();
        return back()->with('message', 'success delete');
    }
}
