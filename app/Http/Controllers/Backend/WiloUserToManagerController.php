<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\WiloUserToManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Auth\User;
use function GuzzleHttp\json_decode;

class WiloUserToManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //dd('hello');
        $data = DB::table('users')
->join('wilo_user_manager', function($join)
{
    $join->on('users.id', '=', 'wilo_user_manager.manager_id')
         ->where('users.active','=',1);
})->groupBy('wilo_user_manager.manager_id')
->get();

$data1 = DB::table('users')
->join('wilo_user_manager', function($join)
{
    $join->on('users.id', '=', 'wilo_user_manager.user_id');
})->get();


$data = json_decode($data, true);
$data1 =  json_decode($data1, true);
// dd($data);
       // select * from users join wilo_user_manager on users.id = wilo_user_manager.manager_id where users.active = 1
        return View('backend.usertomanager.index')
        ->with('data',$data)
        ->with('data1',$data1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
$data = DB::table('users')
->join('model_has_roles', function($join)
{
    $join->on('users.id', '=', 'model_has_roles.model_id')
         ->where('model_has_roles.role_id', '=', 2)
         ->where('model_has_roles.role_id','!=',1)
         ->where('users.active','=',1);
})
->get();
$data2 = DB::table('users')
->join('model_has_roles', function($join)
{
    $join->on('users.id', '=', 'model_has_roles.model_id')
         ->where('model_has_roles.role_id', '!=', 2)
         ->where('model_has_roles.role_id','!=',1)
         ->where('users.active','=',1);
})
->get();
 //dd($data2);
//dd($data->toArray());
$data1 = $data->toArray();
// $i = 0;
// foreach($data as $row)
// {
//     //$data1 = array_merge($data1, array("id"=>$data[$i]->id,"name"=>$data[$i]->first_name));

//     $data1["id"] = $data[$i]->id;
//     $data1["name"]= $data[$i]->first_name;
//     // array_push($data1["id"]=$data[$i]->id);
//     // array_push($data1["name"]=$data[$i]->first_name);
   
//    $i++;
// }
// for($i=0;$i<count($data1);$i++)
// {
//     $data1[$i]["id"] = 
// }
 //dd($data1);

        return View('backend.usertomanager.create')
        ->with('data',$data1)
        ->with('data2',$data2->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        for($i =0 ; $i<count($request->users) ; $i++)
        {
            DB::table('wilo_user_manager')->insert(
                ['manager_id' => $request->manager, 'user_id' => $request->users[$i] ]
            );
        }
        return redirect()->route('admin.usertomanager.index')->withFlashSuccess(__('User mapped.'));
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WiloUserToManager  $wiloUserToManager
     * @return \Illuminate\Http\Response
     */
    public function show(WiloUserToManager $wiloUserToManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WiloUserToManager  $wiloUserToManager
     * @return \Illuminate\Http\Response
     */
    public function edit(WiloUserToManager $wiloUserToManager,$id)
    {
       //Get selected Manager
       $data3=DB::select(DB::raw('SELECT * FROM users inner join wilo_user_manager on users.id = wilo_user_manager.manager_id where wilo_user_manager.manager_id = '.$id.' group by wilo_user_manager.manager_id ' ));

// $data3 = json_decode($data3, true);
 //dd($data3[0]->id);

 //Get all Child for selected Manager

// $data4=DB::select(DB::raw('SELECT * FROM users inner join wilo_user_manager on users.id = wilo_user_manager.user_id where wilo_user_manager.manager_id = '.$id.' ' ));
//dd($data4);
$data4 = DB::table('users')
->join('wilo_user_manager', function($join) use ($id)
{
    $join->on('users.id', '=', 'wilo_user_manager.user_id')
         ->where('wilo_user_manager.manager_id', '=', $id);
})
->get();
$data4 = $data4->toArray();
$data5 = array();
foreach($data4 as $row2)
{
    // ??dd($row2);

$data5[] = $row2->id;
}
// $data4 = json_decode($data4,true);
 //dd($data4[0]['id']);
        //Get all Manager
$data = DB::table('users')
->join('model_has_roles', function($join)
{
    $join->on('users.id', '=', 'model_has_roles.model_id')
         ->where('model_has_roles.role_id', '=', 2)
         ->where('model_has_roles.role_id','!=',1)
         ->where('users.active','=',1);
})
->get();
// Get all users
$data2 = DB::table('users')
->join('model_has_roles', function($join)
{
    $join->on('users.id', '=', 'model_has_roles.model_id')
         ->where('model_has_roles.role_id', '!=', 2)
         ->where('model_has_roles.role_id','!=',1)
         ->where('users.active','=',1);
})
->get();

$data2= $data2->toArray();
$data4 = array(1,2,3);
foreach($data2 as $key=>$row)
{
    // dd($row->id);
    $id = $row->id;
    if(in_array($id,$data5))
    {
         $data2[$key]->check = true;   
    }
    else
    {
        $data2[$key]->check = false;   
    }
}

$data1 = $data->toArray();

        return View('backend.usertomanager.edit')
        ->with('data',$data1)
        ->with('data2',$data2)
        ->with('data3',$data3);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WiloUserToManager  $wiloUserToManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WiloUserToManager $wiloUserToManager,$id)
    {
        //
        //dd($request);
     //dd($id);
          DB::select(DB::raw('DELETE FROM wilo_user_manager WHERE (manager_id) IN ('.$id.') ' ));
        // $data = DB::table('wilo_user_manager')->where('manager_id',$id)->delete();
         //dd($request->manager);
       
 
        for($i =0 ; $i<count($request->users) ; $i++)
        {
            DB::table('wilo_user_manager')->insert(
                ['manager_id' => $request->manager, 'user_id' => $request->users[$i] ]
            );
        }
   
       // echo "false";
    
         return redirect()->route('admin.usertomanager.index')->withFlashSuccess(__('User mapped.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WiloUserToManager  $wiloUserToManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(WiloUserToManager $wiloUserToManager,$id)
    {
        //
        // $data = DB::select(DB::raw('DELETE FROM wilo_user_manager WHERE (manager_id) IN ('.$id.') ' ));
        // return redirect()->route('admin.usertomanager.index')->withFlashDanger(__('User Relation deleted.'));
    }
}
