<?php
session_start ();
If ($_SERVER ['REQUEST_METHOD'] == "POST") {
	// validate
	if (! is_null ( $_POST ["blessId"] ) && ! is_null ( $_SESSION ['weiboUserId'] )) {
		$weiboUserId = $_SESSION ['weiboUserId'];
		$mysql = new SaeMysql ();
		$sql = "INSERT INTO `Benediction`(`WallId`, `Creator`, `Content`, `Top`, `Left`, `InitialVotes`,`CssClass`) VALUES (" . $wallId . ", '" . $weiboUserId . "', '" . $content . "' ," . $top . "," . $left . "," . $votes . " ,'" . $cssClass . "')";
		$mysql->runSql($sql);
		$sql = "select * from `Benediction` order by `BenedictionId` desc limit 1";
		$newRow = $mysql->getLine ( $sql );
		if ($mysql->errno () != 0) {
			die ( "Error:" . $mysql->errmsg () );
		}
		echo json_encode ( $newRow );
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