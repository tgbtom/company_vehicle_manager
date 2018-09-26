<?php
class Database {
    private static $user = 'root';
    private static $pass = '';
    private static $dsn = 'mysql:host=localhost;dbname=local_portfolio';
    private static $db;
    
    private function __construct() {}
    
    public static function getDB()
    {
        if (!isset(self::$db))
        {
            try
            {
               self::$db = new PDO(self::$dsn, self::$user, self::$pass);
            }
            catch(PDOException $e)
            {
                $error_message = $e->getMessage();
                exit();
            }
        }
        return self::$db;
    }
}

class Vehicle
{
    private $vehicle_num, $make, $model, $year, $colour, $plate, $odo;
    
    public function __construct($vehicle_num, $make, $model, $year, $colour, $plate, $odo) 
    {
        $this->vehicle_num = $vehicle_num;
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
        $this->colour = $colour;
        $this->plate = $plate;
        $this->odo = $odo;
    }
    
    public function getVehicleNum ()
    {return $this->vehicle_num;}
    
    public function getMake()
    {return $this->make;}
    
    public function getModel()
    {return $this->model;}
    
    public function getYear()
    {return $this->year;}
    
    public function getColour()
    {return $this->colour;}
    
    public function getPlate()
    {return $this->plate;}
    
    public function getOdo()
    {return $this->odo;}
}

class VehicleDB
{
    public function getVehicles()
    {
        $dbCon = Database::getDB();
        $query = "SELECT * FROM cvm_vehicles";
        $statement = $dbCon->prepare($query);
        $statement->execute();
        
        $vehicles = array();
        foreach ($statement as $row)
        {
            $vehicle = new Vehicle(
                    $row['vehicle_num'],
                    $row['make'],
                    $row['model'],
                    $row['year'],
                    $row['colour'],
                    $row['plate'],
                    $row['odometer']
                    );
            
            $vehicles[] = $vehicle;
        }
        return $vehicles;
    } 
    
    public function getVehicle($vehicle_num)
    {
        $dbCon = Database::getDB();
        $query = "SELECT * FROM cvm_vehicles WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();
        
        $vehicle = NULL; //Returns NULL if no car is found matching the ID
        foreach ($statement as $row)
        {
            $vehicle = new Vehicle(
                    $row['vehicle_num'], 
                    $row['make'], 
                    $row['model'], 
                    $row['year'], 
                    $row['colour'], 
                    $row['plate'], 
                    $row['odometer']);
        }
        return $vehicle;
    }
    
    public static function isVehicleSignedOut($vehicle_num)
    {
        $dbCon = Database::getDB();
        $query = "SELECT * FROM cvm_sign_outs WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();
        $result = $statement->fetch();
        
        $statement->closeCursor();
        
        return $result;
    }
}

class EmployeeDB
{
    public static function doesEmployeeExist($first_name, $last_name)
    {
        $dbCon = Database::getDB();
        $query = "SELECT * FROM cvm_employees WHERE `first_name` = :first_name AND `last_name` = :last_name";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->execute();
        
        $employee = NULL;
        foreach ($statement as $row)
        {
            $employee = new Employee(
                    $row['employee_num'],
                    $row['first_name'],
                    $row['last_name']);
        }
        
        $statement->closeCursor();
        return $employee;
    }
    
    public static function isVehicleSignedOut($vehicle_num)
    {
        $dbCon = Database::getDB();
        $query = "SELECT * FROM cvm_sign_outs WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();

        $signOutDetails = Null;
        foreach ($statement as $row)
        {
            $signOutDetails = new SignOutDetails(
                    $row['vehicle_num'],
                    $row['employee_num'],
                    $row['sign_out_date']);
        }
        return $signOutDetails;
    }
    
    public static function signOutCar($vehicle_num, $employee_num)
    {
        $dbCon = Database::getDB();
        $query = "INSERT INTO cvm_sign_outs (vehicle_num, employee_num, sign_out_date) VALUES"
                . " (:vehicle_num, :employee_num, :sign_out_date)";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->bindValue(':employee_num', $employee_num);
        $statement->bindValue(':sign_out_date', date('Y-m-d'));
        $statement->execute();
        $statement->closeCursor();
    }
    
    public static function getSignOutDetails($vehicle_num)
    {
        $dbCon = Database::getDB();
        $query = "SELECT * from cvm_sign_outs WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();
        
        foreach ($statement as $row)
        {
            $signOutEmployeeNum = $row['employee_num'];
            $signOutDate = $row['sign_out_date'];
            $query2 = "SELECT * FROM cvm_employees WHERE `employee_num` = :employee_num";
            $statement2 = $dbCon->prepare($query2);
            $statement2->bindValue(':employee_num', $signOutEmployeeNum);
            $statement2->execute();
            foreach ($statement2 as $row2)
            {
                $first_name = $row2['first_name'];
                $last_name = $row2['last_name'];
            }
        }
        $signOutDetails = [$signOutDate, $first_name, $last_name];
        return $signOutDetails;
    }
    
    public function returnCar($vehicle_num, $employee_num, $new_odo)
    {
        //Add to cvm_logs, remove from cvm_sign_outs
        $dbCon = Database::getDB();
        
        //
        //Check old odometer reading and determing distance travveled
        //
        $query = "SELECT * FROM cvm_vehicles WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();
        
        foreach ($statement as $row)
        {
            $old_odo = $row['odometer'];
        }
        
        if ($new_odo - $old_odo < 0)
        {
            //Attempt to reduce Odometer Reading.
            $logged_distance = 0;
        }
        else
        {
            //Store distance added to Odometer
            $logged_distance = $new_odo - $old_odo;
        }
        
        //
        //Find sign-out Date
        //
        $query = "SELECT * FROM cvm_sign_outs WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();
        
        foreach ($statement as $row)
        {
            $sign_out_date = $row['sign_out_date'];
        }
        
        $query = "INSERT INTO cvm_logs (vehicle_num, employee_num, sign_out_date, return_date, logged_distance) VALUES "
                . "(:vehicle_num, :employee_num, :sign_out_date, :return_date, :logged_distance)";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->bindValue(':employee_num', $employee_num);
        $statement->bindValue(':sign_out_date', $sign_out_date);
        $statement->bindValue(':return_date', date('Y-m-d'));
        $statement->bindValue(':logged_distance', $logged_distance);
        $statement->execute();
        
        //Update Odometer in cvm_vehicles
        $query = "UPDATE cvm_vehicles SET `odometer` = :new_odo WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':new_odo', $new_odo);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();
        
        //remove car from sign outs
        $query = "DELETE FROM cvm_sign_outs WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();
        $statement->closeCursor();
    }
    
    private function validateOdo($vehicle_num)
    {
        $dbCon = Database::getDB();
        $query = "SELECT * from cvm_vehicles WHERE `vehicle_num` = :vehicle_num";
        $statement = $dbCon->prepare($query);
        $statement->bindValue(':vehicle_num', $vehicle_num);
        $statement->execute();
        $result = $statement->fetch();
        
        foreach ($statement as $row)
        {
          $old_odo = $row['odometer'];  
        }
        return $old_odo;
        
    }
}

class Employee
{
    private $employee_num, $first_name, $last_name;
    
    public function __construct($employee_num, $first_name, $last_name) {
        $this->employee_num = $employee_num;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }
    
    public function getEmployeeNum()
    {
        return $this->employee_num;
    }
    
}

class SignOutDetails
{
    private $vehicle_num, $employee_num, $sign_out_date;
    
    public function __construct($vehicle_num, $employee_num, $sign_out_date) {
        $this->vehicle_num = $vehicle_num;
        $this->employee_num = $employee_num;
        $this->sign_out_date = $sign_out_date;
    }
    
    public function getVehicleNum()
    {
        return $this->vehicle_num;
    }
    
    public function getEmployeeNum()
    {
        return $this->employee_num;
    }
    
}
?>
