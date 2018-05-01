<?php
include('session.php');
?>
<!DOCTYPE html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css" crossorigin="anonymous">
    <script src="vendor/jquery.slim.js" ></script>
    <script src="vendor/jquery.js"></script>
    <script src="vendor/popper.js" ></script>
    <script src="vendor/bootstrap/bootstrap.min.js"></script>
    <script src="vendor/vuejs/vue.js" ></script>

</head>
<body>
<div id="nav"><span v-html="rawHtml"></span></div>

<div class="jumbotron">
    <h1 class="display-4">This is your profile page <?php echo $login_session; ?>!</h1>
    <p class="lead">Not everything is set, but we think it can be changed)</p>
    <a class="btn btn-primary btn-lg" href="logout.php" role="button">Log Out</a>
</div>
<script src="assets/js/general.js"></script>
</body>
</html>