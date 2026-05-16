<?php
session_start();

if(isset($_GET["query"])) {
    $search = $_GET["query"];

    $conn = mysqli_connect("localhost", "root", "", "cyberlab_db");

    $query = "SELECT * FROM users WHERE username='" . $search . "'";

    $result = mysqli_query($conn, $query);

}



?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body class="search-wrapper">



<form class="search-box" action="search.php" method="GET">
    Username: <input class="search-input" type="text" name="query" /><br><br>
    <input class="search-btn" type="submit" value="Submit" />
</form>

<?php if(isset($result)): ?>

    <table class="results-table">

    <?php while($row = mysqli_fetch_assoc($result)): ?>

    <tr>
        <td><?php echo $row["username"]; ?></td>
        <td><?php echo $row["passwd"]; ?></td>
        <td><?php echo $row["role"]; ?></td>
    </tr>

    <?php endwhile; ?>
    
    </table>

    
<?php endif; ?>


    <form  action="profile.php" method="GET">
        <input class="search-btn" type="submit" value="Go Back" />
    </form>



</body>
</html>