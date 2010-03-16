<?php
if ( !class_exists('myCustomFields') ) {

	class myCustomFields {
		/**
		* @var  string  $prefix  The prefix for storing custom fields in the postmeta table
		*/
		var $prefix = '_uncadeau_';
		/**
		* @var  array  $customFields  Defines the custom fields available
		*/
		var $customFields =	array(
			array(
				"name"			=> "keywords",
				"title"			=> "Mots clefs",
				"description"	=> "",
				"type"			=> "text",
				"scope"			=>	array( "post" ),
				"capability"	=> "edit_posts"
			)
		);
		/**
		* PHP 4 Compatible Constructor
		*/
		function myCustomFields() { $this->__construct(); }
		/**
		* PHP 5 Constructor
		*/
		function __construct() {
			add_action( 'admin_menu', array( &$this, 'createCustomFields' ) );
			add_action( 'save_post', array( &$this, 'saveCustomFields' ), 1, 2 );
		}
		/**
		* Create the new Custom Fields meta box
		*/
		function createCustomFields() {
			if ( function_exists( 'add_meta_box' ) ) {
				add_meta_box( 'my-custom-fields', 'UnCadeau.com', array( &$this, 'displayCustomFields' ), 'page', 'normal', 'high' );
				add_meta_box( 'my-custom-fields', 'UnCadeau.com', array( &$this, 'displayCustomFields' ), 'post', 'normal', 'high' );
			}
		}
		/**
		* Display the new Custom Fields meta box
		*/
		function displayCustomFields() {
			global $post;
			?>
			<div class="form-wrap">
				<?php
				wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );
				foreach ( $this->customFields as $customField ) {
					// Check scope
					$scope = $customField[ 'scope' ];
					$output = false;
					foreach ( $scope as $scopeItem ) {
						switch ( $scopeItem ) {
							case "post": {
								// Output on any post screen
								if ( basename( $_SERVER['SCRIPT_FILENAME'] )=="post-new.php" || $post->post_type=="post" )
									$output = true;
								break;
							}
							case "page": {
								// Output on any page screen
								if ( basename( $_SERVER['SCRIPT_FILENAME'] )=="page-new.php" || $post->post_type=="page" )
									$output = true;
								break;
							}
						}
						if ( $output ) break;
					}
					// Check capability
					if ( !current_user_can( $customField['capability'], $post->ID ) )
						$output = false;
					// Output if allowed
					if ( $output ) { ?>
						<div class="form-field form-required">
							<?php
							// Plain text field
							echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
							echo '<input type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
							?>
							<p>Si vous souhaitez afficher des cadeaux à la fin de votre article entrez les mots clef qui permettront de sélectionner les produits</p>
							<?php if ( $customField[ 'description' ] ) echo '<p>' . $customField[ 'description' ] . '</p>'; ?>
						</div>
					<?php
					}
				} ?>
			</div>
			<?php
		}
		/**
		* Save the new Custom Fields values
		*/
		function saveCustomFields( $post_id, $post ) {
			if ( !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
				return;
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
			if ( $post->post_type != 'page' && $post->post_type != 'post' )
				return;
			foreach ( $this->customFields as $customField ) {
				if ( current_user_can( $customField['capability'], $post_id ) ) {
					if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
						update_post_meta( $post_id, $this->prefix . $customField[ 'name' ], $_POST[ $this->prefix . $customField['name'] ] );
					} else {
						delete_post_meta( $post_id, $this->prefix . $customField[ 'name' ] );
					}
				}
			}
		}

	} // End Class

} // End if class exists statement

// Instantiate the class
if ( class_exists('myCustomFields') ) {
	$myCustomFields_var = new myCustomFields();
}
?>