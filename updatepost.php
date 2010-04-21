<?php
include("db_connect.php");
$post_id = $_GET['id'];
$title = trim(htmlspecialchars(strip_tags($_POST['title'])));
$blog = trim(htmlspecialchars(strip_tags($_POST['blog'])));
if (!empty($_POST['labels'])) $labels = explode(",", htmlspecialchars(strip_tags($_POST['labels'])));

$blogs = $db->blogs;

if (!empty($title)) $blogs->update(array("_id" => new MongoID($post_id)), array('$set' => array("title" => $title)));
if (!empty($blog)) $blogs->update(array("_id" => new MongoID($post_id)), array('$set' => array("blog" => $blog)));
$blogs->update(array("_id" => new MongoID($post_id)), array('$set' => array("labels" => $labels)));

header("location:.?pid=$post_id");
?>