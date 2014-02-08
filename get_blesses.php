<?php
session_start ();
if (! is_null ( $_GET ["id"] )) {
	$mysql = new SaeMysql ();
	$sql = "select * from `Benediction` inner join `UserProfile` on `Creator` = `WeiboId` where `WallId`=" . ( int ) $_GET ["id"] . " order by `BenedictionId` desc";
	$rows = $mysql->getData ( $sql );
	if ($mysql->errno () != 0) {
		die ( "Error:" . $mysql->errmsg () );
	}
	echo json_encode ( $rows );
	$mysql->closeDb ();
}
?>