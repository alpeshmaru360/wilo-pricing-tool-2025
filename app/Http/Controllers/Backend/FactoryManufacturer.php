<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\factory_manufacturer;
use App\WiloProjectManagement;

class FactoryManufacturer extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($_GET) {
            $data = factory_manufacturer::where('is_deleted', 0)
                ->where('name', 'like', '%' . $_GET['filter'] . '%')
                ->get();
            return view('backend.factory_manufacturer.index')->with("data", $data);
        }

        $data = factory_manufacturer::where('is_deleted', 0)->get();
        return view('backend.factory_manufacturer.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.factory_manufacturer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = new factory_manufacturer();
        $data->name = $request->name;
        $data->save();

        return redirect()->route('admin.factory_manufacturer.index')
            ->withFlashSuccess(__('Factory manufacturer created.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = factory_manufacturer::where('id', $id)->first();
        return view('backend.factory_manufacturer.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id_s = [];
        $wp = WiloProjectManagement::select('id')
            ->where('factory_manufacturer', $id)
            ->where('is_deleted', 0)
            ->get()
            ->toArray();

        foreach ($wp as $wp_id) {
            $id_s[] = $wp_id['id'];
        }

        $data = factory_manufacturer::where('id', $id)->first();
        $data->name = $request->name;
        $data->save();

        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')
            ->update_pdf($id_s);

        return redirect()->route('admin.factory_manufacturer.index')
            ->withFlashSuccess(__('Factory manufacturer updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = factory_manufacturer::where('id', $id)->first();
        $data->is_deleted = 1;
        $data->save();

        WiloProjectManagement::where('factory_manufacturer', $id)
            ->update(['factory_manufacturer' => 0]);

        return redirect()->route('admin.factory_manufacturer.index')
            ->withFlashDanger(__('Factory manufacturer deleted.'));
    }
}
