<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/mainDesign.css">
        <?php 
        require_once('model/cvm_database.php');
        $e = filter_input(INPUT_GET, 'e');
        if (isset($e))
        {
            echo "<script>alert('" . htmlspecialchars($e) . "');</script>";
        }
        ?>
    </head>
    <body>
        <header>
            <img src="image/vehicle2.jpg" height="80" width="120" class="rightHeader">
            <img src="image/vehicle1.jpg" height="80" width="120" class="leftHeader">
            <h1>Company Vehicle Manager</h1>
        </header>
        <main>
            <h2 class="subhead">Available Vehicles</h2><br>
            <table class="viewTable" name="available_vehicles" cellspacing="0" cellpadding="5">
                <th>Car #</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Colour</th>
                <th>Plate #</th>
                <th>Odometer (km)</th>
                <th></th>
                
                <?php
                $vehicles = new VehicleDB();
                $vehicles_list = $vehicles->getVehicles();
                
                foreach ($vehicles_list as $current_vehicle) :
                $current_num = $current_vehicle->getVehicleNum();
                if(!VehicleDB::isVehicleSignedOut($current_num)) {
                    
                ?>
                <?php $sign_out_link = "sign_out/?vehicle_num=" . $current_vehicle->getVehicleNum();?>
                <tr>
                <td><?php echo $current_vehicle->getVehicleNum(); ?></td>
                <td><?php echo $current_vehicle->getMake(); ?></td>
                <td><?php echo $current_vehicle->getModel(); ?></td>
                <td><?php echo $current_vehicle->getYear(); ?></td>
                <td><?php echo $current_vehicle->getColour(); ?></td>
                <td><?php echo $current_vehicle->getPlate(); ?></td>
                <td><?php echo $current_vehicle->getOdo(); ?></td>
                <td><form action ="<?php echo $sign_out_link; ?>" method="post"><input type="submit" value="Sign-Out" class="signOutButton"></input></form></td>
                </tr>
                <?php } endforeach;?>
            </table>
            <h2 class="subhead">Signed-Out Vehicles</h2><br>
            <table class="viewTable" cellspacing="0" cellpadding="5">
                <th>Car #</th>
                <th>Signed Out By</th>
                <th>Sign Out Date</th>
                <th>Odometer (km)</th>
                <th></th>
                <?php 
                foreach ($vehicles_list as $current_vehicle) :
                $current_num = $current_vehicle->getVehicleNum();
                $return_link = "return/?vehicle_num=" . $current_num;
                if(VehicleDB::isVehicleSignedOut($current_num)) {
                    $signOutDetails = EmployeeDB::getSignOutDetails($current_num);
                ?>
                <tr>
                <td><?php echo $current_vehicle->getVehicleNum(); ?></td>
                <td><?php echo $signOutDetails[1] . " " . $signOutDetails[2]; ?></td>
                <td><?php echo $signOutDetails[0]; ?></td>
                <td><?php echo $current_vehicle->getOdo(); ?></td>
                <td><form action="<?php echo $return_link; ?>" method="post"><input type="submit" value="Return Vehicle" class="signOutButton"></input></form></td>
                </tr>
                <?php } endforeach; ?>
            </table>
        </main>
    </body>
</html>