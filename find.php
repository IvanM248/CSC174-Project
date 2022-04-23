<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <h1 style="margin-bottom: 0.5rem;">Query Result: </h1>
        
        <?php
            include_once 'dbh.php';

            //Retrieve input values
            $passengerID = isset($_POST['PID']) ? $_POST['PID'] : "";
            $firstName = isset($_POST['First']) ? $_POST['First'] : "";
            $lastName = isset($_POST['Last']) ? $_POST['Last'] : "";
            $address = isset($_POST['Address']) ? $_POST['Address'] : "";
            $phone = isset($_POST['Phone']) ? $_POST['Phone'] : "";
            $emailAddress = isset($_POST['Email']) ? $_POST['Email'] : "";

            //Form SELECT query based on input parameters
            $query = "SELECT * FROM PASSENGER WHERE " 
                        .(strlen($passengerID) ? "passenger_id = " . $passengerID ." AND" : "") 
                        .(strlen($firstName) ? " first_name = '" .$firstName ."' AND" : "")
                        .(strlen($lastName) ? " last_name = '" .$lastName ."' AND": "")
                        .(strlen($address) ? " address = '" .$address ."' AND" : "")
                        .(strlen($phone) ? " phone = '" .$phone ."' AND" : "")
                        .(strlen($emailAddress) ? " email = '" .$emailAddress ."' AND" : "");

            //Remove trailing AND
            $queryEnd = substr($query, -2);
            if(strcmp($queryEnd, "AND")){
                $query = substr($query, 0, strlen($query) - 4) .";";
            }
            
            //Perform query and display result
            $queryResult = mysqli_query($conn, $query);
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
            else{
                echo "No records found";
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