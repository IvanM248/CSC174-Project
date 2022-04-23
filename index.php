
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <h1 style="margin-bottom: 0;" >Insert Passenger:</h1>
        <form action="insert.php" method="POST">
            <input style="margin-top: 0.5rem;" type="text" name = "PID" placeholder="Passenger ID" required></>
            <br>
            <input style="margin-top: 0.5rem;" type="text" name = "First" placeholder="First Name" required></>
            <br>
            <input style="margin-top: 0.5rem;"type="text" name = "Last" placeholder="Last Name" required></>
            <br>
            <input style="margin-top: 0.5rem;" type="text" name = "Address" placeholder="Address" required></>
            <br>
            <input style="margin-top: 0.5rem;" type="tel" name = "Phone" placeholder="Phone Number" required></>
            <br>
            <input style="margin-top: 0.5rem;" type="email" name = "Email" placeholder="Email Address" required></>
            <br>
            <button type="submit" name="insert" style="margin-top: 0.5rem;">Insert</button>
        </form>
    </body>
</html>