<?php

function text_callback ( $args, $post_id ) {

	$value = get_post_meta( $post_id, $args['id'], true );
	if ( $value != "" ) {
		$value = get_post_meta( $post_id, $args['id'], true );
	}else{
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$output = "<tr valign='top'> \n".
		" <th scope='row'> " . $args['name'] . " </th> \n" .
		" <td><input type='text' class='regular-text' id='" . $args['id'] . "'" .
		" name='" . $args['id'] . "' value='" .  $value   . "' />\n" .
		" <label for='" . $name . "'> " . $args['desc'] . "</label>" .
		"</td></tr>";

	return $output;
}


/**
 * Updates when saving post
 *
 */
function iwp_post_save( $post_id ) {

	if ( ! isset( $_POST['post_type']) || 'download' !== $_POST['post_type'] ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;


	//Loop here if you implement more fields.
	//$fields = iwp_fields();
	//foreach ( $fields as $field )

	if ( get_post( $post_id )->ices_post_account != $_POST["iwp_ices_post_account"] ) {
		$api = iwp\iwp_api::get_instance();
		$account = $api->Accounts->get( $_POST["iwp_ices_post_account"] );

		if ( isset( $account->name ) )
			update_post_meta( $post_id, 'ices_post_account', $account->name );
		else
			iwp_errors::iwpw_errors_add( 'posts', __( 'Missing or invalid account: ' . $_POST["iwp_ices_user_account"], 'iwp' ) );

		//Show errors
		iwp_admin_notices_post();
	}

}
add_action( 'save_post', 'iwp_post_save' );


/**
 * Display sidebar metabox in saving post
 *
 */
function iwp_print_meta_box ( $post ) {

	if ( get_post_type( $post->ID ) != 'download' ) return;

	?>
	<div class="wrap">
		<div id="tab_container">
			<table class="form-table">
				<?php
					$fields = iwp_fields();
					foreach ($fields as $field) 
						echo text_callback( $field, $post->ID );
				?>
			</table>
		</div>
		<!-- #tab_container-->
	</div>
	<!-- .wrap -->
	<?php
}


/**
 *
 * Adds metabox to post edit page for integralCES to be configured
 */ 
function iwp_show_post_fields( $post) { 

	add_meta_box( $post->ID, __( "IntegralCES configuration section", 'iwp'), "iwp_print_meta_box", 'download', 'normal', 'high');

}
add_action( 'submitpost_box', 'iwp_show_post_fields' );


/**
 *
 * @retun array To be included in post edit fields
 */
function iwp_fields () {

	$ices_gateway_settings = array(
		array(
			'id' => 'iwp_ices_post_account',
			'name' => __( 'Post ices account', 'iwp' ),
			'desc' => __( 'IntegralCES account that will receive fundraising.', 'iwp' ),
			'type' => 'text',
			'size' => 'regular'
		),
	);

	return $ices_gateway_settings;

}


/**
 *
 * Reads errors and display any existing one for admin users, after cleans stack
 *
 * @uses  mwpw_errors class
 */
function iwp_admin_notices_post() {

	echo "<br>" . iwp\iwp_errors::iwp_errors_get( 'posts' );
	iwp\iwp_errors::iwp_errors_clean( 'posts' );

}
