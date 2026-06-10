<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use DB;
use Session;
use App\ScpPumpType;
use App\ScpPump;
use App\ScpMaterial;
use App\Traits\ComponentModelIdValueGet;
use App\Helpers\ScpGigaDynamicTableCreateHelper;
use App\ScpMasterMotorPrice;
use App\ScpAssemblyCostPcPk;
use App\ScvpAssemblyCostPcPk;
use App\ScpAdder;
use App\ScvpPumpType;
use App\ScvpMasterMotorPrice;

class SCVPFileImportController extends Controller {

    use ComponentModelIdValueGet;

    //1
    public function importAccessories(Request $request) {
        return view('admin.scvp_import.accesories_price_import');
    }

    //2
    // public function importAccessoriesUpload(Request $request) {
    //     set_time_limit(0);
    //     $tableName = 'scvp_accessories_price';
    //     DB::table($tableName)->truncate();
    //     //
    //     $file = $request->file_import;
    //     if (!empty($file)) {
    //         // $path = 'app/public/' . $tableName;
            
    //         // if (!File::exists($path)) {
    //         //     $this->make_directory(storage_path() . '/' . $path);
    //         // }
    //         if (!empty($file)) {
    //             $path = storage_path('app/public/' . $tableName);

    //             if (!File::exists($path)) {
    //                 File::makeDirectory($path, 0777, true, true);
    //             }

    //             // now you can move/store your file inside $path
    //             $file->move($path, $file->getClientOriginalName());
    //         }

    //         // $filePath = storage_path() . $path . "/";
    //         // $file_excel = $this->uploadFile($file, $filePath);
    //         $filePath = $path . '/' . $file->getClientOriginalName();

    //         // $data = new \SpreadsheetReader($filePath . $file_excel);
    //         $data = new \SpreadsheetReader($filePath);

    //         $row1Data = [];
    //         $row2Data = [];
    //         $row3Data = [];
    //         foreach ($data as $key => $d) {

    //             if ($key == 0) {
    //                 unset($d[0]);
    //                 unset($d[1]);
    //                 unset($d[2]);
    //                 unset($d[3]);
    //                 foreach ($d as $row1) {
    //                     if ($row1) {
    //                         $row1Data[] = $this->getIdByValue('App\ScpPumpType', 'name', $row1);
    //                     }
    //                 };
    //             }
    //             if ($key == 1) {
    //                 unset($d[0]);
    //                 unset($d[1]);
    //                 unset($d[2]);
    //                 unset($d[3]);
    //                 foreach ($d as $row2) {
    //                     if ($row2) {
    //                         $row2Data[] = str_replace(".", "__", $row2);
    //                     }
    //                 };
    //             }
    //             if ($key == 2) {
    //                 foreach ($d as $k => $row3) {
    //                     if ($k < 4) {
    //                         $row3Data[] = $row3;
    //                     }
    //                 };
    //             }
    //         }

    //         $combineRows = [];

    //         foreach ($row1Data as $key => $val) {
    //             $combineRows[] = $val . 'x' . $row2Data[$key];
    //         }
			
    //         foreach ($row3Data as $key => $rd) {
    //             $check_space = strpos($rd, ' ');
    //             if ($check_space > 0) {

    //                 $rd = str_replace(" ", "_", $rd);
    //                 $row3Data[$key] = $rd;
    //             }
    //         }

    //         $createTableFieldArray = ScpGigaDynamicTableCreateHelper::createDynamic($tableName, $combineRows, $row3Data);
    //         unset($createTableFieldArray[0]);

    //         foreach ($data as $key => $d) {

    //             if ($key > 2 && is_array($createTableFieldArray)) {

    //                 $insertData = [];
    //                 for ($column = 0; $column < count($createTableFieldArray); $column ++) {

    //                     $insertData[$createTableFieldArray[$column + 1]['name']] = isset($d[$column]) ? $d[$column] : 0;
    //                 }

    //                 DB::table($tableName)->insert(
    //                         array($insertData)
    //                 );
    //             }
    //         }
    //     }

    //     Session::flash('message', "Success! Your file has been imported ");
    //     return redirect()->back();
    // }

    public function importAccessoriesUpload(Request $request)
    {
        set_time_limit(0);

        $tableName = 'scvp_accessories_price';

        // Clear old data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($tableName)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $file = $request->file_import;
        if (!empty($file)) {

            $path = storage_path('app/public/' . $tableName);

            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            // Move uploaded file
            $file->move($path, $file->getClientOriginalName());
            $filePath = $path . '/' . $file->getClientOriginalName();

            // Read Excel
            $data = new \SpreadsheetReader($filePath);
            $rows = iterator_to_array($data); // store once

            $row1Data = [];
            $row2Data = [];
            $row3Data = [];

            // Process first 3 rows
            foreach ($rows as $key => $d) {
                if ($key == 0) {
                    unset($d[0], $d[1], $d[2], $d[3]);
                    foreach ($d as $row1) {
                        if ($row1) {
                            $row1Data[] = $this->getIdByValue('App\ScpPumpType', 'name', $row1);
                        }
                    }
                }
                if ($key == 1) {
                    unset($d[0], $d[1], $d[2], $d[3]);
                    foreach ($d as $row2) {
                        if ($row2) {
                            $row2Data[] = str_replace(".", "__", $row2);
                        }
                    }
                }
                if ($key == 2) {
                    foreach ($d as $k => $row3) {
                        if ($k < 4) {
                            $row3Data[] = $row3;
                        }
                    }
                }
            }

            // Combine row1 + row2
            $combineRows = [];
            foreach ($row1Data as $key => $val) {
                $combineRows[] = $val . 'x' . $row2Data[$key];
            }

            // Replace spaces in row3
            foreach ($row3Data as $key => $rd) {
                if (strpos($rd, ' ') !== false) {
                    $row3Data[$key] = str_replace(" ", "_", $rd);
                }
            }

            // Create dynamic table fields
            $createTableFieldArray = ScpGigaDynamicTableCreateHelper::createDynamic($tableName, $combineRows, $row3Data);
            unset($createTableFieldArray[0]);

            // Insert actual rows (skip empty rows)
            if (is_array($createTableFieldArray)) {
                foreach ($rows as $key => $d) {
                    if ($key > 2 && !empty(array_filter($d))) {
                        $insertData = [];
                        for ($column = 0; $column < count($createTableFieldArray); $column++) {
                            $insertData[$createTableFieldArray[$column + 1]['name']] = $d[$column] ?? 0;
                        }
                        DB::table($tableName)->insert($insertData);
                    }
                }
            }
        }

        Session::flash('message', "Success! Your file has been imported");
        return redirect()->back();
    }

    //3
    public function masterPriceImport(Request $request) {
        return view('admin.scvp_import.master_price_import');
    }
    
    //4
    public function masterPriceImportUpload(Request $request) {
        $file = $request->file_import;
        if (!empty($file)) {
            // $path = '/app/public/';
            $path = storage_path('app/public/');

            // if (!File::exists($path)) {
            //     $this->make_directory(storage_path() . '/' . $path);
            // }
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            // $filePath = storage_path() . $path . "/";
            // $file_excel = $this->uploadFile($file, $filePath);
            // Move uploaded file
            $file->move($path, $file->getClientOriginalName());
            $filePath = $path . '/' . $file->getClientOriginalName();

            $data = new \SpreadsheetReader($filePath);
            ScvpMasterMotorPrice::truncate();
            foreach ($data as $key => $d) {
                if ($key > 0) {
                    $scpMasterMotorPrice = new ScvpMasterMotorPrice();
                    $scpMasterMotorPrice->brand = $d[0];
                    $scpMasterMotorPrice->power = $d[1];
                    $scpMasterMotorPrice->motor_article_number = $d[2];
                    $scpMasterMotorPrice->wilo_article_number = $d[3];
                    $scpMasterMotorPrice->motor_height = $d[4];
                    $scpMasterMotorPrice->frame_size = $d[5];
                    $scpMasterMotorPrice->no_of_pole = $d[6];
                    $scpMasterMotorPrice->no_of_phase = $d[7];
                    $scpMasterMotorPrice->voltage = $d[8];
                    $scpMasterMotorPrice->frequency = $d[9];
                    $scpMasterMotorPrice->efficiency = $d[10];
                    $scpMasterMotorPrice->price = $d[11];
                    $scpMasterMotorPrice->insulate_bearing = $d[12];
                    $scpMasterMotorPrice->forwinding = $d[13];
                    $scpMasterMotorPrice->forbearing = $d[14];
                    $scpMasterMotorPrice->space_heater = $d[15];
                    $scpMasterMotorPrice->save();
                }
            }
        }
        Session::flash('message', "Success! Your file has been imported ");
        return redirect()->back();
    }

    //5
    public function costPaintPackImport(Request $request) {
        return view('admin.scvp_import.costpaint_price_import');
    }

    //6
    public function costPaintPackImportUpload(Request $request) {
        $file = $request->file_import;
        if (!empty($file)) {
            // $path = '/app/public/';
            $path = storage_path('app/public/');

            // if (!File::exists($path)) {
            //     $this->make_directory(storage_path() . '/' . $path);
            // }

            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            // $filePath = storage_path() . $path . "/";
            // $file_excel = $this->uploadFile($file, $filePath);
            $file->move($path, $file->getClientOriginalName());
            $filePath = $path . '/' . $file->getClientOriginalName();
            ScvpAssemblyCostPcPk::truncate();
            // $data = new \SpreadsheetReader($filePath . $file_excel);
            $data = new \SpreadsheetReader($filePath);
            foreach ($data as $key => $d) {
                if ($key > 1) {

                    $scpAssemblyCostPcPk = new ScvpAssemblyCostPcPk();
                    $scpAssemblyCostPcPk->power = $d[0];
                    $scpAssemblyCostPcPk->assembly_charge = $d[1];
                    $scpAssemblyCostPcPk->painting_charge = $d[2];
                    $scpAssemblyCostPcPk->packing_charge = $d[3];
                    $scpAssemblyCostPcPk->labour_hour = $d[4];
                    $scpAssemblyCostPcPk->shipping = $d[5];
                    $scpAssemblyCostPcPk->save();
                }
            }
        }

        Session::flash('message', "Success! Your file has been imported ");
        return redirect()->back();
    }
}
