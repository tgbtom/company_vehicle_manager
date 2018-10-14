<?php
class Validate {
    public static function validateEmployee($first_name, $last_name)
    {
        $validatePattern = '/[a-zA-Z]{' . strlen($first_name) . '}/';
        $validatePattern2 = '/[a-zA-Z]{' . strlen($last_name) . '}/';
        $valid_first = preg_match($validatePattern, $first_name);
        $valid_last = preg_match($validatePattern2, $last_name);
        return $valid_first + $valid_last;
        
    }
    public static function validateVehicle($vehicle_num, $make, $model, $year, $colour, $plate, $odometer)
    {
        $error = [];
                
        $vehicle_num = (string)$vehicle_num;
        $validatePattern = '/[0-9]{' . strlen($vehicle_num) . '}/';
        if (!preg_match($validatePattern, $vehicle_num))
        {
            $error[] = "Vehicle # must be only numbers.";
        }
        
        $validatePattern = '/[a-zA-Z]{' . strlen($make) . '}/';
        if (!preg_match($validatePattern, $make))
        {
            $error[] = "Vehicle Make must be only letters.";
        }
        
        $validatePattern = '/[a-zA-Z]{' . strlen($model) . '}/';
        if (!preg_match($validatePattern, $model))
        {
            $error[] = "Vehicle Model must be only be letters and numbers.";
        }
        
        $validatePattern = '/^\d{4}$/';
        if (!preg_match($validatePattern, $year))
        {
            $error[] = "Vehicle year must only be 4 digits.";
        }
                
        $validatePattern = '/[a-zA-Z]{' . strlen($colour) . '}/';
        if (!preg_match($validatePattern, $colour))
        {
            $error[] = "Vehicle colour must only consist of letters.";
        }
        
        $validatePattern = '/[a-zA-Z0-9\s]{' . strlen($plate) . '}/';
        if (!preg_match($validatePattern, $plate))
        {
            $error[] = "Vehicle plate must only consist of letters and numbers.";
        }
                
        $validatePattern = '/\d{' . strlen($odometer) . '}/';
        if (!preg_match($validatePattern, $odometer))
        {
            $error[] = "Vehicle odometer must only consist of numbers.";
        }
        
        return $error;
    }
}
