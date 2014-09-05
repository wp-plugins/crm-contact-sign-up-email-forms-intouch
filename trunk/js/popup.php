<?php

$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp . '/wp-load.php' );


$popup = trim( $_GET['popup'] );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

</head>
<body>
<div id="intouch-popup">

	<div id="intouch-shortcode-wrap">
		
		<div id="intouch-sc-form-wrap">
		
			<div id="intouch-sc-form-head">
			
				<?php echo _e('InTouch Forms'); ?>
			
			</div>
			<!-- /#intouch-sc-form-head -->
			
			<form method="post" id="intouch-sc-form">
 						
				<table id="intouch-sc-form-table">
				
					<?php  $intouch = new Intouch(); 
					$forms = $intouch->GetSignupForms();
					
					
					?>
					
					<tbody>
						
                        <tr class="form-row">
							<td class="label">Select Form</td>
							<td class="field">
                            	<select name="form" id="forms">
                                	<option value=""> -- Select -- </option>
                                    <?php foreach($forms as $f){ ?>
                                    	<option value="<?php echo $f->uid; ?>"><?php echo $f->Name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>							
						</tr>
                        		
						<tr class="form-row">
							<td class="label">&nbsp;</td>
							<td class="field"><a href="#" class="button-primary intouch-insert">Insert Form</a></td>							
						</tr>
						
					</tbody>
				
				</table>
				<!-- /#intouch-sc-form-table -->
				
			</form>
			<!-- /#intouch-sc-form -->
		
		</div>
		<!-- /#intouch-sc-form-wrap -->
		
		<div class="clear"></div>
		
	</div>
	<!-- /#intouch-shortcode-wrap -->

</div>
<!-- /#intouch-popup -->

</body>
</html>