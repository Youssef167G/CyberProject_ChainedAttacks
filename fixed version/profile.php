FIXED PROFILE:

<?php
session_start();
header("Content-Security-Policy: default-src 'self'");

if(isset($_GET["username"])) {
    $row = $_GET["username"];
}
?>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="search-wrapper">
<?php if(isset($row)): ?>
    <h2>
        Welcome to your user profile, 
        <?php echo htmlspecialchars($row, ENT_QUOTES, 'UTF-8'); ?>
    </h2>
<?php endif; ?>
<?php if(isset($_SESSION["username"])): ?>
    <h2>
        Welcome to your user profile, 
        <?php echo $_SESSION["username"]; ?>
    </h2>
<?php endif; ?>
<form class="search-box" action="search.php" method="GET">
    <input class="search-btn" type="submit" value="Hack Search" />
</form>
<form class="search-box" action="search_safe.php" method="GET">
    <input class="search-btn" type="submit" value="Safe Search" />
</form>
</body>
</html>