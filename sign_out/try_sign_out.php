<?php
require_once("../model/cvm_database.php");
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$vehicle_num = filter_input(INPUT_POST, 'vehicle_num');
$employee = EmployeeDB::doesEmployeeExist($first_name, $last_name);

if ($employee != NULL)
{
    //Employee does exist
    $employee_num = $employee->getEmployeeNum();
    $isVehicleSignedOut = EmployeeDB::isVehicleSignedOut($vehicle_num);
    if ($isVehicleSignedOut == NULL)
    {
      EmployeeDB::signOutCar($vehicle_num, $employee_num);
      header("Location: ../?e=Vehicle Was successfully Signed Out!" );
    }
    else
    {
       header("Location: ../?e=Vehicle Has already been signed out."); 
    }
    
}
else
{
    //Employee does not exist
    header("Location: ../?e=Name did not match an employee in the database!");
}


