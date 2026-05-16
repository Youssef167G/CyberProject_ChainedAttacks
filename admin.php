<?php
session_start();

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];

    $conn = mysqli_connect('localhost', "root", "", "cyberlab_db");
    $query = "UPDATE users SET role='admin' WHERE username='" . $username ."'";

    $result = mysqli_query($conn, $query);
}


$conn_1 = mysqli_connect('localhost', "root", "", "cyberlab_db");
$query_1 = "SELECT * FROM users";
$users = mysqli_query($conn_1, $query_1);


?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body class="search-wrapper">


    <table class="results-table">

    <tr>
        <th>Username</th>
        <th>Role</th>
        <th>Promote Role</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($users)): ?>

    <tr>
        <td><?php echo $row["username"]; ?></td>
        <td><?php echo $row["role"]; ?></td>
        <td><form action="admin.php" method="POST">
            <input type="hidden" name='username' value='<?php echo $row["username"]; ?>'>
            <input class="search-btn" type="submit" value="Promote" />
        </form></td>
    </tr>

    <?php endwhile; ?>
    
    </table>
    


</body>
</html>