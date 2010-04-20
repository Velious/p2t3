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
$title=$_POST['title'];
$doc = array( 
	"name" => $name,
	"date" => $date,
	"month" => $month,
	"day" => $day,
	"year" => $year,
	"time" => $time,
	"title" => $title,
	"blog" => $blog
);
$db->blogs->insert( $doc );

header("location:add.php?saved=1");






?>