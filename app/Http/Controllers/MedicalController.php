<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medical;
use App\Traits\FilesTrait;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalController extends Controller
{
    use ValidationTrait;
    use FilesTrait;

    public function listAllMedicalCases()
    {
        $cases = Medical::all();
        if($cases->count()>0)
        {
            return $this->returnData('emergencyCases', $cases, 'Medical cases are found');
        } else
        {
            return $this->returnError('001', 'No medical cases found');
        }
    }

    public function getMedicalCaseByID(Request $request)
    {
        $case = Medical::find($request->id);
        if($case)
        {
            $arr = array($case);
            return $this->returnData('emergencyCases', $arr, 'Medical case is found');
        } else
        {
            return $this->returnError('001', 'Medical case is not found');
        }
    }

    public function getMedicalCaseByName(Request $request)
    {
        $case = $request->medicalCase_name;
        $case_lowercase = strtolower($case);
        $caseName = ucwords($case_lowercase);

        $exist = Medical::where('caseName', $caseName)->first();
        if($exist)
        {
            $arr = array($exist);
            return $this->returnData('emergencyCases', $arr, 'Medical case is found');
        } else
        {
            return $this->returnError('001', 'Medical case is not found');
        }
    }

    public function deleteMedicalCase(Request $request)
    {
        $case = Medical::find($request->id);
        if(!$case)
        {
            return $this->returnError('001', 'Medical case is not found');
        } else
        {
            $arabicCase = $case->medical_ar;

            if(!$arabicCase)
                return $this->returnError('001', 'Arabic medical case is not found');

            $arabicCase->delete();
            Medical::destroy($case->id);
            return $this->returnSuccessMessage( 'S000','medical case deleted successfully');
        }
    }

    public function addMedicalCase(Request $request)
    {
        $rules = [
            'medicalCase_name' => 'required|unique:medicals,caseName|regex:/^[a-zA-Z\s]+$/' ,
            'description' => 'required',
            'image_url' => 'required|image|max:2048',
            'video_url' => 'required|mimetypes:video/mp4,video/mpeg,video/quicktime',
            'solution' => 'required',
            'case_id' => 'required|unique:medicals,case_id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $medicalCase_name = $request->medicalCase_name;
        $medicalCase_name = strtolower($medicalCase_name);
        $medicalCase_name = ucwords($medicalCase_name);

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

        $exist = Medical::where('caseName', $medicalCase_name)->first();
        if($exist)
        {
            return $this->returnError('002', 'Medical case already exists');
        }
        else
        {

            $check = Medical::where('case_id', $caseID)->first();
            if($check)
                return $this->returnError('505', 'You can\'t assign this case id, It\'s assigned to another case');

            $imageName = $this->uploadImage($request);

            $videoName = $this->uploadVideo($request);

            $medical =  Medical::create([
                'caseName' => $medicalCase_name,
                'description' => $request->description,
                'category_id' => $category_id,
                'category' => 'Medical',
                'caseImg' => $imageName,
                'caseVideo' => $videoName,
                'solution' => $request->solution,
                'case_id' => $caseID
            ]);

            return $this->returnSuccessMessage('S000', 'Medical case is added successfully');
        }


    }

    public function updateMedicalCase(Request $request)
    {
        $rules = [
            'medicalCase_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'description' => 'required',
            'image_url' => 'required|image|max:2048',
            'video_url' => 'required|mimetypes:video/mp4,video/mpeg,video/quicktime',
            'solution' => 'required',
            'case_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $medicalCase_name = $request->medicalCase_name;
        $medicalCase_name = strtolower($medicalCase_name);
        $medicalCase_name = ucwords($medicalCase_name);

        $caseID = strtoupper($request->case_id);

        $check = Medical::where('case_id', $caseID)->first();
        if($check)
        {
            if(!($check->id == $request->id))
            {
                return $this->returnError('505', 'You can\'t assign this case id, It\'s assigned to another case');
            }
        }

        $case = Medical::find($request->id);

        if(!$case)
            return $this->returnError('002', 'Medical case is not found');
        else
        {
            $exist = Medical::where('caseName', $medicalCase_name)->first();

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
                        'case_id' => $caseID
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
                    'case_id' => $caseID
                ]);
                return $this->returnSuccessMessage('S000', 'Medical case updated successfully');
            }
        }
    }

}
