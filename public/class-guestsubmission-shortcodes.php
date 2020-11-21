<?php
//Function for form template
function ajaxpost_show_post() { 
	$user = wp_get_current_user();
	if ( in_array( 'author', (array) $user->roles ) ) { ?>
		<form id="ajaxpostform" action="" method="post" enctype="multipart/form-data">

			<div id="ajaxpost-text">

			<span id="ajaxpost-response" style="background-color:#E6E6FA ;color:blue;"></span>
			<div>
                <label>Post Title</label>
               	<input type="text" id="ajaxpostname" name="ajaxpostname"/>
            </div>
			
            <div>
            	<label >Custom Post Type</label>
                <select class="ajaxposttype" name="ajaxposttype" id="ajaxposttype">
                    <option value="">Select Custom Post Type</option>
                    <?php foreach ( get_post_types( '', 'names' ) as $post_type ) {
                    	echo '<option value="'.$post_type.'">'.strtoupper($post_type).'</option>';
                    } ?>
                </select>
            </div>

            <div>
                <label>Post Description</label>
                <textarea id="ajaxpostcontents" name="ajaxpostcontents"  rows="10" cols="20"></textarea>
            </div>

			<div>
                <label>Post Excerpt</label>
                <input type="text" id="ajaxpostexcerpt" name="ajaxpostexcerpt"/>
            </div>

			<div>
                <label>Post Image</label>
                <input type="file" name="ajaxpostimage" id="ajaxpostimage" class="post_image"/>
            </div>
            <input type="hidden" value="<?php echo get_current_user_id();?>" name="ajaxpostauthor" id="ajaxpostauthor">
			<a onclick="ajaxformsendmail();" style="cursor: pointer"><b>Send</b></a>

			</div>

		</form>
<?php }else{
		echo '<p>'.__('Only authors can access this form.','guestsubmission').'</p>';
	} 
}
//Ajaxfunction to insert post
function ajaxpost_send_mail() {
	$results = '';
	$error = 0;
	$name = $_POST['ajaxpostname'];
	$posttype = $_POST['ajaxposttype'];
	$postexcerpt = $_POST['ajaxpostexcerpt'];
	$contents = $_POST['ajaxpostcontents'];
	$author = $_POST['ajaxpostauthor'];
	$user_last = get_user_meta( $author, 'last_name', true );
	$user_first = get_user_meta( $author, 'first_name', true );
	$full_name = $user_first. ' '.$user_last;
	if( strlen( $name ) == 0 ) {
		$results = "Name is invalid.";
		$error = 1;
	} elseif( strlen( $posttype ) == 0 ) {
		$results = "Posttype is invalid.";
		$error = 1;
	}  elseif( strlen( $contents ) == 0 ) {
		$results = "Content is invalid.";
		$error = 1;
	} elseif( strlen( $postexcerpt ) == 0 ) {
		$results = "Post excerpt is invalid.";
		$error = 1;
	} elseif( $_FILES['ajaxpostimage']['size'] == 0 ) {
		$results = "Please select image.";
		$error = 1;
	}
	if( $error == 0 ) {
	
	    $args = array(
	      'post_type' => $posttype,
	      'post_status' => 'draft',
	      'post_title' => $name,
	      'post_content' => $contents,
	      'post_excerpt' => $postexcerpt,
	      'post_author' => $author
	    );
	    $post_id = wp_insert_post($args);
	    
	    		if($_FILES){
	                if($_FILES['ajaxpostimage']['name'] != ''){
	                    if ($_FILES['ajaxpostimage']['error'] !== UPLOAD_ERR_OK) {
	                        return "upload error : " . $_FILES['ajaxpostimage']['error'];
	                    }else{
	                        $ajaxpostimageoverride = array( 'test_form' => false );
	                        $ajaxpostimagetimes = current_time( 'mysql' );
	                        $ajaxpostimagefiles = wp_handle_upload( $_FILES[ 'ajaxpostimage' ], $ajaxpostimageoverride, $ajaxpostimagetimes );
	                       
	                        
	                        $ajaxpostimageurls     = $ajaxpostimagefiles['url'];
	                        $ajaxpostimagetypes    = $ajaxpostimagefiles['type'];
	                        $ajaxpostimagefiles    = $ajaxpostimagefiles['file'];
	                        $ajaxpostimagename = $_FILES['name'];
	                        $ajaxpostimagetitles   = sanitize_text_field( $ajaxpostimagename );
	                        $ajaxpostimagepost_data = array();
	                        $ajaxpostimagecontents = '';
	                        $ajaxpostimageexcerpts = '';
	                        $ajaxpostimageattachments = array_merge(
	                          array(
	                              'post_mime_type' => $ajaxpostimagetypes,
	                              'guid'           => $ajaxpostimageurls,
	                              'post_parent'    => 0,
	                              'post_title'     => $ajaxpostimagetitles,
	                              'post_content'   => $ajaxpostimagecontents,
	                              'post_excerpt'   => $ajaxpostimageexcerpts,
	                          ),
	                          $ajaxpostimagepost_data
	                        );
	                        $ajaxpostimageattachment_ids = wp_insert_attachment( $ajaxpostimageattachments, $ajaxpostimagefiles, 0, true );
	                    }
                	}
	                update_post_meta($post_id,'_thumbnail_id',$ajaxpostimageattachment_ids);
	          		$results = 'Post Inserted successfully.Admin will review soon and approve your post.';
		          	$to = 'roshniahuja14@gmail.com';
					$subject = 'Page/Post Moderation';
					$body = 'Hello Admin,';
					$body .= $full_name.' user created one post. Please review and approve.';
					$headers = array('Content-Type: text/html; charset=UTF-8');
					wp_mail( $to, $subject, $body, $headers );
					
	          	}
	
	}
	// Return the String
 
	die($results);
}
// creating Ajax call for WordPress
add_action( 'wp_ajax_nopriv_ajaxpost_send_mail', 'ajaxpost_send_mail' );
add_action( 'wp_ajax_ajaxpost_send_mail', 'ajaxpost_send_mail' );
//Shortcode for form
function ajaxpost_shortcode_func( $atts ) {
	ob_start();
	ajaxpost_show_post();
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'guest_post_form', 'ajaxpost_shortcode_func' );
//Shortcode to display pending post of particular user
function userwise_pending_post( $atts ) {
	$user_id = get_current_user_id();
	$return_string = '<ul>';
   query_posts(
   	array(
   		'post_type' => 'guestposts',
   		'orderby' => 'date', 
   		'order' => 'DESC' , 
   		'posts_per_page' => -1,
   		'post_status' => 'draft',
   		'author' => $user_id
   	));
   	$user = wp_get_current_user();
	if ( in_array( 'author', (array) $user->roles ) ) {
   		if (have_posts()) :
	      while (have_posts()) : the_post();
	      	$featured_img_url = get_the_post_thumbnail_url($post->ID, 'full'); 
	 		$return_string .= '<a href="'.get_the_permalink().'" class="trigger_popup_fricc"><img src="'.$featured_img_url.'">'.get_the_title().'</a>
	         <p>'.get_the_content().'</p></li>
	        ';
	      endwhile;
   		endif;
   	}else{
   		echo '<p>'.__('Only authors can see post list.','guestsubmission').'</p>';
   	}
   $return_string .= '</ul>';

   wp_reset_query();
   return $return_string;
}
add_shortcode( 'userwise_pending_posts', 'userwise_pending_post' );
//Shortcode to list pending posts with pagination
function pending_postlist() {
	$return_string = '<ul>';
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$args = array(
	    'post_type'=>'guestposts', // Your post type name
	    'orderby' => 'date', 
   		'order' => 'DESC' , 
   		'post_status' => 'draft',
	    'posts_per_page' => 10,
	    'paged' => $paged,
	);

	$loop = new WP_Query( $args );
	if ( $loop->have_posts() ) {
	    while ( $loop->have_posts() ) : $loop->the_post();
			$featured_img_url = get_the_post_thumbnail_url($post->ID, 'full'); 
 			$return_string .= '<a href="'.get_the_permalink().'" class="trigger_popup_fricc"><img src="'.$featured_img_url.'">'.get_the_title().'</a>
         		<p>'.get_the_content().'</p></li>';
	    endwhile;

	    $total_pages = $loop->max_num_pages;

	    if ($total_pages > 1){

	        $current_page = max(1, get_query_var('paged'));

	        $return_string .= paginate_links(array(
	            'base' => get_pagenum_link(1) . '%_%',
	            'format' => '/page/%#%',
	            'current' => $current_page,
	            'total' => $total_pages,
	            'prev_text'    => __('« prev'),
	            'next_text'    => __('next »'),
	        ));
	    }    
	}
	$return_string .= '</ul>';	
	wp_reset_postdata();
   return $return_string;
}
add_shortcode( 'pending_postslist', 'pending_postlist' );
?>
<script type="text/javascript">
	var ajaxurl= '<?php echo admin_url( 'admin-ajax.php' ) ?>';
	function ajaxformsendmail() {
		    var fd = new FormData(jQuery('#ajaxpostform')[0]);
		    fd.append( "ajaxpostimage", jQuery('#ajaxpostimage')[0].files[0]);
		    fd.append( "action", 'ajaxpost_send_mail');
		   //Append here your necessary data
		    jQuery.ajax({
		        type: 'POST',
		        url: ajaxurl,
		        data: fd, 
		        processData: false,
		        contentType: false,
		        success: function(data, textStatus, XMLHttpRequest) {
		            var id = '#ajaxpost-response';
		            jQuery(id).html('');
		            jQuery(id).append(data);
		            //jQuery('form#ajaxpostform').trigger("reset");
		            
		        },
		        error: function(MLHttpRequest, textStatus, errorThrown) {
		            alert(errorThrown);
		        }

		    });
	}
</script>