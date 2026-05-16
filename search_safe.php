<?php
session_start();

if(isset($_GET["query"])) {
    $search = $_GET["query"];

    $conn = mysqli_connect("localhost", "root", "", "cyberlab_db");

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

}



?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body class="search-wrapper">



    <form class="search-box" action="search_safe.php" method="GET">
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