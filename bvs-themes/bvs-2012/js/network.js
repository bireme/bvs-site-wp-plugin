// Show Hide VHL Network

var vector = [ 'cl_bvs', 'sub_bvs', 'cl_cvsp', 'cl_scielo', 'sub_scielo' ];

$(document).ready(function(){
	$(".closed", this).click(function(){
		if(jQuery.inArray($(this).next().attr("id"), vector) != -1){
			$(this).next().toggle('slow');
			return false;
		}
	});
});
