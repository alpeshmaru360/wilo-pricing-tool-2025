<?php

namespace App\Http\Controllers\Frontend\FireFighting;

use App\Http\Controllers\Controller;
use App\Models\FireFighting\BatteryMaster;
use App\Models\FireFighting\ControlPanelMaster;
use App\Models\FireFighting\DieselPump;
use App\Models\FireFighting\DieselTankMaster;
use App\Models\FireFighting\ElectricalPump;
use App\Models\FireFighting\FireFightingAdders;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['electrical_wilo_pump_models'] = ElectricalPump::select('wilo_pump_models')->pluck('wilo_pump_models')->toArray();
        $data['diesel_pump_models'] = DieselPump::select('pump_models')->pluck('pump_models')->toArray();
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
            case 'adder-ids':
                    return $this->adderIdsAdd($request);
                break;

            case 'price-calculate':
                    return $this->priceCalculate($request);
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
                    $data = JockeyPump::select('id', 'pump_article_no', 'power', 'frequency', 'unit_price')->get()->toArray();
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
            
            default:
                    dd($request->all(), $id);
                break;
        }
        dd($request->all(), $id);
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

    public function adderIdsAdd($request)
    {
        $price = '';
        switch ($request->pump_type) {
            // Adder Ids
            case 'jockey-pump':
                    $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->where('version', 'FireFighting/Jockey')->whereIn('id', $request->adder_ids)->get();
                    if (count($data) > 0) {
                        $price = 0;
                        foreach ($data as $key => $value) {
                            $type = str_replace(' ', '', strtolower($value->type));
                            $optional_master = OptionalMaster::where('category','Jockey')->where($type, 1)->first();
                            if ($optional_master) {
                                $price = $price + $optional_master->unit_price;
                            }
                        }
                    }

                break;
                
            case 'electrical':
                    $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->where('version', 'FireFighting/Electrical')->whereIn('id', $request->adder_ids)->first();
                    if ($data == 'Motor upgrade to TEFC') {
                    dd($data);
                    } else {
                        $type = str_replace(' ', '', strtolower($data->type));
                        $optional_master = OptionalMaster::where('category','Jockey')->where($type, 1)->first();
                        if ($optional_master) {
                            $price = $optional_master->unit_price;
                        }
                    }
                break;
                
            case 'diesel':
                    $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->where('version', 'FireFighting/Diesel')->whereIn('id', $request->adder_ids)->first();
                    dd($data);
                break;
                
            case 'electrical-diesel':
                    $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->whereIn('version', ['FireFighting/Electrical', 'FireFighting/Diesel'])->whereIn('id', $request->adder_ids)->first();
                    dd($data);
                break;
        }

        return $price;
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
                    if (isset($request->adder_ids)) {
                        $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->where('version', 'FireFighting/Jockey')->whereIn('id', $request->adder_ids)->get();
                        if (count($data) > 0) {
                            foreach ($data as $key => $value) {
                                $type = str_replace(' ', '', strtolower($value->type));
                                $optional_master = OptionalMaster::where('category','Jockey')->where($type, 1)->first();
                                if ($optional_master) {
                                    $adderprice = $adderprice + $optional_master->unit_price;
                                }
                            }
                        }
                    }

                    $price = (($pump_price + $control_panel_price + $adderprice)*$overhead)/$ic_margin;

                    return [
                        'success' => true,
                        'msg' => '',
                        'price' => $price
                    ];
                break;
                
            case 'electrical':
                    $change = [
                        'electrical_pumpmodels' => 'wilo_pump_models', 
                        'electrical_pumptype' => 'pump_type', 
                        'electrical_frequency' => 'frequency', 
                        'electrical_pump_approval' => 'pump_approval', 
                        'electrical_flow' => 'flow', 
                        'electrical_head' => 'head', 
                        'electrical_speed' => 'speed_rpm'
                    ];

                    $fetchElectrical = ElectricalPump::select('*');
                    foreach ($request->data as $key => $value) {
                        $fetchElectrical = $fetchElectrical->where($change[$value['name']], $value['value']);
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
                            if (isset($request->adder_ids)) {
                                $data = FireFightingAdders::select('id', 'adder_list','version','code', 'type')->where('version', 'FireFighting/Electrical')->whereIn('id', $request->adder_ids)->get();

                                if (count($data) > 0) {
                                    foreach ($data as $key => $value) {
                                        $type = str_replace(' ', '', strtolower($value->type));
                                        if ($type != '') {
                                            $type = $type == 'terminalbox' ? 'terminal_box' : $type;

                                            $optional_master = OptionalMaster::where('category','Electrical')
                                            ->where($type, 1)
                                            ->where('min_power', '<=', $control_motor_power)
                                            ->where('max_power', '>=', $control_motor_power)
                                            ->first();
                                            if ($optional_master) {
                                                $adderprice = $adderprice + $optional_master->unit_price;
                                                // dump($optional_master->id);
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
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            // dump($pump_price , $control_panel_price , $adderprice);
                            $price = (($pump_price + $control_panel_price + $adderprice)*$overhead)/$ic_margin;

                            return [
                                'success' => true,
                                'msg' => '',
                                'price' => $price,
                                'data' => [
                                    'wilo_pump_models' => $fetchElectrical->wilo_pump_models,
                                    'pump_type' => $fetchElectrical->pump_type,
                                    'frequency' => $fetchElectrical->frequency,
                                ]
                            ];
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
                break;
                
            case 'diesel':
                    $change = [
                        'diesel_pumpmodels' => 'pump_models',
                        'diesel_pumptype' => 'pump_type',
                        'diesel_frequency' => 'frequency',
                        'diesel_pump_approval' => 'pump_approval',
                        'diesel_engine_approval' => 'engine_approval',
                        'diesel_flow' => 'flow',
                        'diesel_head' => 'head',
                        'diesel_speed' => 'speed_rpm'
                    ];

                    $fetchDiesel = DieselPump::select('*');
                    foreach ($request->data as $key => $value) {
                        $fetchDiesel = $fetchDiesel->where($change[$value['name']], $value['value']);
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

                                    $battery_price = $battery->unit_price;

                                    // Adder Id price found
                                    $adderprice = 0;
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
                                                        // dump($optional_master->id);
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
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    // dump($pump_price , $control_panel_price , $disel_tank_price , $battery_price , $adderprice);
                                    $price = (($pump_price + $control_panel_price + $disel_tank_price + $battery_price + $adderprice)*$overhead)/$ic_margin;

                                    return [
                                        'success' => true,
                                        'msg' => '',
                                        'price' => $price,
                                        'data' => [
                                            'wilo_pump_models' => $fetchDiesel->pump_models,
                                            'pump_type' => $fetchDiesel->pump_type,
                                            'frequency' => $fetchDiesel->frequency,
                                        ]
                                    ];   
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
                            'msg' => 'Electrical Data not found please try again..!!',
                            'price' => ''
                        ];
                    }
                break;
                
            case 'electrical-diesel':
                    
                break;
        }
    }
}
