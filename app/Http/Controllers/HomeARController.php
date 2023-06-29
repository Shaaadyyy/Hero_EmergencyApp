<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Home;
use App\Models\Home_AR;
use App\Traits\FilesTrait;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeARController extends Controller
{
    use ValidationTrait;
    use FilesTrait;

    public function listAllHomeCasesAR()
    {
        $cases = Home_AR::all();
        if($cases->count()>0)
        {
            return $this->returnData('emergencyCases', $cases, 'Home cases are found');
        } else
        {
            return $this->returnError('001', 'No Home cases found');
        }
    }

    public function deleteHomeCaseAR(Request $request)
    {
        $case = Home_AR::find($request->id);
        if(!$case)
        {
            return $this->returnError('001', 'Home case is not found');
        }
        else
        {
            $englishCase = $case->home;

            if(!$englishCase)
                return $this->returnError('001', 'English home case is not found');

            $englishCase->delete();
            Home_AR::destroy($case->id);
            return $this->returnSuccessMessage( 'S000','Home case deleted successfully');
        }
    }

    public function getHomeCaseByNameAR(Request $request)
    {
        $caseName = $request->homeCase_name;

        $exist = Home_AR::where('caseName', $caseName)->first();
        if($exist)
        {
            $arr = array($exist);
            return $this->returnData('emergencyCases', $arr, 'Home case is found');
        } else
        {
            return $this->returnError('001', 'Home case is not found');
        }
    }

    public function getHomeCaseByIDAR(Request $request)
    {
        $case = Home_AR::find($request->id);
        if($case)
        {
            $arr = array($case);
            return $this->returnData('emergencyCases', $arr, 'Home case is found');
        } else
        {
            return $this->returnError('001', 'Home case is not found');
        }
    }

    public function addHomeCaseAR(Request $request)
    {
        $rules = [
            'homeCase_name' => 'required|unique:home__a_r_s,caseName|regex:/\p{Arabic}/u',
            'description' => 'required|regex:/\p{Arabic}/u',
            'image_url' => 'required|image|max:2048',
            'solution' => 'required|regex:/\p{Arabic}/u',
            'case_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $homeCase_name = $request->homeCase_name;
        $caseID = strtoupper($request->case_id);

        $exist = Category::where('category_name', 'Home')->first();

        if($exist)
        {
            $category_id = $exist->id;
        }
        else
        {
            return $this->returnError('404', 'Category ID can not be assigned properly, Please check if the category of the new case is exist');
        }

        $englishHomeCase = Home::where('case_id', $caseID)->first();

        if($englishHomeCase)
        {
            $check = Home_AR::where('home_id', $englishHomeCase->id)->first();
            if($check)
                return $this->returnError('505', 'Home ID is assigned for another case, Can\'t be assigned for more than one case.');
            $home_id = $englishHomeCase->id;
        }
        else
        {
            return $this->returnError('404', 'Home ID can not be assigned properly, Please check if the english home case which is equivalent to the new arabic case is exist');
        }

        $exist = Home_AR::where('caseName', $homeCase_name)->first();

        if($exist)
        {
            return $this->returnError('002', 'Home case already exists');
        } else
        {
            $imageName = $this->uploadImage($request);

            $home =  Home_AR::create([
                'caseName' => $homeCase_name,
                'description' => $request->description,
                'category_id' => $category_id,
                'caseImg' => $imageName,
                'caseVideo' => '',
                'category' => 'Home',
                'solution' => $request->solution,
                'home_id' => $home_id
            ]);

            return $this->returnSuccessMessage('S000', 'Home case is added successfully');
        }

    }

    public function updateHomeCaseAR(Request $request)
    {
        $rules = [
            'homeCase_name' => 'required|regex:/\p{Arabic}/u',
            'description' => 'required|regex:/\p{Arabic}/u',
            'image_url' => 'required|image|max:2048',
            'solution' => 'required|regex:/\p{Arabic}/u',
            'case_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $homeCase_name = $request->homeCase_name;
        $caseID = strtoupper($request->case_id);

        $englishHomeCase = Home::where('case_id', $caseID)->first();

        $case = Home_AR::find($request->id);

        if(!$case)
            return $this->returnError('002', 'Home case is not found');
        else
        {
            if($englishHomeCase)
            {
                $check = Home_AR::where('home_id', $englishHomeCase->id)->first();
                if($check)
                {
                    if(!($check->id == $request->id))
                    {
                        return $this->returnError('505', 'Home ID is assigned for another case, Can\'t be assigned for more than one case.');
                    }
                }
                $home_id = $englishHomeCase->id;
            }
            else
            {
                return $this->returnError('404', 'Home ID can not be assigned properly, Please check if the english home case which is equivalent to the new arabic case is exist');
            }

            $exist = Home_AR::where('caseName', $homeCase_name)->first();

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
                        'home_id' => $home_id
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
                    'home_id' => $home_id
                ]);
                return $this->returnSuccessMessage('S000', 'Home case updated successfully');
            }
        }
    }

}
