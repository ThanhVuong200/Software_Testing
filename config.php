<?php
	$DB_HOST='localhost';
	$DB_USER='root';
	$DB_PASS='';
	$DB_NAME='web2';
	$conn=mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
	mysqli_set_charset($conn, 'UTF8');
	if(!$conn)
	{
		die("connect fail".mysqli_connect_error());
	}
?>