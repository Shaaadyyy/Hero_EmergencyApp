<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Home_AR;
use App\Models\Medical;
use App\Models\Medical_AR;
use App\Models\Pet;
use App\Models\Pet_AR;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;

class EmergencyCaseController extends Controller
{
    use ValidationTrait;

    public function getCaseByName(Request $request)
    {
        $arr = array();
        $case = $request->CaseName;
        $case_lowercase = strtolower($case);
        $caseName = ucwords($case_lowercase);
        $exist = Home::where('caseName', $caseName)->first();
        if($exist)
        {
            array_push($arr, $exist);
        }
        $exist2 = Pet::where('caseName', $caseName)->first();
        if($exist2)
        {
            array_push($arr, $exist2);
        }
        $exist3 = Medical::where('caseName', $caseName)->first();
        if($exist3)
        {
            array_push($arr, $exist3);
        }

        return $this->returnData('emergencyCases', $arr, 'Emergency cases are found');
    }


    public function getCaseByNameAR(Request $request)
    {
        $arr = array();
        $caseName = $request->CaseName;

        $exist = Home_AR::where('caseName', $caseName)->first();
        if($exist)
        {
            array_push($arr, $exist);
        }
        $exist2 = Pet_AR::where('caseName', $caseName)->first();
        if($exist2)
        {
            array_push($arr, $exist2);
        }
        $exist3 = Medical_AR::where('caseName', $caseName)->first();
        if($exist3)
        {
            array_push($arr, $exist3);
        }

        return $this->returnData('emergencyCases', $arr, 'Emergency cases are found');
    }

}
