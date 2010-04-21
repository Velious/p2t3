<?php
include("db_connect.php");

$comment_id = $_GET['id'];
$post_id = $_GET['post'];

$db->comments->remove(array("_id" => new MongoID($comment_id)), array("justOne" => true));

header("location:.?pid=$post_id");
?>