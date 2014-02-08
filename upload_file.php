<?php
if ($_FILES ["file"] ["error"] > 0) {
	die("Error: " . $_FILES ["file"] ["error"] . "<br />");
} elseif ((($_FILES ["file"] ["type"] == "image/gif") || ($_FILES ["file"] ["type"] == "image/png")|| ($_FILES ["file"] ["type"] == "image/jpeg") || ($_FILES ["file"] ["type"] == "image/pjpeg")) && ($_FILES ["file"] ["size"] < 1048576)) {
	echo "Upload: " . $_FILES ["file"] ["name"] . "<br />";
	echo "Type: " . $_FILES ["file"] ["type"] . "<br />";
	echo "Size: " . ($_FILES ["file"] ["size"] / 1024) . " Kb<br />";
	echo "Stored in: " . $_FILES ["file"] ["tmp_name"];
	require_once ('utilities.php');
	require_once ('seastorage.class.php');
	$guid =str_replace("-", "_", trim(guid (),"{}")) ;
	$s = new SaeStorage ();
	$ext = pathinfo ( $_FILES ["file"] ["name"], PATHINFO_EXTENSION );
	$url = $s->upload ( 'filestorage', $guid . "." . $ext, $_FILES ["file"] ["tmp_name"] );
	sleep(3);
	echo $url;
} else {
	die("Invalid file");
}
?>
<script type="text/javascript">
   window.top.window.stopUpload('<?php echo $url; ?>');
</script>