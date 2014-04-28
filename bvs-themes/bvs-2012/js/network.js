// Show Hide VHL Network

var imgpath = network_script_vars.imgpath;
var image  = null;

function network(){
        $(".vhl-network").show();
        $(".closed").attr("href", "javascript:void(0)");
	$(".closed").next().hide();
	$(".closed").css({ "background": "url("+ imgpath +"icon_plus.gif) no-repeat scroll 0 5px", "padding-left": "12px" });
	$(".closed").click(function(){
		$(this).next().css("display") == "block" ? image = imgpath + "icon_plus.gif" : image = imgpath + "icon_minus.gif";
		$(this).css({ "background": "url("+ image +") no-repeat scroll 0 5px", "padding-left": "12px" });
		if(jQuery.inArray($(this).next().attr("id"), network_script_vars.group) != -1){
			$(this).next().fadeToggle("slow");
			return false;
		}
	});
}
