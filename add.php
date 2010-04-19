<?php include("db_connect.php"); ?>
<?php $saved = $_GET['saved'] == 1; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>WikiBlog | Add Blog</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div id="wrap">
<?php include("header.php"); ?>
<div id="blog">
<?php
	if ($saved){
		echo "<fieldset style='border:2px solid white; background-color:black;'>";
		echo "<p style='color:white; font-weight:bold; text-align:center;'>";
		echo "You blog has been posted successfully! <br> Return to <a href='index.php'><font color='red'>Home Page</font></a>.";
		echo "</p></fieldset>";
	}
?>
<form method='post' action='addblog.php' align="left">
<br /><label style='vertical-align:top;' for='name'>Blogger Name:</label>
	  <input name='name' type='text' placeholder="Name of Blogger" />
<br /><label style='vertical-align:top;' for='blog'>Blog Entry: <br> <h5>**MAX 300 Characters**</h5></label>
	  <textarea name='blog' rows=10 maxlength=300 placeholder="Blog Entry             (MAX 300 characters)"></textarea>

<p><input style='display:block; margin-left:auto; margin-right:auto;' type='submit' value=' Submit ' /></p>
</form>
</div> <!-- End 'blog' div -->
</div> <!-- End 'wrap' div -->
</body>
</html>