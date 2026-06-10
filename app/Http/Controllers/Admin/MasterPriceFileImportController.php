<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Product;
use App\Range;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Helpers\DynamicTableCreateHelper;
use DB;
use Session;

class MasterPriceFileImportController extends Controller {

    public function index() {
        abort_unless(\Gate::allows('product_access'), 403);

        $products = Product::all();

        return view('admin.products.index', compact('products'));
    }

    public function create() {
        abort_unless(\Gate::allows('product_create'), 403);

        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request) {
        abort_unless(\Gate::allows('product_create'), 403);

        $product = Product::create($request->all());

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product) {
        abort_unless(\Gate::allows('product_edit'), 403);

        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product) {
        abort_unless(\Gate::allows('product_edit'), 403);

        $product->update($request->all());

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product) {
        abort_unless(\Gate::allows('product_show'), 403);

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product) {
        abort_unless(\Gate::allows('product_delete'), 403);

        $product->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request) {
        Product::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function import(Request $request) {
//        dd('dd');
        $ranges = Range::all();
        return view('admin.master-file-import.import', compact('ranges'));
    }

    public function upload(Request $request) {
        $file = $request->file_import;
        if (!empty($file)) {
            $path = '/app/public/';

            if (!File::exists($path)) {
                $this->make_directory(storage_path() . '/' . $path);
            }

            $filePath = storage_path() . $path . "/";
            $file_excel = $this->uploadFile($file, $filePath);

            $data = new \SpreadsheetReader($filePath . $file_excel);

            
            $tableName = 'master_price_sheet_electrical_components';
//            dd($tableName);
            foreach ($data as $key => $d) {

                if ($key < 1) { //Only first row 
                    $createTableField = DynamicTableCreateHelper::createOnlyMasterSheetDynamic($tableName, $d); // $d equal to first row  
//                    unset($createTableField[0]); //Remove Id column
                    unset($d[0]); // Remove S.No Rows
                } else if ($key > 0 && is_array($createTableField)) {
//                 echo "<pre>" . print_r($createTableField, 1);
                    $insertData = [];
                    for ($column = 1; $column < count($createTableField); $column ++) {


                        $insertData[$createTableField[$column]['name']] = $d[$column]; // $d[$column] = $row  
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
