$(document).ready(function () {
	
	$("#is_private").click(function(){
		if ($(this).prop("checked")) 
		{
			$("#password-container").show();
		}else
		{
			$("#password-container").hide();
		}
	});	
});