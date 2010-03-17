<?php
/*
Plugin Name: UnCadeau.com
Plugin URI: http://uncadeau.com
Description: Permet d'afficher une liste d'idée cadeau en dessou d'un article
Version: 1.0
Author: Jonathan Boyer
Author URI: http://www.grafikart.fr
*/
 
$pluginDir = WP_PLUGIN_DIR.'/'.plugin_basename(dirname(__FILE__)).'/';
$pluginURL = WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)).'/';

/**
 *      Création de champ perso
 **/
include("uncadeau.fields.php");

/**
 *	Add CSS/Js into the head
 **/
add_action('wp_head', 'uncadeau_head');
function uncadeau_head(){
	if(is_single()){
		global $post;
		$files = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/uncadeau/';
		?>
		<?php $uncadeau = get_post_meta($post->ID,"_uncadeau_keywords"); if(!empty($uncadeau)): ?>
			<?php if(get_option("uncadeau_jquery",true)): ?>
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
			<?php endif; ?>
			<script type="text/javascript" src="<?php echo $files; ?>js/uncadeau.js"></script>
			<link rel="stylesheet" type="text/css" href="<?php echo $files; ?>style.css" media="screen" /> 

			<script type="text/javascript">
				$jq = jQuery.noConflict();
				$jq(function(){
					uncadeauWP.getLinked(<?php echo get_option("uncadeau_partnerID","0"); ?>,"<?php echo $uncadeau[0]; ?>");
				});
			</script>
		<?php
		endif;
	}
}

/*
 * 	Admin actions
 **/
add_action('admin_menu', 'uncadeau_admin_actions'); 
function uncadeau_admin() {  
    include('uncadeau.admin.php');  
}  
   
function uncadeau_admin_actions() {  
    add_options_page(__("UnCadeau.com","uncadeau"),__("UnCadeau.com","uncadeau"), 1,__("UnCadeau.com","uncadeau"), "uncadeau_admin");  
}  

/**
 *	Ajout du contenue à la fin de l'article
 **/
add_filter('the_content', 'uncadeau_content', 8);
function uncadeau_content($content){
	global $post;
	if(is_single()){
		$content.='<div id="wpuncadeauCom"></div>';
	}
	return $content;
}
?>