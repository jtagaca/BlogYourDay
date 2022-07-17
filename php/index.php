<?php

include_once("./db.php");


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- import bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <!-- import a css -->
    <link rel="stylesheet" href="../style/style.css" />

    <title>Document</title>
</head>
<header>

    <h1 class="logo">Blog Your Day</h1>

    <nav>
        <ul class="nav__links">
            <li><a href="mainpage.php">Main Page</a></li>
            <li><a href="admin.php">Admin</a></li>
            <li><a href="index.php">Index</a></li>
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




<body>


    <?php
    // create a function and return rows instead of printing


    //do a query the table lab6 and show in reverse order
    $db = get_connection();

    $rows = array();
    $results = $db->query("SELECT * FROM lab6 ORDER BY written_on DESC");
    if ($results->num_rows > 0) {

        // QUESTION I'm already sanitzing in the insert do I need to sanitize here again?
        while ($row = $results->fetch_assoc()) {
            $rows[] = $row;
        }
    } else {
        echo "0 results";
    }

    ?>




    <?php


    // a for loop that loops over the rows array and prints out the data in a div box
    foreach ($rows as $row) {

    ?>
    <div class="container  horizontally-centered ">
        <div class='row full-width bdrRadius vertical-center'>
            <div class='col-md-6 '>
                <h3>Title: <?= $row['blog_title'] ?> </h3>
                <p>Content: <?= $row['blog_content'] ?></p>
                <p>Posted on: <?= $row['written_on'] ?> </p>
            </div>
        </div>
    </div>
    <?php
    }

    ?>








</body>

</html>