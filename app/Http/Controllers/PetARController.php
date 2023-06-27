<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pet;
use App\Models\Pet_AR;
use App\Traits\FilesTrait;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetARController extends Controller
{
    use ValidationTrait;
    use FilesTrait;

    public function ListAllPetsCasesAR()
    {
        $cases = Pet_AR::all();
        if($cases->count()>0)
        {
            return $this->returnData('emergencyCases', $cases, 'Pets cases are found');
        } else
        {
            return $this->returnError('001', 'No Pets cases found');
        }
    }

    public function deletePetsCaseAR(Request $request)
    {
        $case = Pet_AR::find($request->id);
        if(!$case)
        {
            return $this->returnError('001', 'Pets case is not found');
        }
        else
        {
            $englishCase = $case->pet;

            if(!$englishCase)
                return $this->returnError('001', 'English pets case is not found');

            $englishCase->delete();
            Pet_AR::destroy($case->id);
            return $this->returnSuccessMessage('S000', 'Pets case deleted successfully');
        }
    }

    public function getPetsCaseByNameAR(Request $request)
    {
        $caseName = $request->petsCase_name;


        $exist = Pet_AR::where('caseName', $caseName)->first();
        if($exist)
        {
            $arr = array($exist);
            return $this->returnData('emergencyCases', $arr, 'Pets case is found');
        } else
        {
            return $this->returnError('001', 'Pets case is not found');
        }
    }

    public function getPetsCaseByIDAR(Request $request)
    {
        $case = Pet_AR::find($request->id);
        if($case)
        {
            $arr = array($case);
            return $this->returnData('emergencyCases', $arr, 'Pets case is found');
        } else
        {
            return $this->returnError('001', 'Pets case is not found');
        }
    }

    public function addPetsCaseAR(Request $request)
    {
        $rules = [
            'petsCase_name' => 'required|unique:pet__a_r_s,caseName|regex:/\p{Arabic}/u',
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

        $petsCase_name = $request->petsCase_name;
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

        $englishPetsCase = Pet::where('case_id', $caseID)->first();

        if($englishPetsCase)
        {
            $check = Pet_AR::where('pet_id', $englishPetsCase->id)->first();
            if($check)
                return $this->returnError('505', 'Pet ID is assigned for another case, Can\'t be assigned for more than one case.');
            $pets_id = $englishPetsCase->id;
        }
        else
        {
            return $this->returnError('404', 'Pets ID can not be assigned properly, Please check if the english pets case which is equivalent to the new arabic case is exist');
        }

        $imageName = $this->uploadImage($request);

        $pet =  Pet_AR::create([
            'caseName' => $petsCase_name,
            'description' => $request->description,
            'category_id' => $category_id,
            'category' => 'Pets',
            'caseImg' => $imageName,
            'caseVideo' => '',
            'solution' => $request->solution,
            'pet_id' => $pets_id
        ]);
        return $this->returnSuccessMessage('S000', 'Pets case is added successfully');

    }

    public function updatePetsCaseAR(Request $request)
    {
        $rules = [
            'petsCase_name' => 'required|regex:/\p{Arabic}/u',
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

        $petsCase_name = $request->petsCase_name;
        $caseID = strtoupper($request->case_id);

        $englishPetsCase = Pet::where('case_id', $caseID)->first();

        $case = Pet_AR::find($request->id);

        if(!$case)
            return $this->returnError('002', 'Pets case is not found');
        else
        {
            if($englishPetsCase)
            {
                $check = Pet_AR::where('pet_id', $englishPetsCase->id)->first();
                if($check)
                {
                    if(!($check->id == $request->id))
                    {
                        return $this->returnError('505', 'Pet ID is assigned for another case, Can\'t be assigned for more than one case.');
                    }
                }
                $pets_id = $englishPetsCase->id;
            }
            else
            {
                return $this->returnError('404', 'Pets ID can not be assigned properly, Please check if the english pets case which is equivalent to the new arabic case is exist');
            }

            $exist = Pet_AR::where('caseName', $petsCase_name)->first();

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
                        'pet_id' => $pets_id
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
                    'pet_id' => $pets_id
                ]);
                return $this->returnSuccessMessage('S000', 'Pets case updated successfully');
            }
        }
    }
}
