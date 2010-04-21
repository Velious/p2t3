<?php
include("db_connect.php");

$post_id = $_GET['id'];

$db->blogs->remove(array("_id" => new MongoID($post_id)), array("justOne" => true));

header("location:.");
?>