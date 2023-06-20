<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include '../database/connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['title'])) {
        $title = $_POST['title'];

        if (!empty($title)) {
                $stmt = $conn->prepare("INSERT INTO `to-do_list`.`todos` (title, date_time) VALUES (?, 0, NOW())");
                $stmt->execute([$title]);
                header("Location: dashboard.php");
                exit;
        }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Dashboard</title>
        <link rel="stylesheet" href="../css/style.css">

        <style>
        .navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                background-color: #f2f2f2;
        }

        .navbar-menu li a {
                font-size: 20px;
        }

        .navbar-menu {
                list-style: none;
                margin: 0;
                padding: 0;
        }

        .navbar-menu li {
                display: inline-block;
                margin-left: 10px;
        }

        .navbar-menu li:first-child {
                margin-left: auto;
        }
        </style>
</head>

<body>
        <div class="navbar">
                <div class="welcome-section">
                        <h2>Welcome
                                <?php echo $_SESSION['username']; ?>
                        </h2>
                </div>
                <ul class="navbar-menu">
                        <li><a href="../auth/logout.php" class="logout-button">Logout</a></li>
                </ul>
        </div>
        <div class="main-section">
                <div class="add-section">
                        <form action="../app/add.php" method="POST" autocomplete="off">
                                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') {?>
                                <input type="text" name="title" style="border-color: #ff6666"
                                        placeholder="This field is required" />
                                <button type="submit">Add &nbsp; <span>&#43;</span></button>

                                <?php } else {?>
                                <input type="text" name="title" placeholder="What do you need to do?" />
                                <button type="submit">Add &nbsp; <span>&#43;</span></button>
                                <?php }?>
                        </form>
                </div>
                <?php
$todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
?>
                <div class="show-todo-section">
                        <?php if ($todos->rowCount() <= 0) {?>
                        <div class="todo-item">
                                <div class="empty">
                                        <img src="../img/f.png" width="100%" />
                                        <img src="../img/Ellipsis.gif" width="80px">
                                </div>
                        </div>
                        <?php }?>

                        <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) {?>
                        <div class="todo-item">
                                <span id="<?php echo $todo['id']; ?>" class="remove-to-do">x</span>
                                <?php if ($todo['checked']) {?>
                                <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>"
                                        checked />
                                <h2 class="checked">
                                        <?php echo $todo['title'] ?>
                                </h2>
                                <?php } else {?>
                                <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
                                <h2>
                                        <?php echo $todo['title'] ?>
                                </h2>
                                <?php }?>
                                <br>
                                <small>created:
                                        <?php echo $todo['date_time'] ?>
                                </small>
                        </div>

                        <?php }?>

                        <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) {?>

                        <div class="todo-item">

                                <form class="task-form" action="" method="POST">
                                        <span>
                                                <button type="submit" name="complete"
                                                        value="<?php echo $todo['id']; ?>">Complete</button>
                                                <button type="submit" name="delete"
                                                        value="<?php echo $todo['id']; ?>">Delete</button>
                                        </span>
                                </form>

                                <?php if ($todo['checked']) {?>
                                <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>"
                                        checked />
                                <h2 class="checked">
                                        <?php echo $todo['title'] ?>
                                </h2>
                                <?php } else {?>
                                <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
                                <h2>
                                        <?php echo $todo['title'] ?>
                                </h2>
                                <?php }?>
                                <br>

                                <small>created:
                                        <?php echo $todo['date_time'] ?>
                                </small>
                        </div>
                        <?php }?>
                </div>
        </div>

        <script src="js/jquery-3.2.1.min.js"></script>

        <script>
        $(document).ready(function() {
                $('.remove-to-do').click(function() {
                        const id = $(this).attr('id');

                        $.post("../app/remove.php", {
                                        id: id
                                },
                                (data) => {
                                        if (data) {
                                                $(this).parent().hide(600);
                                        }
                                }
                        );
                });

                $(".check-box").click(function(e) {
                        const id = $(this).attr('data-todo-id');

                        $.post('../app/check.php', {
                                        id: id
                                },
                                (data) => {
                                        if (data != 'error') {
                                                const h2 = $(this).next();
                                                if (data === '1') {
                                                        h2.removeClass(
                                                                'checked'
                                                        );
                                                } else {
                                                        h2.addClass('checked');
                                                }
                                        }
                                }
                        );
                });
        });
        </script>
</body>

</html>