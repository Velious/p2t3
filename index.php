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
	<div id="blog"><br />
  
	<?php
	$cursor = $db->blogs->find();
	$num_posts = $db->blogs->count();
	$posts = array();
	$count = 0;
	
	foreach ($cursor as $id => $value)
	{
		$posts[$count++] = $value;
	}
	
	if (empty($post_id))
	{
		if (empty($start) && empty($end))
		{
			$start = 1;
			$end = 10;
		}
	
		else
		{
			if (empty($start))
			{
				if (($end - 10) > 0) $start = $end - 10;
			}
			
			if (empty($end))
			{
				$end = $start + 10;
				
				while ($end > $num_posts)
				{
					$end--;
				}
			}
		}
	}
	
	$count = 1;
	
	for ($i = $num_posts; $i > 0; $i--)
	{
		$post = $posts[($i - 1)];
		$id = $post["_id"]->__toString();
		$title = $post['title'];
		$body = nl2br($post['blog']);
		$author = $post['name'];
		$date = $post['date'];
		$time = $post['time'];
		$date = $post['date'];
		$labels = $post['labels'];
		$label_matches = false;
		
		if (is_array($labels))
		{
			foreach ($labels as $l)
			{
				if (!empty($l) && (strtolower(trim($l)) == strtolower($label))) $label_matches = true;
			}
		}
		
		if ($label_matches || ($id == $post_id) || (empty($label) && ($count >= $start) && ($count <= $end)))
		{			
			echo "<fieldset class='blog-post'>\n";
			echo "<h1 style='text-align:center;'><a class='post-title' href='?pid=$id'>$title</a></h1>\n";
			echo "<p class='post-body;' style='text-align:left;'>$body</p>\n";
			
			$first_label = true;
			
			if (is_array($labels))
			{
				foreach ($labels as $l)
				{
					$l = trim($l);
					$label_link = "<a href='.?label=$l'>$l</a>";
					
					if ($first_label)
					{
						echo "<p style='text-align:left;'>";
						echo "<span style='font-weight:bold;'>Labels:</span> $label_link";
						$first_label = false;
					}
					
					else echo ", $label_link";
				}
			}
			
			if (!$first_label) echo "</p>\n";
			
			echo "<span class='post-info'>\n";
			echo "Posted by $author on $date.";
			echo "</span>\n";
			
			echo "<span style='float:right;'>";
			
			if (empty($post_id))
			{
				echo "<a href='.?pid=$id#comments'>Comments</a>";
			}
			
			else
			{
				echo "<input onClick=\"parent.location = '.?pid=$post_id&edit=1';\" type='button' value='Edit' /> \n";
				echo "<input onClick=\"if (confirm('Permanently delete this post?')) ";
				echo "parent.location = 'deletepost.php?id=$post_id';\" type='button' value='Delete' />\n";
			}
			
			echo "</span>\n";			
			echo "</fieldset>\n";
			
			if (!empty($post_id))
			{
				include("listcomments.php");
			}
		}
		
		$count++;
	}
	
	if (empty($post_id) && empty($label))
	{
		echo "<p style='text-align:center;'>\n";
	
		if ($start > 1) echo "<a href='.?start=".($start - 10)."&end=".($start - 1)."'>Previous Page</a>\n";
		
		if (($start > 1) && ($num_posts > $end))
		{
			for ($i = 0; ($i < 15) && ($start > 0) && ($end < $num_posts); $i++)
			{
				echo "&nbsp;\n";
			}
		}
		
		if ($num_posts > $end) echo "<a href='.?start=".($end + 1)."&end=".($end + 10)."'>Next Page</a>\n";
		echo "</p>\n";
	}
		
	echo "<br />";
	?>
	
	</div>
</div>
</div>
</body>
</html>