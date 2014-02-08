<?php
session_start ();
If ($_SERVER ['REQUEST_METHOD'] == "POST") {
	// validate
	if (! is_null ( $_POST ["owner"] ) && ! is_null ( $_SESSION ['weiboUserId'] )) {
		$owner = $_POST ["owner"];
		$description = $_POST ["description"];
		$signature = $_POST ["signature"];
		$weiboUserId = $_SESSION ['weiboUserId'];
		$mysql = new SaeMysql ();
		$createTime = time ();
		$sql = "select `WeiboId` from UserProfile where WeiboName='" . $owner . "' limit 1";
		$ownerWeiboId = $mysql->getVar ( $sql );
		$sql = "INSERT INTO `BirthdayWall`(`BirthdayOwner`, `WallCreator`, `CreateTime`, `Description`, `Signature`) VALUES (" . $ownerWeiboId . ", " . $weiboUserId . ", " . $createTime . " ,'" . $description . "','" . $signature . "')";
		$mysql->runSql ( $sql );
		$sql = "select `WallId` from `BirthdayWall` order by `WallId` desc limit 1";
		$wallId = $mysql->getVar ( $sql );
		if ($mysql->errno () != 0) {
			die ( "Error:" . $mysql->errmsg () );
		}
		echo json_encode ( $wallId );
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