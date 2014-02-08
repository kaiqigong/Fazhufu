<?php session_start();?>
<!DOCTYPE html>
<html xmlns:wb=“http://open.weibo.com/wb”>
<head>
<meta charset="utf-8">
<meta property="wb:webmaster" content="15e494bd38f5ed41" />
<script
	src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=3634013707"
	type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet"
	href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="http://malsup.github.io/jquery.form.js"></script>
<script type="text/javascript" src="myscript.js"></script>
<link href="mystyles.css" type="text/css" rel="stylesheet" />
<link rel="Stylesheet" type="text/css" href="style/jHtmlArea.css" />
<script type="text/javascript" src="scripts/jHtmlArea-0.7.5.min.js"></script>
<title>Fa Zhu Fu</title>
</head>
<body>
	<div id="createBlessDialog" title="Create new Bless">
		<p class="validateTips">All form fields are required.</p>
		<form id="createBlessForm" action="create_bless_ajax.php"
			method="post">
			<fieldset>
				<label for="content">Content</label>
				<div style="overflow: hidden; text-align: center;">
					<textarea style="width: 400px;" name="content" id="content"></textarea>
				</div>
				<input type="hidden" name="wallId" id="wallIdHiddenField" /> 
				<input type="hidden" name="imgurl" id="imgurlHiddenField" /> 
				样式1
				<input type="radio" checked="checked" name="cssClass" value="normal1" />
				样式2
				<input type="radio" name="cssClass" value="normal2" />
				样式3
				<input type="radio" name="cssClass" value="normal3" />
				<label for="top">Top</label>
				<input type="number" name="top" id="top" value="0"
					class="text ui-widget-content ui-corner-all" /> <label for="left">Left</label>
				<input type="number" name="left" id="left" value="0"
					class="text ui-widget-content ui-corner-all" />
			</fieldset>
		</form>
		<form id="uploadForm" action="upload_file.php" method="post" enctype="multipart/form-data" target="upload_target" >
			<label for="upload_image">Image(&lt;1M):</label>
            <input name="file" type="file" id="upload_image" accept="image/*"/>
            <div id="loading" style="visibility:hidden;">loading...</div>
            <div id="upload_status"></div>
		</form>
		<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe> 
	</div>
	<div id="createWallDialog" title="Create New BD Wall">
		<p class="validateTips">All form fields are required.</p>
		<form id="createWallForm" action="create_wall_ajax.php" method="post">
			<fieldset>
				<input type="button" id="beginSelectFriend" value="选择一个微博好友" /> <input
					id="owner" name="owner"
					class="text ui-widget-content ui-corner-all" /> <label
					for="Description">Description</label>
				<div style="overflow: hidden; text-align: center;">
					<textarea style="width: 400px;" name="description" id="description"></textarea>
				</div>
			</fieldset>
		</form>
	</div>
	<div id="header">
		<div id="header-content">
		<a id="header-logo" href="/">发祝福啦</a>
			<div>
				<div id="wb_connect_btn"></div>
			</div>
		</div>
		<wb:share-button appkey="5RgQ9d" addition="number" type="button" language="zh_cn" default_text="快来给某某某发祝福吧" ralateUid="1948101653" pic="http%3A%2F%2Fww2.sinaimg.cn%2Fsquare%2F741dac15jw1e97sj9qmw6j2028028743.jpg"></wb:share-button>
	</div>

	<div id="main">
		<div id="main-content">
			<div id="main-benediction">
			<?php
			if (! is_null ( $_GET ["id"] )) {
				$wallId=$_GET ["id"];
				$mysql = new SaeMysql ();
				$sql = "select Wall.WallId, Wall.CreateTime, Wall.Description, Wall.Signature, Wall.ImageUrl, 
						Owner.WeiboName as OwnerName, Owner.HDAvator as OwnerAvator, 
						Creator.WeiboName as CreatorName, Creator.PhotoUrl as CreatorAvator
						from `BirthdayWall` as Wall 
						join `UserProfile` as Owner on Wall.BirthdayOwner = Owner.WeiboId 
						join `UserProfile` as Creator on Wall.WallCreator = Creator.WeiboId
						where Wall.WallId = ".$wallId;
				$newRow = $mysql->getLine ( $sql );
				if ($mysql->errno () != 0) {
					die ( "Error:" . $mysql->errmsg () );//append log
				}
				if($newRow===FALSE)
				{
				}else {
					echo "<img src='".$newRow["OwnerAvator"]."' id='owner-avator'/>
		                  <div style='float: left;margin-top: 20px;margin-left: 15px;width: 740px;height:160px;'>
		                  <div id='owner-name'>To: @".$newRow["OwnerName"]."</div>
						  <div id='main-benediction-description'>".$newRow["Description"]."</div>
						  <div style='float: left;margin-left: 500px;clear:both;'>From: @".$newRow["CreatorName"]."</div>
						  <img src='".$newRow["CreatorAvator"]."' style='float: left;margin-left: 540px;width:50px;height:50px'/></div>";
				}
				echo "<input
				id='beginCreateBlessBtn' type='button' style='float: right'
						value='发祝福' />";
				$mysql->closeDb ();
			}else{
				echo("<input id='beginCreateWallBtn' type='button' value='创建生日墙' />");
			}
			?>
				<div class="clear"></div>
			</div>
			<?php
			if ($_GET ["id"] == "1") {
				echo "<div id='bdwall'>
				<div id='draggable6' class='ui-widget-content'
					style='top: 150px; left: 325px; width: 300px; height: 220px'>
					<img alt='亲爱的老婆' height='200px'
						src='http://fmn.rrimg.com/fmn060/20130309/1815/original_rf16_30fa00000fe9118c.jpg' />
					<div style='font-size: 19px'>This is my girlfriend, happy birthday!</div>
				</div></div>
			";
			}elseif(! is_null ( $_GET ["id"] )){
				echo "<div id='bdwall'></div>";
			}else {
				$mysql = new SaeMysql ();
				$sql = "select Wall.WallId, Wall.CreateTime, Wall.Description, Wall.Signature, Wall.ImageUrl, 
						Owner.WeiboName as OwnerName, Owner.PhotoUrl as OwnerAvator, Owner.WeiboId as OwnerId,Creator.WeiboId as CreatorId,  
						Creator.WeiboName as CreatorName, Creator.PhotoUrl as CreatorAvator
						from `BirthdayWall` as Wall 
						join `UserProfile` as Owner on Wall.BirthdayOwner = Owner.WeiboId 
						join `UserProfile` as Creator on Wall.WallCreator = Creator.WeiboId
						order by Wall.CreateTime desc";
				$rows = $mysql->getData ( $sql );
				if ($mysql->errno () != 0) {
					die ( "Error:" . $mysql->errmsg () );
				}
				if($rows===FALSE||count($rows)==0)
				{
				}else {
					foreach ($rows as $row) {
						echo "<div style='margin:10px'>
							    <img wb_user_id='".$row[CreatorId]."' href='http://weibo.com' src='".$row["CreatorAvator"]."' style='width:50px;height:50px;float:left'/>
								<a wb_user_id='".$row[CreatorId]."' href='http://weibo.com' style='float:left;margin-left:15px'>".$row["CreatorName"]."</a><br />
							  	<div style='float:left;margin-left:15px'>为<a wb_user_id='".$row[OwnerId]."' href='http://weibo.com'>".$row["OwnerName"]."</a>创建了#生日墙#，</div>
							  	<a style='float:left' href='/?id=".$row["WallId"]."'>".$row["Description"]."</a>
								<div class='clear'></div>
							  </div>";
					}
				}
				$mysql->closeDb ();
			}
			?>
		</div>
	</div>

	<div id="footer">
		<div></div>
	</div>
</body>
</html>