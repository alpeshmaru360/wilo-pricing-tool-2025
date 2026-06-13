<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\WiloProjectManagement;
use App\Http\Requests\Backend\Auth\Product\StoreProductRequest;
use App\Http\Requests\Backend\Auth\Product\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;
use Validator;
use Redirect;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $rules_for_image =
    [
        'p_pictures'      => 'image|max:5120',
    ];

    protected $messages =
    [
        'p_pictures.max' => 'The uploaded product image(s) size should not be greater than 5 Mb.',
    ];

    public function index()
    {
        //
        // if (isset($_GET['filter']) && $_GET['filter'] != "") {
        //     $data = Product::where('is_deleted', 0)
        //         ->Where('name', 'like', '%' . $_GET['filter'] . '%')
        //         ->get();

        //     $images = array();
        //     for ($i = 0; $i < count($data); $i++) {
        //         $images = json_decode($data[$i]->product_picture);
        //         $data[$i]['media'] = $images;
        //     }
        //     // $product_list_url = url('admin/products');
        //     return View('backend.product.index')->with('data', $data);
        //     // ->with('product_list_url', $product_list_url);
        // }

        $query = Product::where('is_deleted', '!=', 1)->orderBy('name', 'asc');

        if(isset($_GET['filter']) && $_GET['filter'] != ""){
            $query->Where('name', 'LIKE', '%'.$_GET['filter'].'%');
        }

        $data = $query->paginate('10');

       
        $images = array();
        for ($i = 0; $i < count($data); $i++) {

            $images = json_decode($data[$i]->product_picture);

            $data[$i]['media'] = $images;
        }
        return View('backend.product.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = WiloProjectManagement::all();
        return View('backend.product.create')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //for

    //      $file_extensions  = ["odt","doc","docs","html","ppt","pdf","xls","xlsx","ods","ppt","pptx",'txt','csv'];
    //      $video_extensions = ["mp4","avi","wmv","mov","mp4"];
    //      $image_extensions = ["jpeg","png","jpg","gif","JPEG","PNG","JPG","GIF"];

    //      if(!in_array($request->document->getClientOriginalExtension(),$file_extensions))
    //      {
    //         return redirect()->route('admin.products.index')->withFlashDanger(__('wrong file format.'));
    //      }
    //      if(!in_array($request->video->getClientOriginalExtension(),$video_extensions))
    //      {
    //         return redirect()->route('admin.products.index')->withFlashDanger(__('wrong video format.'));
    //      }
    //      for($k = 0 ; $k<count($request->p_pictures) ; $k++)
    //      {
    //          if(!in_array($request->p_pictures[$k]->getClientOriginalExtension(),$image_extensions))
    //          {
    //              return redirect()->route('admin.products.index')->withFlashDanger(__('wrong product image format.'));
    //          }
    //      }
    //      for($m = 0 ; $m<count($request->t_pictures) ; $m++)
    //      {
    //          if(!in_array($request->t_pictures[$m]->getClientOriginalExtension(),$image_extensions))
    //          {
    //              return redirect()->route('admin.products.index')->withFlashDanger(__('wrong technical image format.'));
    //          }
    //      }

    //     $technical_array = array();
    //     $product_array = array();

    //     $doc = str_slug("document_".$request->document->getClientOriginalName().'-'.date('d-M-Y')).
    //     '-'.system('date +%s%N').'.'.$request->document->getClientOriginalExtension();

    //     $video = str_slug("video_".$request->video->getClientOriginalName().'-'.date('d-M-Y')).
    //     '-'.system('date +%s%N').'.'.$request->video->getClientOriginalExtension();


    //     for($i=0 ;$i<count($request->t_pictures) ; $i++)
    //     {
    //         $technical_picture[$i] = str_slug("technical_image_".$request->t_pictures[$i]->getClientOriginalName().'-'.date('d-M-Y')).
    //         '-'.system('date +%s%N').'.'.$request->t_pictures[$i]->getClientOriginalExtension();
    //         array_push($technical_array,$technical_picture[$i]);
    //         $request->t_pictures[$i]->move(public_path("product/technical_pictures"),$technical_picture[$i]);
    //         // array_push($technical_array,$technical_picture[$i])
    //     }


    //     for($j=0 ;$j<count($request->p_pictures) ; $j++)
    //     {
    //         $product_picture[$j] = str_slug("product_image_".$request->p_pictures[$j]->getClientOriginalName().'-'.date('d-M-Y')).
    //         '-'.system('date +%s%N').'.'.$request->p_pictures[$j]->getClientOriginalExtension();
    //         array_push($product_array,$product_picture[$j]);
    //         $request->p_pictures[$j]->move(public_path("product/product_pictures"),$product_picture[$j]);
    //     }

    //     $request->document->move(public_path("product/document"),$doc); 
    //     $request->video->move(public_path("product/video"),$video);

    //     $technical_images = json_encode($technical_array);
    //     $product_images = json_encode($product_array);

    //     $data = new Product();
    //     $data->name = $request->product_name;
    //     $data->description = $request->description;
    //     $data->specification_document = $doc;
    //     $data->video = $video;
    //     $data->product_picture = $product_images;
    //     $data->technical_picture =  $technical_images;
    //     $data->save();

    //     for($x =0 ; $x<count($request->project_name) ; $x++)
    //     {
    //         DB::table('product_project')->insert(
    //             ['product_id' => $data->id,'project_id' => $request->project_name[$x] ]
    //         );
    //     }
    //     for($y =0 ; $y<count($request->t_pictures) ; $y++)
    //     {

    //         DB::table('product_data')->insert(
    //             ['productid' => $data->id,'technical_non_tech'=>0,'images' => $technical_array[$y] ]
    //         );
    //     }
    //     for($z =0 ; $z<count($request->p_pictures) ; $z++)
    //     {
    //         DB::table('product_data')->insert(
    //             ['productid' => $data->id,'technical_non_tech'=>1,'images' => $product_array[$z] ]
    //         );
    //     }

    //     // $data = Product::where('is_deleted','!=',1)->get();
    //     // // if(empty($data))
    //     // //     {
    //     // //         return View('backend.product.index')->withFlashSuccess(__('No product found.'));
    //     // //     }


    //     // // dd(json_decode($data[0]->product_picture));
    //     // $images = array();
    //     // for($i = 0 ; $i<count($data) ; $i++)
    //     // {

    //     //     $images = json_decode($data[$i]->product_picture);

    //     //     $data[$i]['media'] = $images;

    //     // }

    //     return redirect()->route('admin.products.index')->withFlashSuccess(__('Product created.'));
    // }

    public function store(StoreProductRequest $request)
    {
        // $ImageDir = public_path("product/product_pictures/");
        // $test_script = Product::all();
        // foreach($test_script as $ts)
        // {
        //     $decode = (json_decode($ts->product_picture,true));
            
        //    // dd($decode);
        //     //echo "<pre>";
        //     if(!empty($decode)){


            

        //     $countArr= count($decode);
        //     // print_r($dc);
        //     //echo "</pre>";
        //     //die;
        //     for($i=0;$i<$countArr;$i++)
                
        //             {
        //                     // echo "<pre>";
        //                     // print_r($decode[$i]);
        //                     // echo "</pre>";
        //                 $img = Image::make($ImageDir . $decode[$i]);
        //                     if($img)
        //                         {
        //                             $img->resize(600, null, function ($constraint) {
        //                                 $constraint->aspectRatio();
        //                                 $constraint->upsize();
        //                             });
        //                             $img->save($ImageDir."slider_". $decode[$i]);
                                    
        //                         }
        //                          die;
        //             }

        //         }
        //     // dd($decode);
        // }
        // die("done");
        // dd($test_script);
        //for

        // $file_extensions  = ["odt", "doc", "docs", "html", "ppt", "pdf", "xls", "xlsx", "ods", "ppt", "pptx", 'txt', 'csv'];
        // $video_extensions = ["mp4", "avi", "wmv", "mov", "mp4"];
        
        $rules = array();
        array_push($rules,$this->rules_for_image['p_pictures']);
        // $rules = $this->rules_for_image['p_pictures'];
        // dd($this->rules_for_image['p_pictures']);
        // foreach($request->p_pictures as $p){
        $validator = Validator::make($request->p_pictures, $rules, $this->messages);
        // dd($validator);
        //  dd($validator->customMessages['p_pictures.max']);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->customMessages['p_pictures.max'])->withInput();
        }
        // }
        $image_extensions = ["jpeg", "png", "jpg", "gif", "JPEG", "PNG", "JPG", "GIF", "TIF", "tif"];
        $ImageDir = public_path("product/product_pictures/");

        $data = new Product();
        $data->name = $request->product_name;
        $data->description = empty($request->description) ? '' : $request->description;
        $data->max_head = empty($request->max_head) ? '' : $request->max_head;
        $data->max_flow = empty($request->max_flow) ? '' : $request->max_flow;
        $product_array = array();
        if (isset($request->p_pictures)) {
            for ($j = 0; $j < count($request->p_pictures); $j++) {
                $product_picture[$j] = str_slug("product_image_" . $request->p_pictures[$j]->getClientOriginalName() . '-' . date('d-M-Y')) .
                    '-' . '.' . $request->p_pictures[$j]->getClientOriginalExtension();
                array_push($product_array, $product_picture[$j]);
            //    dd($product_picture[$j]);
                $request->p_pictures[$j]->move(public_path("product/product_pictures"), $product_picture[$j]);
                // dd($ImageDir);
                 $img = Image::make($ImageDir . $product_picture[$j]);
                // // open file a image resource
    
                // // resize the image to a height of 200 and constrain aspect ratio (auto width)
                 $img->resize(600, null, function ($constraint) {
                     $constraint->aspectRatio();
                     $constraint->upsize();
                });
                 $img->save($ImageDir."slider_". $product_picture[$j]);
    
            }
        }


        // if(!empty($request->video_url))
        // {
        //     $youtube_code = explode("=",$request->video_url);
        //     $youtube_base = explode("/",$request->video_url);
        //     $embed_url = end($youtube_base);
        //     dd($embed_url);
        //     $embed_url = "";
        //     $embed_url = $youtube_code;
        //     // $embed_string = "/embed/";
        //     // $youtube_base = $youtube_base.$embed_string.$embed_url;
        //     // dd($youtube_base);
        // }

        $product_images = json_encode($product_array);
        $data->product_picture = $product_images;
        $data->technical_spec_url =  empty($request->technical_spec_url) ? '' : $request->technical_spec_url;
        $data->video_url = empty($request->video_url) ? '' : $request->video_url;

        $data->save();

        //Upload documents hide
        // if (isset($request->document)) {
        //     if (!in_array($request->document->getClientOriginalExtension(), $file_extensions)) {
        //         return redirect()->route('admin.products.index')->withFlashDanger(__('wrong file format.'));
        //     }
        //     $doc = str_slug("document_" . $request->document->getClientOriginalName() . '-' . date('d-M-Y')) .
        //         '-' . system('date +%s%N') . '.' . $request->document->getClientOriginalExtension();
        //     $request->document->move(public_path("product/document"), $doc);
        //     $data->specification_document = $doc;
        //     $data->save();
        // }

        //Upload multiple video hide
        // if (isset($request->video)) {
        //     if (!in_array($request->video->getClientOriginalExtension(), $video_extensions)) {
        //         return redirect()->route('admin.products.index')->withFlashDanger(__('wrong video format.'));
        //     }
        //     $video = str_slug("video_" . $request->video->getClientOriginalName() . '-' . date('d-M-Y')) .
        //         '-' . system('date +%s%N') . '.' . $request->video->getClientOriginalExtension();
        //     $request->video->move(public_path("product/video"), $video);
        //     $data->video = $video;
        //     $data->save();
        // }
        // for ($k = 0; $k < count($request->p_pictures); $k++) {
        //     if (!in_array($request->p_pictures[$k]->getClientOriginalExtension(), $image_extensions)) {
        //         return redirect()->route('admin.products.index')->withFlashDanger(__('wrong product image format.'));
        //     }
        // }

        // if (isset($request->t_pictures)) {
        //     for ($m = 0; $m < count($request->t_pictures); $m++) {
        //         if (!in_array($request->t_pictures[$m]->getClientOriginalExtension(), $image_extensions)) {
        //             return redirect()->route('admin.products.index')->withFlashDanger(__('wrong technical image format.'));
        //         }
        //     }
        //     $technical_array = array();
        //     for ($i = 0; $i < count($request->t_pictures); $i++) {
        //         $technical_picture[$i] = str_slug("technical_image_" . $request->t_pictures[$i]->getClientOriginalName() . '-' . date('d-M-Y')) .
        //             '-' . system('date +%s%N') . '.' . $request->t_pictures[$i]->getClientOriginalExtension();
        //         array_push($technical_array, $technical_picture[$i]);
        //         $request->t_pictures[$i]->move(public_path("product/technical_pictures"), $technical_picture[$i]);
        //         // array_push($technical_array,$technical_picture[$i])
        //     }
        //     $technical_images = json_encode($technical_array);
        //     $data->technical_picture =  $technical_images;
        //     $data->save();
        // }

        // $product_array = array();



        // for ($j = 0; $j < count($request->p_pictures); $j++) {
        //     $product_picture[$j] = str_slug("product_image_" . $request->p_pictures[$j]->getClientOriginalName() . '-' . date('d-M-Y')) .
        //         '-' . system('date +%s%N') . '.' . $request->p_pictures[$j]->getClientOriginalExtension();
        //     array_push($product_array, $product_picture[$j]);
        //     $request->p_pictures[$j]->move(public_path("product/product_pictures"), $product_picture[$j]);
        // }




        // $product_images = json_encode($product_array);


        // $data->name = $request->product_name;
        // $data->description = $request->description;
        // // $data->specification_document = $doc;
        // // $data->video = $video;
        // $data->product_picture = $product_images;
        // // $data->technical_picture =  $technical_images;
        // $data->save();

        // for ($x = 0; $x < count($request->project_name); $x++) {
        //     DB::table('product_project')->insert(
        //         ['product_id' => $data->id, 'project_id' => $request->project_name[$x]]
        //     );
        // }
        // for($y =0 ; $y<count($request->t_pictures) ; $y++)
        // {

        //     DB::table('product_data')->insert(
        //         ['productid' => $data->id,'technical_non_tech'=>0,'images' => $technical_array[$y] ]
        //     );
        // }
        // for($z =0 ; $z<count($request->p_pictures) ; $z++)
        // {
        //     DB::table('product_data')->insert(
        //         ['productid' => $data->id,'technical_non_tech'=>1,'images' => $product_array[$z] ]
        //     );
        // }

        // $data = Product::where('is_deleted','!=',1)->get();
        // // if(empty($data))
        // //     {
        // //         return View('backend.product.index')->withFlashSuccess(__('No product found.'));
        // //     }


        // // dd(json_decode($data[0]->product_picture));
        // $images = array();
        // for($i = 0 ; $i<count($data) ; $i++)
        // {

        //     $images = json_decode($data[$i]->product_picture);

        //     $data[$i]['media'] = $images;

        // }
        // if($request->quantity != null)
        // {
        //     $data->quantity = $request->quantity;
        //     $data->save();
        // }
        return redirect()->route('admin.products.index')->withFlashSuccess(__('Product created.'));
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
        // for($j = 0 ; $j<count($data) ; $j++)
        // {

        //     $product_images = json_decode($data[$j]->product_picture);

        //     $data[$i]['media'] = $images;

        // }

        $string = "";

        $data = Product::where('id', $id)->first();

        $product_picture = json_decode($data->product_picture);
        $technical_picture = json_decode($data->technical_picture);
        //start project count hide
        // $project = DB::table('product_project')->select(
        //     'project_id'
        // )->where('product_id', $id)->get();
        // $names = array();
        // $refined_names = array();
        // for ($i = 0; $i < count($project); $i++) {
        //     $project_data = WiloProjectManagement::where('id', $project[$i]->project_id)->pluck('project_name');
        //     array_push($names, $project_data);
        //     array_push($refined_names, $names[$i][0]);
        // }
        // $data['project_names'] = $refined_names;
        //end project count hide
        $data['pro_picture']   = $product_picture;
        $data['tech_picture']  = $technical_picture;
        // dd($data);
        return View('backend.product.view')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data = Product::where('id', $id)->where('is_deleted', '!=', 1)->get();

        $project = DB::table('product_project')->select(
            'project_id'
        )->where('product_id', $id)->get();

        $used_in = array();
        for ($a = 0; $a < count($project); $a++) {
            $p_id = $project[$a]->project_id;
            array_push($used_in, $p_id);
        }

        $project_populate = WiloProjectManagement::all();
        // $project_id = $project_populate->pluck('id')->toArray();
        // $project_name = $project_populate->pluck('project_name')->toArray();

        $product_images = array();
        $technical_images = array();
        for ($i = 0; $i < count($data); $i++) {
            $product_images = json_decode($data[$i]->product_picture);
            $technical_images = json_decode($data[$i]->technical_picture);


            // $data['selected_val'][0]  = $used_in; 
        }
        $data[0]['product_media'] = $product_images;
        $data[0]['technical_media'] = $technical_images;
        $data[0]['selected_val']  = $used_in;
        // $data['selected_val'] = $data[0]->used_in;
        // dd($data[0]->project_name);

        return View('backend.product.edit')
            ->with('data', $data)
            ->with('project', $project)
            ->with('project_name', $project_populate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        // dd("ping");
        
        // dd($ok);
        $number_used = DB::table('product_project')->select('project_id')
                       ->join('wilo_project','wilo_project.id','=','product_project.project_id')
                       ->where('product_id',$id)->where('wilo_project.is_deleted','!=',1)->get();
                    //    dd($number_used);
        $number_used = $number_used->toArray();
        $projects_in = array();
        foreach($number_used as $nu)
            {
                array_push($projects_in,$nu->project_id);
            }
            // dd($projects_in);
        $image_extensions = ["jpeg", "png", "jpg", "gif", "JPEG", "PNG", "JPG", "GIF"];
        $ImageDir = public_path("product/product_pictures/");
        if (!empty($request->p_pictures)) {
            $rules = array();
            array_push($rules,$this->rules_for_image['p_pictures']);
            $validator = Validator::make($request->p_pictures, $rules, $this->messages);
                if ($validator->fails()) {
                    return Redirect::back()->withErrors($validator->customMessages['p_pictures.max'])->withInput();
            }
            for ($k = 0; $k < count($request->p_pictures); $k++) {
                if (!in_array($request->p_pictures[$k]->getClientOriginalExtension(), $image_extensions)) {
                    return redirect()->route('admin.products.index')->withFlashDanger(__('wrong product image format update failed.'));
                }
            }
        }
        //Upload documents hide
        // if (!empty($request->t_pictures)) {
        //     for ($m = 0; $m < count($request->t_pictures); $m++) {
        //         if (!in_array($request->t_pictures[$m]->getClientOriginalExtension(), $image_extensions)) {
        //             return redirect()->route('admin.products.index')->withFlashDanger(__('wrong technical image format update failed.'));
        //         }
        //     }
        // }
        // $technical_array = array();
        $product_array   = array();
        //  dd($request);
        $product = Product::where('id', $id)->first();
        $product_images = Product::where('id', $id)->select("product_picture")->get();
        $product_images = json_decode($product_images[0]->product_picture);
        
            // dd($product_images);
            // dd($product_images);
        if (!empty($request->p_pictures)) {
            
            for ($j = 0; $j < count($request->p_pictures); $j++) {
                $product_picture[$j] = str_slug("product_image_" . $request->p_pictures[$j]->getClientOriginalName() . '-' . date('d-M-Y')) .
                    '-' . system('date +%s%N') . '.' . $request->p_pictures[$j]->getClientOriginalExtension();
                array_push($product_images, $product_picture[$j]);
                $request->p_pictures[$j]->move(public_path("product/product_pictures"), $product_picture[$j]);
                $img = Image::make($ImageDir . $product_picture[$j]);
                // // open file a image resource
    
                // // resize the image to a height of 200 and constrain aspect ratio (auto width)
                 $img->resize(600, null, function ($constraint) {
                     $constraint->aspectRatio();
                     $constraint->upsize();
                });
                 $img->save($ImageDir."slider_". $product_picture[$j]);
                
            }
            // dd($product_images);
            $product_images = json_encode($product_images);
            $product->product_picture = $product_images;
            $product->save();
        }
        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf($projects_in);    

        $product->name =  empty($request->product_name) ? '' : $request->product_name;
        $product->description = empty($request->description) ? '' : $request->description;
        // $product->quantity = $request->quantity;
        $product->max_head = empty($request->max_head) ? '' : $request->max_head;
        $product->max_flow = empty($request->max_flow) ? '' : $request->max_flow;
        $product->technical_spec_url = empty($request->technical_spec_url) ? '' : $request->technical_spec_url;
        $product->video_url = empty($request->video_url) ? '' : $request->video_url;
        $product->save();

        return redirect()->route('admin.products.index')->withFlashSuccess(__('Product updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Product::where('id', $id)->first();
        $data->is_deleted = 1;
        $data->save();
        DB::table('product_project')->where('product_id',$id)->delete();
        return redirect()->route('admin.products.index')->withFlashDanger(__('Product deleted.'));
    }
}
