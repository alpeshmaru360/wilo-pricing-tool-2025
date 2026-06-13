<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WiloProjectManagement;
use App\sub_application;

class SubApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if ($_GET) {
            $data = sub_application::where('root_id', '>', 0)->where('is_deleted', 0)
                ->Where('name', 'like', '%' . $_GET['filter'] . '%')
                ->get();

            $data_names = array();
            for ($i = 0; $i < count($data); $i++) {
                $names = sub_application::where('id', $data[$i]["root_id"])->pluck('name');
                array_push($data_names, $names[0]);
                $data[$i]["parent"] = $data_names[$i];
            }


            return View('backend.sub_application.index')->with("data", $data)->with("parent_name", $data_names);
        }
        $data = sub_application::where('root_id', '>', 0)->where("is_deleted", 0)->orderBy('name', 'asc')->get();

        $data_names = array();
        for ($i = 0; $i < count($data); $i++) {
            $names = sub_application::where('id', $data[$i]["root_id"])->pluck('name');
            array_push($data_names, $names[0]);
            $data[$i]["parent"] = $data_names[$i];
        }


        return View('backend.sub_application.index')->with("data", $data)->with("parent_name", $data_names);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = sub_application::where('root_id', 0)->where("is_deleted", 0)->get();
        return View('backend.sub_application.create')->with('data', $data);
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
        //        return redirect()->route('admin.subapplication.index')->withFlashDanger(__('wrong image format.'));
        //     }
        // }
        // $naming = str_slug("sub_application_logo".$request->logo.'-'.date('d-M-Y')).
        // '-'.system('date +%s%N').'.'.$request->logo->getClientOriginalExtension();

        // $request->logo->move(public_path("logo/sub_application_logo"),$naming); 

        $field = new sub_application();
        $field->name = $request->name;
        $field->root_id = $request->parent;
        // $field->logo = $naming;
        $field->save();
        return redirect()->route('admin.subapplication.index')->withFlashSuccess(__('Sub-application created.'));
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
        //
        $data = sub_application::where('id', $id)->first();
        $data_drop = sub_application::where('root_id', 0)->where("is_deleted", 0)->get();

        //  $selected = sub_application::where('id',$data->root_id)->where("is_deleted",0)->first();
        // // dd($selected);
        //  for($i=0;$i<count($data_drop);$i++)
        //  {
        //      if($data_drop[$i] == $selected)
        //      {
        //          $data_drop[$i] = $data_drop[0]; 
        //          $data_drop[0] = $selected;

        //      }
        //  }

        // dd($data_drop);
        // $data = sub_application::where('id',$id)->pluck("root_id");

        return View("backend.sub_application.edit")->with("data", $data)->with('drop', $data_drop);
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
        //        return redirect()->route('admin.subapplication.index')->withFlashDanger(__('wrong image format.'));
        //     }
        // }
        // $naming = str_slug("sub_application_logo".$request->logo.'-'.date('d-M-Y')).
        // '-'.system('date +%s%N').'.'.$request->logo->getClientOriginalExtension();

        // $request->logo->move(public_path("logo/sub_application_logo"),$naming); 
        $id_s = array(); 
        $wp = WiloProjectManagement::select('id')->
        where('sub_application_type',$id)->where('is_deleted',0)
        ->get()->toArray();  
        // dd($wp);
        foreach($wp as $wp_id)
                {
                    //array_push($id_s,$wp_id->id);
                    $id_s[] =  $wp_id['id'];  
                }   
        $data = sub_application::where('id', $id)->first();

        $data->name = $request->name;
        $data->root_id = $request->parent;
        // $data->logo = $naming;
        $data->save();
        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf($id_s);
        return redirect()->route('admin.subapplication.index')->withFlashSuccess(__('Sub-application updated.'));
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
        $data = sub_application::where('id', $id)->first();
        $data->delete();
        WiloProjectManagement::where('sub_application_type',$id)->update(array('sub_application_type' => 0));
        return redirect()->route('admin.subapplication.index')->withFlashDanger(__('Sub-application deleted.'));
    }
}
