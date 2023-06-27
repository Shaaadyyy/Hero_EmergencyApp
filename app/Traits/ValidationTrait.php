<?php
namespace App\Traits;

trait ValidationTrait
{
    public function returnError($errNum = "00", $msg = "Error!!")
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnSuccessMessage($errNum = "S000", $msg = "")
    {
        return [
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ]);
    }

    public function returnValidationError($validator,$code = "E001")
    {
        return $this->returnError($msg=$validator->errors()->first(),$errNum=$code);
    }

    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "category_name")
            return 'E002';

        else if ($input == "description")
            return 'E003';

        else if ($input == "medicalCase_name")
            return 'E004';

        else if ($input == "petsCase_name")
            return 'E005';

        else if ($input == "homeCase_name")
            return 'E006';

        else if ($input == "image_url")
            return 'E007';

        else if ($input == "video_url")
            return 'E008';

        else if ($input == "solution")
            return 'E009';

        else if ($input == "password")
            return 'E010';

        else if ($input == "email")
            return 'E011';

        else if ($input == "number")
            return 'E012';
        else if ($input == "case_id")
            return 'E013';
        else
            return "";
    }




}
