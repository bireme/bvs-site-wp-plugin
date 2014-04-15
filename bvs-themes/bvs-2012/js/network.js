// Show Hide VHL Network

var imgpath = network_script_vars.imgpath;
var image  = null;

$(document).ready(function(){
    $(".vhl-network").show();
	$(".closed").next().hide();
	$(".closed").css({ "background": "url("+ imgpath +"icon_plus.gif) no-repeat scroll 0 5px", "padding-left": "12px" });
	$(".closed", this).click(function(){
		$(this).next().css("display") == "block" ? image = imgpath + "icon_plus.gif" : image = imgpath + "icon_minus.gif";
		$(this).css({ "background": "url("+ image +") no-repeat scroll 0 5px", "padding-left": "12px" });
		if(jQuery.inArray($(this).next().attr("id"), network_script_vars.group) != -1){
			$(this).next().toggle("slow");
			return false;
		}
	});
});