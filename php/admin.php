<!-- initialize a session  -->

<!-- display a log in form  -->
<!-- use a form builder to create a form that will allow the user to log in. -->
<!-- this should just have the password and no username -->
<!-- after the submission we should see if the password is in the database  -->
<!-- if it in the database   -->
<!-- show a message that they have logged in and show a new form that will allow them to post contents -->
<!-- else  -->
<!-- <!-- show a message that they have not logged in  -->

<!-- if the user makes a post insert the post into the table lab6  -->
<!-- make use of prepared statements for the insert -->
<!-- make a success message if the post is successful -->
<!-- else display an error message if the post is not successful -->


<!-- Remember to include jQuery :) -->

<!-- modal error -->




<?php

session_start();



include_once("./db.php");
include_once("./functions/phpformbuilder.php");
include_once("./functions/modal.php");


$modalError = false;
$modalSuccess = false;




if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION["loggedin"]);
    $isVisible = "block";
    $inputIsVisible = "none";
}

$isVisible = "block";
$inputIsVisible = "none";


if (isset($_POST['submit'])) {
    $password = $_POST['password'];
    $db = get_connection();
    // clean the input as well

    // ! QUESTION we are getting a -1 error?
    $results = $db->prepare("SELECT * FROM  lab6_credentials");
    $results->execute();
    $found = false;

    if (mysqli_stmt_bind_result($results, $blog_password)) {


        while ($row = $results->fetch()) {

            // why is my row true?

            if (password_verify($password, $blog_password)) {
                $_SESSION["loggedin"] = 'true';

                $modalSuccess = true;
                $found = true;
            }
        }
        if ($found == false) {
            $_SESSION["error"] = "Error: the password was not found";

            $modalError = true;
        }
    }
} else {

    // unset session error
    if (isset($_SESSION["error"])) {
        unset($_SESSION["error"]);
    }
}



?>



<?php


if (isset($_SESSION["loggedin"])) {
    $isVisible = "none";
    $inputIsVisible = "block";
    if (isset($_POST['submitPost'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $db = get_connection();



        //strip the $title of htmlspecial chars
        $title = htmlspecialchars($title);
        $content = htmlspecialchars($content);

        $results = $db->prepare("INSERT INTO lab6 (blog_title, blog_content) VALUES (?, ?)");
        $results->bind_param('ss', $title, $content);
        $results->execute();
        // check if the query is successful
        if ($results->affected_rows == 1) {
            // make an alert with success
            echo '<script>alert("Success")</script>';
        } else {
            echo '<script>alert("Failed")</script>';
        }
        $results->close();
        unset($_POST['submitPost']);
    }
}

// if logout is in get then unset the session

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- import css  -->
    <link rel="stylesheet" href="../style/style.css" />

    <script>
    document.onkeydown = function(evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            //QUESTION my form is not submitting?
            document.getElementById("inputButton").click();
        }
    }
    </script>



</head>

<body>


    <div class="modal fade" id="error" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">
                    <p><?= $_SESSION['error'] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- success modal -->
    <div class="modal fade" id="sucess" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">
                    <p>Sucess</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!-- make one nav bar with anchor tags -->

    <?php

    if($modalSuccess==true){

        echo '<script type="text/javascript">
        $(document).ready(function() {
            $("#sucess").modal("show");
        });
        </script>';
    }
   
    if($modalError==true){

        echo '<script type="text/javascript">
        $(document).ready(function() {
            $("#error").modal("show");
        });
        </script>';
    }
    ?>

    <header>

        <h1 class="logo">Blog Your Day</h1>

        <nav>
            <ul class="nav__links">
                <li><a href="mainpage.php">Main Page</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="index.php">Index</a></li>
                <li><a href="admin.php?logout=true">Logout</a></li>
            </ul>
        </nav>
        <p class="menu cta">Menu</p>
    </header>
    <div id="mobile__menu" class="overlay">
        <a class="close">&times;</a>
        <div class="overlay__content">
            <a href="#">Services</a>
            <a href="#">Projects</a>
            <a href="#">About</a>
        </div>
    </div>
    <script type="text/javascript" src="../js/mobile.js"></script>







    <div style="display: <?= $inputIsVisible ?>">
        <!-- make a container in bootstrap -->


        <!-- QUESTION my event listener for enter is not working  -->
        <div class="container horizontally-centered vertical-centered">




            <!-- make a form with two inputs one for blog title and one for blog content -->
            <form action="admin.php" method="post" class="formStyle" name='inputForm' id="inputForm">
                <div class="form-group">
                    <label style="font-size:40px;" for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="form-group">
                    <label style="font-size:20px;" for="content">Content</label>
                    <textarea class="form-control" id="content" name="content"></textarea>
                </div>


                <div class="bottom-right">

                    <button type="submit" class="btn btn-default  btnColor" id="inputButton"
                        name="submitPost">Submit</button>
                </div>


            </form>
        </div>
    </div>




    <!-- make the password form required -->


    <!-- make a div container  -->
    <div class="container " style="display: <?= $isVisible ?>">
        <div class=" row centerHeader styleLogin">



            <form method="post">
                <label for="password">Password</label>
                <input type="password" name="password" required>
                <input type="submit" name="submit" value="submit">
            </form>


        </div>
    </div>




</body>

</html>