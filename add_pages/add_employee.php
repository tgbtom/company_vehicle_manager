<?php 
require_once '../model/cvm_form.php';
require_once '../model/cvm_database.php';

$first_name = filter_input(INPUT_POST, 'first_name');
if (isset($first_name))
{
    $last_name = filter_input(INPUT_POST, 'last_name');
    switch (Validate::validateEmployee($first_name, $last_name))
    {
    case 2: 
        $success_message = "Employee: $first_name $last_name has been added successfully.";
        EmployeeDB::addNewEmployee($first_name, $last_name);
        break;
    case 1:
    case 0:
        $error_message = "First or Last Name is Invalid.";
        break;
    default:
        echo 'There was a problem validating';
    }
}

?>
<html>
    <head>
        <title>Add New Employee</title>
        <link rel="stylesheet" type="text/css" href="../css/mainDesign.css">
    </head>
    <body>
        <?php include('../view/header.php'); ?>
        <main>
            <?php include('../view/aside.php'); ?>
            <div class='employee_form'>
                <form method='post'>
                <?php
                if (isset($error_message))
                {
                    echo "<p style='color: red;'>" . $error_message . "</p>";
                    echo "<label for='fname'>First Name: </label><input type='text' placeholder='First Name' id='fname' name='first_name' required value='" . $first_name . "'><br />" .
                    "<label for='lname'>Last Name: </label><input type='text' placeholder='Last Name' id='lname' name='last_name' required value='" . $last_name . "'><br />" .
                    "<input class='formspecial' type='submit' value='Add Employee'>"; 
                }
                else
                {
                    if(isset($success_message))
                    {
                        echo "<p style='color: blue;'>" . $success_message . "</p>";
                    }
                    echo "<label for='fname'>First Name: </label><input type='text' placeholder='First Name' id='fname' name='first_name' required><br />" .
                    "<label for='lname'>Last Name: </label><input type='text' placeholder='Last Name' id='lname' name='last_name' required><br />" .
                    "<input class='formspecial' type='submit' value='Add Employee'>";                   
                }
                ?>
            </form>
            </div>
        </main>
    </body>
</html>


