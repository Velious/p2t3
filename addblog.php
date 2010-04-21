<?php
include("db_connect.php");
$name = htmlspecialchars(strip_tags($_POST['name']));
$blog = htmlspecialchars(strip_tags($_POST['blog']));
if (!empty($_POST['labels'])) $labels = explode(",", htmlspecialchars(strip_tags($_POST['labels'])));
//$date= date('m/d/y');
//$time= time();
$date=date("F j, Y ");
$date.= "at ".date(" g:i a");

$month=date("F");
$day=date("j");
$year=date("Y");
$time=date("g:i a");
$title = htmlspecialchars(strip_tags($_POST['title']));

$doc = array( 
	"name" => $name,
	"date" => $date,
	"month" => $month,
	"day" => $day,
	"year" => $year,
	"time" => $time,
	"title" => $title,
	"blog" => $blog,
	"labels" => $labels
);
$db->blogs->insert( $doc );

//header("location:add.php?saved=1");
header("location:.");
?>