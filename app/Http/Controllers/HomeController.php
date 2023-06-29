<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Home;
use App\Traits\FilesTrait;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    use ValidationTrait;
    use FilesTrait;

    public function listAllHomeCases()
    {
        $cases = Home::all();
        if($cases->count()>0)
        {
            return $this->returnData('emergencyCases', $cases, 'Home cases are found');
        } else
        {
            return $this->returnError('001', 'No Home cases found');
        }
    }

    public function getHomeCaseByID(Request $request)
    {
        $case = Home::find($request->id);
        if($case)
        {
            $arr = array($case);
            return $this->returnData('emergencyCases', $arr, 'Home case is found');
        } else
        {
            return $this->returnError('001', 'Home case is not found');
        }
    }

    public function getHomeCaseByName(Request $request)
    {
//        $case = $request->homeCase_name;
//        $case_lowercase = strtolower($case);
//        $caseName = ucwords($case_lowercase);
//        $exist = Home::where('caseName', $caseName)->first();
//        if($exist)
//        {
//            $arr = array($exist);
//            return $this->returnData('emergencyCases', $arr, 'Home case is found');
//        } else
//        {
//            return $this->returnError('001', 'Home case is not found');
//        }

        $case = $request->homeCase_name;
        $case_lowercase = strtolower($case);
        $caseName = ucwords($case_lowercase);
        $englishHomeCase = Home::where('caseName', $caseName)->first();
        if($englishHomeCase)
        {
            $lang = strtoupper($request->lang);
            if($lang == 'EN')
            {
                return $englishHomeCase;
            }
            elseif ($lang == 'AR')
            {
                return $englishHomeCase->home_ar;
            }
        }
        else
            return $this->returnError('001', 'Home case is not found');

    }

    public function deleteHomeCase(Request $request)
    {
        $case = Home::find($request->id);

        if(!$case)
        {
            return $this->returnError('001', 'Home case is not found');
        }
        else
        {
            $arabicCase = $case->home_ar;

            if(!$arabicCase)
                return $this->returnError('001', 'Arabic home case is not found');

            $arabicCase->delete();
            Home::destroy($case->id);
            return $this->returnSuccessMessage( 'S000','Home case deleted successfully');
        }
    }

    public function addHomeCase(Request $request)
    {
        $rules = [
            'homeCase_name' => 'required|unique:homes,caseName|regex:/^[a-zA-Z\s]+$/',
            'description' => 'required',
            'image_url' => 'required|image|max:2048',
            'solution' => 'required',
            'case_id' => 'required|unique:homes,case_id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $homeCase_name = $request->homeCase_name;
        $homeCase_name = strtolower($homeCase_name);
        $homeCase_name = ucwords($homeCase_name);

        $caseID = strtoupper($request->case_id);

        $exist = Category::where('category_name', 'Home')->first();

        if($exist)
        {
            $category_id = $exist->id;
        }
        else
        {
            return $this->returnError('003', 'Category ID can not be assigned properly, Please check if the category of the new case is exist');
        }

        $exist = Home::where('caseName', $homeCase_name)->first();

        if($exist)
        {
            return $this->returnError('002', 'Home case already exists');
        } else
        {
            $check = Home::where('case_id', $caseID)->first();
            if($check)
                return $this->returnError('505', 'You can\'t assign this case id, It\'s assigned to another case');

            $imageName = $this->uploadImage($request);

            $home =  Home::create([
                'caseName' => $homeCase_name,
                'description' => $request->description,
                'category_id' => $category_id,
                'caseImg' => $imageName,
                'caseVideo' => '',
                'category' => 'Home',
                'solution' => $request->solution,
                'case_id' => $caseID
            ]);

            return $this->returnSuccessMessage('S000', 'Home case is added successfully');
        }

    }

    public function updateHomeCase(Request $request)
    {
        $rules = [
            'homeCase_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'description' => 'required',
            'image_url' => 'required|image|max:2048',
            'solution' => 'required',
            'case_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $homeCase_name = $request->homeCase_name;
        $homeCase_name = strtolower($homeCase_name);
        $homeCase_name = ucwords($homeCase_name);

        $caseID = strtoupper($request->case_id);

        $check = Home::where('case_id', $caseID)->first();
        if($check)
        {
            if(!($check->id == $request->id))
            {
                return $this->returnError('505', 'You can\'t assign this case id, It\'s assigned to another case');
            }
        }

        $case = Home::find($request->id);

        if(!$case)
            return $this->returnError('002', 'Home case is not found');
        else
        {
            $exist = Home::where('caseName', $homeCase_name)->first();

            if($exist)
            {
                if($exist->id == $request->id)
                {
                    $imageName = $this->uploadImage($request);

                    $case->update([
                        'caseName' => $homeCase_name,
                        'description' => $request->description,
                        'caseImg' => $imageName,
                        'caseVideo' => '',
                        'solution' => $request->solution,
                        'case_id' => $caseID
                    ]);
                    return $this->returnSuccessMessage('S000', 'Home case updated successfully');
                }
                else
                {
                    return $this->returnError('001', 'Home case with the same name already exists');
                }

            }
            else
            {
                $imageName = $this->uploadImage($request);

                $case->update([
                    'caseName' => $homeCase_name,
                    'description' => $request->description,
                    'caseImg' => $imageName,
                    'caseVideo' => '',
                    'solution' => $request->solution,
                    'case_id' => $caseID
                ]);
                return $this->returnSuccessMessage('S000', 'Home case updated successfully');
            }
        }
    }

}
