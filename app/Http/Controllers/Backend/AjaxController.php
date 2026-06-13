<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\sub_application;
use App\Country;
use App\StakeHolders;
use App\WiloProjectManagement;
use App\Continent;
use App\Product;
use App\project_segment;
use App\factory_manufacturer;
use App\wilo_application;
use App\types_project;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\WiloUserToManager;
use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{

    public function SubApplication($id)
    {   
        $data = sub_application::where('root_id', $id)->orderBy('name')->get();
        return $data;
    }

    public function multipleSubApplication($ids)
    {   
        if($ids != 0){
            $tmp = [];
            $parent = wilo_application::whereIn('id',explode(',',$ids))->get();
            foreach($parent as $p){
                $tmp[$p->name] = wilo_application::where('root_id', $p->id)->get();
            }
            // $data = DB::table('wilo_application as wp1')
            // ->select(
            //     'wp1.id', 
            //     'wp1.name', 
            //     'wp1.root_id', 
            //     'wp2.name as parent'
            // )
            // ->join('wilo_application as wp2','wp1.root_id','=','wp2.id')
            // ->whereIn('wp1.root_id', explode(',',$ids))->get();
            return $tmp;
        }
        return false;
    }

    public function ajaximage($id)
    {
        // dd($id);
        if ($id == 0) {

            $url = url('images') . "/" . "placeholder_img.png";
        } else {
            $data = StakeHolders::where('id', $id)->first();

            if ($data->type == 1)
                $url = url('logo/client_logo') . "/" . $data->logo;
            if ($data->type == 2)
                $url = url('logo/consultant_logo') . "/" . $data->logo;
            if ($data->type == 3)
                $url = url('logo/contractor_logo') . "/" . $data->logo;
        }
        return $url;
    }

    public function projectimage($id)
    {
        $data = WiloProjectManagement::where('id', $id)->pluck('project_image');
        $url = url('logo/project_details/main_image') . "/" . $data[0];
        return $url;
    }

    public function users()
    {


        $data = User::where('active', 1)
            ->where('user_type', '!=', 1)
            ->get();

        //dd($data);
        $data2 = DB::table('users')
            ->join('wilo_user_manager', function ($join) {
                $join->on('users.id', '=', 'wilo_user_manager.manager_id');
            })->groupBy('wilo_user_manager.manager_id')
            ->get();
        //dd($data2);
        $data2 = $data2->toArray();
        $data1 = array();
        foreach ($data2 as $row) {
            // ??dd($row2);

            $data1[] = $row->id;
        }


        foreach ($data as $key => $row) {
            // dd($row->id);
            $id = $row->id;
            if (in_array($id, $data1)) {
                $data[$key]->Manager = true;
                // $data[$key]->manager_id = $id;  
            } else {
                $data[$key]->Manager = false;
                // $data[$key]->manager_id = 0;  
            }
        }
        //dd($data);
        // /dd($data1);
        //         select * from users
        // where user_type !=1
        // select * from users 
        // join wilo_user_manager on users.id = wilo_user_manager.manager_id
        // GROUP by wilo_user_manager.manager_id

        return $data;
    }

    public function getuser($id)
    {
        // $data = $_POST['data'];
        //   select * from project_to_user where projectid = 2
        $data3 = DB::select("select * from project_to_user where projectid =" . $id . "");
        $data4 = array();
        foreach ($data3 as $row) {
            // ??dd($row2);
            $data4[] = $row->userid;
        }
        //dd($data4);


        $data = User::where('active', 1)
            ->where('user_type', '!=', 1)
            ->get();

        //dd($data);
        $data2 = DB::table('users')
            ->join('wilo_user_manager', function ($join) {
                $join->on('users.id', '=', 'wilo_user_manager.manager_id');
            })->groupBy('wilo_user_manager.manager_id')
            ->get();
        //dd($data2);
        $data2 = $data2->toArray();
        $data1 = array();
        foreach ($data2 as $row) {
            // ??dd($row2);
            $data1[] = $row->id;
        }


        foreach ($data as $key => $row) {
            // dd($row->id);
            $id = $row->id;
            if (in_array($id, $data1)) {
                $data[$key]->Manager = true;
            } else {
                $data[$key]->Manager = false;
                //$data[$key]->manager_id = 0;  
            }
        }
        foreach ($data as $key => $row) {
            // dd($row->id);
            $id = $row->id;
            if (in_array($id, $data4)) {
                $data[$key]->Check = true;
            } else {
                $data[$key]->check = false;
            }
        }


        return $data;
        //dd($data);
    }
    public function manager_user($id)
    {

        //dd("dd");
        // select * from users join wilo_user_manager on users.id = wilo_user_manager.user_id where wilo_user_manager.manager_id = 67 GROUP by wilo_user_manager.user_id

        $data = DB::table('users')
            ->join('wilo_user_manager', function ($join) use ($id) {
                $join->on('users.id', '=', 'wilo_user_manager.user_id')
                    ->where('wilo_user_manager.manager_id', '=', $id);
            })->groupBy('wilo_user_manager.user_id')
            ->get();
        return $data;
        //dd($data);
    }
    public function globe_data()
    {
        //  dd("here");
        // dd(Hash::make('secret'));
        $user_id =  auth()->user()->id;
        //  dd(Auth::user()->roles[0]->id);
        //dd(Auth::user()->roles[0]);
        if (isset(Auth::user()->id)) {

            // $datauser = WiloUserToManager::where('manager_id', $user_id)->get();
            //dd($datauser[1]->user_id);
            // $usersid = '';
            // foreach ($datauser as $row) {
            //     $usersid .= $row->user_id . ',';
            // }
            // $usersid = substr($usersid, 0, -1);
            // dd($usersid);
            $data = DB::select("SELECT country.*, continent.name as regionname,COUNT(DISTINCT wp.id) as ProjectsCount, COUNT(DISTINCT wp.contractor_id) as ContractorCount, wp.is_deleted, COUNT(DISTINCT wp.consultant_id) as consultantCount,
            COUNT(DISTINCT city) as CityCount FROM `wilo_project` wp 
    -- join project_to_user ptu on wp.id = ptu.projectid
    join country  on country.id = wp.country_id
    join continent on country.continent_id = continent.id
    where wp.is_deleted = 0 ANd country.is_active = 1
    GROUP by wp.project_name, wp.is_deleted , country.is_active");
            // dd($data);
        } else {
            $data = DB::select("SELECT country.*, continent.name as regionname,COUNT(DISTINCT wp.id) as ProjectsCount, COUNT(DISTINCT wp.contractor_id) as ContractorCount, wp.is_deleted, COUNT(DISTINCT wp.consultant_id) as consultantCount,
            COUNT(DISTINCT city) as CityCount FROM `wilo_project` wp 
    -- join project_to_user ptu on wp.id = ptu.projectid
    join country  on country.id = wp.country_id
    join continent on country.continent_id = continent.id
    where wp.is_deleted = 0 ANd country.is_active = 1
    GROUP by wp.project_name, wp.is_deleted , country.is_active");
            //dd($user_id);
            // $data =    DB::select("SELECT country.*,con.name as regionname,COUNT(DISTINCT wp.id) as ProjectsCount, COUNT(DISTINCT wp.contractor_id) as ContractorCount, COUNT(DISTINCT wp.consultant_id) as consultantCount,
            // COUNT(DISTINCT city) as CityCount FROM `country`
            // JOIN continent con on country.id = con.id
            // join wilo_project wp on country.id = wp.country_id
            // GROUP  BY country.name");
        }
        // dd($data);
        return $data;
    }

    public function only_continents()
    {
        // dd(WiloProjectManagement::advancesearchpopulator());
        // $data = Continent::where('is_active', 1)->orderBy('name')->get();
        $data = Continent::select('continent.*')->join('country', 'continent.id', '=', 'country.continent_id')
                ->join('wilo_project','wilo_project.country_id','=','country.id')
                ->where('continent.is_active', 1)->orderBy('continent.name')
                ->where('wilo_project.is_deleted', 0)
                ->groupBy('continent.name')
                ->get();
        return $data;
    }

    public function only_continent_name($id)
    {
        // dd(WiloProjectManagement::advancesearchpopulator());
        $data = Continent::where('id', $id)->where('is_active', 1)->get();
        return $data;
    }

    public function only_application_name($id)
    {
        // dd(WiloProjectManagement::advancesearchpopulator());
        $data = wilo_application::where('id', $id)->where('is_deleted', 0)->get();
        return $data;
    }

    public function continent_countries($id)
    {
        $data = DB::Select("SELECT DISTINCT c.name, c.* FROM 
        `country` as c join wilo_project as p on p.country_id = c.id 
        where continent_id = $id AND is_deleted = 0 ORDER BY c.name");
        //   $data = Country::where('continent_id', $id)->where('is_active', 1)->orderBy('name')->get();
        return $data;
    }

    public function front_end_segment()
    {

        $data = project_segment::where('is_deleted', 0)->orderBy('name')->get();
        return $data;
    }

    public function front_end_factory_manufacturer()
    {

        $data = factory_manufacturer::where('is_deleted', 0)->orderBy('name')->get();
        return $data;
    }

    public function front_end_application()
    {
        $data = wilo_application::where('root_id', 0)->where('is_deleted', 0)->orderBy('name')->get();
        return $data;
    }

    public function front_end_subapplication($id)
    {
        $data = sub_application::where('root_id', $id)->orderBy('name')->get();
        return $data;
    }

    public function front_end_projecttype()
    {
        $data = types_project::where('is_deleted', 0)->orderBy('name')->get();
        return $data;
    }

    public function globe_projects($id)
    {
        $user = Auth::user();
        $data = DB::select("SELECT country.*,con.name as regionname,COUNT(DISTINCT wp.id) as ProjectsCount, COUNT(DISTINCT wp.contractor_id) as ContractorCount, COUNT(DISTINCT wp.consultant_id) as consultantCount,
    COUNT(DISTINCT city) as CityCount FROM `country`
    JOIN continent con on country.continent_id = con.id
    join wilo_project wp on country.id = wp.country_id
    -- join project_to_user ptu on wp.id = ptu.projectid
    where country.id = $id AND is_deleted = 0
    GROUP  BY country.name");
        // "SELECT country.*, continent.name as regionname,COUNT(DISTINCT wp.id) as ProjectsCount, COUNT(DISTINCT wp.contractor_id) as ContractorCount, wp.is_deleted, COUNT(DISTINCT wp.consultant_id) as consultantCount,
        //         COUNT(DISTINCT city) as CityCount FROM `wilo_project` wp 
        // join project_to_user ptu on wp.id = ptu.projectid
        // join country  on country.id = wp.country_id
        // join continent on country.continent_id = continent.id
        // where ptu.userid in($user_id) And wp.is_deleted = 0 ANd country.is_active = 1
        // GROUP by wp.project_name, wp.is_deleted , country.is_active"
        // dd($data);
        return view('frontend.globe.project_count', compact('data'));
    }


    public function user_search(Request $request)
    {
        // dd($request->search_request);        
        $data = DB::table('users')
            ->where('first_name', 'like', '%' . $request->search_request . '%')
            ->orWhere('last_name', 'like', '%' . $request->search_request . '%')
            ->get();

        return $data;
    }


    public function roles()
    {
        return Role::all();
    }

    public function products_ajax()
    {
        
        $data = Product::where('is_deleted',0)->get();

        foreach ($data as $d) {
            $technical_image = json_decode($d['product_picture']);
            if (!empty($technical_image)) {
                $d['thumbnail_image'] = $technical_image[0];
            } else {
                $d['thumbnail_image'] = "no_image_found";
            }
        }
        //  dd($data);
        return $data;
    }

    public function associated_data($id)
    {
        $users_roles = DB::table('project_to_user')->where('projectid', $id)->get();
        $product_quantity = DB::table('product_project')->where('project_id', $id)->get();

        $response = array();
        $response['users_roles'] = $users_roles;
        $response['products_quantity'] = $product_quantity;
        return $response;
    }

    public function product_quantity($id, $p_id)
    {
        $product_quantity = DB::table('product_project')->where('product_id', $id)->where('project_id', $p_id)->get();
        return $product_quantity;
    }

    public function ajaxDeleteImage(Request $request)
    {
        // dd($request->table);
        $imgs = Product::where('id',$request->id)->pluck('product_picture');
        $imgs = json_decode($imgs[0]);
        if(count($imgs) == 1)
        {
            return "Add atleast one product picture";
        }
        $table_name = $request->table;
        $query = DB::table($table_name)->where('id', $request->id);
        // dd($query);

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if($table_name == 'wilo_project'){
            $data = $query->update(['project_image' => '']);
        }
        else if($table_name == 'wilo_product'){
            $data = $query->get();
            $image_name = $request->imagee;
            $images_array = array();
            $images = json_decode($data[0]->product_picture);
            // dd($images);
            // dd($images);
            // if(in_array($image_name, $images)){
                // dd('yes');
            $image_index = array_search($image_name, $images);
                // dd($images);
            unset($images[$image_index]);
            //dd($images);
            // $new_images = ($images);
            // dd($new_images);
            // echo count($images);
            // die;

            foreach ($images as $image) {
                array_push($images_array, $image);
            }

            $product_images = json_encode($images_array);

            $data = $query->update(['product_picture' => $product_images]);
            // dd($images);
            // }

        }
        else{
            $data = $query->update(['logo' => '']);
        }

        
        
        // dd($data);
        return 'Image Deleted Successfuly';
    }
	
	
	
	
	
	
	
	public function pdf_updater(){
            $ids = DB::table('wilo_project')->where('is_deleted',0)->where('id','>',312)->paginate(20);
			//$ids = DB::table('wilo_project')->where('is_deleted',0)->paginate(20);
            $count = 0;
            $p_id = 0; 
            foreach($ids as $d){
                
                app('App\Http\Controllers\Frontend\ProjectFrontendController')->update_pdf(array($d->id));    
                $count++;
                if($count == 20){
                    $p_id = $d->id;
                }
            }
            return $count." pdf updated and last pd id is ".$p_id;
    }
}
