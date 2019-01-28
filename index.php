<?php 
/*
 Plugin Name: Tijarat Business Pro Posttype
 Plugin URI: https://www.Themeseye.com/
 Description: Creating new post type for Tijarat Business Pro Theme
 Author: Themeseye
 Version: 1.0
 Author URI: https://www.Themeseye.com/
*/

define( 'tijarat_business_pro_posttype_version', '1.0' );
add_action( 'init', 'tijarat_business_pro_posttype_create_post_type' );

function tijarat_business_pro_posttype_create_post_type() {
	register_post_type( 'services',
    array(
        'labels' => array(
            'name' => __( 'Services','tijarat-business-pro-posttype' ),
            'singular_name' => __( 'Services','tijarat-business-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
	);

  register_post_type( 'project',
    array(
        'labels' => array(
            'name' => __( 'Project','tijarat-business-pro-posttype' ),
            'singular_name' => __( 'Project','tijarat-business-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );

  register_post_type( 'experts',
    array(
        'labels' => array(
            'name' => __( 'Expert','tijarat-business-pro-posttype' ),
            'singular_name' => __( 'Expert','tijarat-business-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );

  register_post_type( 'faq',
    array(
      'labels' => array(
        'name' => __( 'Faq','tijarat-business-pro-posttype' ),
        'singular_name' => __( 'Faq','tijarat-business-pro-posttype' )
        ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-media-spreadsheet',
      'public' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
        )
      )
  );

  register_post_type( 'testimonials',
	array(
		'labels' => array(
			'name' => __( 'Testimonials','tijarat-business-pro-posttype-pro' ),
			'singular_name' => __( 'Testimonials','tijarat-business-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-businessman',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
  
}
// --------------- Services ------------------
// Serives section
function tijarat_business_pro_posttype_images_metabox_enqueue($hook) {
  if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
    wp_enqueue_script('tijarat-business-pro-posttype-pro-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

    global $post;
    if ( $post ) {
      wp_enqueue_media( array(
          'post' => $post->ID,
        )
      );
    }

  }
}
add_action('admin_enqueue_scripts', 'tijarat_business_pro_posttype_images_metabox_enqueue');
// Services Meta
function tijarat_business_pro_posttype_bn_custom_meta_services() {

    add_meta_box( 'bn_meta', __( 'Services Meta', 'tijarat-business-pro-posttype-pro' ), 'tijarat_business_pro_posttype_bn_meta_callback_services', 'services', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'tijarat_business_pro_posttype_bn_custom_meta_services');
}

function tijarat_business_pro_posttype_bn_meta_callback_services( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <p>
            <label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
            <input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo $bn_stored_meta['meta-image'][0]; ?>">
            <input type="button" class="button image-upload" value="Browse">
          </p>
          <div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image'][0]; ?>" style="max-width: 250px;"></div>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}


function tijarat_business_pro_posttype_bn_meta_save_services( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  // Save Image
  if( isset( $_POST[ 'meta-image' ] ) ) {
      update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
  }
  
}
add_action( 'save_post', 'tijarat_business_pro_posttype_bn_meta_save_services' );

/* Services shortcode */
function tijarat_business_pro_posttype_services_func( $atts ) {
  $services = '';
  $services = '<div id="our-services">
              <div class="row">';
  $query = new WP_Query( array( 'post_type' => 'services') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=services');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $services_image= get_post_meta(get_the_ID(), 'meta-image', true);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        if(get_post_meta($post_id,'meta-services-url',true !='')){$custom_url =get_post_meta($post_id,'meta-services-url',true); } else{ $custom_url = get_permalink(); }
        $services .= '<div class="col-lg-4 col-md-4 col-sm-6 services-content-div">
                        <div class="services-content">
                            <div class="services-img">
                             <img src="'.esc_url($thumb_url).'" />
                          </div>
                          <div class="services-data">
                            <a href="'.esc_url($custom_url).'"><h3 class="services-heading-data">'.esc_html(get_the_title()) .'</h3></a>
                            <div class="services-text">
                              <p>
                                '.$excerpt.'
                              </p>
                            </div>
                          </div>
                        </div>
                        <a href="'.esc_url($custom_url).'"><h3 class="services-heading">'.esc_html(get_the_title()) .'</h3><span class="service-icon"><i class="fa fa-angle-right"></i></span></a>
                      </div>';
    if($k%2 == 0){
      $services.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $services = '<h2 class="center">'.esc_html__('Post Not Found','tijarat-business-pro-posttype').'</h2>';
  endif;
  $services .= '</div></div>';
  return $services;
}

add_shortcode( 'list-services', 'tijarat_business_pro_posttype_services_func' );

// Project

/* Adds a meta box to the Project editing screen */
function tijarat_business_pro_posttype_bn_project_meta_box() {
  add_meta_box( 'tijarat-business-pro-posttype-project-meta', __( 'Enter Project Name', 'tijarat-business-pro-posttype' ), 'tijarat_business_pro_posttype_bn_meta_callback_project', 'project', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'tijarat_business_pro_posttype_bn_project_meta_box');
}

function tijarat_business_pro_posttype_bn_meta_callback_project( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $project_name = get_post_meta( $post->ID, 'project-name', true );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <p>
            <label for="project-name"><?php echo esc_html('Project Name'); ?></label><br>
            <input type="text" name="project-name" id="project-name" class="project-name regular-text" value="<?php echo esc_attr( $project_name ); ?>">
          </p>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

function tijarat_business_pro_posttype_bn_meta_save_project( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  // Save Image
  
  if( isset( $_POST[ 'project-name' ] ) ) {
    update_post_meta( $post_id, 'project-name', sanitize_text_field($_POST[ 'project-name']) );
  }
  
}
add_action( 'save_post', 'tijarat_business_pro_posttype_bn_meta_save_project' );

/* Services shortcode */
function tijarat_business_pro_posttype_project_func( $atts ) {
  $project = '';
  $project = '<div id="project">
              <div class="row">';
  $query = new WP_Query( array( 'post_type' => 'project') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=project');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $project_name= get_post_meta(get_the_ID(), 'project-name', true);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        if(get_post_meta($post_id,'meta-project-url',true !='')){$custom_url =get_post_meta($post_id,'meta-project-url',true); } else{ $custom_url = get_permalink(); }
        $project .= '<div class="col-lg-4 col-md-6 project-box">
                        <div class="projects-hover">
                          <div class="projects-img">
                            <img src="'.esc_url($thumb_url).'" />
                          </div>
                          <div class="projects-content">
                            <div class="project-title">
                              <a href="'.esc_url($custom_url).'"><h3 class="project-heading">'.esc_html(get_the_title()) .'</h3></a>
                            </div>
                            <div class="t-project">
                            '.$project_name.'
                          </div>
                            <p class="project-text">
                              '.$excerpt.'
                            </p>
                          </div>
                        </div>
                        <a href="'.esc_url($custom_url).'">
                          <span class="project-icon"><i class="fa fa-angle-right"></i></span>
                        </a>
                      </div>';
    if($k%2 == 0){
      $project.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $project = '<h2 class="center">'.esc_html__('Post Not Found','tijarat-business-pro-posttype').'</h2>';
  endif;
  $project .= '</div></div>';
  return $project;
}

add_shortcode( 'projects', 'tijarat_business_pro_posttype_project_func' );


/* ----------------- Expert ---------------- */

function tijarat_business_pro_posttype_bn_designation_meta() {
    add_meta_box( 'tijarat_business_pro_posttype_bn_meta', __( 'Enter Designation','tijarat-business-pro-posttype' ), 'tijarat_business_pro_posttype_bn_meta_callback', 'experts', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'tijarat_business_pro_posttype_bn_designation_meta');
}
/* Adds a meta box for custom post */
function tijarat_business_pro_posttype_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'tijarat_business_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_designation = get_post_meta( $post->ID, 'meta-designation', true );
    $meta_team_face = get_post_meta( $post->ID, 'meta-facebookurl', true );
    $meta_team_twit = get_post_meta( $post->ID, 'meta-twitterurl', true );
    $meta_team_linkdin = get_post_meta( $post->ID, 'meta-linkdenurl', true );
    ?>
    <div id="experts_custom_stuff">
        <table id="list-table">         
          <tbody id="the-list" data-wp-lists="list:meta">
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Designation', 'tijarat-business-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_attr($meta_designation); ?>" />
                </td>
              </tr>
              <tr id="meta-3">
                <td class="left">
                  <?php esc_html_e( 'Facebook Url', 'tijarat-business-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_attr($meta_team_face); ?>" />
                </td>
              </tr>
              <tr id="meta-5">
                <td class="left">
                  <?php esc_html_e( 'Twitter Url', 'tijarat-business-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_attr($meta_team_twit); ?>" />
                </td>
              </tr>
              <tr id="meta-6">
                <td class="left">
                  <?php esc_html_e( 'linkedin URL', 'tijarat-business-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-linkdenurl" id="meta-linkdenurl" value="<?php echo esc_attr($meta_team_linkdin); ?>" />
                </td>
              </tr>              
          </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function tijarat_business_pro_posttype_bn_metadesig_experts_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', sanitize_text_field($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', sanitize_text_field($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url_raw($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', sanitize_text_field($_POST[ 'meta-designation' ]) );
    }
}
add_action( 'save_post', 'tijarat_business_pro_posttype_bn_metadesig_experts_save' );

/* experts shorthcode */
function tijarat_business_pro_posttype_experts_func( $atts ) {
    $experts = ''; 
    $custom_url ='';
    $experts = '<div id="our-experts">
                  <div class="row">';
    $query = new WP_Query( array( 'post_type' => 'experts' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=experts'); 
    while ($new->have_posts()) : $new->the_post();
      $post_id = get_the_ID();
      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      $url = $thumb['0'];
      $excerpt = wp_trim_words(get_the_excerpt(),15);
      $designation= get_post_meta($post_id,'meta-designation',true);
      $call= get_post_meta($post_id,'meta-call',true);
      $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
      $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
      $twitter=get_post_meta($post_id,'meta-twitterurl',true);
      $experts .= '<div class="col-lg-4 col-md-6 experts-content-div">
                    <div class="experts-content">
                      <div class="experts-img">
                        <img class="client-img" src="'.esc_url($thumb_url).'" alt="experts-thumbnail" />
                      </div>
                      <div class="experts-data">
                        <a href="'.esc_url($custom_url).'"><h3 class="experts-heading">'.esc_html(get_the_title()) .'</h3></a>
                        <div class="experts-text">'
                          .$designation.'</div>
                          <div class="experts-icons" id="experts-icons">
                            <a href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                            <a href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                            <a href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                          </div>
                        </div>
                      </div>
                      <a href="'.esc_url($custom_url).'">
                        <h3 class="experts-heading">'.esc_html(get_the_title()) .'</h3>
                      </a>
                      <a href="'.esc_url($custom_url).'">
                        <span class="experts-icon"><i class="fa fa-angle-right"></i></span>
                      </a>
                    </div>';
  $k++;         
  endwhile; 
  wp_reset_postdata();
  $experts.= '</div></div>';
  else :
    $experts = '<h2 class="center">'.esc_html_e('Not Found','tijarat-business-pro-posttype').'</h2>';
  endif;
  return $experts;
}
add_shortcode( 'experts', 'tijarat_business_pro_posttype_experts_func' );


/* Faq shortcode */
function tijarat_business_pro_posttype_faq_func( $atts ) {
  $faq = '';
  $faq = '<div id="our-faq">
          <div id="accordion" class="row">';
  $query = new WP_Query( array( 'post_type' => 'faq') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=faq');
  while ($new->have_posts()) : $new->the_post();
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $desigstory= get_post_meta($post_id,'tijarat_business_pro_posttype_faq_desigstory',true);
        $faq .= '
         <div class="col-lg-6 col-md-6 faq">
          <div class="card">
            <div class="card-header card-header-'.esc_attr($k).'" id="heading'.esc_attr($k).'">
              <a href="#panelBody'.esc_attr($k).'" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                <div class="row">
                  <span class="faq-i">
                    <i class="fas fa-plus"></i>
                  </span>
                  <b class="panel-title">'.get_the_title().'</b>                    
                </div>
              </a>
            </div>
            <div id="panelBody'.esc_attr($k).'" class="panel-collapse collapse in">
            <div class="panel-body">
                <p>'.get_the_content().'</p>
              </div>
            </div>
          </div>
          </div>';
    if($k%2 == 0){
      $faq.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $faq = '<h2 class="center">'.esc_html__('Post Not Found','vw-lawyer-pro-posttype-pro').'</h2>';
  endif;
  $faq .= '</div></div>';
  return $faq;
}
add_shortcode( 'list-faq', 'tijarat_business_pro_posttype_faq_func' );

/* Testimonial section */
/* Adds a meta box to the Testimonial editing screen */
function tijarat_business_pro_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'tijarat-business-pro-posttype-testimonial-meta', __( 'Enter Designation', 'tijarat-business-pro-posttype' ), 'tijarat_business_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'tijarat_business_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function tijarat_business_pro_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'tijarat_business_pro_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  $desigstory = get_post_meta( $post->ID, 'tijarat_business_pro_posttype_testimonial_desigstory', true );
  $facebook = get_post_meta( $post->ID, 'tijarat_business_pro_posttype_testimonial_facebookurl', true );
  $twitter = get_post_meta( $post->ID, 'tijarat_business_pro_posttype_testimonial_twitterurl', true );
  $googleplus = get_post_meta( $post->ID, 'tijarat_business_pro_posttype_testimonial_googleplusurl', true );
  $pinterest = get_post_meta( $post->ID, 'tijarat_business_pro_posttype_testimonial_pinteresturl', true );
	$instagram = get_post_meta( $post->ID, 'tijarat_business_pro_posttype_testimonial_instagramurl', true );
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
                  <?php _e( 'Designation', 'tijarat-business-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="tijarat_business_pro_posttype_testimonial_desigstory" id="tijarat_business_pro_posttype_testimonial_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
                  <?php _e( 'Facebook Url', 'tijarat-business-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="tijarat_business_pro_posttype_testimonial_facebookurl" id="tijarat_business_pro_posttype_testimonial_facebookurl" value="<?php echo esc_url($facebook); ?>" />
          </td>
        </tr>
        <tr id="meta-5">
          <td class="left">
            <?php esc_html_e( 'Twitter Url', 'tijarat-business-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="tijarat_business_pro_posttype_testimonial_twitterurl" id="tijarat_business_pro_posttype_testimonial_twitterurl" value="<?php echo esc_url( $twitter); ?>" />
          </td>
        </tr>
        <tr id="meta-6">
          <td class="left">
            <?php esc_html_e( 'GooglePlus URL', 'tijarat-business-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="tijarat_business_pro_posttype_testimonial_googleplusurl" id="tijarat_business_pro_posttype_testimonial_googleplusurl" value="<?php echo esc_url($googleplus); ?>" />
          </td>
        </tr>
        <tr id="meta-7">
          <td class="left">
            <?php esc_html_e( 'Pinterest URL', 'tijarat-business-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="tijarat_business_pro_posttype_testimonial_pinteresturl" id="tijarat_business_pro_posttype_testimonial_pinteresturl" value="<?php echo esc_url($pinterest); ?>" />
          </td>
        </tr>
        <tr id="meta-8">
          <td class="left">
            <?php esc_html_e( 'Instagram URL', 'tijarat-business-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="tijarat_business_pro_posttype_testimonial_instagramurl" id="tijarat_business_pro_posttype_testimonial_instagramurl" value="<?php echo esc_url($instagram); ?>" />
          </td>
        </tr>
        
      </tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function tijarat_business_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['tijarat_business_pro_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['tijarat_business_pro_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'tijarat_business_pro_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'tijarat_business_pro_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'tijarat_business_pro_posttype_testimonial_desigstory']) );
	}
   // Save facebookurl
    if( isset( $_POST[ 'tijarat_business_pro_posttype_testimonial_facebookurl' ] ) ) {
        update_post_meta( $post_id, 'tijarat_business_pro_posttype_testimonial_facebookurl', esc_url_raw($_POST[ 'tijarat_business_pro_posttype_testimonial_facebookurl' ]) );
    }
    // Save twitterurl  
    if( isset( $_POST[ 'tijarat_business_pro_posttype_testimonial_twitterurl' ] ) ) {
        update_post_meta( $post_id, 'tijarat_business_pro_posttype_testimonial_twitterurl', esc_url_raw($_POST[ 'tijarat_business_pro_posttype_testimonial_twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'tijarat_business_pro_posttype_testimonial_googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'tijarat_business_pro_posttype_testimonial_googleplusurl', esc_url_raw($_POST[ 'tijarat_business_pro_posttype_testimonial_googleplusurl' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'tijarat_business_pro_posttype_testimonial_pinteresturl' ] ) ) {
        update_post_meta( $post_id, 'tijarat_business_pro_posttype_testimonial_pinteresturl', esc_url_raw($_POST[ 'tijarat_business_pro_posttype_testimonial_pinteresturl' ]) );
    }

     // Save Instagram
    if( isset( $_POST[ 'tijarat_business_pro_posttype_testimonial_instagramurl' ] ) ) {
        update_post_meta( $post_id, 'tijarat_business_pro_posttype_testimonial_instagramurl', esc_url_raw($_POST[ 'tijarat_business_pro_posttype_testimonial_instagramurl' ]) );
    }

}

add_action( 'save_post', 'tijarat_business_pro_posttype_bn_metadesig_save' );

/* Testimonials shortcode */
function tijarat_business_pro_posttype_testimonial_func( $atts ) {
	$testimonial = '';
	$testimonial = '<div id="testimonials">
                    <div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $desigstory= get_post_meta($post_id,'tijarat_business_pro_posttype_testimonial_desigstory',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '
          <div class="col-lg-4 col-md-6">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="testimonial-data-srtcd">
                  <div class="testimonials-icon-srtcd"><i class="fa fa-quote-left"></i></div>
                  <div class="testimonials-info">'.$excerpt.'</div>
                  <div class="row">
                    <div class="col-lg-5 col-md-5">
                      <div class="testimonials-img-srtcd">
                        <img src="'.esc_url($thumb_url).'" />
                      </div>
                    </div>
                    <div class="col-lg-7 col-md-7"> 
                      <h5 class="shortcodetesti-title"> <a href="'.$custom_url.'">'.esc_html(get_the_title()) .'</a></h5>
                      <span class="shortcodetesti-desig">'
                        .$desigstory.
                      '</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>';
           
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$testimonial = '<h2 class="center">'.esc_html__('Post Not Found','tijarat-business-pro-posttype-pro').'</h2>';
  endif;
  $testimonial .= '</div></div>';
  return $testimonial;
}

add_shortcode( 'testimonials', 'tijarat_business_pro_posttype_testimonial_func' );