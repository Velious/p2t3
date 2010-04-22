<?php
include("db_connect.php");

$post_id = htmlspecialchars(strip_tags($_GET['pid']));
$poster = htmlspecialchars(strip_tags($_POST['poster']));
$message = htmlspecialchars(strip_tags($_POST['comment']));
//$reply = htmlspecialchars(strip_tags($_POST['reply']));
$date = date("F j, Y ");
$date .= "at ".date(" g:i a");

$month = date("F");
$day = date("j");
$year = date("Y");
$time = date("g:i a");

$comments = $db->comments;

$doc = array(
	"post" => $post_id,
	"poster" => $poster,
	"comment" => $message,
	//"reply" => $reply,
	"date" => $date,
	"month" => $month,
	"day" => $day,
	"year" => $year,
	"time" => $time
);

$comments->insert($doc);

header("location:.?pid=$post_id#comments");
?>