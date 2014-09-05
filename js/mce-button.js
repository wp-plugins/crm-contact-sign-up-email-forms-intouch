/* global tinymce */
( function () {
	tinymce.PluginManager.add( 'intouch_mce_button', function( editor, url ) {
		
		var ed = tinymce.activeEditor;
		
		ed.addCommand("inTouchPopup", function ( a, params ) {
				var popup = params.identifier;
				
				// load thickbox
				tb_show("Insert InTouch Form", url + "/popup.php?popup=" + popup + "&width=500&height=200");
			});
		
		editor.addButton( 'intouch_mce_button', {
			title: 'InTouch Shortcode',
			icons: false,
			image:'http://intouchv6.customersreallymatter.co.uk/app/images/intouchlogo.jpg',
			onclick: function(){
								tinyMCE.activeEditor.execCommand("inTouchPopup", false, {
									title: 'InTouch Forms',
									identifier: 'intouch_forms'
								})
							}
		});
	});
})();

