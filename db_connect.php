<?php
try
{
	$connection = new Mongo();
	$db = $connection->blogdb->blogs;
}

catch (MongoConnectionException $e)
{
	error_log($e->getMessage());
}
?>