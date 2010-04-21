<?php
include("db_connect.php");

$post_id = $_GET['pid'];
$start = $_GET['start'];
$end = $_GET['end'];
$label = $_GET['label'];
$edit_view = ($_GET['edit'] == 1);

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
	$cursor = $db->blogs->find()->sort(array("year" => 1, "month" => 1, "day" => 1, "time" => 1));
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
	?>
	
	<script type='text/javascript'>
	function validatePostForm(form)
	{
		var title = form.title.value.trim();
		var blog = form.blog.value.trim();
		
		if ((title != "") && (blog != ""))
			return true;
			
		else
		{
			var redBorder = "2px solid red";
			
			if (title == "")
				form.title.style.border = redBorder;
				
			if (blog == "")
				form.blog.style.border = redBorder;
		}
		
		return false;
	}
	</script>
	
	<?php
	$count = 1;
	
	for ($i = $num_posts; $i > 0; $i--)
	{
		$post = $posts[($i - 1)];
		$id = $post["_id"]->__toString();
		$title = $post['title'];
		$body = $post['blog'];
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
			
			if (!$edit_view)
			{
				echo "<h1 style='text-align:center;'><a class='post-title' href='?pid=$id'>$title</a></h1>\n";
			}
			
			else
			{
				echo "<form onClick='return validatePostForm(this);' method='post' action='updatepost.php?id=$id'>\n";
				echo "<input class='edit-post' type='text' name='title' ";
				echo "value='$title' />\n";
			}
			
			if (!$edit_view)
			{
				echo "<p style='text-align:left;'>".nl2br($body)."</p>\n";
			}
			
			else
			{
				echo "<textarea class='edit-post' name='blog' rows=10>$body</textarea>\n";
			}
			
			$first_label = true;
			
			if (is_array($labels))
			{
				foreach ($labels as $l)
				{
					$l = trim($l);
					$label_link = "<a href='.?label=$l'>$l</a>";
					
					if ($first_label)
					{
						if (!$edit_view) echo "<p style='text-align:left;'>";
						else echo "<p style='text-align:center;'>";
						echo "<span style='font-weight:bold;'>Labels:</span> ";
						if (!$edit_view) echo $label_link;
						else echo "<input style='width:525px;' type='text' name='labels' value='$l";
						$first_label = false;
					}
					
					else
					{
						echo ", ";
						if (!$edit_view) echo $label_link;
						else echo $l;
					}
				}
			}
			
			else if ($edit_view)
			{
				echo "<p style='text-align:center;'><span style='font-weight:bold;'>Labels:</span> ";
				echo "<input style='width:525px;' type='text' name='labels' /></p>\n";
			}
			
			if (!$first_label)
			{
				if ($edit_view) echo "' />";
				echo "</p>\n";
			}
			
			if ($edit_view)
			{
				echo "<input style='display:block; margin:0px auto 20px auto;' type='submit' ";
				echo "value='  Save Changes  ' />\n";
				echo "</form>\n";
			}
			
			echo "<span class='post-info'>\n";
			echo "Posted by $author on $date.";
			echo "</span>\n";
			
			echo "<span style='float:right;'>";
			
			if (empty($post_id) && !$edit_view)
			{
				echo "<a href='.?pid=$id#comments'>Comments</a>";
			}
			
			else
			{
				if (!$edit_view)
				{
					echo "<input onClick=\"parent.location = 'edit?pid=$post_id';\" type='button' ";
					echo "value='Edit' /> \n";
				}
				
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
		
		if (empty($label) || $label_matches) $count++;
	}
	
	if (empty($post_id))
	{
		echo "<p style='text-align:center;'>\n";
	
		if ($start > 1)
		{
			echo "<a href='";
			if (!$edit_view) echo ".";
			else echo "edit";
			echo "?start=".($start - 10)."&end=".($start - 1);
			echo "'>Previous Page</a>\n";
		}
		
		if (($start > 1) && ($num_posts > $end))
		{
			for ($i = 0; ($i < 15) && ($start > 0) && ($end < $num_posts); $i++)
			{
				echo "&nbsp;\n";
			}
		}
		
		if ($num_posts > $end)
		{
			echo "<a href='";
			if (!$edit_view) echo ".";
			else echo "edit";
			echo "?start=".($end + 1)."&end=".($end + 10)."'>Next Page</a>\n";
		}
		
		echo "</p>\n";
	}
		
	echo "<br />";
	?>
	
	</div>
</div>
</div>
</body>
</html>