<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medical;
use App\Models\Medical_AR;
use App\Traits\FilesTrait;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalARController extends Controller
{
    use ValidationTrait;
    use FilesTrait;

    public function listAllMedicalCasesAR()
    {
        $cases = Medical_AR::all();
        if($cases->count()>0)
        {
            return $this->returnData('emergencyCases', $cases, 'Medical cases are found');
        } else
        {
            return $this->returnError('001', 'No medical cases found');
        }
    }

    public function getMedicalCaseByIDAR(Request $request)
    {
        $case = Medical_AR::find($request->id);
        if($case)
        {
            $arr = array($case);
            return $this->returnData('emergencyCases', $arr, 'Medical case is found');
        } else
        {
            return $this->returnError('001', 'Medical case is not found');
        }
    }

    public function getMedicalCaseByNameAR(Request $request)
    {
        $caseName = $request->medicalCase_name;

        $exist = Medical_AR::where('caseName', $caseName)->first();
        if($exist)
        {
            $arr = array($exist);
            return $this->returnData('emergencyCases', $arr, 'Medical case is found');
        } else
        {
            return $this->returnError('001', 'Medical case is not found');
        }
    }

    public function deleteMedicalCaseAR(Request $request)
    {
        $case = Medical_AR::find($request->id);
        if(!$case)
        {
            return $this->returnError('001', 'Medical case is not found');
        } else
        {
            $englishCase = $case->medical;

            if(!$englishCase)
                return $this->returnError('001', 'English medical case is not found');

            $englishCase->delete();
            Medical_AR::destroy($case->id);
            return $this->returnSuccessMessage( 'S000','medical case deleted successfully');
        }
    }

    public function addMedicalCaseAR(Request $request)
    {
        $rules = [
            'medicalCase_name' => 'required|unique:medical__a_r_s,caseName|regex:/\p{Arabic}/u' ,
            'description' => 'required|regex:/\p{Arabic}/u',
            'image_url' => 'required|image|max:2048',
            'video_url' => 'required|mimetypes:video/mp4,video/mpeg,video/quicktime',
            'solution' => 'required|regex:/\p{Arabic}/u',
            'case_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $medicalCase_name = $request->medicalCase_name;
        $caseID = strtoupper($request->case_id);

        $exist = Category::where('category_name', 'Medical')->first();

        if($exist)
        {
            $category_id = $exist->id;
        }
        else
        {
            return $this->returnError('003', 'Category ID can not be assigned properly, Please check if the category of the new case is exist');
        }

        $englishMedicalCase = Medical::where('case_id', $caseID)->first();

        if($englishMedicalCase)
        {
            $check = Medical_AR::where('medical_id', $englishMedicalCase->id)->first();
            if($check)
                return $this->returnError('505', 'Medical ID is assigned for another case, Can\'t be assigned for more than one case.');
            $medical_id = $englishMedicalCase->id;
        }
        else
        {
            return $this->returnError('404', 'Medical ID can not be assigned properly, Please check if the english medical case which is equivalent to the new arabic case is exist');
        }

        $exist = Medical_AR::where('caseName', $medicalCase_name)->first();
        if($exist)
        {
            return $this->returnError('002', 'Medical case already exists');
        }
        else
        {
            $imageName = $this->uploadImage($request);

            $videoName = $this->uploadVideo($request);

            $medical =  Medical_AR::create([
                'caseName' => $medicalCase_name,
                'description' => $request->description,
                'category_id' => $category_id,
                'category' => 'Medical',
                'caseImg' => $imageName,
                'caseVideo' => $videoName,
                'solution' => $request->solution,
                'medical_id' => $medical_id
            ]);

            return $this->returnSuccessMessage('S000', 'Medical case is added successfully');
        }

    }

    public function updateMedicalCaseAR(Request $request)
    {
        $rules = [
            'medicalCase_name' => 'required|regex:/\p{Arabic}/u',
            'description' => 'required|regex:/\p{Arabic}/u',
            'image_url' => 'required|image|max:2048',
            'video_url' => 'required|mimetypes:video/mp4,video/mpeg,video/quicktime',
            'solution' => 'required|regex:/\p{Arabic}/u',
            'case_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $medicalCase_name = $request->medicalCase_name;
        $caseID = strtoupper($request->case_id);

        $englishMedicalCase = Medical::where('case_id', $caseID)->first();


        $case = Medical_AR::find($request->id);

        if(!$case)
            return $this->returnError('002', 'Medical case is not found');
        else
        {
            if($englishMedicalCase)
            {
                $check = Medical_AR::where('medical_id', $englishMedicalCase->id)->first();
                if($check)
                {
                    if(!($check->id == $request->id))
                    {
                        return $this->returnError('505', 'Medical ID is assigned for another case, Can\'t be assigned for more than one case.');
                    }
                }
                $medical_id = $englishMedicalCase->id;
            }
            else
            {
                return $this->returnError('404', 'Home ID can not be assigned properly, Please check if the english home case which is equivalent to the new arabic case is exist');
            }

            $exist = Medical_AR::where('caseName', $medicalCase_name)->first();

            if($exist)
            {
                if($exist->id == $request->id)
                {
                    $imageName = $this->uploadImage($request);

                    $videoName = $this->uploadVideo($request);


                    $case->update([
                        'caseName' => $medicalCase_name,
                        'description' => $request->description,
                        'caseImg' => $imageName,
                        'caseVideo' => $videoName,
                        'solution' => $request->solution,
                        'medical_id' => $medical_id
                    ]);
                    return $this->returnSuccessMessage('S000', 'Medical case updated successfully');
                }
                else
                {
                    return $this->returnError('001', 'Medical case with the same name already exists');
                }

            }
            else
            {
                $imageName = $this->uploadImage($request);

                $videoName = $this->uploadVideo($request);


                $case->update([
                    'caseName' => $medicalCase_name,
                    'description' => $request->description,
                    'caseImg' => $imageName,
                    'caseVideo' => $videoName,
                    'solution' => $request->solution,
                    'medical_id' => $medical_id
                ]);
                return $this->returnSuccessMessage('S000', 'Medical case updated successfully');
            }
        }
    }

}
