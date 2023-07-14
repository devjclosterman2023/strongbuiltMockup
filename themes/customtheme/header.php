<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package customTheme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'customtheme' ); ?></a>

	<header id="masthead" class="site-header">
		<div id="container">
			<a href="/">
			<img src="/wp-content/uploads/2023/07/cropped-StrongBuilt_USA_Logo_Electric-2048x711-1.png" alt="Picture of Strongbuilt Logo" class="headerLogo">
		    </a>

          <div class="right-align">
			<div class="area">
				<b><span style="color:#000000">TUCSON:</span> <span style="color:#0f4b7d"><a href="tel:5207299200">(520) 729-9200</a></span></b>
				<b><span style="color:#000000">ALBUQUERQUE:</span> <span style="color:#0f4b7d"><a href="tel:5053937659">(505) 393-7659</a></span></b>
		   </div>
          

			   <div class="mainbtn"><button class="btn"><b>SCHEDULE SERVICE</b></button>
		       </div>

			<img src="/wp-content/uploads/2023/07/veteran-logo.png" alt="Something for now" class="veteranpic">
		    </div>
         </div>




		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$customtheme_description = get_bloginfo( 'description', 'display' );
			if ( $customtheme_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $customtheme_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'customtheme' ); ?></button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				)
			);
			?>

		</nav><!-- #site-navigation -->
		



	</header><!-- #masthead -->
