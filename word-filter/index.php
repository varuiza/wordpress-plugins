<?php

/*
Plugin Name: Word Filter
Description: Replaces a list of words.
Version: 1.1
Requires PHP: 8.1
Author: Víctor Ruiz
Author URI: https://es.linkedin.com/in/varuiza
*/

if ( !defined('ABSPATH') ) exit; // Exit if accessed directly


class WordFilterPlugin {
    function __construct() {
        // Add 'Word Filter' in the admin menu
        add_action( 'admin_menu', array($this, 'wordFilterMenu'));
    }

    function wordFilterMenu() {
        $mainPageHook = add_menu_page( 'Words to filter', 'Word Filter', 'manage_options', 'wordfilter', array($this, 'wordFilterListPage'), 'dashicons-smiley', 100);
        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
        add_submenu_page( 'wordfilter', 'Words to Filter', 'Words List', 'manage_options', 'wordfilter', array($this, 'wordFilterListPage'));
        add_submenu_page( 'wordfilter', 'Word Filter Options', 'Options', 'manage_options', 'word-filter-options', array($this, 'wordFilterOptionsSubpage'));        
    }

function mainPageAssets() {
        wp_enqueue_style('filterAdminCSS', plugin_dir_url(__FILE__).'styles.css');
}

    function wordFilterListPage() { ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <form method="POST">
                <label for="words_to_filter"><p>Enter a <strong>comma-separated</strong> list of words.</p></label>
            </form>
            <div class="word-filter__flex-container">
                <textarea name="words_to_filter" id="words_to_filter" placeholder="silly, stupid, horrible"></textarea>                
            </div>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save changes">
        </div>
    <?php }

function wordFilterOptionsSubpage() { ?>
    hw! 2
<?php }

}

$wordFilterPlugin = new WordFilterPlugin();