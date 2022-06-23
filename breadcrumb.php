<?php 
function show_breadcrumbs() {
  $home_breadcrumbs = 0; // put 1 to show breadcrumbs on home page, otherwise leave it as it is
  $separator = '&raquo;'; // separator
  $home = 'Home'; // home text
  $mycurrent = 1; //put 1 to show current post/page title in breadcrumbs, otherwise leave put 0
  global $post;
  $myhome_url = get_bloginfo('url');
  if (is_home() || is_front_page()) {
    if ($home_breadcrumbs == 1) echo '<div id="crumbs"><a href="' . $myhome_url . '">' . $home . '</a></div>';
  } else {
    echo '<div id="crumbs"><a href="' . $myhome_url . '">' . $home . '</a> ' . $separator . ' ';
    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $separator . ' ');
      echo '<span>' . 'Archive :"' . single_cat_title('', false) . '"' . '</span>';
    } elseif ( is_search() ) {
      echo '<span>' . 'Results :"' . get_search_query() . '"' . '</span>';
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $separator . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $separator . ' ';
      echo '<span>' . get_the_time('d') . '</span>';
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $separator . ' ';
      echo '<span>' . get_the_time('F') . '</span>';
    } elseif ( is_year() ) {
      echo '<span>' . get_the_time('Y') . '</span>';
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $myhome_url . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($mycurrent == 1) echo ' ' . $separator . ' ' . '<span>' . get_the_title() . '</span>';
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $separator . ' ');
        if ($mycurrent == 0) $cats = preg_replace("#^(.+)\s$separator\s$#", "$1", $cats);
        echo $cats;
        if ($mycurrent == 1) echo '<span>' . get_the_title() . '</span>';
      }
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo '<span>' . $post_type->labels->singular_name . '</span>';
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $separator . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($mycurrent == 1) echo ' ' . $separator . ' ' . '<span>' . get_the_title() . '</span>';
    } elseif ( is_page() && !$post->post_parent ) {
      if ($mycurrent == 1) echo '<span>' . get_the_title() . '</span>';
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $separator . ' ';
      }
      if ($mycurrent == 1) echo ' ' . $separator . ' ' . '<span>' . get_the_title() . '</span>';
    } elseif ( is_tag() ) {
      echo '<span>' . 'Posts tagged "' . single_tag_title('', false) . '"' . '</span>';
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo '<span>' . 'Articles By ' . $userdata->display_name . '</span>';
    } elseif ( is_404() ) {
      echo '<span>' . '404' . '</span>';
    }
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
    echo '</div>';
  }
}
?>
