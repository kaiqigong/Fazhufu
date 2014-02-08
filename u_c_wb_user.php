<?php
session_start ();

If ($_SERVER ["REQUEST_METHOD"] == "POST") {
	// account/verify_credentials
	require_once ('saetv2.ex.class.php');
	$appKey = "3634013707";
	$appSecret = "86959e8efbf85fc2a1fc2bee942e8db3";
	$c = new SaeTOAuthV2 ( $appKey, $appSecret );
	$token = $c->getTokenFromJSSDK ();
	if ($token === false || $token === null) {
		echo "Error occured";
		return false;
	} else {
		$_SESSION["oauth_token"]=$token['access_token'];
		$_SESSION["oauth_refresh_token"]=$token['refresh_token'];
		// validate
		if (! is_null ( $_REQUEST ["id"] ) && ! is_null ( $_REQUEST ["name"] )) {
			$_SESSION ["weiboUserId"] = $_REQUEST ["id"];
			$_SESSION ["weiboUserName"] = $_REQUEST ["name"];
			// update or create
			$mysql = new SaeMysql ();
			$sql = "select count(*) from `UserProfile` where `WeiboId`=" .(int) $_SESSION ["weiboUserId"];
			$count = $mysql->getVar ( $sql );
			if ($count == 0) {
				$sql = "insert into UserProfile (WeiboId,WeiboName,PhotoUrl,HDAvator) values (" . $_REQUEST ["id"] . ", '" . $_REQUEST ["screen_name"] . "', '" . $_REQUEST ["profile_image_url"] . "','".$_REQUEST ["avatar_hd"]."' )";
			} else {
				$sql = "update UserProfile set WeiboName='" . $_REQUEST ["screen_name"] . "', PhotoUrl='" . $_REQUEST ["profile_image_url"]. "', HDAvator='" . $_REQUEST ["avatar_hd"] . "' where WeiboId= " . $_REQUEST ["id"];
			}
			$mysql->runSql ( $sql );
			if ($mysql->errno () != 0) {
				die ( "Error:" . $mysql->errmsg () );
			}
			$mysql->closeDb ();
		}
	}
} else {
	// do nothing
}
?>
