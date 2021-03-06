<?php

class Post_Links {
  public function __construct() {
    add_shortcode( 'link', array( $this, 'post_link_shortcode' ) );
    add_shortcode( 'link-list', array( $this, 'link_list_shortcode' ) );
    add_shortcode( 'btn-list', array( $this, 'btn_list_shortcode' ) );
    add_shortcode( 'link-cards', array( $this, 'link_cards_shortcode' ) );
  }

  public function post_link_shortcode( $atts ) {
    $is_external = false;    
    $post_id = $atts['id'];
    $shortcode_html = '';

    if ( get_post_status( $post_id ) === 'publish' ){
      $link_classes = $atts['class'];

      $link_url = get_post_meta( $post_id, 'resource_url', true );

      if ( empty( $link_url ) ){
        $link_url = post_permalink( $post_id );
      }
      else{
        $is_external = true;
      }

      $link_title = get_the_title( $post_id );

      $link_classes_html = '';

      if (!empty( $link_classes )){
        $link_classes_html = ' class="' . $link_classes . '"';      
      }
  
      $shortcode_html = '<a href="' . $link_url . '" ' . $link_classes_html . '>' . $link_title . '</a>'
                      . ($is_external ? ': ' . get_the_excerpt($post_id) . ' (' .  parse_url($link_url)['host'] . ')' : '');
    }


    return $shortcode_html;
  }

  public function btn_list_shortcode( $atts ) {
    $is_external = false;

    $post_ids = explode( ',', $atts['ids'] );
    $link_list_html = '<ul class="btn-list mb-2">';

    foreach ($post_ids as $post_id) {
      $link_url = get_post_meta( $post_id, 'resource_url', true );

      if ( empty( $link_url ) ){
        $link_url = post_permalink( $post_id );
      }
      else{
        $is_external = true;
      }

      $link_title = get_the_title( $post_id );
      
      if ( get_post_status( $post_id ) === 'publish' ){
        $link_list_html .= '<li><a data-resource-id="' . $post_id . '" href="' . $link_url . '" class="btn">' . $link_title . '</a>' 
                        . ($is_external ? ': ' . get_the_excerpt($post_id) . ' (' .  parse_url($link_url)['host'] . ')' : '')
                        . '</li>' ;
      }
    }

    $link_list_html .= '</ul>';

    return $link_list_html;
  }

  public function link_list_shortcode( $atts ) {
    $post_ids = explode( ',', $atts['ids'] );
    $link_list_html = '<ul>';

    foreach ($post_ids as $post_id) {
      if ( get_post_status( $post_id ) === 'publish' ){
        $is_external = false;
        $link_url = get_post_meta( $post_id, 'resource_url', true );

        if ( empty( $link_url ) ){
          $link_url = post_permalink( $post_id );
        }
        else{
          $is_external = true;
        }

        $link_title = get_the_title( $post_id );
        $link_list_html .= '<li><a data-resource-id="' . $post_id . '" href="' . $link_url . '">' . $link_title . '</a>';

        $link_authors = explode( PHP_EOL, get_post_meta( $post_id, 'resource_author_info', true ) );

        $link_authors_html = '';


        if ( count( $link_authors ) === 1 ){
          if ( !empty( $link_authors[0] ) && $is_external ){
            list($author_name, $author_url ) = explode(',', $link_authors[0]);          
          }
          elseif ( !$is_external ) {
            $post_author = get_post_field( 'post_author', $post_id );

            $author_name = get_the_author_meta( 'nickname', $post_author);
            $author_url = get_author_posts_url($author_id, get_the_author_meta('nickname', $author_id));;
          }
          $link_authors_html .= '<a data-resource-id="' . $post_id . '" href="' . $author_url . '">' . $author_name . '</a>';
        }
        else{
          $link_authors_html_arr = array();

          foreach ($link_authors as $link_author) {
            list($author_name, $author_url ) = explode(',', $link_author);

            if ( $author_url ){
              array_push( $link_authors_html_arr, '<a data-resource-id="' . $post_id . '" href="">' . $author_name . '</a>' );
            }
            else{
              array_push( $link_authors_html_arr, $author_name );
            }            
          }

          global $helpers;
          $link_authors_html = $helpers->join_with_and( $link_authors_html_arr );
        }

        if ( $is_external ){
          $link_domain = str_replace('www.', '', parse_url($link_url)['host']);



          $link_list_html .= ': ' . get_the_excerpt($post_id) . ' ('
                          . ( !empty(  $link_authors_html ) ?  $link_authors_html . ' via ' : '' )
                          .  $link_domain . ')';
        }
        else{
          $link_list_html .= ' ('
                          . ( !empty(  $link_authors_html ) ? 'by ' . $link_authors_html : '' )
                          . ')';        
        }

        if ( current_user_can('administrator') ){
          $link_list_html .= ' <a href="/wp-admin/post.php?post=' . $post_id . '&action=edit"><code>[edit]</code></a>';
        }


        $link_list_html .= '</li>';

      }
    }

    $link_list_html .= '</ul>';

    return $link_list_html;
  }

  public function link_cards_shortcode( $atts ) {
    $post_ids_unfiltered = explode( ',', $atts['ids'] );
    $post_ids = array();



    foreach ($post_ids_unfiltered as $post_id) {
      if ( get_post_status( $post_id ) === 'publish' ){
        $post_ids[] = $post_id;
      }
    }

    $post_id_groups = array_chunk( $post_ids, 2 );
    $include_description = ( $atts['description'] === 'yes' || $atts['description'] === 'true' );

    $link_list_html = '<div class="mt-md-5 mb-md-5">';

    foreach ($post_id_groups as $post_ids) {
      $link_list_html .= '<div class="card-deck mt-md-4 mb-md-4">';
      foreach ($post_ids as $post_id) {
        if ( get_post_status( $post_id ) === 'publish' ){
          $link_url = get_post_meta( $post_id, 'resource_url', true );

          if ( empty( $link_url ) ){
            $link_url = post_permalink( $post_id );
          }

          $link_title = get_post_meta( $post_id, 'card_title', true );
          if ( empty( $link_title ) ){
            $link_title = get_the_title( $post_id );
          }

          $link_thumbnail = get_the_post_thumbnail( $post_id, 'full', array( 'class' => 'card-img-top' ) );
          $link_description = ( $include_description ? get_the_excerpt( $post_id ) : '');

          $link_list_html .= '    <div class="card">' .
                             '      <a data-resource-id="' . $post_id . '" href="' . $link_url . '">' . $link_thumbnail . '</a>' .
                             '      <div class="card-body">' .
                             '        <h5 class="card-title">' . $link_title . '</h5>' .
                             '        <p class="card-text">' . $link_description . '</p>' .
                             '      </div>' .
                             '      <div class="card-footer">' .
                             '        <a data-resource-id="' . $post_id . '" href="' . $link_url . '" class="btn">Browse</a>' .
                             '      </div>' .
                             '    </div>';
          if ( count($post_ids) === 1 ){
            $link_list_html .= '<div class="card d-none d-sm-block" style="visibility: hidden;"></div>';
          }
        }
      }

      $link_list_html .= '</div>';
    }
    $link_list_html .= '</div>';

    return $link_list_html;
  }
}

$post_links_init = new Post_Links();
