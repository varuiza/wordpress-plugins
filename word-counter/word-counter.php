<?php

/*
Plugin Name: Word Counter
Description: Permite añadir estadísticas en los posts. Configurable.
Version: 1.0
Requires PHP: 8.1
Author: Víctor Ruiz
Author URI: https://es.linkedin.com/in/varuiza
Text Domain: wrdcntr
*/


defined('ABSPATH') or die('Are you a juanker?');

class WordCounterPlugin
{
	function __construct()
	{
		add_action('admin_menu', array($this, 'adminPage'));
		add_action('admin_init', array($this, 'settings'));
		add_filter('the_content', array($this, 'ifWrap'));
	}

	function ifWrap($content)
	{
		if ((is_main_query() and is_single()) and
			(get_option('wcp_wordcount', '1') or
				get_option('wcp_charactercount', '1') or
				get_option('wcp_readtime', '1')
			)
		) {
			return $this->createHTML($content);
		}
		return $content;
	}

	function createHTML($content)
	{
		$html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

		// get word count only if it's needed
		if (get_option('wcp_wordcount', '1') or get_option('wcp_readtime', '1')) {
			$wordCount = str_word_count(strip_tags($content));
		}

		if (get_option('wcp_wordcount', '1')) {
			$html .= 'This post has ' . $wordCount . ' words.<br>';
		}

		if (get_option('wcp_charactercount', '1')) {
			$html .= 'This post has ' . strlen(strip_tags($content)) . ' characters.<br>';
		}

		if (get_option('wcp_readtime', '1')) {
			$html .= 'This will take about ' . round($wordCount / 225) . ' minute(s) to read.';
		}

		$html . '</p>';

		if (get_option('wcp_location', 'Beginning') == 'Beginning') {
			return $html . $content;
		}
		return $content . $html;
	}


	function settings()
	{
		add_settings_section('wcp_first_section', null, null, 'word-counter-settings-page');

		// Display Location
		add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-counter-settings-page', 'wcp_first_section');
		register_setting('wordcounterplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => 'Beginning'));

		// Headline Text
		add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-counter-settings-page', 'wcp_first_section');
		register_setting('wordcounterplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

		// Word Count
		add_settings_field('wcp_wordcount', 'Word Count', array($this, 'wordCountHTML'), 'word-counter-settings-page', 'wcp_first_section');
		register_setting('wordcounterplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

		// Character Count
		add_settings_field('wcp_charactercount', 'Character Count', array($this, 'characterCountHTML'), 'word-counter-settings-page', 'wcp_first_section');
		register_setting('wordcounterplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

		// Read Time
		add_settings_field('wcp_readtime', 'Read Time', array($this, 'readTimeHTML'), 'word-counter-settings-page', 'wcp_first_section');
		register_setting('wordcounterplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
	}

	function sanitizeLocation($input)
	{
		if ($input != "Beginning" and $input != "End") {
			add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either "Beginning" or "End".');
			return get_option('wcp_location');
		} else {
			return $input;
		}
	}

	function locationHTML()
	{ ?>
		<select name="wcp_location">
			<option value="Beginning" <?php selected(get_option('wcp_location'), 'Beginning'); ?>>Beginning of post</option>
			<option value="End" <?php selected(get_option('wcp_location'), 'End'); ?>>End of post</option>
		</select>
	<?php }

	function headlineHTML()
	{ ?>
		<input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')); ?>">
	<?php }

	function wordCountHTML()
	{ ?>
		<input type="checkbox" name="wcp_wordcount" value="1" <?php checked(get_option('wcp_wordcount')); ?>>
	<?php }

	function characterCountHTML()
	{ ?>
		<input type="checkbox" name="wcp_charactercount" value="1" <?php checked(get_option('wcp_charactercount')); ?>>
	<?php }

	function readTimeHTML()
	{ ?>
		<input type="checkbox" name="wcp_readtime" value="1" <?php checked(get_option('wcp_readtime')); ?>>
	<?php }

	function adminPage()
	{
		add_options_page('Word Counter Settings', 'Word Counter', 'manage_options', 'word-counter-settings-page', array($this, 'settingsHTML'));
	}

	function settingsHTML()
	{ ?>
		<div class="wrap">
			<h1>Word Counter Settings</h1>
			<form action="options.php" method="POST">
				<?php
				settings_fields('wordcounterplugin');
				do_settings_sections('word-counter-settings-page');
				submit_button();
				?>
			</form>
		</div>
<?php }
}

$wordCounterPlugin = new WordCounterPlugin();
