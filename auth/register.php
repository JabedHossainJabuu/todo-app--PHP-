<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="container">
        <form action="login.php" method="post">
            <h2>Registration Form</h2>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required> <br><br>

            <button type="submit" name="register">Register</button>
            <p>Don't have an account? <a href="register_process.php">Register</a></p>
        </form>
    </div>
</body>
</html>
