<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\types_project;
use App\WiloProjectManagement;
class ProjectTypes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if($_GET){
            $data =types_project::where('is_deleted',0)
            ->Where('name', 'like', '%' . $_GET['filter'] . '%')
            ->get();
            
        
            return View('backend.project_type.index')->with("data",$data);
            }
        $data = types_project::where('is_deleted',0)->orderBy('name')->get();
        return View('backend.project_type.index')->with("data",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return View('backend.project_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $image_extensions = ["jpeg","png","jpg","gif","JPEG","PNG","JPG","GIF"];
         
        if(!empty($request->logo))
        {
            if(!in_array($request->logo->getClientOriginalExtension(),$image_extensions))
            {
               return redirect()->route('admin.typeproject.index')->withFlashDanger(__('wrong image format.'));
            }
        }
        $naming = str_slug("project_type_logo".$request->logo.'-'.date('d-M-Y')).
        '-'.'.'.$request->logo->getClientOriginalExtension();
        
        $request->logo->move(public_path("logo/project_type_logo"),$naming); 

        $data = new types_project();
        $data->name = $request->name; 
        $data->logo = $naming;
        $data->save();
        return redirect()->route('admin.typeproject.index')->withFlashSuccess(__('Project type created.'));

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
        $data =  types_project::where('id',$id)->first();
        return View('backend.project_type.edit')->with('data',$data);
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
        where('type_of_project',$id)->where('is_deleted',0)
        ->get()->toArray();  
        // dd($wp);
        foreach($wp as $wp_id)
                {
                    //array_push($id_s,$wp_id->id);
                    $id_s[] =  $wp_id['id'];  
                } 
        $image_extensions = ["jpeg","png","jpg","gif","JPEG","PNG","JPG","GIF"];
        $data =  types_project::where('id',$id)->first();
        if(!empty($request->logo))
        {
            if(!in_array($request->logo->getClientOriginalExtension(),$image_extensions))
            {
               return redirect()->route('admin.typeproject.index')->withFlashDanger(__('wrong image format.'));
            }
        }
        if(isset($request->logo)){
            $naming = str_slug("project_type_logo".$request->logo.'-'.date('d-M-Y')).
            '-'.'.'.$request->logo->getClientOriginalExtension();
            $request->logo->move(public_path("logo/project_type_logo"),$naming); 
            $data->logo = $naming;
        }
        
        $data->name = $request->name;
        $data->save();
        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf($id_s);
        return redirect()->route('admin.typeproject.index')->withFlashSuccess(__('Project type updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = types_project::where('id',$id)->first();
        $data->is_deleted = 1;
        $data->save();
        WiloProjectManagement::where('type_of_project',$id)->update(array('type_of_project' => 0));
        return redirect()->route('admin.typeproject.index')->withFlashDanger(__('Project type deleted.'));


    }
}
