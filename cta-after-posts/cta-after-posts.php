<?php

/*
Plugin Name: CTA after Posts
Description: Add a call to action in the end of every post.
Version: 1.0
Requires PHP: 8.1
Author: Víctor Ruiz
Author URI: https://es.linkedin.com/in/varuiza
Text Domain: ctaps
*/


defined('ABSPATH') or die('Are you a juanker?');

function addToEndOfPosts($content)
{
	$cta = '<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
			<div class="is-layout-flex wp-container-11 wp-block-columns alignwide">
					<div class="is-layout-flow wp-block-column">
					<p class="has-x-large-font-size" style="line-height:1.2">¿Quieres recomendar algún libro?</p>
					<div class="is-layout-flex wp-block-buttons">
						<div class="wp-block-button has-custom-font-size has-small-font-size">
							<a class="wp-block-button__link wp-element-button">Contáctanos</a>
						</div>
					</div>
				</div>
				<div class="is-layout-flow wp-block-column">
					<hr class="wp-block-separator has-alpha-channel-opacity">
				</div>
			</div>';
	return $content . $cta;
}
add_filter('the_content', 'addToEndOfPosts');
