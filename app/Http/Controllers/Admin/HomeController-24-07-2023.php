<?php

namespace App\Http\Controllers\Admin;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Country;
use App\Quotation;
use Excel;
use App\Exports\QuotationDetailsExcel;

class HomeController
{
    public function index()
    {
        // PIE CHART 1 STARTED..
        $unique_quotation = Quotation::select("users.id as userId","users.country_id","quotations.id as quotationId","quotations.user_id","quotations.quotation_number","countries.id","countries.country")
                        ->selectRaw('COUNT(distinct quotations.quotation_number) as count')
                        ->leftJoin("users","users.id","=","quotations.user_id")
                        ->leftJoin("countries","countries.id","=","users.country_id")
                        ->groupBy("users.country_id")
                        ->get();
        
        $country = Country::select('*')->get();
        $data = array();
        $data1 = array();

        foreach($unique_quotation as $val)
        {
            if($val->count)
            {
                $data[$val->country] = $val->count;
            }
        }
        foreach($country as $val_con)
        {
            if($val_con->country)
            {
                $data1[$val_con->country] = 0;
            }
        }
        
        $keys = array_fill_keys(array_keys($data + $data1), 0);
        $data2 = array_fill_keys(array_keys($data + $data1), 0);
        array_walk($data2, function (&$value, $key, $arrs) { $value = @($arrs[0][$key] + $arrs[1][$key]); }, array($data, $data1));
        $data3 =json_encode($data2);
        //PIE CHART 1 COMPLETED..

        //PIE CHART 2 STARTED..
        $country_quotation_value = Quotation::select('countries.id as CountryId','countries.country','users.id as UserId','users.country_id','quotations.user_id')
        ->selectRaw('SUM(total_quotation_value) as total')
        ->leftJoin('users','users.id','=','quotations.user_id')
        ->leftJoin('countries','countries.id','=','users.country_id')
        ->groupBy('users.country_id')
        ->get();
        
        $data4 = array();
        $data5 = array();

        foreach($country_quotation_value as $value)
        {
            $data4[$value->country] = round($value->total);
        }
        foreach($country as $val_country)
        {
            $data5[$val_country->country] = 0;
        }
        
        $sums = array_fill_keys(array_keys($data4 + $data5), 0);
        array_walk($sums, function (&$value, $key, $arrs) { $value = @($arrs[0][$key] + $arrs[1][$key]); }, array($data4, $data5));
        $array_merge = json_encode($sums);
        //PIE CHART 2 COMPLETED..
        return view('home',['data3'=>$data3,'array_merge'=>$array_merge]);
    }

    public function document(){
         
        return view('admin.document.update');
    }

    public function tool_tip_page()
    {
        // $data = [
        //     [
        //     'name'=>'Booster set',
        //     'created_at'=>Carbon::now(),
        //     'updated_at'=>Carbon::now(),
        // ],

        // [
        //     'name'=>'Control Panel',
        //     'created_at'=>Carbon::now(),
        //     'updated_at'=>Carbon::now(),
        // ],

        // [
        //     'name'=>'Scp Pump Assembly',
        //     'created_at'=>Carbon::now(),
        //     'updated_at'=>Carbon::now(),
        // ],

        // [
        //     'name'=>'Atmos Giga',
        //     'created_at'=>Carbon::now(),
        //     'updated_at'=>Carbon::now(),
        // ]];
        // DB::table('parts')->insert($data);
        return view('admin.tool_tip.parts');
    }

    public function booster_set(){
        // $data = [
        //     [
        //         'component_name'=>'pump_info',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'control_panel',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'application',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],

        //     [
        //         'component_name'=>'ambient_type',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'stater_type',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'communication_protocol',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'ip_rating',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'components',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'enclosure',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'optional',
        //         'part_id' => 1,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        // ];
        //     DB::table('tool_tip')->insert($data);
        $current_data = DB::table('tool_tip')->where('part_id',1)->get();
        return view('admin.tool_tip.booster_set')->with('current_data',$current_data);
        
    }

    public function save_booster_tool_tip(Request $request){

        
        $data = $request->all();
        unset($data['_token']);
        
        foreach($data as $key => $val){
         
            DB::table('tool_tip')->where('component_name',$key)->where('part_id',1)->update([
                'tool_tip' => $val
            ]);
        
        }

        return view('admin.tool_tip.parts');
    }
    
    public function atmos_giga(){
        // $data = [
        //     [
        //         'component_name'=>'pump_model',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'impeller_material',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'motor_power',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'power_supply',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'frequency',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'no_of_poles',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'efficiency',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'motor_brand',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'application',
        //         'part_id' => 4,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
          
    
           
    
        // ];
        //     DB::table('tool_tip')->insert($data);
        $current_data = DB::table('tool_tip')->where('part_id',4)->get();
        return view('admin.tool_tip.atmos_giga')->with('current_data',$current_data);

    }

    public function control_panel(){
        // $data = [
        //     [
        //         'component_name'=>'article_number',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'no_of_pumps',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'motor_power',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'supply_voltage',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'application',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'ambient_temp',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'stater_type',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'communication_protocol',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'ip_rating',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'components',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'enclosure',
        //         'part_id' => 2,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
           
    
        // ];
        //     DB::table('tool_tip')->insert($data);


        $current_data = DB::table('tool_tip')->where('part_id',2)->get();
        return view('admin.tool_tip.control_panel')->with('current_data',$current_data);

    }

    public function scp_pumps(){
        // $data = [
        //     [
        //         'component_name'=>'pump_model',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'impeller_material',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'seal_gland_pack',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'motor_power',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'power_supply',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'frequency',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'no_of_poles',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'efficiency',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'motor_brand',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
        //     [
        //         'component_name'=>'application',
        //         'part_id' => 3,
        //         'created_at'=>Carbon::now(),
        //         'updated_at'=>Carbon::now(),
        //     ],
    
          
    
           
    
        // ];
        //     DB::table('tool_tip')->insert($data);
        $current_data = DB::table('tool_tip')->where('part_id',3)->get();
        return view('admin.tool_tip.scp_pump_assemby')->with('current_data',$current_data);

    }

    public function save_control_panel_tool_tip(Request $request){

        // dd($request);
        $data = $request->all();
        unset($data['_token']);
        
        foreach($data as $key => $val){
         
            DB::table('tool_tip')->where('component_name',$key)->where('part_id',2)->update([
                'tool_tip' => $val
            ]);
        
        }

        return view('admin.tool_tip.parts');
    }


    public function scp_t_tip(Request $request){

        // dd("here");
        
        $data = $request->all();
        unset($data['_token']);
        
        foreach($data as $key => $val){
         
            DB::table('tool_tip')->where('component_name',$key)->where('part_id',3)->update([
                'tool_tip' => $val
            ]);
        
        }
        

      

        return view('admin.tool_tip.parts');
    }


    public function giga(Request $request){

        // dd("here");

        $data = $request->all();
        unset($data['_token']);
        
        foreach($data as $key => $val){
         
            DB::table('tool_tip')->where('component_name',$key)->where('part_id',4)->update([
                'tool_tip' => $val
            ]);
        
        }
        
      

        return view('admin.tool_tip.parts');
    }

    public function setup(){
      
        return View('admin.setup.setup')->with('current_data',DB::table('setup_fields')->get());
    }

    public function setup_post(Request $request){


        $data = $request->all();
        unset($data['_token']);

        foreach($data as $key => $val){
         
            DB::table('setup_fields')->where('name',$key)->update([
                'value' => $val
            ]);
        
        }

       

        return View('admin.setup.setup')->with('current_data',DB::table('setup_fields')->get());
    }


    public function ic_margin(){
      

    //     for($i = 1 ; $i<=4 ; $i++){
    //     $country = [
    //         [
    //             "country" => "lebanon",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],

    //         [
    //             "country" => "syria",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],

    //         [
    //             "country" => "jordan",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],

    //         [
    //             "country" => "egypt",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],

    //         [
    //             "country" => "uae",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],

    //         [
    //             "country" => "ksa",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],

    //         [
    //             "country" => "qatar",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],

    //         [
    //             "country" => "pakistan",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],
    //         [
    //             "country" => "morocco",
    //             "value" => "9",
    //             "part_id" => $i
    //         ],
            
    //     ];
    //         DB::table('ic_margin')->insert($country);
    // }
    //     dd("herr");
        $part_id = $_GET['part_id'];
        return View('admin.ic_margin.margin',compact('part_id'))->with('current_data',DB::table('ic_margin')->where('part_id',$part_id)->get());
    }

    public function ic_margin_post(Request $request){

        $part_id = $request->part_id;

        $data = $request->all();
        unset($data['_token']);
        unset($data['part_id']);
        
        foreach($data as $key => $val){
         
            DB::table('ic_margin')->where('country',$key)->where('part_id',$part_id)->update([
                'value' => $val
            ]);
        
        }
        

        return View('admin.ic_margin.margin',compact('part_id'))->with('current_data',DB::table('ic_margin')->where('part_id',$part_id)->get());
    }

  public function export_quotation()
    {
		//ini_set('max_execution_time', '0');
		set_time_limit(0);
        $excel_file = 'QuotationLog_' . Carbon::now()->format('m-d-Y h:i:s') . '.xlsx';
        return Excel::download(new QuotationDetailsExcel(),$excel_file);
    }


}
