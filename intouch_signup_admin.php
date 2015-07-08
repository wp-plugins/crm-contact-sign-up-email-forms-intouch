<?php 
	//Blank for now
	//Test comment
	$Intouch = new Intouch();
	
    if($_POST['intouch_hidden'] == 'Y') {
		 
	    //What are they trying to do?
		if($_POST['submit'] == "Clear settings"){
			//Clear their current key and tell them
			$Intouch->ClearSettings();
		}
		
		if($_POST['submit'] == "Update settings"){
			//Create new intouch instance with their entered key
			$Intouch = new Intouch($_POST['intouch_apikey']);
			$Intouch->ValidateApiKey();
			update_option('intouch_affliate_id', $_POST['intouch_affliate_id']);
			update_option('intouch_activate_powered_by', $_POST['intouch_activate_powered_by']);
		}
		 
    }
?>
<style type="text/css">
	.datagrid table { border-collapse: collapse; text-align: left; width: 100%; }
	.datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 5px solid #006699; -webkit-border-radius: 20px; -moz-border-radius: 20px; border-radius: 20px; }
	.datagrid table td, .datagrid table th { padding: 7px 10px; }
	.datagrid table thead th {
		background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );
		background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');
		background-color:#006699; color:#FFFFFF; font-size: 13px; font-weight: bold; border-left: 3px solid #0070A8; 
	} 
	.datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #00496B; border-left: 3px solid #E1EEF4;font-size: 12px;font-weight: normal; }
	.datagrid table tbody .alt td { background: #E1EEF4; color: #00496B; }
	.datagrid table tbody td:first-child { border-left: none; }
	.datagrid table tbody tr:last-child td { border-bottom: none; }
	.datagrid table tfoot td div { border-top: 1px solid #006699;background: #E1EEF4;} 
	.datagrid table tfoot td { padding: 0; font-size: 13px } 
	.datagrid table tfoot td div{ padding: 8px; }
	.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }
	.datagrid table tfoot  li { display: inline; }
	.datagrid table tfoot li a { 
		text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; 
		-moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );
		background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; 
	}
		
	.datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #006699; color: #FFFFFF; background: none; background-color:#00557F;}
	div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }
	
	.alert-box {
		color:#555;
		border-radius:10px;
		font-family:Tahoma,Geneva,Arial,sans-serif;font-size:11px;
		padding:10px;
		margin:10px;
	}
	.alert-box span {
		font-weight:bold;
		text-transform:uppercase;
	}
	.error {
		background:#ffecec;
		border:1px solid #f5aca6;
	}
	.success {
		background:#e9ffd9;
		border:1px solid #a6ca8a;
	}
	.warning {
		background:#fff8c4;
		border:1px solid #f2c779;
	}
	.notice {
		background:#e3f7fc;
		border:1px solid #8ed9f6;
	}
	
</style>

<script type="text/javascript">
		
</script>


<div class="wrap">
    <?php    echo "<h2>" . __( 'InTouch Forms Settings', 'intouch_admin' ) . "</h2>"; ?>
     
    <form name="intouch_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="intouch_hidden" value="Y">
        <?php    echo "<h4>" . __( 'InTouch Forms Authentication', 'intouch_admin' ) . "</h4>"; ?>
        <p><?php _e("Api key: " ); ?><input type="text" name="intouch_apikey" value="<?php echo $Intouch->GetApiKey(); ?>" size="50"><?php _e(" ex: abcdefg1234ssd" ); ?></p>
        
        <div class="alert-box notice"><span>Where is my Api key? </span> Your Api key is located within your account settings. Click <a href="https://www.intouchcrm.co.uk/app/account/" target="_blank">here</a> to login and then be automatically sent to your settings page. The key is on the right hand side at the bottom. </div>
        <hr />
        <?php if($Intouch->GetApiKey() != ""){ ?>
        <p><?php _e("Affiliate ID: " ); ?><input type="text" name="intouch_affliate_id" value="<?php echo $Intouch->GetAffliateID(); ?>" size="50"><?php _e("Optional: Enter your Affiliate id to create an affiliate link to intouchcrm." ); ?></p>
        <div class="alert-box notice"><span>Where is My Affiliate ID? </span> Click <a href="http://intouch.postaffiliatepro.com/affiliates/" target="_blank">here</a> to get Affiliate ID. </div>
        
        <?php 
			$account_type = $Intouch->GetAccountLevel();
			
		if(in_array($account_type , $Intouch->paidUserRoles)){ ?>
        		<p><?php _e("Activate Powered By: " ); ?><input type="checkbox" name="intouch_activate_powered_by" value="1" <?php echo (get_option('intouch_activate_powered_by') == 1)?'checked="checked"':''; ?> /></p>
        	<?php } ?>
        <?php } ?>
        <p class="submit">
        <input type="submit" name="submit" value="<?php _e('Update settings', 'intouch_admin' ) ?>" />
        <input type="submit" name="submit" value="<?php _e('Clear settings', 'intouch_admin') ?>" title="Clear your Api key and optionally remove any InTouch Form tags from your blog." onclick="return confirm('Are you sure? As this will clear your Api key.');" />
        </p>
		
		<?php if($Intouch->GetApiKey() != "")
			{ ?>
		<h2>Your current signup forms (ordered by date created)</h2>
		<div class="datagrid">
			<table>
				<thead>
				<tr>
					<th>Name</th>
					<th>Date Created</th>
					<th>Code for use in pages</th>				
				</tr>
				</thead>
				<?php 
					//Retrieve forms
					$data = $Intouch->GetSignupForms();
					
					//Alternating rows
					$alt = false;
					
					foreach($data as $item){
						
						$alt = $alt ? false : true;
						
						printf("<tr %s><td>%s</td><td>%s</td><td>[intouch_signupform uid=\"%s\"]</td></tr>", ($alt ? "class=\"alt\"" : ""), $item->{'Name'}, $item->{'DateCreated'}, $item->{'uid'});
					}
					
				?>
				
			</table>
		</div>
        <p>If you want to add new form then <a href=" https://intouchcrm.co.uk/app/settings/signupforms/" target="_blank"><?php _e('Click here' , 'intouch_admin'); ?></a></p>
		<?php 
			}
			else
				$Intouch->PrettyReturn(Response::error, "To get started, please enter your API key and click update details."); ?>
    </form>		
</div>