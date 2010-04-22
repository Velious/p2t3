<?php
include("db_connect.php");

$post_id = $_GET['id'];
if (empty($post_id)) $post_id = $_GET['pid'];

$db->blogs->remove(array("_id" => new MongoID($post_id)), array("justOne" => true));

if (empty($_GET['pid'])) header("location:edit");
else header("location:.");
?>