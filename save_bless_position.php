<?php
session_start ();
If ($_SERVER ['REQUEST_METHOD'] == "POST") {
	// validate
	if (! is_null ( $_POST ["id"] ) && ! is_null ( $_SESSION ['weiboUserId'] )) {
		$top = (int)str_replace("px","",$_POST ["top"]);
		$left = (int)str_replace("px","",$_POST ["left"]);
		$mysql = new SaeMysql ();
		$sql = "UPDATE `Benediction` set `Top` = '".$top."', `Left`='".$left."' where `BenedictionId` = ".$_POST ["id"]."";
		$mysql->runSql($sql);
		if ($mysql->errno () != 0) {
			die ( "Error:" . $mysql->errmsg () );
		}
		$mysql->closeDb ();
	} else if (is_null ( $_SESSION ['weiboUserId'] )) {
		die ( "You need login to create benedication!" );
	} else
		die ( "Unknow error, check login or content you input!" );
} else {
	// do nothing
	die ( "Error: do not support http-get!" );
}
?>