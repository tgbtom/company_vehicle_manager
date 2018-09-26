<?php
require_once("../model/cvm_database.php");
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$vehicle_num = filter_input(INPUT_POST, 'vehicle_num');
$new_odometer = filter_input(INPUT_POST, 'new_odometer');

$employee = EmployeeDB::doesEmployeeExist($first_name, $last_name);

if ($employee != NULL)
{
    //Employee does exist
    $employee_num = $employee->getEmployeeNum();
    $isVehicleSignedOut = EmployeeDB::isVehicleSignedOut($vehicle_num);
    if ($isVehicleSignedOut != NULL)
    {
      //car is signed out, return it.
        $logged_employee_num = $isVehicleSignedOut->getEmployeeNum();
        if ($employee_num == $logged_employee_num)
        {
            EmployeeDB::returnCar($vehicle_num, $employee_num, $new_odometer);
            header("Location: ../?e=Vehicle Was successfully Returned! The trip was logged in the database." ); 
        }
        else 
        {
            header("Location: ../?e=Vehicle Was Signed Out by A Different Employee!" );
        }
    }
    else
    {
       header("Location: ../?e=Vehicle was not signed out!."); 
    }
    
}
else
{
    //Employee does not exist
    header("Location: ../?e=Name did not match an employee in the database!");
}

