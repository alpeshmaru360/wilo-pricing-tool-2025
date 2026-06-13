<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\AtmosMasterMotorPrice;
use App\AtmosAssemblyCostPcPk;
use App\AtmosPump;
use App\AtmosPumpType;
use App\AtmosCart;
use App\AtmosItem;
use App\AtmosAdder;
use App\User;
use App\AtmosMaterial;
use App\Helpers\CurrencyHelper;

class AtmosGigaController extends Controller {

    public function index() {
        // dd(User::ic_margin()." ".AtmosPumpType::atmos_shipping_percentage()." ".AtmosPumpType::atmos_over_head()." ".AtmosPumpType::atmos_adder_code_no_4());
        $power = DB::table('atmos_master_motor_prices')->distinct()->pluck('power');
        $voltage = DB::table('atmos_master_motor_prices')->distinct()->pluck('voltage');
        $brand = DB::table('atmos_master_motor_prices')->distinct()->pluck('brand');
        $frequency = DB::table('atmos_master_motor_prices')->distinct()->pluck('frequency');
        $poles = DB::table('atmos_master_motor_prices')->distinct()->pluck('no_of_pole');
        $efficiency = DB::table('atmos_master_motor_prices')->distinct()->pluck('efficiency');
        return view('frontend.atmos_giga.index', compact('power', 'voltage', 'efficiency', 'poles', 'brand', 'frequency'))->with('pump_types', DB::table('atmos_pump_types')->get()
                )->with(
                        'atmos_materials', DB::table('atmos_materials')->get()
        );
    }

    public function get_price(Request $request) {
        $impeller_id = DB::table('atmos_materials')->where('id', $request['impeller_id'])->first();
        $pump_model = DB::table('atmos_pump_types')->where('id', $request['pump_model'])->first();
        $get_price = DB::table('atmos_pumps')->where('pump_id', $pump_model->id)
                        ->where('material_id', $impeller_id->id)->get();
        if (count($get_price) >= 1) {
            return $get_price;
        } else {
            return "price not found";
        }
    }

    public function get_frame(Request $request) {
        $masterData = AtmosMasterMotorPrice::select('id', 'frame_size')
                ->where('no_of_pole', $request['poles'])
                ->where('brand', $request['motor_brand'])
                ->where('frequency', $request['frequency'])
                ->where('power', $request['motor_power'])
                ->where('efficiency', $request['effieciency'])
                ->where('voltage', $request['power_supply'])
                ->get();
        if (isset($masterData[0])) {
            return $masterData[0];
        }
        return 0;
    }

    public function get_motor_price(Request $request) {
        if($request->val == 2){
            return AtmosMasterMotorPrice::where('id', $request['master_price_id'])->sum(DB::raw('price + insulate_bearing'));
        } else {
            return AtmosMasterMotorPrice::where('id', $request['master_price_id'])->pluck('price')[0];
        }
    }

    public function check_for_column($request) {
        $columnName = $request['pump_id'] . 'x' . $request['frame'];
        $tbls = DB::getSchemaBuilder()->getColumnListing('atmos_accessories_price');
        $new_col = array();
        foreach ($tbls as $tb) {
            if (strpos($tb, strtolower($request['frame']))) {
                array_push($new_col, $tb);
            }
        }
        if (in_array(strtolower($columnName), $new_col)) {
            return $columnName;
        } else {

            sort($new_col);
           
            $array_size = sizeof($new_col) - 1;
            $i = 0;
            // foreach ($new_col as $n_c) {
            //     // dd($n_c);
            //     // dd((int)$request['pump_id'] + 1);
            //     $i++;
            //     $col_name = (int)$request['pump_model'] + $i . 'x' . $request['frame_size'];
            //     // dd($col_name);
            //     $col_name = strtolower($col_name);
                
            //     if (in_array($col_name,$new_col)) {

            //         return $col_name;

            //     } else {

            //         continue;

            //     }

                // $i++;
            // }
            do{
                
                $i++;
                $col_name = (int)$request['pump_id'] + $i . 'x' . $request['frame'];
                
                $col_name = strtolower($col_name);
                
                if (in_array($col_name,$new_col)) {
                   
                    return $col_name;

                }
                $pmp_present = explode('x',$new_col[$array_size]);
               
            }while($i <= (int)$pmp_present[0]);
        }
    }
	
    public function get_accessories(Request $request) {
        $col_name = $this->check_for_column($request);
        if ($col_name != 0) {
            $col = DB::table("atmos_accessories_price")->where($col_name, '>', 0)->select('unit_price', $col_name)->get();
            $total = 0;
            foreach ($col->toArray() as $c) {
                $total += $c->unit_price * $c->$col_name;
            }
            //  return number_format($total, 2);
            return $total;
        } else {
            return 0;
        }
    }

    public function ajaxCalculate(Request $request) {
        $getAtmosPumpName = AtmosPumpType::where('id', $request->pump_model)->pluck('name')[0];
        $atmosGigaPrice = 0.00;
        $optionalPrice = 0.00; //Adders Code
        // dd("fds");
        $interCompanyMargin = User::ic_margin_atmos(); // This is temporary 
        $shippingPercentage = AtmosPumpType::atmos_shipping_percentage() / 100; //This percentage can be editable by admin
        $overHead = AtmosPumpType::atmos_over_head(); //This $overHead can be editable by admin
        $assemblyPrice = 0.00;
        if ($request->code_price && $request->code_price != 'undefined') {
            $optionalPrice = $request->code_price;
        }
        $getAPPPrice = AtmosAssemblyCostPcPk::where('power', $request->motor_power)->get();
        if ($getAPPPrice) {
            $assemblyPrice = $getAPPPrice[0]->assembly_charge + $getAPPPrice[0]->painting_charge + $getAPPPrice[0]->packing_charge;
        }
        // dd($interCompanyMargin);
        $shippingCost = ($request->bare_shaft_price + $request->acessories_price) * $shippingPercentage;
        $atmosGigaPrice += ((($request->bare_shaft_price + $request->acessories_price + $request->motor_price + $optionalPrice + $assemblyPrice) * $overHead) + $shippingCost ) / $interCompanyMargin;
        //        dd($shippingCost);
        $returnHTML = view('frontend.atmos_giga.table')->with('pumpName', $getAtmosPumpName)
                ->with('price', $atmosGigaPrice)
                ->with('motor_power', $request->motor_power)
                ->with('motor_brand', $request->motor_brand)
                ->render();
        $data['cp_records_html'] = $returnHTML;
        $data['cp_price'] = number_format($atmosGigaPrice, 2);
        $data['total_price'] = $atmosGigaPrice;
        return response()->json(array('success' => true, 'data' => $data));
    }

    public function ajaxOptionalSelectedAdderData(Request $request) {
        $ids = explode(",", $request->adder_ids); //Code ids
        $price = 0.00;
        if ($ids) {
            foreach ($ids as $id) {

                switch ($id) {

                    case ($id == 1):
                        $price += AtmosMasterMotorPrice::where('id', $request['master_price_id'])->pluck('forwinding')[0];

                        break;
                    case ($id == 2):
                        $price += AtmosMasterMotorPrice::where('id', $request['master_price_id'])->pluck('forbearing')[0];
                        break;
                    case ($id == 3):

                        $price += AtmosMasterMotorPrice::where('id', $request['master_price_id'])->pluck('space_heater')[0];
                        break;
                    case ($id == 4):

                        $price += AtmosPumpType::atmos_adder_code_no_4();
                        break;
                    default: //default
                        $price;
                        break;
                }
            }
        }
        return ['code_price' => $price];
    }

    public function ajaxOptionalModal(Request $request) {
        $atmosAdderData = DB::table('atmos_adders')->get();
        $data = view('frontend.atmos_giga.modal_optional')->with('atmosAdderData', $atmosAdderData)
                ->render();
        return response()->json(array('success' => true, 'data' => $data));
    }

    public function addToCart(Request $request) {
        //code starts for search via article number
        //$atmosGigaCart = db::table('atmos_carts')->where('full_article_number','=',$request->full_article_number)->latest('id')->first();
        $atmosGigaCart = DB::table('atmos_carts')->where('full_article_number','=',$request->full_article_number);
        if(auth()->user()->country_id == 6){
            $atmosGigaCart = $atmosGigaCart->orWhere('ksa_full_article_number','=',$request->full_article_number);
        }
        $atmosGigaCart = $atmosGigaCart->latest('id')->first();
		if($request->impeller_material == null && $request->application == null && $request->adder_ids == null){
            $request->adder_ids = $atmosGigaCart->adder_ids;
        }
        if($request->motor_power == ""){
            $request->motor_power = $atmosGigaCart->power;
        }
        if($request->pump_model == ""){
            $request->pump_model = $atmosGigaCart->pump_id;
        }
        if($request->impeller_material == ""){
            $request->impeller_material = $atmosGigaCart->material_id;
        }
        if($request->application == ""){
            $request->application = $atmosGigaCart->application;
        }
        if($request->master_price_id == ""){
            $request->master_price_id = $atmosGigaCart->master_id;
        }
        if($request->bare_shaft_price == ""){
            $request->bare_shaft_price = $atmosGigaCart->bare_pump_price;
        }
        if($request->power_supply == ""){
            $request->power_supply = $atmosGigaCart->voltage;
        }
        //will add it
        if($request->frame_size == ""){
            $request->frame_size = $atmosGigaCart->frame_size;
        }
        if($request->frequency == ""){
            $request->frequency = $atmosGigaCart->frequency;
        }
        if($request->efficiency == ""){
            $request->efficiency = $atmosGigaCart->efficiency;
        }
        if($request->poles == ""){
            $request->poles = $atmosGigaCart->no_of_pole;
        }
        if($request->motor_brand == ""){
            $request->motor_brand = $atmosGigaCart->brand;
        }
        if($request->acessories_price == ""){
            $request->acessories_price = $atmosGigaCart->accesories_price;
        }

        if($atmosGigaCart && $request->is_acessories_price_manual == "0"){
            $request->is_acessories_price_manual = $atmosGigaCart->is_accesories_manual;
        }

        // {
        //     dd("test");
        // }
        //code ends for search via article number
        $getAssemblyPrice = $this->getCartAssemblyPrices($request->motor_power);
        $interCompanyMargin = User::ic_margin_atmos(); // This is temporary 
        $shippingPercentage = AtmosPumpType::atmos_shipping_percentage() / 100; //This percentage can be editable by admin
        $overHead = AtmosPumpType::atmos_over_head(); //This $overHead can be editable by admin
        if($request->adder_ids){
            $atmosCartData = AtmosCart::where('pump_id', $request->pump_model)
                    ->where('material_id', $request->impeller_material)
                    ->where('master_id', $request->master_price_id)
                    ->where('application', $request->application)
                    ->where('adder_ids', $request->adder_ids)
                    ->where('user_id', auth()->user()->id)
                    ->orderBy('id', 'desc')
                    ->first();
            if($atmosCartData == null){
                $atmosCartData1 = AtmosCart::where('pump_id', $request->pump_model)
                    ->where('material_id', $request->impeller_material)
                    ->where('master_id', $request->master_price_id)
                    ->where('application', $request->application)
                    ->where('adder_ids', $request->adder_ids)
                    ->orderBy('id', 'desc')
                    ->first();
                //query for find article number and full article number ends diff user id..!!
                $atmosCart = new AtmosCart;
				$new_ksa_article_number = '';
                if(auth()->user()->country_id == 6){
                    if($atmosCartData){
                        if($atmosCartData->full_article_number != "" || $atmosCartData->full_article_number != null){
                            if($request->country == "ksa"){
                                $new_ksa_article_number = str_replace("683", "339", $atmosCartData->full_article_number);
								$atmosCart->ksa_full_article_number = $new_ksa_article_number;
                            }
                        }
                    }
                    elseif($atmosCartData1){
						if($atmosCartData1->full_article_number != "" || $atmosCartData1->full_article_number != null){
                            if($request->country == "ksa"){
                                $new_ksa_article_number = str_replace("683", "339", $atmosCartData1->full_article_number);
                                $atmosCart->ksa_full_article_number = $new_ksa_article_number;
                            }
                        }
                    }
                    else{

                    }
				}
                //BarE sHAFT dATA
                if($atmosCartData1 != null)
                {
                    $atmosCart->article_number = ($atmosCartData1->article_number==null)?null:$atmosCartData1->article_number;
                    $atmosCart->full_article_number = $atmosCartData1->full_article_number;  
                    $request->code_price = $atmosCartData1->total_adders_price;     
                }
                //BarE sHAFT dATA
                $atmosCart->pump_id = $request->pump_model;
                $atmosCart->pump_name = isset(AtmosPumpType::where('id', $request->pump_model)->pluck('name')[0]) ? AtmosPumpType::where('id', $request->pump_model)->pluck('name')[0] : '';
                $atmosCart->material_id = $request->impeller_material;
                $atmosCart->bare_pump_price = $request->bare_shaft_price;
                $atmosCart->is_bare_manual = $request->is_bare_shaft_price_manual;

                //Matrer Data
                $atmosCart->power = $request->motor_power;
                $atmosCart->voltage = $request->power_supply;
                $atmosCart->frame_size = $request->frame_size;
                $atmosCart->frequency = $request->frequency;
                $atmosCart->efficiency = $request->efficiency;
                $atmosCart->no_of_pole = $request->poles;
                $atmosCart->brand = $request->motor_brand;
                $atmosCart->master_id = $request->master_price_id;
                //Asscesories Price
                $atmosCart->accesories_price = $request->acessories_price;
                $atmosCart->is_accesories_manual = $request->is_acessories_price_manual;
                //Assembly Charge Price
                $atmosCart->assembly_charge = $getAssemblyPrice['assembly_charge'];
                $atmosCart->painting_charge = $getAssemblyPrice['painting_charge'];
                $atmosCart->packing_charge = $getAssemblyPrice['packing_charge'];
                //Shiiping Cost Price
                $atmosCart->shipping_cost_price = ($request->bare_shaft_price + $request->acessories_price) * $shippingPercentage;
                $atmosCart->shipping_cost_percentage = $shippingPercentage;
                $atmosCart->overhead_price = $overHead;
                $atmosCart->inter_company_margin_price = $interCompanyMargin;
                $atmosCart->adder_ids = $request->adder_ids;
                $atmosCart->total_adders_price = $request->code_price;
                $atmosCart->application = $request->application;
                $atmosCart->price = $request->total_price;
                $atmosCart->total_price = $request->total_price;
                $atmosCart->qty = 1;
                $atmosCart->user_id = auth()->user()->id;
                $atmosCart->created_at = date("Y-m-d H:i:s");
                $atmosCart->updated_at = date("Y-m-d H:i:s");
				$atmosCart->country_origin = $request->country;
                $atmosCart->ksa_full_article_number = $new_ksa_article_number;
                $atmosCart->save();
                $atmosCartId = $atmosCart->id;
                // if ($request->is_acessories_price_manual == 0) {
                    $this->insertItem($atmosCartId, $request);
                // }
            }else{
                if(empty($atmosCartData->quotation_no)){
                    $msg = 'This item already in your cart.';
                    return response()->json(array('success' => true, 'msg' => $msg));
                } else {
					$new_ksa_article_number = '';
                        if(auth()->user()->country_id == 6){
                            if($atmosCartData){
								if($atmosCartData->full_article_number != "" || $atmosCartData->full_article_number != null){
                                    // Replace "683" with "339"
                                    if($request->country == "ksa"){
                                        $new_ksa_article_number = str_replace("683", "339", $atmosCartData->full_article_number);
                                    }
								}
							}
						}
                    $atmosCart = $atmosCartData->replicate();
                   //  $atmosCart->bare_shaft_price = $request->bare_shaft_price;
                   // $atmosCart->is_bare_manual = $request->is_bare_shaft_price_manual;

                   //Asscesories Price
                   $atmosCart->accesories_price = $request->acessories_price;
                   $atmosCart->is_accesories_manual = $request->is_acessories_price_manual;

                   // //Assembly Charge Price

                   // $atmosCart->assembly_charge = $getAssemblyPrice['assembly_charge'];
                   // $atmosCart->painting_charge = $getAssemblyPrice['painting_charge'];
                   // $atmosCart->packing_charge = $getAssemblyPrice['packing_charge'];
                   // //Shiiping Cost Price
                   // $atmosCart->shipping_cost_price = ($request->bare_shaft_price + $request->acessories_price) * $shippingPercentage;
                   // $atmosCart->shipping_cost_percentage = $shippingPercentage;
                   // $atmosCart->overhead_price = $overHead;
                    $atmosCart->inter_company_margin_price = $interCompanyMargin;

                   // $atmosCart->adder_ids = $request->adder_ids;
                   // $atmosCart->total_adders_price = $request->code_price;
                    $atmosCart->price = $request->total_price;
                    $atmosCart->total_price = $request->total_price;

                    $atmosCart->quotation_no = null;
                    $atmosCart->qty = 1;
					$atmosCart->country_origin = $request->country;
                    $atmosCart->ksa_full_article_number = $new_ksa_article_number;
                    $atmosCart->save();
                    $atmosCartId = $atmosCart->id;

                    //if ($request->is_acessories_price_manual == 0) {
                        $this->insertItem($atmosCartId, $request);
                    //}
                }
            }
        }else{
            // DB::enableQueryLog();
            $atmosCartData = AtmosCart::where('pump_id', $request->pump_model)
                    ->where('material_id', $request->impeller_material)
                    ->where('master_id', $request->master_price_id)
                    ->where('application', $request->application)
                    ->whereNull('adder_ids')
                    ->where('user_id', auth()->user()->id)
                    ->orderBy('id', 'desc')
                    ->first();
            if($atmosCartData == null){
                //query for find article number and full article number starts for diff user id..!!
                $atmosCartData1 = AtmosCart::where('pump_id', $request->pump_model)
                    ->where('material_id', $request->impeller_material)
                    ->where('master_id', $request->master_price_id)
                    ->where('application', $request->application)
                    ->whereNull('adder_ids')
                    ->orderBy('id', 'desc')
                    ->first();
                //query for find article number and full article number ends diff user id..!!
                $atmosCart = new AtmosCart;
				$new_ksa_article_number = '';
                    if(auth()->user()->country_id == 6){
                        if($atmosCartData){
							if($atmosCartData->full_article_number != "" || $atmosCartData->full_article_number != null){
                                if($request->country == "ksa"){
                                    $new_ksa_article_number = str_replace("683", "339", $atmosCartData->full_article_number);
                                    $atmosCart->ksa_full_article_number = $new_ksa_article_number;
                                }
                            }
						}
				elseif($atmosCartData1){
                            if($atmosCartData1->full_article_number != "" || $atmosCartData1->full_article_number != null){
                                if($request->country == "ksa"){
                                    $new_ksa_article_number = str_replace("683", "339", $atmosCartData1->full_article_number);
                                    $atmosCart->ksa_full_article_number = $new_ksa_article_number;
                                }
							}
				}
				else{

                        }
                    }
					
                //BarE sHAFT dATA
                if($atmosCartData1 != null)
                {
                    $atmosCart->article_number = ($atmosCartData1->article_number==null)?null:$atmosCartData1->article_number;
                    $atmosCart->full_article_number = $atmosCartData1->full_article_number;  
                }
                $atmosCart->pump_id = $request->pump_model;
                $atmosCart->pump_name = isset(AtmosPumpType::where('id', $request->pump_model)->pluck('name')[0]) ? AtmosPumpType::where('id', $request->pump_model)->pluck('name')[0] : '';
                $atmosCart->material_id = $request->impeller_material;
                $atmosCart->bare_pump_price = $request->bare_shaft_price;
                $atmosCart->is_bare_manual = $request->is_bare_shaft_price_manual;
                //Matrer Data
                $atmosCart->power = $request->motor_power;
                $atmosCart->voltage = $request->power_supply;
                $atmosCart->frame_size = $request->frame_size;
                $atmosCart->frequency = $request->frequency;
                $atmosCart->efficiency = $request->efficiency;
                $atmosCart->no_of_pole = $request->poles;
                $atmosCart->brand = $request->motor_brand;
                $atmosCart->master_id = $request->master_price_id;
                //Asscesories
                $atmosCart->accesories_price = $request->acessories_price ?? 0;
                $atmosCart->is_accesories_manual = $request->is_acessories_price_manual;

                //Assembly Charge
                $atmosCart->assembly_charge = $getAssemblyPrice['assembly_charge'];
                $atmosCart->painting_charge = $getAssemblyPrice['painting_charge'];
                $atmosCart->packing_charge = $getAssemblyPrice['packing_charge'];
                //Shiiping Cost
                $atmosCart->shipping_cost_price = ($request->bare_shaft_price + $request->acessories_price) * $shippingPercentage;
                $atmosCart->shipping_cost_percentage = $shippingPercentage;
                $atmosCart->overhead_price = $overHead;
                $atmosCart->inter_company_margin_price = $interCompanyMargin;
                $atmosCart->application = $request->application;
                $atmosCart->price = $request->total_price;
                $atmosCart->total_price = $request->total_price;
                $atmosCart->qty = 1;
                $atmosCart->user_id = auth()->user()->id;
                $atmosCart->created_at = date("Y-m-d H:i:s");
                $atmosCart->updated_at = date("Y-m-d H:i:s");
				$atmosCart->country_origin = $request->country;
                $atmosCart->ksa_full_article_number = $new_ksa_article_number;
                $atmosCart->save();
                $atmosCartId = $atmosCart->id;
               // if ($request->is_acessories_price_manual == 0) {
                    $this->insertItem($atmosCartId, $request);
                //}
            } else {
                if (empty($atmosCartData->quotation_no)) {
                    $msg = 'This item already in your cart.';
                    return response()->json(array('success' => true, 'msg' => $msg));
                } else {
					$atmosCartData = AtmosCart::where('pump_id', $request->pump_model)
                    ->where('material_id', $request->impeller_material)
                    ->where('master_id', $request->master_price_id)
                    ->where('application', $request->application)
                    ->whereNull('adder_ids')
                    //->where('user_id', auth()->user()->id)
                    ->orderBy('id', 'desc')
                    ->first();
					$new_ksa_article_number = '';
                    if(auth()->user()->country_id == 6){
                        if($atmosCartData){
                            if($atmosCartData->full_article_number != "" || $atmosCartData->full_article_number != null){
                                // Replace "683" with "339"
                                if($request->country == "ksa"){
									$new_ksa_article_number = str_replace("683", "339", $atmosCartData->full_article_number);
                                }
                            }
                        }
                    }
                    $atmosCartData['price'] = round($request->total_price,2);
				    $atmosCartData['total_price'] = round($request->total_price,2);
                    $atmosCart = $atmosCartData->replicate();
                    
                   //     $atmosCart->bare_shaft_price = $request->bare_shaft_price;
                   // $atmosCart->is_bare_manual = $request->is_bare_shaft_price_manual;

                       //Asscesories Price
                       $atmosCart->accesories_price = $request->acessories_price;
                       $atmosCart->is_accesories_manual = $request->is_acessories_price_manual;

                   //     //Assembly Charge Price

                   //     $atmosCart->assembly_charge = $getAssemblyPrice['assembly_charge'];
                   //     $atmosCart->painting_charge = $getAssemblyPrice['painting_charge'];
                   //     $atmosCart->packing_charge = $getAssemblyPrice['packing_charge'];
                   //     //Shiiping Cost Price
                   //     $atmosCart->shipping_cost_price = ($request->bare_shaft_price + $request->acessories_price) * $shippingPercentage;
                   //     $atmosCart->shipping_cost_percentage = $shippingPercentage;
                   //     $atmosCart->overhead_price = $overHead;
                          $atmosCart->inter_company_margin_price = $interCompanyMargin;
                          //     $atmosCart->adder_ids = $request->adder_ids;
                   //     $atmosCart->total_adders_price = $request->code_price;
                   //     $atmosCart->price = $request->total_price;
                   //     $atmosCart->total_price = $request->total_price;
                    $atmosCart->user_id = auth()->user()->id;
                    $atmosCart->quotation_no = null;
                    $atmosCart->qty = 1;
					$atmosCart->country_origin = $request->country;
                    $atmosCart->ksa_full_article_number = $new_ksa_article_number;
                    $atmosCart->save();
                    $atmosCartId = $atmosCart->id;
                    // if ($request->is_acessories_price_manual == 0) {
                        $this->insertItem($atmosCartId, $request);
                    // }
                }
            }
        }
        return response()->json(array('success' => true, 'url' => url('/controlpanel/cart/' . auth()->user()->id)));
    }

    public function insertItem($atmosCartId, $request) {
        $col_name = $this->check_for_column_insert_item($request);
        $col_name = strtolower($col_name); 
        $atmosItem = new AtmosItem;
        if($col_name != 0){
            $col = DB::table("atmos_accessories_price")->where($col_name, '>', 0)->select('description', 'unit_price', 'wilo_article_number', $col_name)->get();
            foreach($col->toArray() as $c){
                $atmosItem = new AtmosItem;
                $atmosItem->atmos_cart_id = $atmosCartId;
                $atmosItem->item_description = $c->description;
                $atmosItem->wilo_artilce_no = $c->wilo_article_number;
                $atmosItem->qty = $c->$col_name;
                $atmosItem->unit_price = $c->unit_price;
                $atmosItem->total_price = $c->unit_price * $c->$col_name;
                $atmosItem->save();
            }
        }
    }

    public function check_for_column_insert_item($request) {
        //Code added for search functionality
        //$atmosGigaCart = db::table('atmos_carts')->where('full_article_number','=',$request->full_article_number)
         //               ->latest('id')->first();
        // dd($atmosGigaCart);
		$atmosGigaCart = DB::table('atmos_carts')->where('full_article_number','=',$request->full_article_number);
            if(auth()->user()->country_id == 6){
$atmosGigaCart = $atmosGigaCart->orWhere('ksa_full_article_number','=',$request->full_article_number);
            }
            $atmosGigaCart = $atmosGigaCart->latest('id')->first();
        if(!empty($atmosGigaCart)){
            if($request->pump_model == ""){
                $request->pump_model = $atmosGigaCart->pump_id;
            }
            $request['pump_model'] = $atmosGigaCart->pump_id;
            if($request->frame_size == ""){
                $request->frame_size = $atmosGigaCart->frame_size;
            }
                $request['frame_size'] = $atmosGigaCart->frame_size;
        }
        $columnName = $request['pump_model'] . 'x' . $request['frame_size'];
        // dd($columnName);
        $tbls = DB::getSchemaBuilder()->getColumnListing('atmos_accessories_price');
        $new_col = array();
        foreach($tbls as $tb){
            if (strpos($tb, strtolower($request['frame_size']))) {
                array_push($new_col, $tb);
            }
        }
        if(in_array(strtolower($columnName), $new_col)){
            return $columnName;
        }else{
            sort($new_col);
            $array_size = sizeof($new_col) - 1;
            $i = 0;
            // foreach ($new_col as $n_c) {
            //     // dd($n_c);
            //     // dd((int)$request['pump_id'] + 1);
            //     $i++;
            //     $col_name = (int)$request['pump_model'] + $i . 'x' . $request['frame_size'];
            //     // dd($col_name);
            //     $col_name = strtolower($col_name);
            //     if (in_array($col_name,$new_col)) {
            //         return $col_name;
            //     } else {
            //         continue;
            //     }
            //     $i++;
            // }
            do{
                $i++;
                $col_name = (int)$request['pump_model'] + $i . 'x' . $request['frame_size'];
                $col_name = strtolower($col_name);
                if (in_array($col_name,$new_col)) {
                    return $col_name;
                }
            $pmp_present = explode('x',$new_col[$array_size]);

            }while($i <= (int)$pmp_present[0]);
            }
    }

    public function getCartAssemblyPrices($motorPower){
        $getAPPPrice = AtmosAssemblyCostPcPk::where('power', $motorPower)->get();
        $data = [];
        if($getAPPPrice){
            $data['assembly_charge'] = $getAPPPrice[0]->assembly_charge;
            $data['painting_charge'] = $getAPPPrice[0]->painting_charge;
            $data['packing_charge'] = $getAPPPrice[0]->packing_charge;
        }
        return $data;
    }

    public function ajaxQtyUpdate(Request $request) {
        $qty = $request->qty;
        $atmosCartId = $request->atmos_cart_id;
        $atmosUpdate = AtmosCart::find($atmosCartId);
        $atmosUpdate->qty = $qty;
        $atmosUpdate->total_price = $atmosUpdate->qty * $atmosUpdate->price;
        $atmosUpdate->save();
        $data['id'] = $atmosCartId;
        $data['total_price_update'] = CurrencyHelper::withCurrency($qty * $atmosUpdate->price);
        return response()->json(array('success' => true, 'data' => $data));
    }

    public function removeCart($id) {
        $deleteAtmosCart = AtmosCart::where('id', $id)->delete();
        $deleteItem = AtmosItem::where('atmos_cart_id', $id)->delete();
    }

    public function cartItems($cartId) { //$val is itemData
        $adderData = [];
        $items = AtmosItem::where('atmos_cart_id', $cartId)->with('atmosCart')->get();
        if((isset($items[0]->atmosCart->adder_ids) && $items[0]->atmosCart->adder_ids != null))
        {   
            $is_manual = 0;
            $ids = explode(",", $items[0]->atmosCart->adder_ids); //Code ids
            if ($ids) {
                foreach ($ids as $id) {
                    switch ($id) {
                        case ($id == 1):
                            $adderData[$id]['id'] = 1;
                            $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->atmosCart->master_id)->pluck('forwinding')[0];
                            $adderData[$id]['name'] = AtmosAdder::where('id', 1)->get()[0]->adder_list;
                            break;
                        case ($id == 2):
                            $adderData[$id]['id'] = 2;
                            $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->atmosCart->master_id)->pluck('forbearing')[0];
                            $adderData[$id]['name'] = AtmosAdder::where('id', 2)->get()[0]->adder_list;
                            break;
                        case ($id == 3):
                            $adderData[$id]['id'] = 3;
                            $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->atmosCart->master_id)->pluck('space_heater')[0];
                            $adderData[$id]['name'] = AtmosAdder::where('id', 3)->get()[0]->adder_list;
                            break;
                        case ($id == 4):
                            $adderData[$id]['id'] = 4;
                            $adderData[$id]['price'] = AtmosPumpType::atmos_adder_code_no_4();
                            $adderData[$id]['name'] = AtmosAdder::where('id', 4)->get()[0]->adder_list;
                            break;
                            default: //default
                            null;
                            break;
                    }
                }
            }
        }
        else{
            if(count($items) == 0){
                $items = atmosCart::where('id', $cartId)->get();
            }
            $is_manual = 1;
            $ids = explode(",", $items[0]->adder_ids);
            if ($ids && $items[0]->adder_ids != null) {
                foreach ($ids as $id) {
                    switch ($id) {
                        case ($id == 1):
                            $adderData[$id]['id'] = 1;
                            $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->master_id)->pluck('forwinding')[0];
                            $adderData[$id]['name'] = AtmosAdder::where('id', 1)->get()[0]->adder_list;
                            break;
                        case ($id == 2):
                            $adderData[$id]['id'] = 2;
                            $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->master_id)->pluck('forbearing')[0];
                            $adderData[$id]['name'] = AtmosAdder::where('id', 2)->get()[0]->adder_list;
                            break;
                        case ($id == 3):
                            $adderData[$id]['id'] = 3;
                            $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->master_id)->pluck('space_heater')[0];
                            $adderData[$id]['name'] = AtmosAdder::where('id', 3)->get()[0]->adder_list;
                            break;
                        case ($id == 4):
                            $adderData[$id]['id'] = 4;
                            $adderData[$id]['price'] = AtmosPumpType::atmos_adder_code_no_4();
                            $adderData[$id]['name'] = AtmosAdder::where('id', 4)->get()[0]->adder_list;
                            break;
                            default: //default
                            null;
                            break;
                    }
                }
            }
        }
        return view('frontend.atmos_giga.items', compact('items', 'adderData','cartId','is_manual'));
    }

    public function ajaxDetailModalAtmos(Request $request) {
        $adderData = [];
        $atmos_id = $request->atmos_id;
        $atmosData = atmosCart::where('id', $atmos_id)->get()[0];
        $items = AtmosItem::where('atmos_cart_id', $atmos_id)->with('atmosCart')->get();
        $getMaterial = AtmosMaterial::where('id', $atmosData->material_id)->pluck('name')[0];
        if(!empty($atmosData->adder_ids) && $atmosData->adder_ids != null){
            $adderIds = explode(",", $atmosData->adder_ids);
            if($adderIds){
                foreach ($adderIds as $id) {
                    switch ($id) {
                        case ($id == 1):
                            $adderData[$id]['id'] = 1;
                            if(count($items) != 0)
                            {
                                $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->atmosCart->master_id)->pluck('forwinding')[0];
                            }
                            $adderData[$id]['name'] = AtmosAdder::where('id', 1)->get()[0]->adder_list;
                            break;
                        case ($id == 2):
                            $adderData[$id]['id'] = 2;
                            if(count($items) != 0)
                            {
                                $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->atmosCart->master_id)->pluck('forbearing')[0];
                            }
                            $adderData[$id]['name'] = AtmosAdder::where('id', 2)->get()[0]->adder_list;

                            break;
                        case ($id == 3):
                            $adderData[$id]['id'] = 3;
                            if(count($items) != 0)
                            {
                                $adderData[$id]['price'] = AtmosMasterMotorPrice::where('id', $items[0]->atmosCart->master_id)->pluck('space_heater')[0];
                            }
                            $adderData[$id]['name'] = AtmosAdder::where('id', 3)->get()[0]->adder_list;
                            break;
                        case ($id == 4):
                            $adderData[$id]['id'] = 4;
                            $adderData[$id]['price'] = AtmosPumpType::atmos_adder_code_no_4();
                            $adderData[$id]['name'] = ucfirst(AtmosAdder::where('id', 4)->get()[0]->adder_list);
                        break;
                        default: //default
                        null;
                        break;
                    }
                }
            }
            // $addersData = DB::table('main_electrical_list')->select('adder_list')
           //                 ->whereIn('id', $adderIds)->get();
        }
        $atmosData["power"] = $atmosData["power"] . " Kw";
        $atmosData["frequency"] = $atmosData["frequency"] . " Hz";
        $returnHTML = view('frontend.cart.atmos_detail_modal')->with('atmos_data', $atmosData)
                ->with('adderData', $adderData)
                ->with('impeller', $getMaterial)
                ->render();
        $data['html'] = $returnHTML;
        return response()->json(array('success' => true, 'data' => $data));
    }

    //Function for find all details with full article number..!!
    //where('user_id', auth()->user()->id)->
    public function searchByArticleNumber(Request $request){
        //$atmosCartData = AtmosCart::where('full_article_number', $request->full_article_number)->latest('id')->first();
		$atmosCartData = AtmosCart::where('full_article_number', $request->full_article_number);
if(auth()->user()->country_id == 6){
            $atmosCartData = $atmosCartData->orWhere('ksa_full_article_number','=',$request->full_article_number);
        }
        $atmosCartData = $atmosCartData->latest('id')->first();
        if($atmosCartData)
        {
            $interCompanyMargin = User::ic_margin_atmos(); // This is temporary 
            $atmosGigaPrice = 0.00;
            $optionalPrice = 0.00; //Adders Code.
            $motor_price = DB::table('atmos_master_motor_prices')
            ->where('power','=',$atmosCartData->power)
            ->where('no_of_pole','=',$atmosCartData->no_of_pole)
            ->where('voltage','=',$atmosCartData->voltage)
            ->where('frequency','=',$atmosCartData->frequency)
            ->where('efficiency','=',$atmosCartData->efficiency)
            ->where('frame_size','=',$atmosCartData->frame_size)
            // ->where('no_of_phase','=',$atmosCartData->no_of_phase)
            ->first();
            if($motor_price)
            {
                $setup_field = DB::table('setup_fields')->where('name','=','atmos_adder_code_4')->first();
                // dd($motor_price);
                // if(!empty($atmosCartData->total_adders_price) && $atmosCartData->total_adders_price != null)
                // {
                //     $total_adders_price = $atmosCartData->total_adders_price;
                // }
                // else{
                //     $total_adders_price = 0.00;
                // }
                $enclousreAdderItemData = null;

                if($atmosCartData->adder_ids && $atmosCartData->adder_ids != '') {
                $explode_ids = explode(",",$atmosCartData->adder_ids);
                $total_adders_price = 0.00;
                $adder_id_one_price = 0.00;
                $adder_id_two_price = 0.00;
                $adder_id_three_price = 0.00;
                $adder_id_four_price = 0.00;

                foreach($explode_ids as $key=>$value)
                {
                        if($value == "1")
                        {
                            $adder_id_one_price = $motor_price->forwinding;
                        }
                        if($value == "2")
                        {
                            $adder_id_two_price = $motor_price->forbearing;
                        }
                        if($value == "3")
                        {
                            $adder_id_three_price = $motor_price->space_heater;
                        }
                        if($value == "4")
                        {
                            $adder_id_four_price = $setup_field->value;
                        }
                }
                $total_adders_price = $adder_id_one_price + $adder_id_two_price + $adder_id_three_price + $adder_id_four_price;
                }
                else{
                    $total_adders_price = 0.00;
                }
                // dd($total_adders_price);
                
                $overHead = AtmosPumpType::atmos_over_head(); //This $overHead can be editable by admin
                // dd($overHead);
                //1.1
                $assemblyPrice = $atmosCartData->assembly_charge + $atmosCartData->painting_charge + $atmosCartData->packing_charge;
                // dd($assemblyPrice);
                //=371
                $shippingPercentage = AtmosPumpType::atmos_shipping_percentage() / 100; //This percentage can be editable by admin
                // dd($shippingPercentage);
                //0.1
                $shippingCost =($atmosCartData->bare_pump_price + $atmosCartData->accesories_price) * $shippingPercentage;
                // dd($shippingCost);
                //83.151
                // dd($atmosCartData->bare_pump_price);591.65
                // dd($atmosCartData->accesories_price);239.86
                // dd($assemblyPrice);371.0
                //1=costant 2= variable
                if($atmosCartData->application == "1")
                {
                    //(((591.65 + 239.86 + 890.4  + 0.00 + 371.0) * 1.1  ) + 83.151) / 0.9
                    $motor_price = $motor_price->price;
                }
                elseif($atmosCartData->application == "2"){
                    $motor_price = $motor_price->price + $motor_price->insulate_bearing;
                }
                else{
                    $motor_price = $motor_price->price;
                }
                // dd($motor_price);
                $atmosGigaPrice = ((($atmosCartData->bare_pump_price + $atmosCartData->accesories_price + $motor_price + $total_adders_price + $assemblyPrice) * $overHead) + $shippingCost ) / $interCompanyMargin;
                // dd($atmosGigaPrice);
                //added for track..
                // dd($motor_price->price);=890.4;
                // dd($atmosGigaPrice);
                // dd($interCompanyMargin);
                //+$request->motor_price 
                $returnHTML = view('frontend.atmos_giga.table')->with('pumpName', $atmosCartData->pump_name)
                ->with('price', $atmosGigaPrice)
                ->with('motor_power', $atmosCartData->power)
                ->with('motor_brand', $atmosCartData->brand)
                ->render();
                $data['cp_records_html'] = $returnHTML;
                $data['motor_power'] = $atmosCartData->power;
                $data['pump_model'] = $atmosCartData->pump_model;
                $data['motor_power'] = $atmosCartData->power;
                $data['cp_price'] = number_format($atmosGigaPrice, 2);
                $data['total_price'] = $atmosGigaPrice;
                return response()->json(array('success' => true, 'data' => $data));
            }
        }
     else {
        $data['cp_records_html_error'] = 'This article number does not exits. Please select another article number or manually selects.';
        return response()->json(array('success' => true, 'data' => $data));
        }
    }
}
