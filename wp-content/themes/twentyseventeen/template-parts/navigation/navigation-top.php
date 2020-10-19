<?php
/**
 * Displays top navigation
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */
 session_start();
?>
<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'twentyseventeen' ); ?>">
	<button class="menu-toggle" aria-controls="top-menu" aria-expanded="false">
		<?php
		echo twentyseventeen_get_svg( array( 'icon' => 'bars' ) );
		echo twentyseventeen_get_svg( array( 'icon' => 'close' ) );
		_e( 'Menu', 'twentyseventeen' );
		?>
	</button>

				

	<?php 
	if (($_SESSION["role_user"]=='user') || ($_SESSION["role_user"]=='guest')) {
		//if (is_page(105)){
		//	wp_nav_menu( array( 'theme_location' => 'manage-menu-gen' ) ); 
		//} else {
			wp_nav_menu( array( 'theme_location' => 'homepg-menu-all' ) );
		//}

	} elseif ($_SESSION["role_user"]=='power user') {		
		if ((is_page(67)) || (is_page(105))){
			wp_nav_menu( array( 'theme_location' => 'manage-menu-puser' ) ); 

		} else if ((is_page(52)) || (is_page(122)) || (is_page(124)) || (is_page(126))){
			wp_nav_menu( array( 'theme_location' => 'learn-menu' ) );
		
		} else {
			wp_nav_menu( array( 'theme_location' => 'homepg-menu-puser' ) );
		}
		
	} elseif ($_SESSION["role_user"]=='admin'){
		if ((is_page(67)) || (is_page(57)) || (is_page(105))){
			wp_nav_menu( array( 'theme_location' => 'manage-menu-admin' ) ); 

		} else if ((is_page(52)) || (is_page(122)) || (is_page(124)) || (is_page(126))){
			wp_nav_menu( array( 'theme_location' => 'learn-menu' ) );
		
		} else {
			wp_nav_menu( array( 'theme_location' => 'homepg-menu-admin' ) );
		}
	}
	else {
		wp_nav_menu( array('theme_location' => 'top', 'menu_id' => 'top-menu',) ); 
	}
	?>
	
	<?php if ( ( twentyseventeen_is_frontpage() || ( is_home() && is_front_page() ) ) && has_custom_header() ) : ?>
		<a href="#content" class="menu-scroll-down"><?php echo twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ); ?><span class="screen-reader-text"><?php _e( 'Scroll down to content', 'twentyseventeen' ); ?></span></a>
	<?php endif; ?>

</nav><!-- #site-navigation -->
