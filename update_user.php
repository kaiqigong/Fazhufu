<?php
session_start ();
if (! is_null ( $_REQUEST ["id"] ) && ! is_null ( $_REQUEST ["screen_name"] )) {
	// update or create
	require_once ('saetv2.ex.class.php');
	$appKey = "3634013707";
	$appSecret = "86959e8efbf85fc2a1fc2bee942e8db3";
	echo $_REQUEST ["id"];
	echo $_SESSION["oauth_token"];
	//$ch = curl_init("https://api.weibo.com/2/users/show.json?source=".$appKey."&uid=".$_REQUEST ["id"]."&access_token=".$_SESSION["oauth_token"]);
	//$msg=curl_exec($ch);
	//curl_close($ch);
	$c = new SaeTClientV2 ( $appKey, $appSecret,$_SESSION["oauth_token"],$_SESSION["oauth_refresh_token"] );
	$u_id = (int)$_REQUEST ["id"];
	$msg = $c->show_user_by_id($u_id);
	echo json_encode ( $msg );
	if ($msg === false || $msg === null){
		die ("Error occured");
	}
	if (isset($msg['error_code']) && isset($msg['error'])){
		die ('Error_code: '.$msg['error_code'].';  Error: '.$msg['error'] );
	}
	$mysql = new SaeMysql ();
	$sql = "select count(*) from `UserProfile` where `WeiboId`=" . $_REQUEST ["id"];
	$count = $mysql->getVar ( $sql );
	if ($count == 0) {
		$sql = "insert into UserProfile (WeiboId,WeiboName,PhotoUrl,HDAvator) values (" . $_REQUEST ["id"] . ", '" . $msg ["screen_name"] . "', '" . $msg["profile_image_url"] . "','".$msg ["avatar_hd"]."' )";
	} else {
		$sql = "update UserProfile set WeiboName='" . $msg ["screen_name"] . "', PhotoUrl='" . $msg ["profile_image_url"]. "', HDAvator='" . $msg ["avatar_hd"] . "' where WeiboId= " . $_REQUEST ["id"];
	}
	$mysql->runSql ( $sql );
	if ($mysql->errno () != 0) {
		die ( "Error:" . $mysql->errmsg () );
	}
	$mysql->closeDb ();
}else{
	die ("id or screen_name missing");
}
?>