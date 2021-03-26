<?php

	session_start();
	unset($_SESSION['logged_user_id']);
	header('Location: index.php');
