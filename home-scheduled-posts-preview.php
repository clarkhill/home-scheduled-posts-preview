<?php

/*
  Plugin Name: Home Scheduled Posts Preview
  Description: View scheduled posts and drafts on the home page to test the article layout.
  Plugin URI: http://jhill3rd.com
  Author: Joseph C Hill III
  Author URI: http://jhill3rd.com
  Version: 0.1
 */

 /**
 * Adds the home page preview button to the admin bar in WP-Admin
 *
 * @since 0.1
 **/

 function home_scheduled_preview_button( $admin_bar ) {

   $admin_bar->add_menu(
     array(
       'id' => 'preview-scheduled-posts',
       'title' => apply_filters( 'the_title', 'Home Page Preview (tm)' ),
       'href' => home_url( '/?preview=true' ),
       'target'=>'_blank',
       'meta' => array( 'class' => 'preview-scheduled-posts',)
     )
  );

}

add_action( 'admin_bar_menu','home_scheduled_preview_button', 100 );

/**
* Registers and enqueus stylesheet for both the admin and front end of the SeekableIterator
*
* @since 0.1
**/

function home_scheduled_css() {

  $plugin_url = plugin_dir_url( __FILE__ );

  wp_register_style( 'preview-scheduled-posts', $plugin_url . 'assets/styles.css' );

  wp_enqueue_style( 'preview-scheduled-posts' );

}

add_action( 'admin_enqueue_scripts', 'home_scheduled_css' );

add_action( 'wp_enqueue_scripts', 'home_scheduled_css' );

/**
* Displays draft and future articles for logged in users if the URL variable preview is set (?preview=true)
*
* @since 0.1
**/

function home_scheduled_include_posts( $query ) {

  if ( is_user_logged_in() && $query->is_preview() ) {

    $GLOBALS[ 'wp_post_statuses' ][ 'future' ]->public = true;

    $GLOBALS[ 'wp_post_statuses' ][ 'draft' ]->public = true;

    add_action( 'wp_footer', 'home_scheduled_message' );
  };
}

add_filter( 'pre_get_posts', 'home_scheduled_include_posts' );

/**
* Adds a fixed message to the bottom of the browser
*
* @since 0.1
**/

function home_scheduled_message() {

  $current_user = wp_get_current_user();

  do_action( 'before_scheduled_message' );

  echo 'Hello, ' . $current_user->user_firstname . '! You are currently in preview mode.';

  do_action( 'after_scheduled_message' );
}

/**
* Adds the opening container for the scheduled message
*
* @since 0.1
**/

function home_scheduled_opening_div() {
  echo '<div class="home-scheduled-message">';
}

add_action( 'before_scheduled_message', 'home_scheduled_opening_div' );

/**
* Adds the closing container for the scheduled message
*
* @since 0.1
**/

function home_scheduled_closing_div() {
  echo '</div>';
}

add_action( 'after_scheduled_message', 'home_scheduled_closing_div' );
