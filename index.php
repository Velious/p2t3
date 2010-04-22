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
  <title>WikiBlog<?php if ($edit_view) echo " | Edit" ?></title>
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
			$title = str_ireplace("[b]", "<span style='font-weight:bold;'>", $title);
			$title = str_ireplace("[i]", "<span style='font-style:italic;'>", $title);
			$title = str_ireplace("[u]", "<span style='text-decoration:underline;'>", $title);
			$title = str_ireplace("[s]", "<span style='text-decoration:line-through;'>", $title);
			$title = str_ireplace("[/b]", "</span>", $title);
			$title = str_ireplace("[/i]", "</span>", $title);
			$title = str_ireplace("[/u]", "</span>", $title);
			$title = str_ireplace("[/s]", "</span>", $title);
			
			$formatted_body = nl2br($body);
			$formatted_body = str_ireplace("[b]", "<span style='font-weight:bold;'>", $formatted_body);
			$formatted_body = str_ireplace("[i]", "<span style='font-style:italic;'>", $formatted_body);
			$formatted_body = str_ireplace("[u]", "<span style='text-decoration:underline;'>", $formatted_body);
			$formatted_body = str_ireplace("[s]", "<span style='text-decoration:line-through;'>", $formatted_body);
			$formatted_body = str_ireplace("[url]", "<a href='", $formatted_body);
			$formatted_body = str_ireplace("[/b]", "</span>", $formatted_body);
			$formatted_body = str_ireplace("[/i]", "</span>", $formatted_body);
			$formatted_body = str_ireplace("[/u]", "</span>", $formatted_body);
			$formatted_body = str_ireplace("[/s]", "</span>", $formatted_body);
			$formatted_body = str_ireplace("[/url]", "' />[link]</a>", $formatted_body);
			$formatted_body = str_ireplace("[img]", "<img style='display:block; margin-left:auto; margin-right:auto;' src='", $formatted_body);
			$formatted_body = str_ireplace("[/img]", "' />", $formatted_body);
			$formatted_body = str_ireplace("[youtube]", "<center><object width='560' height='340'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube.com/v/", $formatted_body);
			$formatted_body = str_ireplace("[/youtube]", "&hl=en_US&fs=1&' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='560' height='340'></embed></object></center>", $formatted_body);
			
			echo "<a name='post$id'></a>\n";
			echo "<fieldset class='blog-post'>\n";
			
			if (!$edit_view)
			{
				echo "<h1 style='text-align:center;'><a class='post-title' href='?pid=$id'>$title</a></h1>\n";
			}
			
			else
			{
				echo "<form onClick='return validatePostForm(this);' method='post' ";
				echo "action='updatepost.php?id=$id&start=$start&end=$end'>\n";
				echo "<input class='edit-post' type='text' name='title' ";
				echo "value='$title' />\n";
			}			
			
			if (!$edit_view) echo "<p style='text-align:left;'>$formatted_body</p>\n";
			
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
				
				if (!empty($post_id))
				{
					echo "<ul style='text-align:left; font-size:x-small;'>";
					echo "<li>HTML is not allowed.</li>";
					echo "<li>For <span style='font-weight:bold;'>bold</span> text use the format: [b]bold text here[/b].</li>";
					echo "<li>For <span style='font-style:italic;'>italic</span> text: [i]italic text here[/i].</li>";
					echo "<li>For <span style='text-decoration:underline;'>underlined</span>: [u]underlined text here[/u].</li>";
					echo "<li>For <span style='text-decoration:line-through;'>strikethroughs</span>: [s]strikethrough here[/s].</li>";
					echo "<li>To embed an image use the format: [img]source URL here[/img].";
					echo "<li>For floating images: [img float=left]...[/img] or [img float=right]...[/img].</li></li>";
					echo "<li>To embed a <a href='http://www.youtube.com' target='_blank'>YouTube</a> clip: [youtube]clip ID (e.g., qrO4YZeyl0I) here[/youtube].</li>";
					echo "</ul>\n";
				}
				
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
				echo "parent.location = 'deletepost.php?id=$id&pid=$post_id&';\" type='button' value='Delete' />\n";
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
	
	if (empty($post_id) && empty($label))
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
</body>
</html>