<?php require_once 'config/session.php';
$username=$_SESSION['username'];
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">-->
    <link rel="stylesheet" href="css/stylenav.css" media="all">
    <link href="https://fonts.googleapis.com/css?family=Mukta+Malar" rel="stylesheet">
    <title>Welcome-<?php echo ucwords($username)?></title>
</head>
<body>
<a href="home.php" ><img src="img/logo.png" style="float:left;margin-left: 10px;"><hr>
<h2 style="float: right;font-family:'Mukta Malar';color: black!important; margin-right: 5px; "><?php echo ucwords($username);?></h2>
<h1 align="center" style="color: #0B4F7B;text-decoration:underline;font-size:35px;font-family: 'Mukta Malar', sans-serif;">Quote Management</h1>
<nav id="nav-1">
    <a class="link-1" href="home.php">Home</a>
    <a class="link-1" href="editServicesUser.php">View Services</a>
    <a class="link-1" href="createClient.php">Add Client</a>
    <a class="link-1" href="viewclients.php">View Clients</a>
    <a class="link-1" href="showOrders.php">Quotes</a>
    <a class="link-1" href="logout.php">Logout</a>
</nav>



<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>