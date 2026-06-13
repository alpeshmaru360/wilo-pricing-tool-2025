<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Continent;
use App\Country;

class ContinentController extends Controller
{
    //
    public function getCountries($id)
    {
        $country = Country::where('continent_id',$id)->get();
        return $country;

    }
}
