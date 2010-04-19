<?php
include("db_connect.php");
	
$post_id = $_GET['pid'];
$start = $_GET['start'];
$end = $_GET['end'];
$label = $_GET['label'];

if (!empty($post_id) && (!empty($start) || !empty($end) || !empty($label)))
{
	header("location:.?pid=$post_id");
	exit;
}
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>WikiBlog</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div id="wrap">
    <?php include("header.php"); ?>
	<center><div id="blog">
  
	<?php
	$posts = array();
	
	if (!empty($post_id))
	{
		//$posts = query to get single post here
	}
	
	else if (!empty($label))
	{
		//$posts = query to get posts with the given label here
	}
	
	else
	{
		if (empty($start) && empty($end))
		{
			//$end = query to get most recent post;
			//$start = $end - 10;
			if ($start < 1) $start = 1;
		}
		
		else if (empty($start))
		{
			//$start = $end - 10;
			if ($start < 1) $start = 1;
		}
		
		else if (empty($end))
		{
			$end = $start + 10;
			
			//while ($end > query to get most recent post here)
			//{
			//	--$end;
			//}
		}
	}
	
	foreach ($posts as $post)
	{	
		echo "<div class='blog-post'>\n";
		echo "<h1><a href='?pid=$id'>$title</a></h1>\n";
		echo "<p>$body</p>\n";
		
		if (empty($post_id))
		{
			echo "<span style='float:right;'><a href='.?pid=$id#comments'>Comments ($num_comments)</span>";
		}
		
		echo "</div>\n";
		
		if (!empty($post_id))
		{
			include("listcomments.php");
		}
	}
	
	echo "<p style='text-align:center;'>\n";
	echo "<a href='.?start=".($start - 11)."&end=".($start - 1)."'>Previous Page</a>\n";
	
	for ($i = 0; $i < 15; $i++)
	{
		echo "&nbsp;";
	}
	
	echo "\n";	
	echo "<a href='.?start=".($end + 1)."&end=".($end + 11)."'>Next Page</a>\n</p>\n";
	?>
	
	</div></center>
</div>
</div>
</body>
</html>
