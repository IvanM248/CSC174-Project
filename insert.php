<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>

        <h1>Passenger Table:</h1>

        <?php
            //include_once 'dbh.php';
            $dbServerName = "csc174project.czexzyjnislo.us-west-1.rds.amazonaws.com";
            $dbUserName = "admin";
            $dbPassword = "Q2v9x!t7gMBM2LKH*^R5";
            $dbName = "AIRLINE";

            $conn = mysqli_connect($dbServerName, $dbUserName, $dbPassword, $dbName);

            $passengerID = $_POST['PID'];
            $firstName = $_POST['First'];
            $lastName = $_POST['Last'];
            $address = $_POST['Address'];
            $phone = $_POST['Phone'];
            $emailAddress = $_POST['Email'];

            $query = "INSERT INTO PASSENGER (passenger_id, first_name, last_name, address, phone, email) 
                        VALUES (?, ?, ?, ?, ?, ?);";


            try{
                $preparedStmt = mysqli_stmt_init($conn);
                if(mysqli_stmt_prepare($preparedStmt, $query)){

                    //Bind user input parameters to preprared statement
                    mysqli_stmt_bind_param($preparedStmt, "isssss", 
                                                $passengerID, 
                                                $firstName,
                                                $lastName,
                                                $address,
                                                $phone,
                                                $emailAddress);

                    //Execute insert
                    if(mysqli_stmt_execute($preparedStmt)){

                        //If insert statement was successful, display passenger table contents.
                        $passengerData = "SELECT * FROM PASSENGER;";
                        $queryResult = mysqli_query($conn, $passengerData);
                        $resultCheck = mysqli_num_rows($queryResult);
                        if($resultCheck > 0){
                            while($row = mysqli_fetch_assoc($queryResult)){
                                echo $row['passenger_id'] 
                                . " &nbsp&nbsp&nbsp'" . $row['first_name'] ."' " 
                                . " &nbsp&nbsp&nbsp'" . $row['last_name'] ."' " 
                                . " &nbsp&nbsp&nbsp'" . $row['address'] ."' " 
                                . " &nbsp&nbsp&nbsp'" . $row['phone'] ."' " 
                                . " &nbsp&nbsp&nbsp'" . $row['email'] ."' " 
                                ."<br>"
                                ."<br>";
                            }
                        }
                    }
                    else{
                        echo "Insert failed";
                    }
                }
            }
            catch(Exception $e){
                echo "Insert failed";
            }

            mysqli_close($conn);
        ?>

        <?php
            
            //Return to home page on return button click
            if(isset($_POST['return_button'])) {
                header("Location:index.php");
            }
        ?>

        <form method="post">
            <input type="submit" name="return_button"
                    value="Return"/>
        </form>
    </body>
</html>