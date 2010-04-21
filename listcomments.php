<script type='text/javascript'>
function validateForm(form)
{
	var comment = form.comment.value.trim();
	var poster = form.poster.value.trim();
	
	if ((comment != "") && (poster != ""))
		return true;
	
	else
	{
		var redBorder = "2px solid red";
		
		if (comment == "")
			form.comment.style.border = redBorder;
			
		if (poster == "")
			form.poster.style.border = redBorder;
	}
	
	return false;
}
</script>

<?php
echo "<a name='comments'></a>\n";
//echo "<h1>Comments</h1>\n";
echo "<form onSubmit='return validateForm(this);' method='post' action='postcomment.php?pid=$post_id'>\n";
//echo "<input type='hidden' name='reply' value='0' />";
echo "<p style='text-align:center;'>";
echo "<textarea class='comment' name='comment' rows=4></textarea></p>";
echo "<p style='text-align:center;'>";
echo "<span style='font-weight:bold; color:dimgray; font-size: 0.8em;'>Your Name:&nbsp;&nbsp;</span>";
echo "<input name='poster' type='text'>";
echo "</p>";
echo "<input style='display:block; margin-left:auto; margin-right:auto;' type='submit' value='  Post Comment  ' />";
echo "\n</form>\n<br />\n";

$cursor = $db->comments->find();
$num_comments = $db->comments->count();
$count = 0;
$display_count = 0;
$comments = array();

foreach ($cursor as $id => $value)
{
	$comments[$count++] = $value;
}

for ($i = ($count - 1); $i >= 0; $i--)
{
	$comment = $comments[$i];
	$comment_id = $comment['_id']->__toString();
	$poster = $comment['poster'];
	$date = $comment['date'];
	$time = $comment['time'];
	$message = $comment['comment'];
	
	if ($comment['post'] == new MongoID($post_id))
	{
		echo "<a name='comment$comment_count'></a>";
		echo "<fieldset class='comment'>\n";
		echo "<span style='font-weight:bold;'>$poster said...</span>\n";
		echo "<blockquote>$message</blockquote>\n";
		
		echo "<span class='post-info'>Posted on $date at $time.</span>\n";
		echo "<span style='float:right;'>";
		echo "<input onClick=\"if (confirm('Are you sure you want to delete this comment?')) ";
		echo "parent.location = 'deletecomment.php?id=$comment_id&post=$post_id';\" type='button' value='Delete' />";
		echo "</span>\n";
		echo "</fieldset>\n";
		
		$display_count++;
	}
}

if ($display_count > 0) echo "<fieldset class='comment'></fieldset>";

echo "<br />\n";
?>