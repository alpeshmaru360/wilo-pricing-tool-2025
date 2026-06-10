<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Helpers\DynamicTableCreateHelper;
use DB;
use Session;

class AdderOptionalListImportController extends Controller {

   

    public function import(Request $request) {
        return view('admin.adder_optional_list_file_import.import');
    }

    public function upload(Request $request) {
    $file = $request->file_import;
    $adderName = $request->adder;
    $adderType = $request->adderType;
    
    $tableName = $adderName ."_" . $adderType;
        if (!empty($file)) {
            $path = '/app/public/';
            
            if (!File::exists($path)) {
                $this->make_directory(storage_path() . '/' . $path);
            }

            $filePath = storage_path() . $path . "/";
            $file_excel = $this->uploadFile($file, $filePath);

            $data = new \SpreadsheetReader($filePath . $file_excel);

            foreach ($data as $key => $d) {

                if ($key < 1) { //Only first row 
                    $createTableField = DynamicTableCreateHelper::createMasterSheetDynamic($tableName, $d); // $d equal to first row  
//                    unset($createTableField[0]); //Remove Id column
                    unset($d[0]); // Remove S.No Rows
//                    dd($createTableField);
                } else if ($key > 0 && is_array($createTableField)) {
//                 echo "<pre>" . print_r($createTableField, 1);
                    $insertData = [];
                    for ($column = 1; $column < count($createTableField); $column ++) {


                        $insertData[$createTableField[$column]['name']] = isset($d[$column]) ? $d[$column] : ''; // $d[$column] = $row  
                    }
                    DB::table($tableName)->insert(
                            array($insertData)
                    );
                }
            }
        }

        Session::flash('message', "Success! Your file has been imported ");
        return redirect()->back();

//        redirect()->back()
    }

    public function make_directory($path) {
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    }

    public function uploadFile($file, $path, $type = '') {
        $is_uploaded = false;

        if (!empty($file)) {

            $fileName = $file->getClientOriginalName();
            //$extension = \File::extension($file);
//             $fileName = rand(11111111, 99999999) . '.' . $extension;

            if ($file->move($path, $fileName)) {
                $is_uploaded = $fileName;
            }
        }
        return $is_uploaded;
    }

}
