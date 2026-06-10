<?php

namespace App\Http\Controllers\Frontend\FireFighting;

use App\Helpers\CurrencyHelper;
use App\Http\Controllers\Controller;
use App\Models\FireFighting\BatteryMaster;
use App\Models\FireFighting\ControlPanelMaster;
use App\Models\FireFighting\DieselPump;
use App\Models\FireFighting\DieselTankMaster;
use App\Models\FireFighting\ElectricalPump;
use App\Models\FireFighting\FireFightingAdders;
use App\Models\FireFighting\FireFightingCarts;
use App\Models\FireFighting\FireFightingFlowMeter;
use App\Models\FireFighting\FireFightingMotor;
use App\Models\FireFighting\FireFightingPressureReliefValve;
use App\Models\FireFighting\FireFightingWasteCone;
use App\Models\FireFighting\JockeyPump;
use App\Models\FireFighting\OptionalMaster;
use App\User;
use Illuminate\Http\Request;

class FireFightingPumpController extends Controller
{
    public function __construct()
    {
       
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['electrical_pump_type'] = ElectricalPump::select('pump_type')->groupBy('pump_type')->pluck('pump_type')->toArray();
		
        $data['diesel_pump_type'] = DieselPump::select('pump_type')->groupBy('pump_type')->pluck('pump_type')->toArray();
        $data['ic_margin'] = User::ic_margin_fire_fighting();
        $data['overhead'] = current(\DB::table('setup_fields')->where('name','fire_fighting_over_head')->pluck('value')->toArray());
        return view('frontend.fire-fighting.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'post_type' => 'required',
            'pump_type' => 'required'
        ]);

        switch ($request->post_type) {
            case 'price-calculate':
                    return $this->priceCalculate($request);
                break;

            case 'qty-change':
                    $qty = $request->qty;
                    $data = FireFightingCarts::find($request->firefighting_id);
                    $data->qty = $qty;
                    $data->total_adders_price = $data->adder_ids_prices * $qty;
                    $data->total_price = $data->price * $qty;
                    $data->save();

                    return [
                        'qty' => $data->qty,
                        'price' => $data->price,
                        'total_price' => CurrencyHelper::withCurrency($data->total_price)
                    ];
                break;

            case 'delete-cart':
                    $data = FireFightingCarts::find($request->firefighting_id);
                    if (!is_null($data)) {
                        $data->delete();
                    }
                    return true;
                break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        switch ($id) {
            case 'jockey-pump':
                    $data = JockeyPump::select('id', 'pump_article_no', 'description', 'power', 'frequency', 'unit_price')->get()->toArray();
                    return $data;
                break;
            
            case 'electrical-pump':
                    $data = ElectricalPump::select('id', 'wilo_pump_models', 'pump_type', 'frequency', 'pump_approval', 'flow', 'head', 'speed_rpm', 'unit_price')->get()->toArray();
                    return $data;
                break;
            
            case 'diesel-pump':
                    $data = DieselPump::select('id', 'pump_models', 'pump_type', 'frequency', 'pump_approval', 'engine_approval', 'flow', 'head', 'speed_rpm', 'unit_price')->get()->toArray();
                    return $data;
                break;

            // Full Articles Number
            case 'electrical-pump-articles':
                    $data = FireFightingCarts::select('id', 'quotation_no', 'article_number', 'full_article_number', 'pump_id', 'category', 'all_prices', 'field_val')->where('category', 'electrical')->get()->toArray();
                    return $data;
                break;
                
            case 'diesel-pump-articles':
                    $data = FireFightingCarts::select('id', 'quotation_no', 'article_number', 'full_article_number', 'pump_id', 'category', 'all_prices', 'field_val')->where('category', 'diesel')->get()->toArray();
                    return $data;
                break;
            
            // Adder Ids
            case 'adder-jockey-pump':
                    $data = FireFightingAdders::select('id', 'adder_list','version','code')->where('version', 'FireFighting/Jockey')->get()->toArray();
                    return $data;
                break;
                
            case 'adder-electrical':
                    $data = FireFightingAdders::select('id', 'adder_list','version','code')->where('version', 'FireFighting/Electrical')->get()->toArray();
                    return $data;
                break;
                
            case 'adder-diesel':
                    $data = FireFightingAdders::select('id', 'adder_list','version','code')->where('version', 'FireFighting/Diesel')->get()->toArray();
                    return $data;
                break;
                
            case 'adder-electrical-diesel':
                    $data = FireFightingAdders::select('id', 'adder_list','version','code')->whereIn('version', ['FireFighting/Electrical', 'FireFighting/Diesel'])->get()->toArray();
                    return $data;
                break;

            default:
                    $data = [];
                    return $data;
                break;
        }
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
        $this->validate($request, [
            'pump_type' => 'required'
        ]);

        switch ($request->pump_type) {
            case 'jockey-pump':
                    return $this->jockeyPumpAddToCard($request);
                break;
            
            case 'electrical':
                    return $this->electricalPumpAddToCard($request);
                break;
            
            case 'diesel':
                    return $this->dieselPumpAddToCard($request);
                break;

            case 'electrical-diesel':
                    return $this->electricalDieselPumpAddToCard($request);
                break;

            default:
                    return [
                        'success' => false,
                        'msg' => 'Pump type not found',
                        'price' => ''
                    ];
                break;
        }
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
    }

    public function cartItems($id)
    {
        $cart = FireFightingCarts::find($id);
        if (!is_null($cart)) {
            $items = [];
            $prices = $cart->all_prices;
            // dd($prices);
            if (array_key_exists('pump_price', $prices)) {
                array_push($items, [
                    'description' => ucwords(str_replace('-pump', '', $cart->category)).' Pump',
                    'article_number' => '',
                    'addder_code' => '',
                    'unit_price' => $prices['pump_price'],
                    'qty' => '1',
                    'total_price' => $prices['pump_price'],
                ]);
            }

            if (array_key_exists('control_panel_price', $prices)) {
                array_push($items, [
                    'description' => 'Control Panel',
                    'article_number' => '',
                    'addder_code' => '',
                    'unit_price' => $prices['control_panel_price'],
                    'qty' => '1',
                    'total_price' => $prices['control_panel_price'],
                ]);
            }

            if (array_key_exists('disel_tank_price', $prices)) {
                array_push($items, [
                    'description' => 'Disel Tank',
                    'article_number' => '',
                    'addder_code' => '',
                    'unit_price' => $prices['disel_tank_price'],
                    'qty' => '1',
                    'total_price' => $prices['disel_tank_price'],
                ]);
            }

            if (array_key_exists('battery_orignal_price', $prices) && array_key_exists('battery_qty', $prices)) {
                array_push($items, [
                    'description' => 'Battery',
                    'article_number' => '',
                    'addder_code' => '',
                    'unit_price' => $prices['battery_orignal_price'],
                    'qty' => $prices['battery_qty'],
                    'total_price' => $prices['battery_orignal_price'] * $prices['battery_qty'],
                ]);
            }

            if (array_key_exists('adderprice', $prices) && (array_key_exists('adderpricelist', $prices) && is_array($prices['adderpricelist']))) {
                foreach ($prices['adderpricelist'] as $k => $v) {
                    array_push($items, [
                        'description' => $v['list'],
                        'article_number' => '',
                        'addder_code' => $v['code'],
                        'unit_price' => $v['price'],
                        'qty' => '1',
                        'total_price' => $v['price'],
                    ]);
                }
            }

            $data['items'] = $items;

        } else {
            return redirect('controlpanel/cart/' . auth()->id());
        }
        return view('frontend.fire-fighting.items', $data);
    }

    public function jockeyPumpAddToCard($request)
    {
        $ic_margin = User::ic_margin_fire_fighting();
        $overhead = current(\DB::table('setup_fields')->where('name','fire_fighting_over_head')->pluck('value')->toArray());


        $article_number = $request->data[0]['value'];
        $pumppower = $request->data[1]['value'];
        $frequency = $request->data[2]['value'];

        $pump_data = JockeyPump::where('pump_article_no', $article_number)->where('power', $pumppower)->where('frequency', $frequency)->first();
        if (!is_null($pump_data)) {
            $pump_price = $pump_data->unit_price;
            $power = $pumppower * 1.341;

            $adder_ids = [];
            if (isset($request->adder_ids)) {
                $adder_ids = $request->adder_ids;
            }
            
            $price = $this->jockeyPumpPriceCalculate($pump_price, $power, $frequency, $overhead, $ic_margin, $adder_ids);

            // Pump Data check in cart
            $cart = FireFightingCarts::where('category', $request->pump_type)->where('pump_id', $pump_data->id)->where('jockey_article_number', $article_number)->where('power', $pumppower)->where('frequency', $frequency);
            if (isset($request->adder_ids)) {
                $cart = $cart->whereJsonContains('adder_ids', $request->adder_ids);
            } else {
                $cart = $cart->whereNull('adder_ids');
            }
            $cart = $cart->whereNull('quotation_no')->whereNull('article_number')->whereNull('full_article_number')->where('user_id', auth()->id())->first();
            if (!is_null($cart)) {
                return [
                    'success' => false,
                    'msg' => 'This item already in your cart.',
                ];
            }

            // New Cart Data create
            $cart = new FireFightingCarts();

            // Check same data in cart without userid or article number
            $cart_check_other = FireFightingCarts::where('category', $request->pump_type)->where('pump_id', $pump_data->id)->where('jockey_article_number', $article_number)->where('power', $pumppower)->where('frequency', $frequency);
            if (isset($request->adder_ids)) {
                $cart_check_other = $cart_check_other->whereJsonContains('adder_ids', $request->adder_ids);
            } else {
                $cart_check_other = $cart_check_other->whereNull('adder_ids');
            }
            $cart_check_other = $cart_check_other->first();
            if (!is_null($cart_check_other)) {
                $cart->article_number = $cart_check_other->article_number;
                $cart->full_article_number = $cart_check_other->full_article_number;
                // dd($cart, $request->all());
                // $request->code_price = $atmosCartData1->total_adders_price;     
            }

            // dd($cart_check_other);

            $cart->category = $request->pump_type;
            $cart->pump_id = $pump_data->id;
            $cart->jockey_article_number = $article_number;
            $cart->pump_models = $pump_data->description;
            $cart->power = $pumppower;
            $cart->frequency = $frequency;
            if (isset($request->adder_ids)) {
                $cart->adder_ids = $request->adder_ids;
            }
            $cart->adder_ids_prices = $price['adderprice'];
            $cart->total_adders_price = $price['adderprice'];
            $cart->overhead_price = $overhead;
            $cart->inter_company_margin_price = $ic_margin;
            $cart->qty = 1;
            $cart->price = $price['price'];
            $cart->total_price = $price['price'];
            $cart->all_prices = $price;
            $cart->user_id = auth()->id();
            $cart->save();

            // dd($request->all(), $pump_data, $price);
            return [
                'success' => true,
            ];
        } else {
            return [
                'success' => false,
                'msg' => 'Jockey Pump data not found please contact to admin.',
                'price' => ''
            ];
        }
    }

    public function electricalPumpAddToCard($request)
    {
        $ic_margin = User::ic_margin_fire_fighting();
        $overhead = current(\DB::table('setup_fields')->where('name','fire_fighting_over_head')->pluck('value')->toArray());
        $price_res = $this->electricalPumpPriceCalculate($request, $overhead, $ic_margin, true);

        if (!$price_res['success']) {
            return $price_res;
        }

        $price = $price_res['price_list'];
        $electrical = $price_res['electrical_data'];
        $field_val = $price_res['field_val'];

        $adder_ids = [];
        if (isset($request->adder_ids)) {
            $adder_electrical = $this->show('adder-electrical');
            $adder_electrical_ids = array_map(function($val) use ($request)
            {
                if (in_array(''.$val['id'], $request->adder_ids)) {
                    return ''.$val['id'];
                }
            }, $adder_electrical);
            $adder_electrical_ids = array_filter($adder_electrical_ids);
            $adder_electrical_ids = array_values($adder_electrical_ids);
            // $request->adder_ids = $adder_electrical_ids;
            $adder_ids = $adder_electrical_ids;
        }
        
        $request->pump_type = 'electrical';
        // $pump_models = ucwords($request->pump_type);
        $pump_models = $electrical->wilo_pump_models;
        // $pump_models .= '/'.$electrical->control_panel_model;
        $pump_models .= '/'.$electrical->pump_type;
        $pump_models .= '/'.$electrical->frequency;
        $pump_models .= '/'.$electrical->pump_approval;

        // Pump Data check in cart
        $cart = FireFightingCarts::where('category', $request->pump_type)->where('pump_id', $electrical->id)->where('pump_models', $pump_models)->where('power', $electrical->motor_power)->where('frequency', $electrical->frequency)->where('pump_approval', $electrical->pump_approval)->where('flow', $electrical->flow)->where('head', $electrical->head)->where('speed_rpm', $electrical->speed_rpm)->where('wilo_article_number', $electrical->wilo_article_number);
        if (count($adder_ids) > 0) {
            $cart = $cart->whereJsonContains('adder_ids', $adder_ids);
        }
        $cart = $cart->whereNull('quotation_no')->whereNull('article_number')->whereNull('full_article_number')->where('user_id', auth()->id())->first();

        if (!is_null($cart)) {
            return [
                'success' => false,
                'msg' => 'This item already in your cart.',
            ];
        }

        // New Pump Data Save
        $cart = new FireFightingCarts();

        // Check other with same data
        $cart_check_other = FireFightingCarts::where('category', $request->pump_type)->where('pump_id', $electrical->id)->where('pump_models', $pump_models)->where('power', $electrical->motor_power)->where('frequency', $electrical->frequency)->where('pump_approval', $electrical->pump_approval)->where('flow', $electrical->flow)->where('head', $electrical->head)->where('speed_rpm', $electrical->speed_rpm)->where('wilo_article_number', $electrical->wilo_article_number);
        if (count($adder_ids) > 0) {
            $cart_check_other = $cart_check_other->whereJsonContains('adder_ids', $adder_ids);
        }
        $cart_check_other = $cart_check_other->first();
        if (!is_null($cart_check_other)) {
            $cart->article_number = $cart_check_other->article_number;
            $cart->full_article_number = $cart_check_other->full_article_number;
        }

        $cart->category = $request->pump_type;
        $cart->pump_id = $electrical->id;
        $cart->pump_models = $pump_models;
        $cart->pump_type = $electrical->pump_type;
        $cart->power = $electrical->motor_power;
        $cart->frequency = $electrical->frequency;
        $cart->pump_approval = $electrical->pump_approval;
        $cart->flow = $electrical->flow;
        $cart->head = $electrical->head;
        $cart->speed_rpm = $electrical->speed_rpm;
        $cart->wilo_article_number = $electrical->wilo_article_number;

        if (count($adder_ids) > 0) {
            $cart->adder_ids = $adder_ids;
        }

        $cart->adder_ids_prices = $price['adderprice'];
        $cart->total_adders_price = $price['adderprice'];
        $cart->overhead_price = $overhead;
        $cart->inter_company_margin_price = $ic_margin;
        $cart->qty = 1;
        $cart->price = $price['total_price'];
        $cart->total_price = $price['total_price'];
        $cart->all_prices = $price;
        $cart->field_val = $field_val;
        $cart->user_id = auth()->id();
        $cart->save();

        // dd($request->all(), $pump_data, $price);
        return [
            'success' => true,
        ];
    }

    public function dieselPumpAddToCard($request)
    {
        $ic_margin = User::ic_margin_fire_fighting();
        $overhead = current(\DB::table('setup_fields')->where('name','fire_fighting_over_head')->pluck('value')->toArray());
        $price_res = $this->dieselPumpPriceCalculate($request, $overhead, $ic_margin, true);

        if (!$price_res['success']) {
            return $price_res;
        }
        // dd($price_res, $request->all());

        $price = $price_res['price_list'];
        $diesel = $price_res['diesel_data'];
        $field_val = $price_res['field_val'];

        $adder_ids = [];
        if (isset($request->adder_ids)) {
            $adder_diesel = $this->show('adder-diesel');
            $adder_diesel_ids = array_map(function($val) use ($request)
            {
                if (in_array(''.$val['id'], $request->adder_ids)) {
                    return ''.$val['id'];
                }
            }, $adder_diesel);
            $adder_diesel_ids = array_filter($adder_diesel_ids);
            $adder_diesel_ids = array_values($adder_diesel_ids);
            // $request->adder_ids = $adder_diesel_ids;
            $adder_ids = $adder_diesel_ids;
        }
        // dd($diesel);
        $request->pump_type = 'diesel';
        
        // $pump_models = ucwords($request->pump_type);
        $pump_models = $diesel->pump_models;
        // $pump_models .= '/'.$diesel->control_panel_model;
        $pump_models .= '/'.$diesel->pump_type;
        $pump_models .= '/'.$diesel->frequency;
        $pump_models .= '/'.$diesel->pump_approval;

        // Pump Data check in cart
        $cart = FireFightingCarts::where('category', $request->pump_type)->where('pump_id', $diesel->id)->where('pump_models', $pump_models)->where('power', $diesel->engine_power)->where('frequency', $diesel->frequency)->where('pump_approval', $diesel->pump_approval)->where('engine_approval', $diesel->engine_approval)->where('flow', $diesel->flow)->where('head', $diesel->head)->where('speed_rpm', $diesel->speed_rpm)->where('wilo_article_number', $diesel->wilo_article_number);
        if (count($adder_ids) > 0) {
            $cart = $cart->whereJsonContains('adder_ids', $adder_ids);
        }
        $cart = $cart->whereNull('quotation_no')->whereNull('article_number')->whereNull('full_article_number')->where('user_id', auth()->id())->first();

        if (!is_null($cart)) {
            return [
                'success' => false,
                'msg' => 'This item already in your cart.',
            ];
        }

        // new Data Save
        $cart = new FireFightingCarts();

        // Check if other data exist
        $cart_check_other = FireFightingCarts::where('category', $request->pump_type)->where('pump_id', $diesel->id)->where('pump_models', $pump_models)->where('power', $diesel->engine_power)->where('frequency', $diesel->frequency)->where('pump_approval', $diesel->pump_approval)->where('engine_approval', $diesel->engine_approval)->where('flow', $diesel->flow)->where('head', $diesel->head)->where('speed_rpm', $diesel->speed_rpm)->where('wilo_article_number', $diesel->wilo_article_number);
        if (count($adder_ids) > 0) {
            $cart_check_other = $cart_check_other->whereJsonContains('adder_ids', $adder_ids);
        }
        $cart_check_other = $cart_check_other->first();
        if (!is_null($cart_check_other)) {
            $cart->article_number = $cart_check_other->article_number;
            $cart->full_article_number = $cart_check_other->full_article_number;
        }


        $cart->category = $request->pump_type;
        $cart->pump_id = $diesel->id;
        $cart->pump_models = $pump_models;
        $cart->pump_type = $diesel->pump_type;

        $cart->power = $diesel->engine_power;
        $cart->frequency = $diesel->frequency;
        $cart->pump_approval = $diesel->pump_approval;
        $cart->engine_approval = $diesel->engine_approval;
        $cart->flow = $diesel->flow;
        $cart->head = $diesel->head;
        $cart->speed_rpm = $diesel->speed_rpm;
        $cart->wilo_article_number = $diesel->wilo_article_number;

        if (count($adder_ids) > 0) {
            $cart->adder_ids = $adder_ids;
        }

        $cart->adder_ids_prices = $price['adderprice'];
        $cart->total_adders_price = $price['adderprice'];
        $cart->overhead_price = $overhead;
        $cart->inter_company_margin_price = $ic_margin;
        $cart->qty = 1;
        $cart->price = $price['total_price'];
        $cart->total_price = $price['total_price'];
        $cart->all_prices = $price;
        $cart->field_val = $field_val;
        $cart->user_id = auth()->id();
        $cart->save();

        // dd($request->all(), $pump_data, $price);
        return [
            'success' => true,
        ];
    }

    public function electricalDieselPumpAddToCard($request)
    {
        // dd($request->all());
        $electrical = $this->electricalPumpAddToCard($request);
        if (!$electrical['success']) {
            return $electrical;
        }

        $request->data = $request->extra_data;
        $diesel = $this->dieselPumpAddToCard($request);
        if (!$diesel['success']) {
            return $diesel;
        }
        return [
            'success' => true
        ];
    }

    public function priceCalculate($request)
    {
        $ic_margin = User::ic_margin_fire_fighting();
        $overhead = current(\DB::table('setup_fields')->where('name','fire_fighting_over_head')->pluck('value')->toArray());
        $price = '';
        switch ($request->pump_type) {
            case 'jockey-pump':
                    $frequency = $request->data['frequency'];

                    // Pump Price
                    $pump_price = $request->data['unit_price'];

                    // Control Panel Price
                    $power = $request->data['power'] * 1.341;
                    
                    $adder_ids = [];
                    if (isset($request->adder_ids)) {
                        $adder_ids = $request->adder_ids;
                    }
                    $price = $this->jockeyPumpPriceCalculate($pump_price, $power, $frequency, $overhead, $ic_margin, $adder_ids);

                    return [
                        'success' => true,
                        'msg' => '',
                        'price' => $price['price']
                    ];
                break;
                
            case 'electrical':
                    return $this->electricalPumpPriceCalculate($request, $overhead, $ic_margin);
                break;
                
            case 'diesel':
                    return $this->dieselPumpPriceCalculate($request, $overhead, $ic_margin);
                break;
                
            case 'electrical-diesel':
                    return $this->electricalDieselPumpPriceCalculate($request, $overhead, $ic_margin);
                break;
        }
    }

    public function jockeyPumpPriceCalculate($pump_price, $power, $frequency, $overhead, $ic_margin, $adder_ids)
    {
        $control_panel = ControlPanelMaster::select('*')->where('category', 'Jockey')->where('frequency', $frequency)->get()->toArray();
        // dd($control_panel);
        $control_panel_price = collect($control_panel)->pluck('unit_price', 'motor_power')->pipe(function ($data) use ($power) {
            $closest = null;
            $closest_price = null;
            foreach ($data as $item => $item_price) {
                if ($closest === null || abs($power - $closest) > abs($item - $power)) {
                    $closest = $item;
                    $closest_price = $item_price;
                }
            }
            return $closest_price;
        });

        $adderprice = 0;
        $adderpricelist = [];
        if (count($adder_ids) > 0) {
            $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->where('version', 'FireFighting/Jockey')->whereIn('id', $adder_ids)->get();
            if (count($data) > 0) {
                foreach ($data as $key => $value) {
                    $type = str_replace(' ', '', strtolower($value->type));
                    $optional_master = OptionalMaster::where('category','Jockey')->where($type, 1)->first();
                    if ($optional_master) {
                        $adderprice = $adderprice + $optional_master->unit_price;
                        array_push($adderpricelist, [
                            'list' => $value->adder_list,
                            'code' => $value->id,
                            'price' => $optional_master->unit_price
                        ]);
                    }
                }
            }
        }

        $price = (($pump_price + $control_panel_price + $adderprice)*$overhead)/$ic_margin;
        return [
            'price' => $price,
            'pump_price' => $pump_price,
            'control_panel_price' => $control_panel_price,
            'adderprice' => $adderprice,
            'adderpricelist' => $adderpricelist
        ];
    }

    public function electricalPumpPriceCalculate($request, $overhead, $ic_margin, $cart = false)
    {
        $change = [
            'id' => 'id',
            'electrical_pumpmodels' => 'wilo_pump_models', 
            'electrical_pumptype' => 'pump_type', 
            'electrical_frequency' => 'frequency', 
            'electrical_pump_approval' => 'pump_approval', 
            'electrical_flow' => 'flow', 
            'electrical_head' => 'head', 
            'electrical_speed' => 'speed_rpm'
        ];

        $field_val = [];

        $fetchElectrical = ElectricalPump::select('*');
        foreach ($request->data as $key => $value) {
            $fetchElectrical = $fetchElectrical->where($change[$value['name']], $value['value']);
            array_push($field_val, [$value['name'] => $value['value']]);
        }
        $fetchElectrical = $fetchElectrical->first();

        if (!is_null($fetchElectrical)) {
            
            // Pump Price
            $pump_price = $fetchElectrical->unit_price;

            $control_panel = ControlPanelMaster::select('*')->where('category', 'Electrical')->where('model', $fetchElectrical->control_panel_model)->where('frequency', $fetchElectrical->frequency)->where('motor_power', $fetchElectrical->motor_power)->first();

            if (!is_null($control_panel)) {
                $control_panel_price = $control_panel->unit_price;
                $control_motor_power = (float)$control_panel->motor_power;

                // Adder Id price found
                $adderprice = 0;
                $adderpricelist = [];

                if (isset($request->adder_ids)) {
                    $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->where('version', 'FireFighting/Electrical')->whereIn('id', $request->adder_ids)->get();
                    // dd($data);
                    if (count($data) > 0) {
                        foreach ($data as $key => $value) {
                            $type = str_replace(' ', '', strtolower($value->type));
                            if ($type != '' && $type != 'null') {
                                $type = $type == 'terminalbox' ? 'terminal_box' : $type;

                                $optional_master = OptionalMaster::where('category','Electrical')
                                ->where($type, 1)
                                ->where('min_power', '<=', $control_motor_power)
                                ->where('max_power', '>=', $control_motor_power)
                                ->first();
                                if ($optional_master) {
                                    $adderprice = $adderprice + $optional_master->unit_price;
                                    array_push($adderpricelist, [
                                        'list' => $value->adder_list,
                                        'code' => $value->id,
                                        'price' => $optional_master->unit_price
                                    ]);
                                } else {
                                    return [
                                        'success' => false,
                                        'msg' => $value->adder_list . ' data not match please contact to admin.',
                                        'price' => ''
                                    ];
                                }
                            } else {
                                if (strpos($value->adder_list, 'TEFC') !== false) {
                                    if ($fetchElectrical->speed_rpm >= 2900) {
                                        $pole = 2;
                                    } else {
                                        $pole = 4;
                                    }

                                    $motor = FireFightingMotor::where('motor_power', $control_motor_power)->where('frequency', $fetchElectrical->frequency)->where('number_of_pole', $pole)->first();
                                    if (is_null($motor)) {
                                        return [
                                            'success' => false,
                                            'msg' => $value->adder_list . ' data not match please contact to admin.',
                                            'price' => ''
                                        ];
                                    } else {
                                        $adderprice = $adderprice + $motor->unit_price;
                                        array_push($adderpricelist, [
                                            'list' => $value->adder_list,
                                            'code' => $value->id,
                                            'price' => $motor->unit_price
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                // dump($pump_price , $control_panel_price , $adderprice);
                $price = (($pump_price + $control_panel_price + $adderprice)*$overhead)/$ic_margin;
                $return = [
                    'success' => true,
                    'msg' => '',
                    'price' => $price,
                    'data' => [
                        'wilo_pump_models' => $fetchElectrical->wilo_pump_models,
                        'pump_type' => $fetchElectrical->pump_type,
                        'frequency' => $fetchElectrical->frequency,
                    ]
                ];

                if ($cart) {
                    $return['price_list'] = [
                        'total_price' => $price,
                        'pump_price' => $pump_price,
                        'control_panel_price' => $control_panel_price,
                        'adderprice' => $adderprice,
                        'adderpricelist' => $adderpricelist
                    ];
                    $return['electrical_data'] = $fetchElectrical;
                    $return['field_val'] = $field_val;
                }

                return $return;
            } else {
                return [
                    'success' => false,
                    'msg' => 'Control panel modal data not match please try again..!!',
                    'price' => ''
                ];
            }
        } else {
            return [
                'success' => false,
                'msg' => 'Electrical Data not found please try again..!!',
                'price' => ''
            ];
        }
    }

    public function dieselPumpPriceCalculate($request, $overhead, $ic_margin, $cart = false)
    {
        $change = [
            'id' => 'id',
            'diesel_pumpmodels' => 'pump_models',
            'diesel_pumptype' => 'pump_type',
            'diesel_frequency' => 'frequency',
            'diesel_pump_approval' => 'pump_approval',
            'diesel_engine_approval' => 'engine_approval',
            'diesel_flow' => 'flow',
            'diesel_head' => 'head',
            'diesel_speed' => 'speed_rpm'
        ];

        $field_val = [];

        $fetchDiesel = DieselPump::select('*');
        foreach ($request->data as $key => $value) {
            $fetchDiesel = $fetchDiesel->where($change[$value['name']], $value['value']);
            array_push($field_val, [$value['name'] => $value['value']]);
        }
        $fetchDiesel = $fetchDiesel->first();

        if (!is_null($fetchDiesel)) {
            
            // Pump Price
            $pump_price = $fetchDiesel->unit_price;
            

            // Control Panel Price Get
            $control_panel = ControlPanelMaster::select('*')->where('category', 'Diesel')->where('model', $fetchDiesel->control_panel_model)->where('frequency', $fetchDiesel->frequency)->first();

            if (!is_null($control_panel)) {
                $control_panel_price = $control_panel->unit_price;

                // Disel Tank Price Get
                $disel_tank = DieselTankMaster::where('tank_size', $fetchDiesel->diesel_tank_us)->first();
                if (!is_null($disel_tank)) {

                    $disel_tank_price = $disel_tank->unit_price;

                    // Battery Price Get
                    $battery = BatteryMaster::where('model', $fetchDiesel->battery_rating)->first();
                    if (!is_null($battery)) {
                        $battery_price = $battery->unit_price * $fetchDiesel->battery_qty;

                        // Adder Id price found
                        $adderprice = 0;
                        $adderpricelist = [];
                        if (isset($request->adder_ids)) {
                            $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->where('version', 'FireFighting/Diesel')->whereIn('id', $request->adder_ids)->get();

                            if (count($data) > 0) {

                                foreach ($data as $key => $value) {
                                    $type = str_replace(' ', '', strtolower($value->type));
                                    if ($type != '') {
                                        $type = $type == 'terminalbox' ? 'terminal_box' : $type;

                                        $optional_master = OptionalMaster::where('category','Diesel')
                                        ->where($type, 1)
                                        ->first();
                                        if ($optional_master) {
                                            $adderprice = $adderprice + $optional_master->unit_price;
                                            array_push($adderpricelist, [
                                                'list' => $value->adder_list,
                                                'code' => $value->id,
                                                'price' => $optional_master->unit_price
                                            ]);
                                        }
                                    } else {
                                        if (strpos($value->adder_list, 'Pressure relief valve') !== false) {
                                            $pressure_releif_valve = FireFightingPressureReliefValve::where('size', $fetchDiesel->pressure_releif_valve)->first();
                                            if (is_null($pressure_releif_valve)) {
                                                return [
                                                    'success' => false,
                                                    'msg' => $value->adder_list.' data not match please contact to admin.',
                                                    'price' => ''
                                                ];
                                            } else {
                                                $adderprice = $adderprice + $pressure_releif_valve->unit_price;
                                                array_push($adderpricelist, [
                                                    'list' => $value->adder_list,
                                                    'code' => $value->id,
                                                    'price' => $pressure_releif_valve->unit_price
                                                ]);
                                            }
                                        }
                                        if (strpos($value->adder_list, 'Flow meter') !== false) {
                                            $flow_meter = FireFightingFlowMeter::where('size', $fetchDiesel->flow_meter_size)
                                                ->where('min_gpm', '<=', (float)$fetchDiesel->flow)
                                                ->where('max_gpm', '>=', (float)$fetchDiesel->flow)
                                                ->first();
                                            if (is_null($flow_meter)) {
                                                return [
                                                    'success' => false,
                                                    'msg' => $value->adder_list.' data not match please contact to admin.',
                                                    'price' => ''
                                                ];
                                            } else {
                                                $adderprice = $adderprice + $flow_meter->unit_price;
                                                array_push($adderpricelist, [
                                                    'list' => $value->adder_list,
                                                    'code' => $value->id,
                                                    'price' => $flow_meter->unit_price
                                                ]);
                                            }
                                        }
                                        if (strpos($value->adder_list, 'Waste cone') !== false) {
                                            $waste_cone_brand = FireFightingWasteCone::where('size', $fetchDiesel->waste_cone_brand)->first();
                                            if (is_null($waste_cone_brand)) {
                                                return [
                                                    'success' => false,
                                                    'msg' => $value->adder_list.' data not match please contact to admin.',
                                                    'price' => ''
                                                ];
                                            } else {
                                                $adderprice = $adderprice + $waste_cone_brand->unit_price;
                                                array_push($adderpricelist, [
                                                    'list' => $value->adder_list,
                                                    'code' => $value->id,
                                                    'price' => $waste_cone_brand->unit_price
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // dump($pump_price , $control_panel_price , $disel_tank_price , $battery_price , $adderprice);
                        $price = (($pump_price + $control_panel_price + $disel_tank_price + $battery_price + $adderprice)*$overhead)/$ic_margin;


                        $return = [
                            'success' => true,
                            'msg' => '',
                            'price' => $price,
                            'data' => [
                                'wilo_pump_models' => $fetchDiesel->pump_models,
                                'pump_type' => $fetchDiesel->pump_type,
                                'frequency' => $fetchDiesel->frequency,
                            ]
                        ];

                        if ($cart) {
                            $return['price_list'] = [
                                'total_price' => $price,
                                'pump_price' => $pump_price,
                                'control_panel_price' => $control_panel_price,
                                'disel_tank_price' => $disel_tank_price,
                                'battery_orignal_price' => $battery->unit_price,
                                'battery_qty' => $fetchDiesel->battery_qty,
                                'battery_price' => $battery_price,
                                'adderprice' => $adderprice,
                                'adderpricelist' => $adderpricelist
                            ];
                            $return['diesel_data'] = $fetchDiesel;
                            $return['field_val'] = $field_val;
                        }

                        return $return;
                    } else {
                        return [
                            'success' => false,
                            'msg' => 'Battery master modal data not match please try again..!!',
                            'price' => ''
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'msg' => 'Disel Tank size data not match please try again..!!',
                        'price' => ''
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'msg' => 'Control panel modal data not match please try again..!!',
                    'price' => ''
                ];
            }
        } else {
            return [
                'success' => false,
                'msg' => 'Disel Data not found please try again..!!',
                'price' => ''
            ];
        }
    }

    public function electricalDieselPumpPriceCalculate($request, $overhead, $ic_margin, $cart = false)
    {
        $request->data = $request->electrical_data;
        $electrical = $this->electricalPumpPriceCalculate($request, $overhead, $ic_margin, $cart = false);
        if (!$electrical['success']) {
            return $electrical;
        }


        $request->data = $request->diesel_data;
        $diesel = $this->dieselPumpPriceCalculate($request, $overhead, $ic_margin, $cart = false);
        if (!$diesel['success']) {
            return $diesel;
        }
        // dd($electrical, $diesel);
        return [
            'success' => true,
            'html' => '<div class="row">
                <div class="col-6">
                    <div class="columns">
                        <ul class="price" style="list-style: none;">
                            <li class="header"><u>Electrical</u></li>
                            <li class="header">'.$electrical['data']['wilo_pump_models'].'</li>
                            <li class="grey">'.$electrical['data']['pump_type'].'</li>
                            <li class="grey">'.$electrical['data']['frequency'].' </li>
                            <li>Total Price: <b>'.round($electrical['price'], 2).'</b><span>$</span> </li>  
                        </ul>
                    </div>
                </div>
                <div class="col-6">
                    <div class="columns">
                        <ul class="price" style="list-style: none;">
                            <li class="header"><u>Diesel</u></li>
                            <li class="header">'.$diesel['data']['wilo_pump_models'].'</li>
                            <li class="grey">'.$diesel['data']['pump_type'].'</li>
                            <li class="grey">'.$diesel['data']['frequency'].' </li>
                            <li>Total Price: <b>'.round($diesel['price'], 2).'</b><span>$</span> </li>  
                        </ul>
                    </div>
                </div>
            </div>'
        ];
        // dd($electrical, $diesel);
    }
}
