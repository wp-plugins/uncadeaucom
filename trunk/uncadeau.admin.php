<?php
if(isset($_POST["uncadeau_hidden"])){
	extract($_POST);
	update_option('uncadeau_partnerID',$uncadeau_partnerID);
	update_option('uncadeau_jquery',isset($uncadeau_jquery));
	?>
	<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
	<?php
}else{
	$uncadeau_api = get_option("uncadeau_partnerID");
	$uncadeau_jquery = get_option("uncadeau_jquery",true);
}
?>

<div class="wrap">
	<h2>Configuration du module UnCadeau.com</h2>			
			<form name="uncadeau_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="uncadeau_hidden" value="Y">
				<p>
					<label>Votre ID :</label>
					<input type="text" name="uncadeau_partnerID" value="<?php echo $uncadeau_partnerID; ?>" size="40">
					<em>Vous pouvez la trouver sur votre espace partner</em>
				</p>
				<p>
					<label>Importer jQuery ?</label>
					<input type="checkbox" name="uncadeau_jquery" <?php if($uncadeau_jquery){?> checked="checked" <?php } ?>/>
				</p>
				
				<p class="submit">
					<input type="submit" name="Submit" value="Sauvegarder" />
				</p>
			</form>
</div>