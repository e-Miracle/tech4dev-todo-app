<?php 
    session_start();
    require_once 'helper.php';
    $conn = con();
    if (!isset($_SESSION['is_logged_in'])) {
        $_SESSION['error'] = "Please login to continue!";
        return header('location: login.php');
    }

    //get user detail
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM `users` WHERE id = '$user_id'";
    $query = $conn->query($sql);
    if ($query->num_rows == 0 ) {
        /**
         * somehow, this user's data got deleted
         */
        return header('location: logout.php');
    }
    $user = $query->fetch_object();

    //get this users tasks
    $sql = "SELECT * FROM `tasks` WHERE user_id = '$user_id'";
    $query = $conn->query($sql);
    $tasks  = $query->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Tasks</title>
</head>
<body>
    <div class="container">
        
        <div class="jumbotron">
            <div class="container">
                <h1>Tasks Page</h1>
            </div>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="Jumbo action link" role="button">Home</a>
                <a class="btn btn-success btn-lg" href="Jumbo action link" role="button">Create task</a>
                <a class="btn btn-danger btn-lg" href="Jumbo action link" role="button">Logout</a>
            </p>
        </div>
        <?php
          if(isset($_SESSION['error'])){
            ?>
              <div class="alert alert-danger">
                <strong>Sorry!</strong> <?=$_SESSION['error']?>
              </div>
              <?php
              unset($_SESSION['error']);
          }

        ?>
        <div class="container">
            <div class="col">
                <?php
                    if (!empty($tasks)) {
                        foreach ($tasks as $task) {
                            ?>
                            <div class="row <?=(is_null($task['expiry']))?'alert alert-success':'alert alert-warning'?>">
                                <p><?=$task['body']?></p>
                                <a href="/taskedit.php?taskId=<?=$task['id']?>" class="btn btn-warning">Edit</a>
                                <a href="/taskdelete.php?taskId=<?=$task['id']?>" class="btn btn-danger">Delete</a>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>