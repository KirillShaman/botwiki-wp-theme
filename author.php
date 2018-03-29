<?php
	get_header();

	global $helpers;

	$author_id = get_query_var('author');
	$author_data = get_userdata( intval($author_id ));

	// echo "<pre><code>";
	// var_dump(get_userdata( $author_id ));
	// echo "</code></pre>";

	$nickname = get_the_author_meta('nickname', $author_id);
	$description = get_the_author_meta('description', $author_id);

	$first_name = get_the_author_meta('nickname', $author_id);
	$last_name = get_the_author_meta('last_name', $author_id);
	$full_name = '';

	if( empty($first_name)){
	    $full_name = $last_name;
	} elseif( empty( $last_name )){
	    $full_name = $first_name;
	} else {
	    $full_name = "{$first_name} {$last_name}";
	}

	// $email = $author_data['user_email'];
	$website_url = esc_attr( get_the_author_meta( 'user_url', $author_id ) );
	$twitter_handle = str_replace('@', '', esc_attr( get_the_author_meta( 'twitter-handle', $author_id ) ) );

	$background_img_url = esc_attr( get_the_author_meta( 'background-img-url', $author_id ) );
	$background_img_dominant_color = esc_attr( get_the_author_meta( 'background-img-dominant-color', $author_id ) );

  $background_img_dominant_color_css = str_replace('[', 'background-color:rgb(', $background_img_dominant_color);
  $background_img_dominant_color_css = str_replace(']', ')', $background_img_dominant_color_css);

  if ( !empty( $background_img_url ) ){ ?>
		<div class="thumbnail-wrapper" style="<?php echo $background_img_dominant_color_css; ?>">
			<img src="<?php echo $background_img_url; ?>">
		</div>
  <?php }
?>

	<main role="main" class="container">
    <div class="mt-5">
			<div class="media mb-5">
			  <img class="mr-3" src="<?php echo get_avatar_url($author_id); ?>" alt="<?php echo $full_name; ?>">
			  <div class="media-body">
			    <h1 class="mt-0 mb-3"><?php echo $nickname; ?></h1>
			    <?php echo $description; ?>
			    <ul class="btn-list mt-4">
			    	<?php if ( !empty( $website_url )){ ?>
			    		<li>
			    			<a class="btn" title="Personal website" href="<?php echo $website_url; ?>"><?php echo $helpers->get_domain_from_url($website_url); ?></a>
			    		</li>
			    	<?php } ?>
			    	<?php if ( !empty( $twitter_handle )){ ?>
			    		<li>
			    			<a class="btn" title="Twitter" href="https://twitter.com/<?php echo $twitter_handle; ?>">@<?php echo $twitter_handle; ?></a>
			    		</li>
			    	<?php } ?>

			    </ul>
			  </div>
			</div>

			<h1 id="author-archives">Author archives <a class="pilcrow" href="#author-archives">¶</a></h1>
			<?php
				get_template_part('loop', 'author');
				get_template_part('pagination');
			?>
		</div>
	</main>

<?php get_footer(); ?>
