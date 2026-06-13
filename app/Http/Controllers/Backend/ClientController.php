<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StakeHolders;
use App\WiloProjectManagement;
use Intervention\Image\ImageManagerStatic as Image;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($_GET) {
            $data = StakeHolders::where('type', 1)->where('is_deleted', "!=", 1)
                ->Where('name', 'like', '%' . $_GET['filter'] . '%')
                ->get();
            return View('backend.client.index')->with("data", $data);
        }
        $data = StakeHolders::where('type', 1)->where('is_deleted', "!=", 1)->orderBy('name', 'asc')->get();
        return View('backend.client.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('backend.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image_extensions = ["jpeg", "png", "jpg", "gif", "JPEG", "PNG", "JPG", "GIF","jpg.webp"];
        $ImageDir = public_path("logo/client_logo/");

        $cosnultant = new StakeHolders();
        $cosnultant->name = $request->name;

        $cosnultant->type = 1;
        if (isset($request->logo)) {
            if (!empty($request->logo)) {
                if (!in_array($request->logo->getClientOriginalExtension(), $image_extensions)) {
                    return redirect()->route('admin.client.index')->withFlashDanger(__('wrong image format.'));
                }
            }
            $naming = str_slug("client_logo" . $request->logo . '-' . date('d-M-Y')) .
                '-' . system('date +%s%N') . '.' . $request->logo->getClientOriginalExtension();

            $request->logo->move(public_path("logo/client_logo"), $naming);
            $img = Image::make($ImageDir . $naming);
            $img->resize(45, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($ImageDir . "cli_" . $naming);
            $cosnultant->logo = $naming;
        }

        $cosnultant->save();
        return redirect()->route('admin.client.index')->withFlashSuccess(__('Client added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data = StakeHolders::where('id', $id)->first();
        return View('backend.client.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id_s = array(); 
        $wp = WiloProjectManagement::select('id')->
        where('client_id',$id)->where('is_deleted',0)
        ->get()->toArray();  
        // dd($wp);
        foreach($wp as $wp_id)
                {
                    //array_push($id_s,$wp_id->id);
                    $id_s[] =  $wp_id['id'];  
                }
        $image_extensions = ["jpeg", "png", "jpg", "gif", "JPEG", "PNG", "JPG", "GIF","jpg.webp"];
        $ImageDir = public_path("logo/client_logo/");
        $data = StakeHolders::where('id', $id)->first();
        $data->name = $request->name;
        if (!empty($request->logo)) {
            if (!in_array($request->logo->getClientOriginalExtension(), $image_extensions)) {
                return redirect()->route('admin.client.index')->withFlashDanger(__('wrong image format.'));
            }
            $naming = str_slug("client_logo" . $request->logo . '-' . date('d-M-Y')) .
            '-' . system('date +%s%N') . '.' . $request->logo->getClientOriginalExtension();
            $request->logo->move(public_path("logo/client_logo"), $naming);
            
            $img = Image::make($ImageDir . $naming);
            $img->resize(45, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($ImageDir . "cli_" . $naming);
            $data->logo = $naming;
        }
        
        $data->save();
        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf($id_s);
        return redirect()->route('admin.client.index')->withFlashSuccess(__('Client updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = StakeHolders::where('id', $id)->first();
        $data->is_deleted = 1;
        $data->save();
        WiloProjectManagement::where('client_id',$id)->update(array('client_id' => 0));
        return redirect()->route('admin.client.index')->withFlashDanger(__('Client deleted.'));
    }
}
