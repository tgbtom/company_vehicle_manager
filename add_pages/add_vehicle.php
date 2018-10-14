<?php 
require_once '../model/cvm_form.php'; 
require_once '../model/cvm_database.php';
?>
<html>
    <head>
        <title>Add New Vehicle</title>
        <link rel="stylesheet" type="text/css" href="../css/mainDesign.css">
        <?php 
        $vehicle_num = filter_input(INPUT_POST, 'vehicle_num');
        if (isset($vehicle_num))
        {
            //A New vehicle has been submitted
            $vehicle_num = filter_input(INPUT_POST, 'vehicle_num');
            $make = filter_input(INPUT_POST, 'car_make');
            $model = filter_input(INPUT_POST, 'car_model');
            $year = filter_input(INPUT_POST, 'car_year');
            $colour = filter_input(INPUT_POST, 'car_colour');
            $plate = filter_input(INPUT_POST, 'car_plate');
            $odometer = filter_input(INPUT_POST, 'car_odometer');
            
            $form_error = Validate::validateVehicle($vehicle_num, $make, $model, $year, $colour, $plate, $odometer);
            if (count($form_error) === 0)
            {
                //Add the vehicle to the database
                VehicleDB::addNewVehicle($vehicle_num, $make, $model, $year, $colour, $plate, $odometer);
                $form_success = "Vehicle # $vehicle_num: $year $make $model, added successfully!";
                unset($form_error);
            }
        }
        ?>
    </head>
    <body>
        <?php include('../view/header.php'); ?>
        <main>
            <?php include('../view/aside.php'); ?>
            <div class='employee_form'>
                <h2 class='subhead'>Add New Vehicle</h2>
            <form method="post">
                <?php
                if (isset($form_error))
                {
                    //Retains all data that was enter previously if there was an error validating.
                    foreach ($form_error as $error)
                    {
                       echo '<p style="color: red;">' . $error . '</p>'; 
                    }
                     echo "<label for='vehicle_num'>Vehicle #: </label><input type='number' placeholder='Vehicle Number' id='vehicle_num' name='vehicle_num' required value='" . $vehicle_num . "'><br />" .
                       "<label for='make'>Make: </label><input type='text' placeholder='Make' id='make' name='car_make' required value='" . $make . "'><br />" .
                       "<label for='model'>Model: </label><input type='text' placeholder='Model' id='model' name='car_model' required value='" . $model . "'><br />" .
                       "<label for='year'>Year: </label><input type='number' placeholder='Year' id='year' name='car_year' required value='" . $year . "'><br />" .
                       "<label for='colour'>Colour: </label><input type='text' placeholder='Colour' id='colour' name='car_colour' required value='" . $colour . "'><br />" .
                       "<label for='plate'>Plate: </label><input type='text' placeholder='License Plate' id='plate' name='car_plate' required value='" . $plate . "'><br />" .
                       "<label for='odometer'>Odometer: </label><input type='text' placeholder='Odometer' id='odometer' name='car_odometer' required value='" . $odometer . "'><br />" .
                       "<input class='formspecial' type='submit' value='Add Vehicle'>";
                }
                else
                {
                    if (isset($form_success))
                    {
                        echo '<p style="color:blue;">' . $form_success . '</p>'; 
                    }
                     echo "<label for='vehicle_num'>Vehicle #: </label><input type='number' placeholder='Vehicle Number' id='vehicle_num' name='vehicle_num' required><br />" .
                       "<label for='make'>Make: </label><input type='text' placeholder='Make' id='make' name='car_make' required><br />" .
                       "<label for='model'>Model: </label><input type='text' placeholder='Model' id='model' name='car_model' required><br />" .
                       "<label for='year'>Year: </label><input type='number' placeholder='Year' id='year' name='car_year' required><br />" .
                       "<label for='colour'>Colour: </label><input type='text' placeholder='Colour' id='colour' name='car_colour' required><br />" .
                       "<label for='plate'>Plate: </label><input type='text' placeholder='License Plate' id='plate' name='car_plate' required><br />" .
                       "<label for='odometer'>Odometer: </label><input type='text' placeholder='Odometer' id='odometer' name='car_odometer' required><br />" .
                       "<input class='formspecial' type='submit' value='Add Vehicle'>";
                }
                ?>

            </form>
            </div>
        </main>
    </body>
</html>