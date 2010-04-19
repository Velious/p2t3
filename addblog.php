<?php
include("db_connect.php");
$name = $_POST['name'];
$blog = $_POST['blog'];
//$date= date('m/d/y');
//$time= time();
$date=date("F j, Y, ");
$date.= "at ".date(" g:i a");

$month=date("F");
$day=date("j");
$year=date("Y");
$time=date("g:i a");

$doc = array( 
	"name" => $name,
	"date" => $date,
	"month" => $month,
	"day" => $day,
	"year" => $year,
	"time" => $time,
	"blog" => $blog
);
$db->insert( $doc );

header("location:add.php?saved=1");






?>