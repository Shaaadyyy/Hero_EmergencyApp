<?php

namespace App\Http\Controllers;

use App\Models\EmergencyNum;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmergencyNumController extends Controller
{
    use ValidationTrait;

    public function listAllEmergencyNums()
    {
        $emergencynums = EmergencyNum::all();
        if($emergencynums->count()>0)
        {
            return $this->returnData('Emergency Numbers', $emergencynums, 'Emergency Numbers found');
        } else
        {
            return $this->returnError("001", 'No Emergency Number found');
        }
    }

    public function addEmergencyNum(Request $request)
    {
        $rules = [
            'name' => 'required|unique:emergency_nums,name',
            'number' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $emerg_name = $request->name;
        $emerg_name = strtolower($emerg_name);
        $emerg_name = ucwords($emerg_name);

        EmergencyNum::create([
            'name' => $emerg_name,
            'number' => $request->number
        ]);
        return $this->returnSuccessMessage('S000', 'Emergency number added');
    }

    public function getEmergencyNumByID(Request $request)
    {
        $emergnum = EmergencyNum::find($request->id);
        if($emergnum)
        {
            return $this->returnData('Emergency number', $emergnum, 'Emergency number is found');
        } else
        {
            return $this->returnError('001', 'Emergency number is not found');
        }
    }

    public function getEmergencyNumByName(Request $request)
    {
        $emerg_lowercase = strtolower($request->name);
        $emerg_name = ucwords($emerg_lowercase);
        $emergnum = EmergencyNum::where('name', $emerg_name)->first();

        if($emergnum)
        {
            return $this->returnData('Emergency number', $emergnum, 'Emergency number is found');
        } else
        {
            return $this->returnError("001", 'Emergency number is not found');
        }
    }

    public function updateEmergencyNum(Request $request)
    {

        $rules = [
            'name' => 'required',
            'number' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $emerg_name = $request->name;
        $emerg_name = strtolower($emerg_name);
        $emerg_name = ucwords($emerg_name);

        $emergency = EmergencyNum::find($request->id);
        if(!$emergency)
        {
            return $this->returnError('002', 'Emergency number is not found');
        } else
        {
            $exist = EmergencyNum::where('name', $emerg_name)->first();
            if($exist)
            {
                if($exist->id == $request->id)
                {
                    $emergency->update([
                        'name' => $emerg_name,
                        'number' => $request->number
                    ]);
                    return $this->returnSuccessMessage('S000', 'Emergency number updated successfully');
                }

                return $this->returnError('001', 'Emergency number with the same name already exists');

            } else
            {
                $emergency->update([
                    'name' => $emerg_name,
                    'number' => $request->number
                ]);
                return $this->returnSuccessMessage('S000', 'Emergency number updated successfully');
            }
        }
    }

    public function deleteEmergencyNum(Request $request)
    {
        $emergnum = EmergencyNum::find($request->id);
        if(!$emergnum)
        {
            return $this->returnError('001', 'Emergency number is not found');
        } else
        {
            EmergencyNum::destroy($emergnum->id);
            return $this->returnSuccessMessage('S000', 'Emergency number deleted successfully');
        }
    }


}
