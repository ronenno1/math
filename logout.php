<?php 
	if(!session_id())
            session_start();
	if(isset($_SESSION['logedin']))
            unset($_SESSION['logedin']);
    header('location: login.php');
