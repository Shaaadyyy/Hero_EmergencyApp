<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pet;
use App\Traits\FilesTrait;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetController extends Controller
{
    use ValidationTrait;
    use FilesTrait;

    public function ListAllPetsCases()
    {
        $cases = Pet::all();
        if($cases->count()>0)
        {
            return $this->returnData('emergencyCases', $cases, 'Pets cases are found');
        } else
        {
            return $this->returnError('001', 'No Pets cases found');
        }
    }

    public function deletePetsCase(Request $request)
    {
        $case = Pet::find($request->id);
        if(!$case)
        {
            return $this->returnError('001', 'Pets case is not found');
        }
        else
        {
            $arabicCase = $case->pet_ar;

            if(!$arabicCase)
                return $this->returnError('001', 'Arabic pets case is not found');

            $arabicCase->delete();
            Pet::destroy($case->id);
            return $this->returnSuccessMessage('S000', 'Pets case deleted successfully');
        }
    }

    public function getPetsCaseByName(Request $request)
    {
        $case = $request->petsCase_name;
        $case_lowercase = strtolower($case);
        $caseName = ucwords($case_lowercase);

        $exist = Pet::where('caseName', $caseName)->first();
        if($exist)
        {
            $arr = array($exist);
            return $this->returnData('emergencyCases', $arr, 'Pets case is found');
        } else
        {
            return $this->returnError('001', 'Pets case is not found');
        }
    }

    public function getPetsCaseByID(Request $request)
    {
        $case = Pet::find($request->id);
        if($case)
        {
            $arr = array($case);
            return $this->returnData('emergencyCases', $arr, 'Pets case is found');
        } else
        {
            return $this->returnError('001', 'Pets case is not found');
        }
    }


    public function addPetsCase(Request $request)
    {
        $rules = [
            'petsCase_name' => 'required|unique:pets,caseName|regex:/^[a-zA-Z\s]+$/',
            'description' => 'required',
            'image_url' => 'required|image|max:2048',
            'solution' => 'required',
            'case_id' => 'required|unique:pets,case_id'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $petsCase_name = $request->petsCase_name;
        $petsCase_name = strtolower($petsCase_name);
        $petsCase_name = ucwords($petsCase_name);

        $caseID = strtoupper($request->case_id);

        $exist = Category::where('category_name', 'Pets')->first();

        if($exist)
        {
            $category_id = $exist->id;
        }
        else
        {
            return $this->returnError('003', 'Category ID can not be assigned properly, Please check if the category of the new case is exist');
        }

        $imageName = $this->uploadImage($request);

        $pet =  Pet::create([
            'caseName' => $petsCase_name,
            'description' => $request->description,
            'category_id' => $category_id,
            'category' => 'Pets',
            'caseImg' => $imageName,
            'caseVideo' => '',
            'solution' => $request->solution,
            'case_id' => $caseID
        ]);
        return $this->returnSuccessMessage('S000', 'Pets case is added successfully');

    }

    public function updatePetsCase(Request $request)
    {
        $rules = [
            'petsCase_name' => 'required|regex:/^[a-zA-Z\s]+$/',
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

        $petsCase_name = $request->petsCase_name;
        $petsCase_name = strtolower($petsCase_name);
        $petsCase_name = ucwords($petsCase_name);

        $caseID = strtoupper($request->case_id);

        $check = Pet::where('case_id', $caseID)->first();
        if($check)
        {
            if(!($check->id == $request->id))
            {
                return $this->returnError('505', 'You can\'t assign this case id, It\'s assigned to another case');
            }
        }

        $case = Pet::find($request->id);

        if(!$case)
            return $this->returnError('002', 'Pets case is not found');
        else
        {
            $exist = Pet::where('caseName', $petsCase_name)->first();

            if($exist)
            {
                if($exist->id == $request->id)
                {
                    $imageName = $this->uploadImage($request);

                    $case->update([
                        'caseName' => $petsCase_name,
                        'description' => $request->description,
                        'caseImg' => $imageName,
                        'caseVideo' => '',
                        'solution' => $request->solution,
                        'case_id' => $caseID
                    ]);
                    return $this->returnSuccessMessage('S000', 'Pets case updated successfully');
                }
                else
                {
                    return $this->returnError('001', 'Pets case with the same name already exists');
                }

            }
            else
            {
                $imageName = $this->uploadImage($request);

                $case->update([
                    'caseName' => $petsCase_name,
                    'description' => $request->description,
                    'caseImg' => $imageName,
                    'caseVideo' => '',
                    'solution' => $request->solution,
                    'case_id' => $caseID
                ]);
                return $this->returnSuccessMessage('S000', 'Pets case updated successfully');
            }
        }
    }

}
