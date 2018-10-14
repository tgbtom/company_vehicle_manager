<html>
    <head>
        <title>Vehicle Sign-Out</title>
        <link rel="stylesheet" href="../css/mainDesign.css" type="text/css">
        <?php 
        require_once("../model/cvm_database.php");
        $vehicle_num = filter_input(INPUT_GET, "vehicle_num");
            $vehicle = new VehicleDB();
            $current_vehicle = $vehicle->getVehicle($vehicle_num);
            $details_string = $current_vehicle->getYear() . ", " . $current_vehicle->getMake() . " " . $current_vehicle->getModel() . " (" . $current_vehicle->getColour() . ")";
        ?>
    </head>
    <body> 
        <?php include('../view/header.php'); ?>
        <main>
            <div class="rightAside">
                <h4 class="subhead">Employees</h4>
               <?php 
                    $employees = EmployeeDB::getEmployees();
                        $firstName  = array_column($employees, 'first_name');
                        $lastName = array_column($employees, 'last_name');
                    array_multisort($lastName, SORT_NATURAL, $firstName, SORT_NATURAL, $employees);
                    foreach ($employees as $employee)
                    {
                        echo "<li>" . $employee['first_name'] . " " . $employee['last_name'] . "</li>";
                    }
               ?> 
            </div>
            <?php include('../view/aside.php'); ?>
            <div class='employee_form'>
            <h2 class="subhead">Vehicle Details</h2>
            <table class="viewTable" cellspacing="0">
                <th>Vehicle Number: <?php echo $current_vehicle->getVehicleNum(); ?></th>
                <tr>
                    <td><?php echo $details_string; ?></td>
                </tr><br>
            </table>
            <div class="employee_form">
            <form action="try_sign_out.php" method="post">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name"></input><br>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name"></input><br>
                <input type="hidden" name="vehicle_num" value="<?php echo $vehicle_num; ?>">
                
                <input type="submit" value="Sign-Out" class="formspecial"></input>
            </form>
            </div>
            </div>
        </main>
    </body>
</html>

