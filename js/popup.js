
// start the popup specific scripts
// safe to use $
jQuery(document).ready(function($) {

	$("body").on('click' , '.intouch-insert', function(){
		var uid = $("#forms").val();

		if(uid == ''){
			alert("Please Select The Form");
			return false;	
		}
		
		var tmce_ver=window.tinyMCE.majorVersion;
		var shortcode = '[intouch_signupform uid="' + uid + '" /]';
					if (tmce_ver>="4") {
				        window.tinyMCE.execCommand('mceInsertContent', false, shortcode);
			    	} else {
					window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, shortcode);
					}
					tb_remove();
					return false;
		
	});
	
});