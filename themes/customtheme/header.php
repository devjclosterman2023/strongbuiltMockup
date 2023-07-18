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
        		 <div class="w-80" id="site-header-top">
				 <div>
				 <?php the_custom_logo(); ?>
	</div>
			
    <div class="flex-align-center gap-20 nav-phone">
		<div id="site-phone">
           <p class="m-0">TUCSON: <a href="">(520)-729-9200</a></p>
           <p class="m-0">ALBUQUERQUE: <a href="">(505)-393-7659</a></p>
		</div>
		<a id="schedule-cta" class="red-btn" href="/">SCHEDULE SERVICE</a>
		<img style="height: 78px; width: 210px;" src="/wp-content/uploads/2023/07/veteran-logo.png" alt="Veteran Picture">
	</div>
</div>    

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
		<div id="megabackground"></div>
	</header><!-- #masthead -->

	<!-- create megabackground -->
