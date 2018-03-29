<?php
/**
* Plugin Name: Library Book Search
* Description: This is a demo plugin for book search functionality. Where end users search books in the different parameter like Book Name, Author, and price etc..
* Author: Chirag Unadkat
* Version: 1.0
**/


include('custom_fields_taxonomy.php');

add_action('admin_menu', 'book_plugin_setup_menu');

function book_plugin_setup_menu(){
        add_menu_page( 'Book Plugin Page', 'Book Plugin', 'manage_options', 'book-plugin', 'book_init' );
}

function book_init(){
        echo "<h1>Short Code</h1>";
        echo "<p>[Library-Book-Search]</p>";
}

function bookDataPlugin(){
     include dirname(__FILE__) . '/bookPage.php';
}

add_shortcode('Library-Book-Search', 'bookDataPlugin');

?>
