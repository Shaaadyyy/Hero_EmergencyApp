<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ValidationTrait;

    public function getAll()
    {
        $categories = Category::all();
        if($categories->count()>0)
        {
            return $this->returnData('Categories', $categories, 'Categories found');
        } else
        {
            return $this->returnError("001", 'No categories found');
        }
    }

    public function addCategory(Request $request)
    {
        $rules = [
            'category_name' => 'required|unique:categories,category_name'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $category_name = $request->category_name;
        $category_name = strtolower($category_name);
        $category_name = ucwords($category_name);

        $exist = Category::where('category_name', $category_name)->first();

        if($exist)
        {
            return $this->returnError('002', 'Category already exists');
        } else
        {
            Category::create([
                'category_name' => $category_name
            ]);
            return $this->returnSuccessMessage('S000', 'Category is added successfully');
        }

    }

    public function getCategoryByID(Request $request)
    {
        $category = Category::find($request->id);
        if($category)
        {
            return $this->returnData('Category', $category, 'Category is found');
        } else
        {
            return $this->returnError("001", 'Category is not found');
        }
    }

    public function getCategoryByName(Request $request)
    {
        $category_lowercase = strtolower($request->category_name);
        $categoryName = ucwords($category_lowercase);
        $category = Category::where('category_name', $categoryName)->first();

        if($category)
        {
            return $this->returnData('Category', $category, 'Category is found');
        } else
        {
            return $this->returnError("001", 'Category is not found');
        }
    }

    public function updateCategory(Request $request)
    {
        $rules = [
            'category_name' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $category_name = $request->category_name;
        $category_name = strtolower($category_name);
        $category_name = ucwords($category_name);

        $category = Category::find($request->id);

        if(!$category)
            return $this->returnError('002', 'Category is not found');
        else
        {
            $exist = Category::where('category_name', $category_name)->first();

            if($exist)
            {
                if($exist->id == $request->id)
                {
                    $category->update([
                        'category_name' => $category_name
                    ]);
                    return $this->returnSuccessMessage('S000', 'category updated successfully');
                }

                return $this->returnError('001', 'Category with the same name already exists');
            }
            else
            {
                $category->update([
                    'category_name' => $category_name
                ]);
                return $this->returnSuccessMessage('S000', 'category updated successfully');
            }
        }
    }

    public function deleteCategory(Request $request)
    {
        $category = Category::find($request->id);
        if(!$category) {
            return $this->returnError('001', 'category is not found');
        } else
        {
            if(strcasecmp($category->category_name, 'medical') == 0)
            {
                $englishCases = $category->medical;
                $arabicCases = $category->medical_ar;

                if(!$englishCases)
                    return $this->returnError('001', 'English medical cases are not found');
                if(!$arabicCases)
                    return $this->returnError('001', 'Arabic medical cases are not found');

                foreach ($category->medical as $medical)
                {
                    $medical->delete();
                }
                foreach ($category->medical_ar as $medicalAR)
                {
                    $medicalAR->delete();
                }
            }
            elseif (strcasecmp($category->category_name, 'home') == 0)
            {
                $englishCases = $category->home;
                $arabicCases = $category->home_ar;

                if(!$englishCases)
                    return $this->returnError('001', 'English home cases are not found');
                if(!$arabicCases)
                    return $this->returnError('001', 'Arabic home cases are not found');

                foreach ($category->home as $home)
                {
                    $home->delete();
                }
                foreach ($category->home_ar as $homeAR)
                {
                    $homeAR->delete();
                }
            }
            elseif (strcasecmp($category->category_name, 'pets') == 0)
            {
                $englishCases = $category->pet;
                $arabicCases = $category->pet_ar;

                if(!$englishCases)
                    return $this->returnError('001', 'English pets cases are not found');
                if(!$arabicCases)
                    return $this->returnError('001', 'Arabic pets cases are not found');

                foreach ($category->pet as $pets)
                {
                    $pets->delete();
                }
                foreach ($category->home_ar as $petsAR)
                {
                    $petsAR->delete();
                }
            }

            Category::destroy($category->id);
            return $this->returnSuccessMessage('S000', 'category deleted successfully');
        }
    }

}
