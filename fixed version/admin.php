FIXED ADMIN.PHP


<?php
// Step 4: SameSite cookie before session_start()
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Step 1: Generate CSRF token once per session
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Step 3: Validate token on POST before touching the DB
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        empty($_POST["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
    ) {
        http_response_code(403);
        die("Invalid CSRF token.");
    }

    // Also fix the SQL injection while we're here
    $conn = mysqli_connect('localhost', "root", "", "cyberlab_db");
    $stmt = mysqli_prepare($conn, "UPDATE users SET role='admin' WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $_POST["username"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
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
        <td><?php echo htmlspecialchars($row["username"]); ?></td>
        <td><?php echo htmlspecialchars($row["role"]); ?></td>
        <td>
            <form action="admin.php" method="POST">
                <!-- Step 2: Embed token as hidden input in every form -->
                <input type="hidden" name="csrf_token"
                       value="<?php echo $_SESSION["csrf_token"]; ?>">
                <input type="hidden" name="username"
                       value="<?php echo htmlspecialchars($row["username"]); ?>">
                <input class="search-btn" type="submit" value="Promote">
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
    </table>
</body>
</html>