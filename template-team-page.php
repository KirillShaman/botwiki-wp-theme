<?php
  /* Template Name: Team Page Template */
  global $helpers;
  get_header();
  $post_id = get_the_ID();

  $admins = get_users( array(
    'role'         => 'administrator',
    'orderby'      => 'registered',
    'order'        => 'ASC',
    // 'fields'       => 'all',
    'who'          => '',
  ) );

  $dominant_color  = get_post_meta( $post_id, 'dominant_color', true );
  $dominant_color_css = str_replace('[', 'background-color:rgb(', $dominant_color);
  $dominant_color_css = str_replace(']', ')', $dominant_color_css);
?>
  <main role="main" class="container-fluid m-0 p-0">
    <div class="thumbnail-wrapper" style="<?php echo $dominant_color_css; ?>">
      <?php
        $post_thumbnail_id = get_post_thumbnail_id();
        the_post_thumbnail('post-thumbnail', ['data-src' => get_the_post_thumbnail_url( $post_thumbnail_id ),'class' => 'lazy-load', 'title' => get_post($post_thumbnail_id)->post_title ]);
      ?>
    </div>
    <div class="container">
      <article id="post-<?php echo $post_id; ?>" <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <ul class="btn-list">
          <li>
            <a class="btn" target="_blank" href="mailto:<?php echo $helpers->get_admin_emails(); ?>">Get in touch</a>
            <a class="btn" href="https://twitter.com/botwikidotorg">Tweet at us</a>
          </li>
        </ul>

        <?php
          echo do_shortcode( get_post_field('post_content', $post_id) );
        ?>

        <!-- <h2 id="admins">Site administrators<a class="pilcrow" href="#admins">¶</a></h2> -->

        <?php
          foreach ( $admins as $index=>$admin ) {

            $author_id = $admin->data->ID;
            $author_data = get_userdata( intval($author_id ));

            $nickname = get_the_author_meta( 'nickname', $author_id);
            $username = get_the_author_meta('user_nicename', $author_id);
            $description = get_the_author_meta( 'description', $author_id);

            $first_name = get_the_author_meta( 'nickname', $author_id);
            $last_name = get_the_author_meta( 'last_name', $author_id);
            $full_name = '';

            if( empty($first_name)){
                $full_name = $last_name;
            } elseif( empty( $last_name )){
                $full_name = $first_name;
            } else {
                $full_name = "{$first_name} {$last_name}";
            }

            if ( user_can($author_id, 'administrator') ){  
              $botwiki_team_role = get_the_author_meta('botwiki-team-role', $author_id);
              if ( empty( $botwiki_team_role ) ){
                $botwiki_team_role = "Botwiki team member.";
              }
            }
            else{
              $botwiki_team_role = "Botwiki contributor.";    
            }

            $website_url = esc_attr( get_the_author_meta( 'user_url', $author_id ) );
            $twitter_handle = str_replace('@', '', esc_attr( get_the_author_meta( 'twitter-handle', $author_id ) ) );

            ?>
            <div class="container mt-5 p-0">
              <div class="row">
                <div class="col-sm-12 col-md-2 text-center">
                  <img class="mr-3 mb-4 u-photo rounded" src="<?php echo get_avatar_url($author_id); ?>" alt="<?php echo $full_name; ?>">
                </div>
                <div class="col-sm-12 col-md-10">
                  <h1 class="mt-0 mb-3"><?php echo $nickname; ?></h1>
                </div>
                <div class="col-sm-12">
                  <div class="card mt-4 mb-4">
                    <div class="card-body">
                      <p class="card-text font-weight-bold"><?php echo $botwiki_team_role; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="p-note">
                    <?php echo $description; ?>
                  </div>
                  <ul class="btn-list mt-4">
                    <li>
                      <a class="btn" rel="me" href="<?php echo get_author_posts_url($author_id, $username ); ?>">
                        View profile
                      </a>                    
                    </li>
                    <?php if ( !empty( $twitter_handle )){ ?>
                      <li>
                        <a class="btn" title="Twitter" rel="me" href="https://twitter.com/<?php echo $twitter_handle; ?>">@<?php echo $twitter_handle; ?></a>
                      </li>
                    <?php } ?>
                    <?php if ( !empty( $website_url )){ ?>
                      <li>
                        <a class="btn" title="Personal website" rel="me" href="<?php echo $website_url; ?>"><?php echo $helpers->get_domain_from_url($website_url); ?></a>
                      </li>
                    <?php } ?>
                  </ul>                  
                </div>
              </div>
            </div>
            <?php }

            $contributors = get_users( array(
              'role__not_in' => ['administrator'],
              'exclude'      => [2],
              'orderby'      => 'registered',
              'order'        => 'ASC'
            ) );

            if ( count( $contributors ) > 0 ){ ?>
              <h2 id="site-contributors">Site contributors<a class="pilcrow" href="#site-contributors">¶</a></h2>
              <div class="row">
              <?php

              foreach ( $contributors as $contributor ) {

                $author_id = $contributor->data->ID;
                $author_data = get_userdata( intval($author_id ));

                $nickname = get_the_author_meta( 'nickname', $author_id);
                $username = get_the_author_meta('user_nicename', $author_id);
                $description = get_the_author_meta( 'description', $author_id);

                $first_name = get_the_author_meta( 'nickname', $author_id);
                $last_name = get_the_author_meta( 'last_name', $author_id);
                $full_name = '';

                if( empty($first_name)){
                    $full_name = $last_name;
                } elseif( empty( $last_name )){
                    $full_name = $first_name;
                } else {
                    $full_name = "{$first_name} {$last_name}";
                }

                $profile_img_url = esc_attr( get_the_author_meta( 'profile-img-url', $author_id ) );

                if ( empty( $profile_img_url )){
                  $profile_img_url = get_avatar_url($author_id);
                }

                $website_url = esc_attr( get_the_author_meta( 'user_url', $author_id ) );
                $twitter_handle = str_replace('@', '', esc_attr( get_the_author_meta( 'twitter-handle', $author_id ) ) );
              ?>
            <div class="col-xs-6 col-s-2 m-2 text-center">
              <a href="<?php echo get_author_posts_url($author_id, $username ); ?>">
                <img class="lazy-load rounded" src="<?php echo $profile_img_url; ?>" data-src="<?php echo $profile_img_url; ?>" alt="<?php echo $full_name; ?>" title="<?php echo $full_name; ?>, click to view profile">
              </a>
            </div>
            <?php }
          } ?>
        </div>
      </article>
    </div>
  </main>
<?php get_footer(); ?>
