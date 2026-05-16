<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["passwd"];

    $conn = mysqli_connect("localhost", "root", "", "cyberlab_db");

    $query = "SELECT * FROM users WHERE username='" . $username . "' AND passwd='". $password ."'";

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if($row) {
        if($row["role"] == "admin") {
            
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];
            header("Location: admin.php");
        }

        else {
            
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];
            header("Location: profile.php");
        }
    }

    else {
        $error = "Invalid Credentials";
    }

}



?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body>


<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form action="login.php" method="POST">
    Username: <input type="text" name="username" /><br><br>
    Password: <input type="password" name="passwd" /><br><br>
    <input type="submit" value="Login" />
</form>

</body>
</html>