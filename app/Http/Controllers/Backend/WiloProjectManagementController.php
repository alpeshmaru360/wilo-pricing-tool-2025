<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WiloProjectManagement;
use App\Product;
use App\wilo_application;
use App\project_segment;
use App\factory_manufacturer;
use App\types_project;
use App\StakeHolders;
use App\Country;
use Illuminate\Support\Facades\DB;
use View;
use URL;
use App\Models\Auth\User;
use Intervention\Image\ImageManagerStatic as Image;
use Redirect;
use Illuminate\Support\Facades\Input;
use Validator;

class WiloProjectManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $rules =
    [
        'project_name'      => 'required',
        'apptype'           => 'required|array|min:1|max:6|exists:wilo_application,id',
        //'subapptype'        => 'required|exists:wilo_application,id',
        'typeproject'       => 'required|array|min:1|max:3|exists:wilo_project_type,id',
        'country'           => 'required|exists:country,id',
        'city'               => 'required',
        'project_brief'     => 'required',
        'manufacturer_year' => 'nullable|integer|min:1990|max:2050',
        // 'year'              => 'required',        
        //'projectimage'      => 'required|image|dimensions:min_width=1330,min_height=665',
    ];

    protected $rules_for_image =
    [
        'projectimage'      => 'image|dimensions:min_width=800,min_height=400|max:5120',
    ];

    protected $messages =
    [
        'apptype.exists' => 'The type of application field is required',
        //'subapptype.exists' => 'The application type field is required',
        'typeproject.exists' => 'The type of project field is required',
        'projectimage.required'   => 'The project image field is required.',
        'projectimage.dimensions' => 'The project image dimensions should not be less than 800x400.',
        'projectimage.max' => 'The project image size should not be greater than 5 Mb.',
    ];

    public function index()
    {
        //echo phpinfo();die;
        $application = wilo_application::where('root_id', 0)->where('is_deleted', 0)->orderBy('name')->get();
        $sub_apps = wilo_application::where('root_id', '!=', 0)->where('is_deleted', 0)->orderBy('name')->get();
        $segment = project_segment::where('is_deleted', 0)->orderBy('name')->get();
        $factory_manufacturer = factory_manufacturer::where('is_deleted', 0)->orderBy('name')->get();
        $type_project = types_project::where('is_deleted', 0)->orderBy('name')->get();
        $country = Country::all();

        $query = WiloProjectManagement::select(
            'wilo_project.id',
            'project_name',
            DB::raw('(select group_concat(wpt.name SEPARATOR " || ") from wilo_project_type_selected as wpts
                inner join wilo_project_type as wpt on wpt.id = wpts.project_type_id 
                where wpts.project_id = `wilo_project`.`id`) as type_of_project'),
            'project_segment',
            DB::raw('(select group_concat(wfm.name SEPARATOR " || ") from wilo_project_factory_manufacturer as wpfm
                inner join wilo_factory_manufacturer as wfm on wfm.id = wpfm.factory_manufacturer_id 
                where wpfm.project_id = `wilo_project`.`id`) as factory_manufacturer_text'),
            DB::raw('(select group_concat(wpt.name SEPARATOR " || ") from wilo_project_type_selected as wpts
                inner join wilo_project_type as wpt on wpt.id = wpts.project_type_id 
                where wpts.project_id = `wilo_project`.`id`) as type_of_project'),
            'country_id',
            DB::raw('(select group_concat(wp.name SEPARATOR " || ") from wilo_project_applications as wpa
                inner join wilo_application as wp on wp.id = wpa.application_id 
                where wpa.project_id = `wilo_project`.`id`) as application_type'),
            DB::raw('(select group_concat(wps.name SEPARATOR " || ") from wilo_project_subapplications as wpas
                inner join wilo_application as wps on wps.id = wpas.sub_application_id 
                where wpas.project_id = `wilo_project`.`id`) as sub_application_type')
        )
            ->leftjoin('wilo_project_applications', 'wilo_project.id', '=', 'wilo_project_applications.project_id')
            ->leftjoin('wilo_project_subapplications', 'wilo_project.id', '=', 'wilo_project_subapplications.project_id')
            ->where('is_deleted', 0);

        if (isset($_GET['filter']) && $_GET['filter'] != "") {
            $query->Where('project_name', 'LIKE', '%' . $_GET['filter'] . '%');
        }
        // Dropdown Filters
        if (isset($_GET['country']) && $_GET['country'] != 0) {
            // echo ("country".$_GET['country']);
            $query->Where('country_id', '=', $_GET['country']);
        }

        if (isset($_GET['segment']) && $_GET['segment'] != 0) {
            // echo ("segment".$_GET['segment']);
            $query->Where('project_segment', '=', $_GET['segment']);
        }

        if (isset($_GET['factory_manufacturer']) && $_GET['factory_manufacturer'] != 0) {
            $query->Where('factory_manufacturer', '=', $_GET['factory_manufacturer']);
        }

        if (isset($_GET['p_type']) && $_GET['p_type'] != 0) {
            // echo ("p_type".$_GET['p_type']);
            $query->Where('type_of_project', '=', $_GET['p_type']);
        }

        if ((isset($_GET['application']) && $_GET['application'] != 0) || (isset($_GET['subapplication']) && $_GET['subapplication'] != 0)) {
            //echo ("application".$_GET['application']);
            if (isset($_GET['application']) && $_GET['application'] != 0)
                $query->Where('wilo_project_applications.application_id', '=', $_GET['application']);
            if (isset($_GET['subapplication']) && $_GET['subapplication'] != 0 && isset($_GET['application']) && $_GET['application'] != 0) {
                $query->orWhere('wilo_project_subapplications.sub_application_id', '=', $_GET['subapplication']);
            } else {
                $query->Where('wilo_project_subapplications.sub_application_id', '=', $_GET['subapplication']);
            }
        }

        // if(isset($_GET['subapplication']) && $_GET['subapplication'] != 0){
        //     // echo ("subapplication".$_GET['subapplication']);
        //     $query->Where('wilo_project_subapplications.sub_application_id', '=', $_GET['subapplication']);
        // }

        $data = $query->groupBy('wilo_project.id')->orderBy('project_name', 'asc')->paginate('20');
        //dd($data);

        return View('backend.projectmanagement.index')->with('data', $data)
            ->with('application', $application)
            ->with('sa', $sub_apps)
            ->with('segment', $segment)
            ->with('factory_manufacturer', $factory_manufacturer)
            ->with('sa', $sub_apps)
            ->with('type', $type_project)
            ->with('country', $country);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $get_application = wilo_application::where('root_id', 0)->where('is_deleted', 0)->orderBy('name')->get();
        $get_sub_application = wilo_application::where('root_id', '<>', 0)->where('is_deleted', 0)->orderBy('name')->get();
        $project_segment = project_segment::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $factory_manufacturer = factory_manufacturer::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $project_type = types_project::where('is_deleted', 0)->orderBy('name')->get();
        $stakeholder_client = StakeHolders::where('is_deleted', 0)->where('type', 1)->orderBy('name')->get();
        $stakeholder_consultant = StakeHolders::where('is_deleted', 0)->where('type', 2)->orderBy('name')->get();
        $stakeholder_contractor = StakeHolders::where('is_deleted', 0)->where('type', 3)->orderBy('name')->get();
        $country   = DB::table('country')->select('id', 'name')->get();

        $stakeHolders = new StakeHolders();
        $years = $stakeHolders->getYearManufactureOptions();
        return View('backend.projectmanagement.create')
            ->with('get_application', $get_application)
            ->with('get_sub_application', $get_sub_application)
            ->with('project_segment', $project_segment)
            ->with('factory_manufacturer', $factory_manufacturer)
            ->with('project_type', $project_type)
            ->with('country', $country)
            ->with('stakeholder_client', $stakeholder_client)
            ->with('stakeholder_consultant', $stakeholder_consultant)
            ->with('stakeholder_contractor', $stakeholder_contractor)
            ->with('years', $years);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //	ini_set('upload_tmp_dir', 'C:\Users\a-lohogaonkaya\Desktop\Temp');		
        $this->rules['projectimage'] = $this->rules_for_image['projectimage'];
        $ImageDir = public_path("logo/project_details/main_image/");
        $validator = Validator::make(Input::all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $product_identification = "";

        // Check iamge width and height
        $checkImageDimensions = getimagesize($_FILES['projectimage']["tmp_name"]);
        if (isset($checkImageDimensions[0]) && isset($checkImageDimensions[1])) {

            $width = $checkImageDimensions[0];
            $height = $checkImageDimensions[1];
            if ($width < $height) {

                $validator = Validator::make(Input::all(), $this->rules_for_image, $this->messages);
                $validator = $validator->getMessageBag()->toArray();
                $validator = $validator['projectimage'][0] = $this->messages['projectimage.dimensions'];
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }

        $naming = str_slug($request->project_name . '-' . date('d-M-Y')) .
            '-' . '.' . $request->projectimage->getClientOriginalExtension();

        $request->projectimage->move(public_path("logo/project_details/main_image"), $naming);

        /** Image Resize  */
        // open an image file
        $img = Image::make($ImageDir . $naming);

        // resize the image to a height of 200 and constrain aspect ratio (auto width)
        $img->resize(1500, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save($ImageDir . "h_" . $naming);

        // crop image
        // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
        $img->fit(700, 300, function ($constraint) {
            $constraint->upsize();
        });
        $img->save($ImageDir . "crop_" . $naming);


        // prevent possible upsizing according to width for Thumbnail
        $img->resize(null, 200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save($ImageDir . "thumbnail_" . $naming);
        /** Image Resize End */
        $product_identification = $naming;
        //}
        $data = new WiloProjectManagement();
        $data->project_name = $request->project_name;
        // $data->type_of_project = $request->typeproject;
        //$data->application_type = $request->apptype;
        //$data->sub_application_type = $request->subapptype;
        $data->project_segment = $request->project_segment;

        $data->remarks = $request->remarks;
        $data->project_brief = $request->project_brief;
        $data->project_image =  $product_identification;
        $data->year_of_installation = $request->year;
        $data->country_id = $request->country;
        $data->city = $request->city;
        $data->client_id = $request->client;
        $data->contractor_id = $request->contractor;
        $data->consultant_id = $request->consultant;
        $data->manufacturer_year = $request->manufacturer_year;
        $data->save();
        if (!empty($request->factory_manufacturer)) {
            foreach ($request->factory_manufacturer as $factory_manufacturer) {
                DB::table('wilo_project_factory_manufacturer')->insert(
                    ['project_id' => $data->id, 'factory_manufacturer_id' => $factory_manufacturer]
                );
            }
        }
        if (!empty($request->apptype)) {
            foreach ($request->apptype as $apptype) {
                DB::table('wilo_project_applications')->insert(
                    ['project_id' => $data->id, 'application_id' => $apptype]
                );
            }
        }

        if (!empty($request->subapptype)) {
            foreach ($request->subapptype as $subapptype) {
                DB::table('wilo_project_subapplications')->insert(
                    ['project_id' => $data->id, 'sub_application_id' => $subapptype]
                );
            }
        }

        if (!empty($request->typeproject)) {
            foreach ($request->typeproject as $type) {
                DB::table('wilo_project_type_selected')->insert(
                    ['project_id' => $data->id, 'project_type_id' => $type]
                );
            }
        }
        // dd($following_project);
        // for($i =0 ; $i<count($request->users) ; $i++)
        // {
        //     DB::table('project_to_user')->insert(
        //         ['projectid' => $data->id, 'userid' => $request->users[$i] ]
        //     );
        // }

        $data2 = WiloProjectManagement::where('id', $data->id)->where('is_deleted', 0)
            // ->where('id',$id)
            ->with('application')
            ->with('subapplication')
            ->with('projectType')
            ->with('projectSegment')
            ->with('stakeholder')
            ->with('stakeholderconsultant')
            ->with('stakeholdercontractor')
            ->with('country')->get();
        // $data2[0]['new_create'] = "true";

        // $data[0]['on_boarding'] = "true";


        $get_application = wilo_application::where('root_id', 0)->where('is_deleted', 0)->get();
        $get_sub_application1 = wilo_application::where('root_id', "!=", 0)->where('is_deleted', 0)->get();
        $project_segment = project_segment::where('is_deleted', 0)->get();
        $factory_manufacturer = factory_manufacturer::where('is_deleted', 0)->get();
        $project_type = types_project::where('is_deleted', 0)->get();
        $stakeholder = StakeHolders::where('is_deleted', "!=", 1)->get();
        $country   = DB::table('country')->select('id', 'name')->get();

        //*****************New Phase Development**********************
        //*****************PDF Creation*******************************
        // app('App\Http\Controllers\PrintReportController')->getPrintReport();

        // $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->single_pdf_download($data->id);
        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf(array($data->id));
        if ($pdf_response == true) {
            DB::table('project_pdf')->where('project_id', $data->id)->updateOrInsert(
                ['project_id' => $data->id, 'project_pdf' => $data->project_name . '_' . $data->id . '.pdf']
            );
        }



        return redirect('admin/projectmanagement/' . $data2[0]->id . '/edit')->with('data', $data2)
            ->with('sub_app', $get_sub_application1)
            ->with('get_application', $get_application)
            ->with('project_segment', $project_segment)
            ->with('factory_manufacturer', $factory_manufacturer)
            ->with('project_type', $project_type)
            ->with('country', $country)
            ->with('stakeholder', $stakeholder);


        // return redirect()->route('admin.projectmanagement.index')->withFlashSuccess(__('Project created.'));



    }
    /**
     * Display the specified resource.
     *
     * @param  \App\WiloProjectManagement  $wiloProjectManagement
     * @return \Illuminate\Http\Response
     */
    public function show(WiloProjectManagement $wiloProjectManagement, $id)
    {
        //
        $data = WiloProjectManagement::where('is_deleted', 0)
            ->where('id', $id)
            ->with('projectType')
            ->with('projectSegment')
            ->with('stakeholder')
            ->with('stakeholderconsultant')
            ->with('stakeholdercontractor')
            ->with('country')->get();
        // dd($data);
        $data['applications'] = wilo_application::select('name')
            ->join('wilo_project_applications as wpa', 'wpa.application_id', '=', 'wilo_application.id')
            ->where('is_deleted', 0)
            ->where('root_id', 0)
            ->where('wpa.project_id', $id)->get();

        $data['subapplications'] = wilo_application::select('name')
            ->join('wilo_project_subapplications as wpa', 'wpa.sub_application_id', '=', 'wilo_application.id')
            ->where('is_deleted', 0)
            ->where('root_id', '<>', 0)
            ->where('wpa.project_id', $id)->get();

        return view('backend.projectmanagement.show')
            ->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WiloProjectManagement  $wiloProjectManagement
     * @return \Illuminate\Http\Response
     */
    public function edit(WiloProjectManagement $wiloProjectManagement, $id)
    {
        $assoc = DB::table('wilo_project_applications')->select('application_id')->where('project_id', $id)->get();
        // dd($assoc);
        $a_id = array();

        foreach ($assoc as $as) {
            array_push($a_id, $as->application_id);
        }

        // dd($a_id);

        $key_value = wilo_application::wherein('id', $a_id)->get();
        foreach ($key_value as $kv) {
            $name[$kv->name] = wilo_application::where('root_id', $kv->id)->get();
        }

        // dd($name);
        // echo "<pre>";
        // print_r($name);
        // echo "</pre>";
        // die;
        //  dd($name["Water Supply"][1]['name']);


        $result = DB::table('product_project')
            ->select(
                'product_project.id',
                'product_project.product_id',
                'product_project.project_id',
                'product_project.quantity',
                'product_project.max_head',
                'product_project.max_flow',
                'wilo_product.name'
            )
            ->join('wilo_product', 'wilo_product.id', '=', 'product_project.product_id')
            ->where('product_project.project_id', $id)->where('wilo_product.is_deleted', 0)->get()->toArray();

        $data = WiloProjectManagement::select(
            'wilo_project.*',
            DB::raw('(select group_concat(wp.id) from wilo_project_applications as wpa
                inner join wilo_application as wp on wp.id = wpa.application_id 
                where wpa.project_id = `wilo_project`.`id`) as application_type_ids'),
            DB::raw('(select group_concat(wps.id) from wilo_project_subapplications as wpas
                inner join wilo_application as wps on wps.id = wpas.sub_application_id 
                where wpas.project_id = `wilo_project`.`id`) as sub_application_type_ids'),
            DB::raw('(select group_concat(wpt.project_type_id) from wilo_project_type_selected as wpt
                where wpt.project_id = `wilo_project`.`id`) as type_of_project_ids'),
            DB::raw('(select group_concat(wpfm.factory_manufacturer_id) from wilo_project_factory_manufacturer as wpfm
                where wpfm.project_id = `wilo_project`.`id`) as factory_manufacturer_ids')
        )
            ->where('is_deleted', 0)
            ->where('id', $id)
            ->get();

        //$get_sub_application1 = wilo_application::where('root_id', $data[0]->application_type)->where('is_deleted', 0)->get();
        $get_sub_application1 = wilo_application::whereIn('root_id', explode(',', $data[0]->application_type_ids))->where('is_deleted', 0)->orderBy('name')->get();
        $get_application = wilo_application::where('root_id', 0)->where('is_deleted', 0)->get();
        $project_segment = project_segment::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $factory_manufacturer = factory_manufacturer::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $project_type = types_project::where('is_deleted', 0)->orderBy('name', 'asc')->get();
        $stakeholder_client = StakeHolders::where('is_deleted', 0)->where('type', 1)->orderBy('name')->get();
        $stakeholder_consultant = StakeHolders::where('is_deleted', 0)->where('type', 2)->orderBy('name')->get();
        $stakeholder_contractor = StakeHolders::where('is_deleted', 0)->where('type', 3)->orderBy('name')->get();
        $country   = DB::table('country')->select('id', 'name')->get();
        
        $stakeHolders = new StakeHolders();
        $years = $stakeHolders->getYearManufactureOptions();

        return View('backend.projectmanagement.edit')->with('data', $data)->with('res', $result)
            ->with('sub_app', $get_sub_application1)
            ->with('get_application', $get_application)
            ->with('project_segment', $project_segment)
            ->with('factory_manufacturer', $factory_manufacturer)
            ->with('project_type', $project_type)
            ->with('country', $country)
            ->with('stakeholder_client', $stakeholder_client)
            ->with('stakeholder_consultant', $stakeholder_consultant)
            ->with('stakeholder_contractor', $stakeholder_contractor)
            ->with('name', $name)
            ->with('years', $years);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WiloProjectManagement  $wiloProjectManagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WiloProjectManagement $wiloProjectManagement, $id)
    {

        //$this->rules['projectimage'] = $this->rules_for_image['projectimage'];
        $ImageDir = public_path("logo/project_details/main_image/");
        $validator = Validator::make(Input::all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            //return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = WiloProjectManagement::where('id', $id)->first();
        if (!empty($request->file('projectimage'))) {

            $validator = Validator::make(Input::all(), $this->rules_for_image, $this->messages);
            if ($validator->fails()) {
                //return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
                return Redirect::back()->withErrors($validator)->withInput();
            }

            // Check iamge width and height
            $checkImageDimensions = getimagesize($_FILES['projectimage']["tmp_name"]);
            if (isset($checkImageDimensions[0]) && isset($checkImageDimensions[1])) {

                $width = $checkImageDimensions[0];
                $height = $checkImageDimensions[1];
                if ($width < $height) {

                    $validator = Validator::make(Input::all(), $this->rules_for_image, $this->messages);
                    $validator = $validator->getMessageBag()->toArray();
                    $validator = $validator['projectimage'][0] = $this->messages['projectimage.dimensions'];
                    return Redirect::back()->withErrors($validator)->withInput();
                }
            }

            $naming = str_slug($request->project_name . '-' . date('d-M-Y')) .
                '-' . '.' . $request->projectimage->getClientOriginalExtension();

            $request->projectimage->move(public_path("logo/project_details/main_image"), $naming);

            /** Image Resize  */
            // open an image file
            $img = Image::make($ImageDir . $naming);

            // open file a image resource

            // resize the image to a height of 200 and constrain aspect ratio (auto width)
            $img->resize(1500, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($ImageDir . "h_" . $naming);

            // crop image
            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $img->fit(700, 300, function ($constraint) {
                $constraint->upsize();
            });
            $img->save($ImageDir . "crop_" . $naming);

            // prevent possible upsizing according to width for Thumbnail
            $img->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($ImageDir . "thumbnail_" . $naming);


            /** Image Resize End */

            $data->project_image = $naming;
        }
        $data->project_name = $request->project_name;
        // $data->type_of_project = $request->typeproject;
        // $data->application_type = $request->apptype;
        // $data->sub_application_type = $request->subapptype;
        $data->project_segment = $request->project_segment;
        // $data->factory_manufacturer = $request->factory_manufacturer;
        $data->remarks = $request->remarks;
        $data->project_brief = $request->project_brief;
        $data->year_of_installation = $request->year;
        $data->country_id = $request->country;
        $data->city = $request->city;
        $data->client_id = $request->client;
        $data->contractor_id = $request->contractor;
        $data->consultant_id = $request->consultant;
        $data->manufacturer_year = $request->manufacturer_year;
        $data->save();
        DB::table('wilo_project_factory_manufacturer')->where('project_id', $data->id)->delete();
        if (!empty($request->factory_manufacturer)) {
            foreach ($request->factory_manufacturer as $factory_manufacturer) {
                DB::table('wilo_project_factory_manufacturer')->insert(
                    ['project_id' => $data->id, 'factory_manufacturer_id' => $factory_manufacturer]
                );
            }
        }
        if (!empty($request->apptype)) {
            DB::table('wilo_project_applications')->where('project_id', $data->id)->delete();
            foreach ($request->apptype as $apptype) {
                DB::table('wilo_project_applications')->insert(
                    ['project_id' => $data->id, 'application_id' => $apptype]
                );
            }
        }
        if (!empty($request->subapptype)) {
            DB::table('wilo_project_subapplications')->where('project_id', $data->id)->delete();
            foreach ($request->subapptype as $subapptype) {
                DB::table('wilo_project_subapplications')->insert(
                    ['project_id' => $data->id, 'sub_application_id' => $subapptype]
                );
            }
        } else if ($request->subapptype == null) {
            DB::table('wilo_project_subapplications')->where('project_id', $data->id)->delete();
        }

        if (!empty($request->typeproject)) {
            DB::table('wilo_project_type_selected')->where('project_id', $data->id)->delete();
            foreach ($request->typeproject as $type) {
                DB::table('wilo_project_type_selected')->insert(
                    ['project_id' => $data->id, 'project_type_id' => $type]
                );
            }
        } else {
            DB::table('wilo_project_type_selected')->where('project_id', $data->id)->delete();
        }

        /** PDF Generation Start **/

        DB::select(DB::raw('DELETE FROM project_to_user WHERE (projectid) IN (' . $id . ') '));
        $pdf_response = app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf(array($id));
        if ($pdf_response == true) {
            DB::table('project_pdf')->where('project_id', $id)->updateOrInsert(
                ['project_id' => $data->id, 'project_pdf' => $data->project_name . '_' . $data->id . '.pdf']
            );
        }

        /** PDF Generation End **/
        if (strpos($request->referer_url, "create") == true)
            return redirect()->route('admin.projectmanagement.index')->withFlashSuccess(__('Project created Successfully.'));
        else
            return redirect()->route('admin.projectmanagement.index')->withFlashSuccess(__('Project updated Successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WiloProjectManagement  $wiloProjectManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = WiloProjectManagement::where('id', $id)->first();
        $data->is_deleted = 1;
        $data->save();
        return redirect()->route('admin.projectmanagement.index')->withFlashDanger(__('project deleted.'));
    }

    public function search_result(Request $request)
    {
        // $country= '';
        // $segments= '';
        // $application = '';
        // $subapplication = '';
        // $project = '';
        if (
            !is_numeric($request->country) && !is_numeric($request->segment)
            && !is_numeric($request->p_type) && !is_numeric($request->application)
            && !is_numeric($request->subapplication)
        ) {
            return redirect()->route('admin.projectmanagement.index');
        }
        $query =  'select * from wilo_project';
        //$query ='';
        $querystr = '';

        if (is_numeric($request->country)) {
            // foreach($request->country as $row1)
            // {
            //     $country .= $row1.',';

            //     // array_push($country,$row1);

            // }

            // $country = substr($country, 0, -1);

            $querystr .= " Where country_id = $request->country";
        }
        if (is_numeric($request->segment)) {
            // foreach($request->segments as $row2)
            // {
            //     $segments .= $row2.',';
            //     // array_push($country,$row1);

            // }
            // $segments = substr($segments, 0, -1);
            if (!empty($querystr)) {
                $querystr .= " and project_segment = $request->segment";
            } else {
                $querystr .= " Where project_segment = $request->segment";
            }
        }
        if (is_numeric($request->p_type)) {
            // foreach($request->applications as $row3)
            // {
            // $application .= $row3.',';
            // array_push($country,$row1);
            //   
            // }
            // $application = substr($application, 0, -1);
            if (!empty($querystr)) {
                $querystr .= " and type_of_project = $request->p_type";
            } else {
                $querystr .= " Where type_of_project = $request->p_type";
            }
        }

        if (is_numeric($request->application)) {
            // foreach($request->sub_apps as $row4)
            // {
            //     $subapplication .= $row4.',';
            //     // array_push($country,$row1);

            // }
            // $subapplication = substr($subapplication, 0, -1);
            if (!empty($querystr)) {
                $querystr .= " and application_type = $request->application";
            } else {
                $querystr .= " Where application_type = $request->application";
            }
        }

        if (is_numeric($request->subapplication)) {
            // foreach($request->project_types as $row5)
            // {
            // $project = '';
            // $project .= $row5.',';
            // array_push($country,$row1);
            //   
            // }
            // $project =  substr($project, 0, -1);
            if (!empty($querystr)) {
                $querystr .= " and sub_application_type = $request->subapplication";
            } else {
                $querystr .= " where sub_application_type = $request->subapplication";
            }
        }
        // if(isset($request->project_name) && $request->project_name != null)
        // {
        //     if(!empty($querystr))
        //     {
        //         $querystr .=" and project_name LIKE '%$request->project_name%'";
        //     }
        //     else
        //     {
        //         $querystr .=" Where project_name LIKE '%$request->project_name%'";
        //     }
        // }

        $query .= $querystr;
        // dd($query);
        $data = DB::select($query);
        // dd($data);
        if (count($data) != 0) {
            foreach ($data as $da) {
                $application = wilo_application::where('id', $da->application_type)->get();
                if (count($application) != 0) {
                    $application_name = $application[0]->name;
                    $da->a_name = $application_name;
                }

                $segment = project_segment::where('id', $da->project_segment)->get();
                if (count($segment) != 0) {
                    $segment_name = $segment[0]->name;
                    $da->s_name = $segment_name;
                }

                $factory_manufacturer = factory_manufacturer::where('id', $da->factory_manufacturer)->get();
                if (count($factory_manufacturer) != 0) {
                    $factory_manufacturer_name = $factory_manufacturer[0]->name;
                    $da->s_name = $factory_manufacturer_name;
                }



                $type = types_project::where('id', $da->type_of_project)->get();
                if (count($type) != 0) {
                    $type_name = $type[0]->name;
                    $da->t_name = $type_name;
                }

                $country = Country::where('id', $da->country_id)->get();
                if (count($country) != 0) {
                    $country_name = $country[0]->name;
                    $da->c_name = $country_name;
                }

                $subapplication = wilo_application::where('id', $da->sub_application_type)->get();
                if (count($subapplication) != 0) {
                    $subapplication_name = $application[0]->name;
                    $da->sa_name = $subapplication_name;
                }
            }
        }
        //  dd($data);
        //  $segment = project_segment::where('is_deleted',0)->get();
        //  $type_project = types_project::where('is_deleted',0)->get();
        //  $country = Country::all();
        // dd($data);
        return view('backend.projectmanagement.search_result')->with("data", $data);
    }

    public function on_boarding(Request $request)
    {
        //  $a[0] = 3;
        // dd($request->products);
        $data =  WiloProjectManagement::where('id', $request->project_id)->first();
        $data->project_name = $request->project_name;
        $data->type_of_project = $request->typeproject;
        $data->application_type = $request->apptype;
        $data->sub_application_type = $request->subapptype;
        $data->project_segment = $request->project_segment;
        $data->factory_manufacturer = $request->factory_manufacturer;
        // $data->remarks = $request->remarks;
        $data->project_brief = $request->project_brief;
        // $data->project_image = $naming;
        $data->year_of_installation = $request->year;
        $data->country_id = $request->country;
        $data->city = $request->city;
        $data->client_id = $request->client;
        $data->contractor_id = $request->contractor;
        $data->consultant_id = $request->consultant;
        $data->save();
        $fault = false;
        if (isset($request->users)) {
            for ($i = 0; $i < count($request->users); $i++) {
                if ($request["role_of_" . $request->users[$i]] != 'Select Role') {
                    DB::table('project_to_user')->insert(
                        ['projectid' => $request->project_id, 'userid' => $request->users[$i], 'user_role' => $request["role_of_" . $request->users[$i]]]
                    );
                } else {
                    $fault = true;
                }
            }
        }
        if (isset($request->products)) {
            for ($x = 0; $x < count($request->products); $x++) {

                DB::table('product_project')->insert(
                    ['product_id' => $request->products[$x], 'project_id' => $request->project_id, 'quantity' => $request["quantity_of_" . $request->products[$x]]]
                );
            }
        }
        if ($fault == true) {
            return redirect()->route('admin.projectmanagement.index')->withFlashSuccess(__('Project created Successfully but role not assigned to selected user.'));
        } else {
            return redirect()->route('admin.projectmanagement.index')->withFlashSuccess(__('Project created Successfully.'));
        }
    }

    public function attribute(Request $request, $id)
    {
        DB::table('product_project')->where('project_id', $id)->delete();

        // $pushable = array();
        $at = array();
        // dd($request);
        if (isset($request["attributes"])) {

            for ($x = 0; $x < count($request["attributes"]); $x++) {

                // die;
                DB::table('product_project')->insert(
                    [
                        'project_id' => $request["attributes"][$x]['project_id'],
                        'product_id' => $request["attributes"][$x]['product_id'],
                        'quantity'   => $request["attributes"][$x]['quantity'],
                        // 'max_head'   => $request["attributes"][$x]['max_head'],
                        // 'max_flow'   => $request["attributes"][$x]['max_flow'],
                    ]
                );

                $attr = DB::table('product_project')->select('quantity'/*, 'max_head', 'max_flow'*/)->where('product_id', $request["attributes"][$x]['product_id'])
                    ->where('project_id', $request["attributes"][$x]['project_id'])->get();

                array_push($at, $attr);
                $name_of_product = Product::select('name')->where('id', $request["attributes"][$x]['product_id'])->get();
                $at[$x]["name"] = $name_of_product;
                // $pushable[$x]['attributes'] = $attr;

            }


            // dd($request["attributes"][0]['project_id']);
        }
        // [0][0]->quantity
        return $at;
    }
}
