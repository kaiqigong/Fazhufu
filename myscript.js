var user;
var lastError;
var FazhufuJS = {
	ajaxForm : function(form, callback, format) {
		$.ajax({
			url : form.action,
			type : form.method,
			dataType : format,
			data : $(form).serialize(),
			success : callback,
			error : popupError
		});
	},

	jsonPost : function(url, callback, data) {
		$.ajax({
			url : url,
			type : "post",
			dataType : "html",
			data : data,
			success : callback,
			error : popupError
		});
	},
};

function stopUpload(success){
	$("#upload_status").text("");
    if (success === false){
    	$("#upload_status").text("update failed");
    }
    else {
    	$("#imgurlHiddenField").val(success);
    	$("#upload_status").html("<img src='"+success+"'></img>");
    }
    return true;   
}

function popupError(e) {
	lastError = e;
}

function getURLParameter(name) {
	return decodeURI((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [
			, null ])[1]);
}

function generateBless(data) {
	if (typeof (data) == "object") {
		if(data.ImageUrl!=null){
			$("#bdwall").append(
					"<div id='draggable-user-"
							+ data.BenedictionId
							+ "' class='"
							+ data.CssClass
							+ "'><img class='user-tile-img' src='"
							+data.ImageUrl+"' ></img><br />"
							+ data.Content
							+ "<br/>"
							+ "<div class='user-tile'><img id='draggable-user-avator-"
							+ data.BenedictionId + "' src='"
							+ data.PhotoUrl
							+ "' class='user-tile-avator' alt='"
							+ data.WeiboName + "' wb_screen_name='"
							+ data.WeiboName + "' ></img>"
							+ "<a class='user-tile-username'>"
							+ data.WeiboName + "</a></div>" 
							+ "<div id='blessRightBottomConer" 
							+ data.BenedictionId 
							+ "' class='bless-right-bottom-coner'><a id='blessRightBottomSavePositionIcon"
							+ data.BenedictionId+"'><img src='Images/disk.png'></img></a></div></div>");
	
		}else{
			$("#bdwall").append(
							"<div id='draggable-user-"
									+ data.BenedictionId
									+ "' class='"
									+ data.CssClass
									+ "'>"
									+ data.Content
									+ "<br/>"
									+ "<div class='user-tile'><img id='draggable-user-avator-"
									+ data.BenedictionId + "' src='"
									+ data.PhotoUrl
									+ "' class='user-tile-avator' alt='"
									+ data.WeiboName + "' wb_screen_name='"
									+ data.WeiboName + "' ></img>"
									+ "<a class='user-tile-username'>"
									+ data.WeiboName + "</a></div>" 
									+ "<div id='blessRightBottomConer" 
									+ data.BenedictionId 
									+ "' class='bless-right-bottom-coner'><a id='blessRightBottomSavePositionIcon"
									+ data.BenedictionId+"'><img src='Images/disk.png'></img></a></div></div>");
		}
		$("#draggable-user-" + data.BenedictionId).draggable({
			scroll : true,
			containment : "#bdwall"
		});
		$("#draggable-user-" + data.BenedictionId).css("top", data.Top + "px");
		$("#draggable-user-" + data.BenedictionId)
				.css("left", data.Left + "px");
		WB2.anyWhere(function(W) {
			W.widget.hoverCard({
				id : "draggable-user-avator-" + data.BenedictionId
			});
		});
		$("#draggable-user-" + data.BenedictionId).mouseover(function(){
			$("#blessRightBottomConer"+data.BenedictionId).css('visibility','visible');
		});
		$("#draggable-user-" + data.BenedictionId).mouseout(function(){
			$("#blessRightBottomConer"+data.BenedictionId).css('visibility','hidden');
		});
		$("#blessRightBottomSavePositionIcon" + data.BenedictionId).click(function(){
			$.ajax({
				type : "POST",
				url : 'save_bless_position.php',
				data : {
					id:data.BenedictionId,
					top:$("#draggable-user-" + data.BenedictionId).css("top"),
					left:$("#draggable-user-" + data.BenedictionId).css("left")
					},
				dataType : "json",
				error : popupError
			});
		});
	} else if (typeof (data) == "string") {
		generateBless(JSON.parse(data));
	} else {
		alert(typeof (data));
	}
}

$(function() {

	/** ******* Weibo login****** */
	WB2.anyWhere(function(W) {
		W.widget.connectButton({
			id : "wb_connect_btn",
			type : "3,2",
			callback : {
				login : function(o) {
					user = o;
					FazhufuJS.jsonPost("u_c_wb_user.php", new function() {
					}, o);
				},
				logout : function() { // 锟剿筹拷锟斤拷幕氐锟斤拷锟斤拷锟� user = null;
				},
				error : function(e) {
					user = null;
					alert(e);
				}
			}
		});
	});
	/** ****End of Weibo login**** */
	
	/** ******* Weibo friend card****** */
	WB2.anyWhere(function(W){
	 
	 W.widget.hoverCard({
	        id: "main-content",
	        search: true
		}); 
	});
	/** ******* Weibo friend card****** */
	
	/** ******* Weibo friend selector****** */
	function weiboFriendSelected(data){
	    //do something.
		user=data;
		FazhufuJS.jsonPost("update_user.php", new function() {
		}, data[0]);
		$("#owner").val(data[0].screen_name);
	}
	WB2.anyWhere(function(W){
	    W.widget.selector({
		'id' : "beginSelectFriend",	
		'limit' : 1,
		'callback' : weiboFriendSelected
	    });	
	});
	/** ******* Weibo friend selector****** */
	
	/* Button */
	$("input[type=submit],input[type=button], button").button().click(
			function(event) {
				event.preventDefault();
			});
	$("#createBtn").button().click(function(event) {
		event.preventDefault();
	});
	/* Button */

	/* Draggable */
	$("[id^='draggable']").draggable({
		scroll : true,
		containment : "#bdwall"
	});
	/* Draggable */

	/* Dialog */
	var content = $("#content"), top = $("#top"), left = $("#left"),description=$("#description"), allFields = $(
			[]).add(content).add(top).add(left), tips = $(".validateTips");

	function updateTips(t) {
		tips.text(t).addClass("ui-state-highlight");
		setTimeout(function() {
			tips.removeClass("ui-state-highlight", 1500);
		}, 500);
	}

	function checkLength(o, n, min, max) {
		if (o.val().length > max || o.val().length < min) {
			o.addClass("ui-state-error");
			updateTips("Length of " + n + " must be between " + min + " and "
					+ max + ".");
			return false;
		} else {
			return true;
		}
	}

	function checkRange(o, min, max, t) {
		if (parseInt(o.val()) < min) {
			o.addClass("ui-state-error");
			updateTips(t);
			return false;
		}
		if (parseInt(o.val()) > max) {
			o.addClass("ui-state-error");
			updateTips(t);
			return false;
		}
		return true;
	}
	function checkRegexp(o, regexp, n) {
		if (!(regexp.test(o.val()))) {
			o.addClass("ui-state-error");
			updateTips(n);
			return false;
		} else {
			return true;
		}
	}

	$("#createBlessDialog").dialog(
			{
				autoOpen : false,
				height : 480,
				width : 640,
				modal : true,
				buttons : {
					"Create a Bless" : function() {
						var bValid = true;
						allFields.removeClass("ui-state-error");

						bValid = bValid
								&& checkLength(content, "content", 1, 100);
						bValid = bValid
								&& checkRange(left, 0, 860,
										"Left should be between 0~860");
						bValid = bValid
								&& checkRange(top, 0, 540,
										"Top should be between 0~540");
						$("#wallIdHiddenField").val(getURLParameter("id"));
						// ajax
						if (bValid) {

							$.ajax({
								type : "POST",
								url : 'create_bless_ajax.php',
								data : $('#createBlessForm').serialize(),
								dataType : "json",
								success : generateBless,
								error : popupError
							});
							$(this).dialog("close");
						}
					},
				},
				close : function() {
				}
			});
	$("#createWallDialog").dialog({
		autoOpen : false,
		height : 450,
		width : 900,
		modal : true,
		buttons : {
			"Create a Wall" : function() {
				var bValid = true;
				allFields.removeClass("ui-state-error");

				bValid = bValid && checkLength(description, "description", 6, 100);

				// ajax
				if (bValid) {

					$.ajax({
						type : "POST",
						url : 'create_wall_ajax.php',
						data : $('#createWallForm').serialize(),
						dataType : "json",
						success : jumpToWall,
						error : popupError
					});
					$(this).dialog("close");
				}
			},
		},
		close : function() {
		}
	});
	$("#beginCreateBlessBtn").button().click(function() {
		$("#createBlessDialog").dialog("open");
	});

	$("#beginCreateWallBtn").button().click(function() {
		$("#createWallDialog").dialog("open");
	});
	/* Dialog */

	/* Get All Blesses */
	
	function jumpToWall(wallId){
		$(window.location).attr('href', '?id='+wallId);
	}
	
	if (getURLParameter("id") != "null") {
		$.ajax({
			type : "GET",
			url : 'get_blesses.php',
			data : {
				"id" : getURLParameter("id")
			},
			dataType : "json",
			success : function(blesses) {
				for (x in blesses) {
					generateBless(blesses[x]);
				}
			},
			error : popupError
		});
	}
	/* Get All Blesses */
	
	/* Upload Image */
	$("#uploadForm").submit(function(){
	});
	$("#upload_image").change(function(){
		if($("#upload_image").val() != '')
    	{
	    	var size = this.files[0].size;
	    	if(size<1048576)
	    		{
		        	$("#upload_status").text("loading...");
		        	$("#uploadForm").submit();
	        	}else{
	        		$("#upload_status").text("Image size should be no more than 1M");
	        	}
	    	}
	});
	
	/* Upload Image */
	
	$("#content").htmlarea(
			{
				toolbar : [ "bold", "italic", "underline", "|", "h1", "h2",
						"h3", "h4", "h5", "h6", "|", "link", "unlink" ]
			});
});
