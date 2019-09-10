<?php
/**
 * Implement theme metabox.
 *
 * @package Swift Blog
 */

if ( ! function_exists( 'swift_blog_add_theme_meta_box' ) ) :

	/**
	 * Add the Meta Box
	 *
	 * @since 1.0.0
	 */
	function swift_blog_add_theme_meta_box() {

		$apply_metabox_post_types = array( 'post', 'page' );

		foreach ( $apply_metabox_post_types as $key => $type ) {
			add_meta_box(
				'swift-blog-theme-settings',
				esc_html__( 'Single Page/Post Settings', 'swift-blog' ),
				'swift_blog_render_theme_settings_metabox',
				$type
			);
		}

	}

endif;

add_action( 'add_meta_boxes', 'swift_blog_add_theme_meta_box' );

if ( ! function_exists( 'swift_blog_render_theme_settings_metabox' ) ) :

	/**
	 * Render theme settings meta box.
	 *
	 * @since 1.0.0
	 */
	function swift_blog_render_theme_settings_metabox( $post, $metabox ) {

		$post_id = $post->ID;
		$swift_blog_post_meta_value = get_post_meta($post_id);

		// Meta box nonce for verification.
		wp_nonce_field( basename( __FILE__ ), 'swift_blog_meta_box_nonce' );
		// Fetch Options list.
		$page_layout = get_post_meta($post_id,'swift-blog-meta-select-layout',true);
	?>
	<div id="swift-blog-settings-metabox-container" class="swift-blog-settings-metabox-container">
		<div id="swift-blog-settings-metabox-tab-layout">
			<h4><?php echo __( 'Layout Settings', 'swift-blog' ); ?></h4>
			<div class="swift-blog-row-content">
				<!-- Checkbox Field-->
				     <p>
				     <div class="swift-blog-row-content">
				         <label for="swift-blog-meta-image-checkbox">
				             <input type="checkbox" name="swift-blog-meta-image-checkbox" id="swift-blog-meta-image-checkbox" value="yes" <?php if ( isset ( $swift_blog_post_meta_value['swift-blog-meta-image-checkbox'] ) ) checked( $swift_blog_post_meta_value['swift-blog-meta-image-checkbox'][1], 'yes' ); ?> />
				             <?php _e( 'Check To Disable Featured Image From Banner', 'swift-blog' )?>
				         </label>
				     </div>
				     </p>
				 <!-- Checkbox Field-->
				     <p>
				     <div class="swift-blog-row-content">
				         <label for="swift-blog-meta-checkbox">
				             <input type="checkbox" name="swift-blog-meta-checkbox" id="swift-blog-meta-checkbox" value="yes" <?php if ( isset ( $swift_blog_post_meta_value['swift-blog-meta-checkbox'] ) ) checked( $swift_blog_post_meta_value['swift-blog-meta-checkbox'][0], 'yes' ); ?> />
				             <?php _e( 'Check To Enable Featured Image On Single Page', 'swift-blog' )?>
				         </label>
				     </div>
				     </p>				 

			     <!-- Select Field-->
			        <p>
			            <label for="swift-blog-meta-select-layout" class="swift-blog-row-title">
			                <?php _e( 'Single Page/Post Layout', 'swift-blog' )?>
			            </label>
			            <select name="swift-blog-meta-select-layout" id="swift-blog-meta-select-layout">
				            <option value="right-sidebar" <?php selected('right-sidebar',$page_layout);?>>
				            	<?php _e( 'Content - Primary Sidebar', 'swift-blog' )?>
				            </option>
				            <option value="left-sidebar" <?php selected('left-sidebar',$page_layout);?>>
				            	<?php _e( 'Primary Sidebar - Content', 'swift-blog' )?>
				            </option>
				            <option value="no-sidebar" <?php selected('no-sidebar',$page_layout);?>>
				            	<?php _e( 'No Sidebar', 'swift-blog' )?>
				            </option>
			            </select>
			        </p>
			</div><!-- .swift-blog-row-content -->
		</div><!-- #swift-blog-settings-metabox-tab-layout -->
	</div><!-- #swift-blog-settings-metabox-container -->

    <?php
	}

endif;



if ( ! function_exists( 'swift_blog_save_theme_settings_meta' ) ) :

	/**
	 * Save theme settings meta box value.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post Post object.
	 */
	function swift_blog_save_theme_settings_meta( $post_id, $post ) {

		// Verify nonce.
		if ( ! isset( $_POST['swift_blog_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['swift_blog_meta_box_nonce'], basename( __FILE__ ) ) ) {
			  return; }

		// Bail if auto save or revision.
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}

		// Check permission.
		if ( 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return; }
		} else if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$swift_blog_meta_image_checkbox =  isset( $_POST[ 'swift-blog-meta-image-checkbox' ] ) ? esc_attr($_POST[ 'swift-blog-meta-image-checkbox' ]) : '';
		update_post_meta($post_id, 'swift-blog-meta-image-checkbox', sanitize_text_field($swift_blog_meta_image_checkbox));

		$swift_blog_meta_checkbox =  isset( $_POST[ 'swift-blog-meta-checkbox' ] ) ? esc_attr($_POST[ 'swift-blog-meta-checkbox' ]) : '';
		update_post_meta($post_id, 'swift-blog-meta-checkbox', sanitize_text_field($swift_blog_meta_checkbox));

		$swift_blog_meta_select_layout =  isset( $_POST[ 'swift-blog-meta-select-layout' ] ) ? esc_attr($_POST[ 'swift-blog-meta-select-layout' ]) : '';
		if(!empty($swift_blog_meta_select_layout)){
			update_post_meta($post_id, 'swift-blog-meta-select-layout', sanitize_text_field($swift_blog_meta_select_layout));
		}
	}

endif;

add_action( 'save_post', 'swift_blog_save_theme_settings_meta', 10, 3 );