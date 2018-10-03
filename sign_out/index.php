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
        <header>
            <img src="../image/vehicle2.jpg" height="80" width="120" class="rightHeader">
            <img src="../image/vehicle1.jpg" height="80" width="120" class="leftHeader">
            <h1>Company Vehicle Manager</h1>
        </header>
        <main>
            <aside>
                <a href="../add_pages/add_vehicle.php">Add New Vehicle</a><br>
                <a href="../add_pages/add_employee.php">Add New Employee</a>
            </aside>
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
        </main>
    </body>
</html>

