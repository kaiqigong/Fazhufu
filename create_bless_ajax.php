<?php
session_start ();
If ($_SERVER ['REQUEST_METHOD'] == "POST") {
	// validate
	if (! is_null ( $_POST ["content"] ) && ! is_null ( $_SESSION ['weiboUserId'] )) {
		$top = $_POST ["top"];
		$left = $_POST ["left"];
		$content = $_POST ["content"];
		$wallId = $_POST["wallId"];
		$imgUrl=$_POST["imgurl"];
		$weiboUserId = (int)$_SESSION['weiboUserId'];
		$votes = 0;
		$cssClass = $_POST["cssClass"];
		$mysql = new SaeMysql ();
		$sql = "INSERT INTO `Benediction`(`WallId`, `Creator`, `Content`, `Top`, `Left`, `InitialVotes`,`CssClass`,`ImageUrl`) VALUES (" . $wallId . ", '" . $weiboUserId . "', '" . $content . "' ," . $top . "," . $left . "," . $votes . " ,'" . $cssClass . "','".$imgUrl."')";
		$mysql->runSql($sql);
		$sql = "select * from `Benediction`  inner join `UserProfile` on `Creator` = `WeiboId` order by `BenedictionId` desc limit 1";
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