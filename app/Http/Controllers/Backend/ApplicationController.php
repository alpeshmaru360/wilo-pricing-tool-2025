<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\wilo_application;
use App\WiloProjectManagement;
class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if ($_GET) {
            $data = wilo_application::where('is_deleted', 0)
                ->where('is_deleted', 0)->Where('name', 'like', '%' . $_GET['filter'] . '%')
                ->get();

            $validation = wilo_application::where('root_id', '!=', 0)->pluck('root_id');
            // dd($validation);
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]["is_parent"] = "false";
                for ($j = 0; $j < count($validation); $j++) {
                    // dd($data[$i]->id." ".$validation[$j]);
                    if ($data[$i]->id == $validation[$j])
                        $data[$i]["is_parent"] = "true";
                }
            }
            // $ok = $data::where('is_parent',"true")->get();
            // dd($data);
            return View('backend.application.index')->with('data', $data)->with('val', $validation);
        }

        $data = wilo_application::where('root_id', 0)->where('is_deleted', 0)->orderBy('name', 'asc')->get();

        $validation = wilo_application::where('root_id', '!=', 0)->pluck('root_id');
        // dd($validation);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]["is_parent"] = "false";
            for ($j = 0; $j < count($validation); $j++) {
                // dd($data[$i]->id." ".$validation[$j]);
                if ($data[$i]->id == $validation[$j])
                    $data[$i]["is_parent"] = "true";
            }
        }
        // $ok = $data::where('is_parent',"true")->get();
        // dd($data);
        return View('backend.application.index')->with('data', $data)->with('val', $validation);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return View('backend.application.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $image_extensions = ["jpeg","png","jpg","gif","JPEG","PNG","JPG","GIF"];

        // if(!empty($request->logo))
        // {
        //     if(!in_array($request->logo->getClientOriginalExtension(),$image_extensions))
        //     {
        //        return redirect()->route('admin.application.index')->withFlashDanger(__('wrong image format.'));
        //     }
        // }
        // $naming = str_slug("application_logo".$request->logo.'-'.date('d-M-Y')).
        // '-'.system('date +%s%N').'.'.$request->logo->getClientOriginalExtension();

        // $request->logo->move(public_path("logo/application_logo"),$naming); 

        $wa = new wilo_application();
        $wa->name = $request->name;
        // $wa->logo = $naming;
        $wa->save();
        return redirect()->route('admin.application.index')->withFlashSuccess(__('Application created.'));
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
        $data = wilo_application::where('id', $id)->first();
        return View('backend.application.edit')->with('data', $data);
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
        // $image_extensions = ["jpeg","png","jpg","gif","JPEG","PNG","JPG","GIF"];

        // if(!empty($request->logo))
        // {
        //     if(!in_array($request->logo->getClientOriginalExtension(),$image_extensions))
        //     {
        //        return redirect()->route('admin.application.index')->withFlashDanger(__('wrong image format.'));
        //     }
        // }
        // $naming = str_slug("application_logo".$request->logo.'-'.date('d-M-Y')).
        // '-'.system('date +%s%N').'.'.$request->logo->getClientOriginalExtension();

        // $request->logo->move(public_path("logo/application_logo"),$naming);
        $id_s = array(); 
        $wp = WiloProjectManagement::select('id')->
        where('application_type',$id)->where('is_deleted',0)
        ->get()->toArray();
            foreach($wp as $wp_id)
                {
                    //array_push($id_s,$wp_id->id);
                    $id_s[] =  $wp_id['id'];  
                } 
            // echo "<pre>";                  
            // print_r($id_s);die;
        $data = wilo_application::where('id', $id)->first();
        $data->name = $request->name;
        // $data->logo = $naming;
        $data->save();
        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf($id_s);        
        return redirect()->route('admin.application.index')->withFlashSuccess(__('Application updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        WiloProjectManagement::where('application_type',$id)->update(array('application_type' => 0));
        $data = wilo_application::where('id', $id)->first();
        $data->is_deleted = 1;
        $data->save();
        return redirect()->route('admin.application.index')->withFlashDanger(__('Application deleted.'));
    }
}
