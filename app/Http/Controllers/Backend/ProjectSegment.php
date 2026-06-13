<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\project_segment;
use App\WiloProjectManagement;
class ProjectSegment extends Controller
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
            $data =project_segment::where('is_deleted',0)
            ->Where('name', 'like', '%' . $_GET['filter'] . '%')
            ->get();
            return View('backend.project_segment.index')->with("data",$data);
            }
        $data = project_segment::where('is_deleted',0)->get();
        return View('backend.project_segment.index')->with("data",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return View('backend.project_segment.create');
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
        //        return redirect()->route('admin.projectsegment.index')->withFlashDanger(__('wrong image format.'));
        //     }
        // }
        // $naming = str_slug("project_segment_logo".$request->logo.'-'.date('d-M-Y')).
        // '-'.system('date +%s%N').'.'.$request->logo->getClientOriginalExtension();
        
        // $request->logo->move(public_path("logo/project_segment_logo"),$naming); 
        $data = new  project_segment();
        $data->name = $request->name; 
        // $data->logo = $naming; 
        $data->save();
        return redirect()->route('admin.projectsegment.index')->withFlashSuccess(__('Project segments created.'));

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
        $data =  project_segment::where('id',$id)->first();
        return View('backend.project_segment.edit')->with('data',$data);
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
        //        return redirect()->route('admin.projectsegment.index')->withFlashDanger(__('wrong image format.'));
        //     }
        // }
        // $naming = str_slug("project_segment_logo".$request->logo.'-'.date('d-M-Y')).
        // '-'.system('date +%s%N').'.'.$request->logo->getClientOriginalExtension();
        
        // $request->logo->move(public_path("logo/project_segment_logo"),$naming); 
        $id_s = array(); 
        $wp = WiloProjectManagement::select('id')->
        where('project_segment',$id)->where('is_deleted',0)
        ->get()->toArray();  
        // dd($wp);
        foreach($wp as $wp_id)
                {
                    //array_push($id_s,$wp_id->id);
                    $id_s[] =  $wp_id['id'];  
                } 
        $data =  project_segment::where('id',$id)->first();
        $data->name = $request->name;
        // $data->logo = $naming;
        $data->save();
        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf($id_s);
        return redirect()->route('admin.projectsegment.index')->withFlashSuccess(__('Project segment updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $data = project_segment::where('id',$id)->first();
        $data->is_deleted = 1;
        $data->save();
        WiloProjectManagement::where('project_segment',$id)->update(array('project_segment' => 0));
        return redirect()->route('admin.projectsegment.index')->withFlashDanger(__('Project segment deleted.'));


    }
}
