<?php   
    require_once './classes/autoload.php';

	if(!session_id())
            session_start();
	if(!isset($_SESSION['logedin']))
        {
            $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : NULL;
            $password  = isset($_POST['password']) ? $_POST['password'] : NULL;
            if(($user_name==config::user_name && $password==config::user_pass))
                $_SESSION['logedin'] = 1;
        }
	if(isset($_SESSION['logedin']))
            header('location: results.php');

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <link rel="stylesheet" href = "style.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <script src="bootstrap.min.js"></script>
	</head>
	<body>
		<div class="box">
			<h1>Login</h1>
			<form action="" method="POST" id="login_form">
				<div class="form-inline"><label >User name: <input placeholder="User name / Email" class="form-control" type="text" id = "user_name" name = "user_name"></label></div>
				<div class="form-inline"><label>Password:   <input placeholder="Password" class="form-control" type="password" id = "password" name = "password"></label></div>
                                <button id="login" class="btn btn-primary">enter</button>
			</form>
				<div id="errors" class="bg-danger"></div>
		</div>
	</body>
</html>
