<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package test
 */

?>


<footer id="colophon" class="site-footer">
	<div class="w-80">

		<div id="footer_nav">
			<?php the_custom_logo(); ?>
			<div>
				<h2 class="font-40" id="footer-quote">The Most Comfortable Call You’ll Ever Make</h2>
			</div>
			<div id="footer-menu" class="flex-align-center margin-left-auto">
				<!-- <?php
				// wp_nav_menu(
				// 	array(
				// 		'name'        => 'Footer Menu',
				// 	)
				// );
				?> -->
			</div>
		</div>

		<div id="footer_info">
			<div class="footer_locations">

				<div class="footer_location">
					<div>
						<h2 class="m-0 footer_city">Tucson</h2>
					</div>
					<div class="flex footer_address">
						<p>4759 S Butterfield Dr <br> Tucson, AZ 85714</p>
						<p><a href="tel:5202638275">(520)-263-8275 <br>24×7-365</p>
					</div>
					<div>
						<p class="m-0">Arizona License</p>
						<p class="m-0"> AZROC : 304276, 304277, 304707, 323982, 326103</p>
					</div>
				</div>
				<div class="footer_location">
					<div>
						<h2 class="m-0 footer_city">Albuquerque</h2>
					</div>
					<div class="flex footer_address">
						<p>3540 Pan American Freeway NE <br> Suite E Albuquerque, NM 87107 Tucson, AZ 85714</p>
						<p><a href="tel:5052576268">(505)-257-6268 <br>24×7-365</p>
					</div>
					<div>
						<p class="m-0">New Mexico License</p>
						<p class="m-0"> 401693</p>
					</div>
				</div>
			</div>
			<div id="footer_socials">
				<div class="flex gap-20">
					<a href=""><i class="fa-brands fa-instagram"></i></a>
					<a href=""><i class="fa-brands fa-facebook"></i></a>
					<a href=""><i class="fa-brands fa-tiktok"></i></a>
					<a href=""><i class="fa-brands fa-youtube"></i></a>
				</div>
				<h2>Open 24/7</h2>
				<p class="m-0">Copyright © 2023 Strongbuilt</p>
				<p class="m-0">Plumbing, Air, Solar, and Electric</p>
			</div>
		</div>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>