<?php
/*
 *  Author: Amerey<info@amerey.eu>
 *  Custom headless theme.
 */

add_theme_support( "post-thumbnails" );



function remove_menus() {
  remove_menu_page( "index.php" ); //Dashboard
  remove_menu_page( "jetpack" ); //Jetpack*
  remove_menu_page( "edit-comments.php" ); //Comments
}
add_action( "admin_menu", "remove_menus" );



function hwp_custom_menu_order( $menu_ord ) {
  if ( !$menu_ord ) return true;

  return array(
    "edit.php?post_type=page", // Pages
    "edit.php", // Posts
    "edit.php?post_type=slideshow", // Custom Post Type
    "edit.php?post_type=news", // Custom Post Type
    "edit.php?post_type=parallax", // Custom Post Type
    "edit.php?post_type=project", // Custom Post Type
    "separator1", // First separator

    "upload.php", // Media
    "themes.php", // Appearance
    "plugins.php", // Plugins
    "users.php", // Users
    "separator2", // Second separator

    "tools.php", // Tools
    "options-general.php", // Settings
    "separator-last", // Last separator
  );
}
add_filter( "custom_menu_order", "hwp_custom_menu_order", 10, 1 );
add_filter( "menu_order", "hwp_custom_menu_order", 10, 1 );



// disable rss
function hwp_disable_feed() {
  wp_die( __('No feed available, please visit our <a href="'. get_bloginfo("url") .'">homepage</a>!') );
}
add_action("do_feed", "hwp_disable_feed", 1);
add_action("do_feed_rdf", "hwp_disable_feed", 1);
add_action("do_feed_rss", "hwp_disable_feed", 1);
add_action("do_feed_rss2", "hwp_disable_feed", 1);
add_action("do_feed_atom", "hwp_disable_feed", 1);
add_action("do_feed_rss2_comments", "hwp_disable_feed", 1);
add_action("do_feed_atom_comments", "hwp_disable_feed", 1);



// Return `null` if an empty value is returned from ACF.
if (!function_exists("acf_nullify_empty")) {
  function acf_nullify_empty($value, $post_id, $field) {
    if (empty($value)) {
      return null;
    }
    return $value;
  }
}
add_filter("acf/format_value", "acf_nullify_empty", 100, 3);



// Allow SVG upload
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
    return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
    'ext'             => $filetype['ext'],
    'type'            => $filetype['type'],
    'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );



// rename posts to kurzy
function hwp_change_post_type_labels( $labels ) {
  $labels->name = 'Kurzy';
  $labels->singular_name = 'Kurz';
  $labels->add_new = 'Přidat nový kurz';
  $labels->add_new_item = 'Přidat nový kurz';
  $labels->edit_item = 'Upravit kurz';
  $labels->new_item = 'Nový kurz';
  $labels->view_item = 'Zobrazit kurz';
  $labels->search_items = 'Najít kurz';
  $labels->not_found = 'Kurz nenalezen';
  $labels->not_found_in_trash = 'V koši nebyly nalezeny žádné kurzy';
  $labels->all_items = 'Všechny kurzy';
  $labels->menu_name = 'Kurzy';
  $labels->name_admin_bar = 'Přidat nový kurz';
  $labels->featured_image = 'Obrázek';
  $labels->category = 'Kategorie';
  $labels->tags = 'Štítky';

  return $labels;
}
add_filter( 'post_type_labels_post', 'hwp_change_post_type_labels' );



// rename categories in posts
function hwp_change_category_labels() {
  global $wp_taxonomies;
  $labels = &$wp_taxonomies['category']->labels;
  $labels->name = 'Kategorie';
  $labels->singular_name = 'Kategorie';
  $labels->add_new = 'Přidat kategorii';
  $labels->add_new_item = 'Přidat kategorii';
  $labels->edit_item = 'Upravit kategorii';
  $labels->new_item = 'Kategorie';
  $labels->view_item = 'Zobrezit kategorii';
  $labels->search_items = 'Prohledat kategorie';
  $labels->not_found = 'Kategorie nenalezena';
  $labels->not_found_in_trash = 'Kategorie nenalezena ani v koši';
  $labels->all_items = 'Všechny kategorie';
  $labels->menu_name = 'Kategorie';
  $labels->name_admin_bar = 'Nová kategorie';
}
add_action( 'init', 'hwp_change_category_labels' );



// remove quick edit option from posts, pages and custom posts overview for editor
function hwp_remove_quick_edit( $actions ) {
  unset($actions['inline hide-if-no-js']);
  return $actions;
}
if ( ! current_user_can('manage_options') ) {
  add_filter('page_row_actions','hwp_remove_quick_edit',10,1);
  add_filter('post_row_actions','hwp_remove_quick_edit',10,1);
}



// remove view option from posts, pages and custom posts overview for editor
function hwp_remove_view( $actions ) {
  unset($actions['view']);
  return $actions;
}
if ( ! current_user_can('manage_options') ) {
  add_filter('page_row_actions','hwp_remove_view',10,1);
  add_filter('post_row_actions','hwp_remove_view',10,1);
}



// remove add page button from page overview for editor
function hwp_remove_add_page_button( $actions ) {
  unset($actions['page-title-action']);
  return $actions;
}
if ( ! current_user_can('manage_options') ) {
  add_filter('page_row_actions','hwp_remove_add_page_button',10,1);
}






















