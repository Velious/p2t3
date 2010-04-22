<?php include("db_connect.php"); ?>
<?php $saved = $_GET['saved'] == 1; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>WikiBlog | Add Entry</title>
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
		echo "You blog has been posted successfully! <br> Return to <a href='index.php'><font color='blue'>Home Page</font></a>.";
		echo "</p></fieldset>";
	}
?>

<script type='text/javascript'>
function validateForm(form)
{
	var name = form.name.value.trim();
	var title = form.title.value.trim();
	var blog = form.blog.value.trim();
	
	if ((name != "") && (title != "") && (blog != ""))
		return true;
		
	else
	{
		var redBorder = "2px solid red";
		
		if (name == "")
			form.name.style.border = redBorder;
			
		if (title == "")
			form.title.style.border = redBorder;
			
		if (blog == "")
			form.blog.style.border = redBorder;
	}
	
	return false;
}
</script>


<form onSubmit='return validateForm(this);' method='post' action='addblog.php'>
<div style="margin-left:50px;">
<p style='text-align:left;'><label style='vertical-align:top;' for='title'>Title:</label> 
<input style='width:300px;' name='title' type='text' id="title" placeholder="Title of this Blog."/></p>
<p style='text-align:left;'><label style='vertical-align:top;' for='blog'>Blog Entry: </label> 
<textarea style='width:400px; vertical-align:text-top; display:inline; margin-left:0px;' name='blog' id="blog" rows=10 maxlength=300 placeholder="Blog Entry             (MAX 300 characters)"></textarea></p>
<p style='text-align:left;'><label style='vertical-align:top;' for='labels'>Labels (separated by commas):</label> 
<input style='width:300px;' name="labels" id="labels" type="text" /></p>
<p style='text-align:left;'><label style='vertical-align:top;' for='name'>Your Name:</label> 
<input style='width:200px;' name='name' type='text' id="name" placeholder="Name of Blogger" /></p>

<p><input style='display:block; margin-left:auto; margin-right:auto;' type='submit' value='  Submit  ' /></p>
</div>
</form>
</div> <!-- End 'blog' div -->
</div> <!-- End 'wrap' div -->
</body>
</html>