<html>
    <head>
        <title>Vehicle Return</title>
        <link rel="stylesheet" href="../css/mainDesign.css" type="text/css">
        <?php 
        require_once("../model/cvm_database.php");
        $vehicle_num = filter_input(INPUT_GET, "vehicle_num");
            $vehicle = new VehicleDB();
            $current_vehicle = $vehicle->getVehicle($vehicle_num);
            $current_odometer = $current_vehicle->getOdo();
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
            <h2 class="subhead">Vehicle Details</h2>
            <table class="viewTable" cellspacing="0">
                <th>Vehicle Number: <?php echo $current_vehicle->getVehicleNum(); ?></th>
                <tr>
                    <td><?php echo $details_string; ?></td>
                </tr><br>
            </table>
            <div class="employee_form">
            <form action="try_return.php" method="post">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" placeholder="First Name"></input><br>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" placeholder="Last Name"></input><br>
                <label for="new_odometer">Odometer Reading (<?php echo $current_odometer; ?>):</label>
                <input type="number" id="new_odometer" name="new_odometer" placeholder="Odometer"></input><br>
                <input type="hidden" name="vehicle_num" value="<?php echo $vehicle_num; ?>">
                
                <input type="submit" value="Return Vehicle" class="formspecial"></input>
            </form>
            </div>
        </main>
    </body>
</html>




