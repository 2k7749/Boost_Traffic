<?php
$host = "localhost";
$username = "root";
$password = "mysql";
$dbname = "facebook";

$page_name = 'Direction Traffic Bot';
$version = 'Bot Build v0.1';

$connection = mysqli_connect($host,$username,$password);

if (!$connection)

{

die('Could not connect: ' . mysqli_error($connection));

}

mysqli_select_db($connection, $dbname) or die(mysqli_error($connection));

mysqli_query($connection, "SET NAMES utf8");
