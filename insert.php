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
            include_once 'dbh.php';

            $passengerID = $_POST['PID'];
            $firstName = $_POST['First'];
            $lastName = $_POST['Last'];
            $address = $_POST['Address'];
            $phone = $_POST['Phone'];
            $emailAddress = $_POST['Email'];

            $insert = "INSERT INTO PASSENGER (passenger_id, first_name, last_name, address, phone, email) 
                        VALUES ('$passengerID', '$firstName', '$lastName', '$address', '$phone', '$emailAddress');";


            try{
                $insertResult = mysqli_query($conn, $insert);

                if($insertResult){
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
            catch(Exception $e){
                echo "Insert failed";
            }
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