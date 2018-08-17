<?php
require_once './classes/autoload.php';
if(!session_id())
	session_start();
    if(!isset($_SESSION['logedin']))
        header('location: login.php?ref=results.php');

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="style.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
                <script src="./js/results.js"></script>
	</head>
	<body>
		<div class="container">
			
                    <?php echo exps::view_result_table();?>
			
		</div>
	</body>
</html>




