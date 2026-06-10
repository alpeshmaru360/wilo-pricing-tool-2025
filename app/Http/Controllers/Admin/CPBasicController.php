<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ControlPanelFileImportProcess;
use Carbon\Carbon;
use App\Traits\ControlPanelModelIdGet;
use Excel;
use App\Imports\UsersImport;
use File;
use App\ControlPanel;
use Artisan;
use DB;
use Session;

class CPBasicController extends Controller {

    use ControlPanelModelIdGet;

    //
    public function import(Request $request) {
        return view('admin.main_cp_file_import.import');
    }
    public function upload(Request $request) {

         $file = $request->file_import;
        if (!empty($file)) {
              $path = '/app/public/';
               if (!File::exists($path)) {
                $this->make_directory(storage_path() . '/' . $path);
            }
            $filePath = storage_path() . $path;
            $file_excel = $this->uploadFile($file, $filePath);
            
          }
          $insertData = array('filename'=>$file_excel) ;
          
           DB::table('control_panel_file_upload')
              ->where('id', 1)
              ->update(['filename' => $file_excel]);
     
              Session::flash('message', "Success! Your file has been uploaded."
                      . "Please click button latest file import");
        return redirect()->back();
          
//          $exitCode = Artisan::call('queue:work --stop-when-empty', []);
        set_time_limit(0);

//        $path = Storage::path('public/File Selection - Final (1).xlsx'); // url come from db
       
         $path = $filePath . $file_excel; // url come from db
//          echo $path;
//        die;
        $insertData = [];
        $data = Excel::toArray(new UsersImport, $path);

        unset($data[0][0]);
        $array = array_chunk($data[0], 500);

        foreach ($array as $chunk) {
            $insertData = [];
            foreach ($chunk as $Row) {
                $insertData[] = array('no_of_pump_id' => $this->getIdByValue('App\NumberOfPump', 'value', $Row[0]),
                    'power_id' => $this->getIdByValue('App\Power', 'value', $Row[1]),
                    'voltage_id' => $this->getIdByValue('App\Voltage', 'value', $Row[2]), //Power Supply
                    'application_id' => $this->getIdByValue('App\Application', 'value', $Row[3]),
                    'ambient_temp_id' => $this->getIdByValue('App\AmbientTemp', 'value', $Row[4]),
                    'stater_type_id' => $this->getIdByValue('App\StarterType', 'value', $Row[5]),
                    'communication_protocol_id' => $this->getIdByValue('App\ComunicationProtocol', 'value', $Row[6]),
                    'ip_rating_id' => $this->getIdByValue('App\IpRating', 'value', $Row[7]),
                    'components_id' => $this->getIdByValue('App\Component', 'value', $Row[8]),
                    'enclosure_id' => $this->getIdByValue('App\Enclousre', 'value', $Row[9]),
                    'range' => $this->getIdByValue('App\Range', 'value', $Row[10]),
                    'folder_name' => isset($Row[11]) ? $Row[11] : '',
                    'file_name_under_folder' => isset($Row[12]) ? $Row[12] : '',
                    'table_name' => isset($Row[13]) ? $Row[13] : '',
                    'starter_code' => isset($Row[14]) ? $Row[14] : '',
                    'price' => 0,
                    'user_id' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                );
            }
            $job = (new ControlPanelFileImportProcess($insertData))
                    ->delay(Carbon::now()->addSeconds(15));
            dispatch($job);
            // dd($job);
//            ControlPanelFileImportProcess::dispatch($insertData);
        }


        return 'test';
    }
    
     public function uploadFile($file, $path, $type = '') {
        $is_uploaded = false;

        if (!empty($file)) {

            $fileName = $file->getClientOriginalName();
//            $extension = \File::extension($file);
//             $fileName = rand(11111111, 99999999) . '.' . $extension;

            if ($file->move($path, $fileName)) {
                $is_uploaded = $fileName;
            }
        }
        return $is_uploaded;
    }
  public function make_directory($path) {
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    }
}
