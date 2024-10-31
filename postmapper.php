<?php
/*
Plugin Name: PostMapper
Plugin URI: http://yourdomain.com/
Description: Allows you to manage realty listings via custom post type
Version: 1.2
Author: Don Kukral
Author URI: http://yourdomain.com
License: GPL
*/

add_action('init', 'postmapper_register');

function postmapper_register() {
	$labels = array( 
		'name' => _x('Post Mapper', 'post type general name'), 
		'singular_name' => _x('Property Item', 'post type singular name'), 
		'add_new' => _x('Add New Listing', 'property item'), 
		'add_new_item' => __('Add New Listing'), 
		'edit_item' => __('Edit Listing'), 
		'new_item' => __('New Listing'), 
		'view_item' => __('View Listing'), 
		'search_items' => __('Search Listings'), 
		'not_found' => __('Nothing found'), 'not_found_in_trash' => __('Nothing found in Trash'), 
		'parent_item_colon' => '' 
	);
		
	$args = array(
		'labels' => $labels, 
		'public' => true, 
		'publicly_queryable' => true, 
		'show_ui' => true, 
		'query_var' => true, 
		#'menu_icon' => get_stylesheet_directory_uri() . '/article16.png', 
		'rewrite' => true, 
		'capability_type' => 'post', 
		'hierarchical' => false, 
		'menu_position' => null, 
		'supports' => array('title', 'thumbnail') 
	); 

	register_post_type( 'postmapper' , $args );
#	register_taxonomy("Type", array("postmapper"), array("hierarchical" => true, "label" => "Types", "singular_label" => "Type", "rewrite" => true));


}

add_action('admin_init', 'admin_init');

function admin_init() {
	add_meta_box('postmapper-meta-box', 'Listing', 'postmapper_address', 'postmapper', 'normal', 'core');
}

function postmapper_address() {
	global $post; 
	$custom = get_post_custom($post->ID);
	$postmapper_address = $custom['postmapper_address'][0];
	$postmapper_city = $custom['postmapper_city'][0];
	$postmapper_state = $custom['postmapper_state'][0];
	$postmapper_zipcode = $custom['postmapper_zipcode'][0];
	$postmapper_phone = $custom['postmapper_phone'][0];
	$postmapper_email = $custom['postmapper_email'][0];
	$postmapper_rent = $custom['postmapper_rent'][0];
	$postmapper_beds = $custom['postmapper_beds'][0];
	$postmapper_baths = $custom['postmapper_baths'][0];
	$postmapper_geocode = $custom['postmapper_geocode'][0];
	$postmapper_property_type = $custom['postmapper_property_type'][0];
	$postmapper_neighborhoods = $custom['postmapper_neighborhoods'][0];
	$postmapper_pets = $custom['postmapper_pets'][0];
	$postmapper_paid = $custom['postmapper_paid'][0];
	$postmapper_website = $custom['postmapper_website'][0];
	$postmapper_details = $custom['postmapper_details'][0];
    $postmapper_notes = $custom['postmapper_notes'][0];
    
	$url = plugins_url() . '/postmapper/js/jquery.validate.js';
	$ptypes = get_option('postmapper_property_types', array('Apartment', 'House', 'Room'));
    if (!is_array($ptypes)) { $ptypes = unserialize($ptypes); }
    
    $neighborhoods = get_option('postmapper_neighborhoods', array('West Davis', 'University', 'Downtown', 'El Macero'));
    if (!is_array($neighborhoods)) { $neighborhoods = unserialize($neighborhoods); }
    
?>
    <script type="text/javascript" src="<?php echo $url; ?>"></script>
    <style type="text/css">
        label { width: 10em; float: left; }
        label.error { float: none; color: pink; padding-left: .5em; vertical-align: top; }
        input.error { border: 1px solid red; background-color: pink; }
    </style>
	<table>
	<tr>
	<td colspan="2">
	<b>Bold Fields required</b>
	</td>
	</tr>
	<tr>
	<td><b>Rent:</b></td>
	<td>$<input type="text" class="required number" name="postmapper_rent" size="5" value="<?php echo $postmapper_rent ?>"/>
	# of Bedrooms: <input type="text" name="postmapper_beds" value="<?php echo $postmapper_beds ?>" size="3" class="number"/>
	# of Bathrooms: <input type="text" name="postmapper_baths" value="<?php echo $postmapper_baths ?>" size="3" class="number"/>
	</tr>
	<tr>
	<td colspan="2">
    	Property Type:
    	<select name="postmapper_property_type"><option>---</option>
    	<?php foreach ($ptypes as $p) { 
    	    if ($p == $postmapper_property_type) { $s = ' selected="selected"'; } else { $s = ''; }
    	    echo '<option'. $s .'>' . $p . '</option>'; 
    	    } 
    	?>
    	</select>
    	Neighborhood:
    	<select name="postmapper_neighborhoods"><option>---</option>
    	<?php foreach ($neighborhoods as $p) { 
    	    if ($p == $postmapper_neighborhoods) { $s = ' selected="selected"'; } else { $s = ''; }
    	    echo '<option'. $s .'>' . $p . '</option>'; 
    	    } 
    	?>
    	</select>
    	&nbsp;&nbsp;<input type="checkbox" name="postmapper_pets" value="1" <?php checked($postmapper_pets, 1); ?>/> Pets OK?
    	&nbsp;&nbsp;<input type="checkbox" name="postmapper_paid" value="1" <?php checked($postmapper_paid, 1); ?>/> Paid?
	</td>
	</tr>
    <tr>
	<td valign="top">Details:</td>
	<td><textarea name="postmapper_details" style="width: 370px;"><?php echo $postmapper_details ?></textarea></td>
	</tr>
	<tr>
	<td><b>Address:</b></td>
	<td><input type="text" class="required" name="postmapper_address" size="50" value="<?php echo $postmapper_address ?>"/></td>
	</tr>
	<tr>
	<td><b>City:</b></td>
	<td><input type="text" class="required" name="postmapper_city" size="30" value="<?php echo $postmapper_city ?>"/>&nbsp;
	<b>State:</b>
	<input type="text" class="required" name="postmapper_state" size="10" value="<?php echo $postmapper_state ?>"/>
	</td>
	</tr>
	<tr>
	<td><b>Zip Code:</b></td>
	<td><input type="text" class="required" name="postmapper_zipcode" size="8" value="<?php echo $postmapper_zipcode ?>"/></td>
	</tr>
	<tr>
	<td>Phone:</td>
	<td><input type="text" name="postmapper_phone" size="20" value="<?php echo $postmapper_phone ?>"/></td>
	</tr>
	<tr>
	<td>Email:</td>
	<td><input type="text" name="postmapper_email" size="50" value="<?php echo $postmapper_email ?>" class="email"/></td>
	</tr>
	<tr>
	<td>Website:</td>
	<td><input type="text" name="postmapper_website" size="50" value="<?php echo $postmapper_website ?>" class="url"/></td>
	</tr>
	<tr>
	<td>Geocode:</td>
	<td><input type="text" value="<?php echo $postmapper_geocode; ?>" size="30" disabled="disabled"/></td>
	</tr>	
	<tr>
	<td valign="top">Notes:</td>
	<td><textarea name="postmapper_notes" style="width: 370px;"><?php echo $postmapper_notes ?></textarea></td>
	</tr>
	</table>
	
	<script>
	var $j = jQuery.noConflict();
    
	$j(document).ready(function(){
	    var f = $j("form[name='post']");

        $j(f).submit(function(e) {
	       //console.log(e.target.id);

	       $j(f).validate({
                 errorPlacement: function(error, element) { }
           });
           
           if ($j(f).valid())
           {
               if (e.target.id == 'save-post') { $j("#draft-ajax-loading").show(); }
               if (e.target.id == 'publish') { $j("#ajax-loading").show(); }
                return true;

           } else {
               alert("Highlighted fields are required."); 
               $j("#ajax-loading").hide();
               $j("#draft-ajax-loading").hide();
               return false;
           }
	    });
    }); 
    </script>
<?php	
}

add_action('save_post', 'save_details', 10, 2);

function save_details($pid, $post) {
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' ) return $pid;
    if ( $post->post_type != 'postmapper' ) return $pid;
    if ($_POST['action'] != 'editpost') { return $pid; }    
    
    global $wpdb;
    
    $ch = curl_init();
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . 
		str_replace(" ", "+", $_POST['postmapper_address'])  . ",+" . str_replace(" ", "+", $_POST['postmapper_city']) . 
		",+" . str_replace(" ", "+", $_POST['postmapper_state']) . "+" . 
		str_replace(" ", "+", $_POST['postmapper_zipcode']) . "&sensor=false";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 

	$result = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($result);
	$location = "Unknown";
	if (in_array('street_address', $data->{'results'}[0]->{'types'})) {
		$lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		$lng = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
		$location = $lat . "," . $lng;
	}
	
	update_post_meta($post->ID, 'postmapper_address', $_POST['postmapper_address']);
	update_post_meta($post->ID, 'postmapper_city', $_POST['postmapper_city']);
	update_post_meta($post->ID, 'postmapper_state', $_POST['postmapper_state']);
	update_post_meta($post->ID, 'postmapper_zipcode', $_POST['postmapper_zipcode']);
	update_post_meta($post->ID, 'postmapper_phone', $_POST['postmapper_phone']);
	update_post_meta($post->ID, 'postmapper_email', $_POST['postmapper_email']);
	update_post_meta($post->ID, 'postmapper_rent', $_POST['postmapper_rent']);
	update_post_meta($post->ID, 'postmapper_beds', $_POST['postmapper_beds']);
	update_post_meta($post->ID, 'postmapper_baths', $_POST['postmapper_baths']);
    update_post_meta($post->ID, 'postmapper_property_type', $_POST['postmapper_property_type']);
    update_post_meta($post->ID, 'postmapper_neighborhoods', $_POST['postmapper_neighborhoods']);	    
    if ($_POST['postmapper_pets'] == '1') { update_post_meta($post->ID, 'postmapper_pets', "1"); }
    else { update_post_meta($post->ID, 'postmapper_pets', "0"); }
    if ($_POST['postmapper_paid'] == '1') { update_post_meta($post->ID, 'postmapper_paid', "1"); }
    else { update_post_meta($post->ID, 'postmapper_paid', "0"); }
	update_post_meta($post->ID, 'postmapper_website', $_POST['postmapper_website']);
	update_post_meta($post->ID, 'postmapper_details', $_POST['postmapper_details']);
	update_post_meta($post->ID, 'postmapper_notes', $_POST['postmapper_notes']);
	
	$sql = "SELECT count(p.ID) as c_posts FROM " . $wpdb->posts . " as p ";
	$sql .= "JOIN " . $wpdb->postmeta . " pm ON pm.post_id = p.ID ";
    $sql .= "WHERE pm.meta_value = '" . $location . "'";
    $sql .= "AND pm.meta_key = 'postmapper_geocode' AND p.ID <> " . $pid . " AND p.post_status = 'publish'";
            
	$row = $wpdb->get_row($sql);
	
	if ($location == 'Unknown') {
        remove_action('save_post', 'save_details', 10, 2);
        wp_update_post(array('ID' => $post->ID, 'post_status' => 'draft'));
        add_action('save_post', 'save_details', 10, 2);
        wp_redirect(admin_url() . "post.php?post=".$post->ID."&action=edit&postmapper_message=2");
        exit;
	} elseif ($row->c_posts > 0) {
        remove_action('save_post', 'save_details', 10, 2);
        wp_update_post(array('ID' => $post->ID, 'post_status' => 'draft'));
        add_action('save_post', 'save_details', 10, 2);
        wp_redirect(admin_url() . "post.php?post=".$post->ID."&action=edit&postmapper_message=1");
        exit;	    
	} else {
        update_post_meta($post->ID, 'postmapper_geocode', $location);
    }
}


function my_post_redirect_filter($location) {
  remove_filter('redirect_post_location', __FILTER__, '99');
  return add_query_arg('my_message', 1, $location);
}

add_action('admin_notices', 'my_post_admin_notices');
function my_post_admin_notices() {
  if (!isset($_GET['postmapper_message'])) return;
  switch (absint($_GET['postmapper_message'])) {
    case 1:
      $message = 'Error: Duplicate Geocode';
      break;
    case 2:
      $message = 'Error: Unknown Geocode';
      break;
    default:
      $message = 'Unexpected error';
  }
  echo '<div id="notice" class="error"><p>' . $message . '</p></div>';
}

add_action('publish_post', 'postmapper_publish_post');

function publish_post($pid, $post) {
    global $wpdb;
    
    if ( $post->post_type != 'postmapper' ) return $pid;
    
    $custom = get_post_custom($post->ID);
	$postmapper_geocode = $custom['postmapper_geocode'][0];
	

} 

add_action("manage_posts_custom_column", "postmapper_custom_columns");
add_filter("manage_edit-postmapper_columns", "postmapper_edit_columns");

function postmapper_edit_columns($columns) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Title",
		"address" => "Address",
		"url" => "Website",
		"rent" => "Rent",
		"rooms" => "Bed/Bath",
	    "paid" => "Paid?",
	    "date" => "Date",
	);
	return $columns;
}

function postmapper_custom_columns($columns) {
	global $post;
	
	$custom = get_post_custom($post->ID);
	switch ($columns) {
		case "address":
//			edit_post_link($custom['postmapper_address'][0], "<b>", "</b>", $post->ID);
            echo $custom['postmapper_address'][0] . "</br>" . $custom[postmapper_city][0] . ", " . $custom['postmapper_state'][0] . " " . $custom['postmapper_zipcode'][0];
			break;
		case "city":
			echo $custom['postmapper_city'][0];
			break;
		case "state":
			echo $custom['postmapper_state'][0];
			break;
		case "zip":
			echo $custom['postmapper_zipcode'][0];
			break;
		case "rent":
			echo "$" . $custom['postmapper_rent'][0];
			break;
		case "rooms":
			echo $custom['postmapper_beds'][0] . "/" . $custom['postmapper_baths'][0];
			break;
		case "phone":
			echo $custom['postmapper_phone'][0];
			break;
		case "email":
			echo $custom['postmapper_email'][0];
			break;
		case "url":
			if ($custom['postmapper_website'][0]) {
			    echo '<a href="' . $custom['postmapper_website'][0] . '">' . $custom['postmapper_website'][0] . '</a>';
			}
			break;
		case "paid":
		    if ($custom['postmapper_paid'][0]) { echo "Yes"; }
		    else { echo "No"; }
		    break;		
	}
}

add_filter('manage_edit-postmapper_sortable_columns', 'postmapper_sortable_columns');

function postmapper_sortable_columns($columns) {	
	$columns['paid'] = 'paid';
	return $columns;
}


add_action('load-edit.php', 'postmapper_load');

function postmapper_load() {
	add_filter('requests', 'postmapper_sort');
}

function postmapper_sort( $vars ) {
	if (isset ($vars['post_type']) && 'postmapper' == $vars['post_type']) {
		if (isset($vars['orderby'] ) && 'address' == $vars['orderby']) {
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'address',
					'orderby' => 'meta_value_number'
				)
			);
		}
	}
	return $vars;
}

add_filter('posts_where', 'postmapper_posts_where');

function postmapper_posts_where($where) {
    global $wpdb;
    if (is_admin()) {
        if ($_GET['post_type'] == 'postmapper') {
			if (isset($_GET['s'])) {
	            $where .= " OR ID IN (SELECT post_id FROM " . $wpdb->postmeta . 
				" WHERE (meta_key IN ('postmapper_address', 'postmapper_city', 'postmapper_state', 
				'postmapper_zip', 'postmapper_email', 'postmapper_phone') 
				AND meta_value LIKE '%" . $_GET['s'] . "%')) ";
			}
        }
    }

    return $where;
}

add_action('admin_menu', 'register_postmapper_options_page');

function register_postmapper_options_page() {
	add_submenu_page( 'edit.php?post_type=postmapper', 'Export', 'Export', 
	 	'publish_posts', 'register_postmapper_export_page', 'register_postmapper_export_callback' ); 
	add_submenu_page( 'edit.php?post_type=postmapper', 'Options', 'Options', 
	 	'manage_options', 'register_postmapper_options_page', 'register_postmapper_options_callback' ); 
}


function register_postmapper_export_callback() {

	$action = plugins_url() . '/postmapper/postmapper-export.php';
    
?>
    <div class="wrap">
	<div id="icon-tools" class="icon32"><br></div>
    <h2>Post Mapper Export</h2>
    <form method="GET" action="<?php echo $action; ?>">
    <input type="hidden" name="post_type" value="postmapper"/>
    <input type="hidden" name="page" value="register_postmapper_export_page"/>

    <div style="margin: 10px">
    Export by: 
    <select name="export_by">
    <option value="size" <?php selected($_GET['export_by'], 'size') ?>>Size</option>
    <option value="location" <?php selected($_GET['export_by'], 'location') ?>>Location</option>
    <option value="price" <?php selected($_GET['export_by'], 'price') ?>>Price</option>
    <option value="type" <?php selected($_GET['export_by'], 'type') ?>>Type</option>
    </select>
    <input type="submit" value="Export"/>
    </form>
    </div>
    </div>
    
<?php
        print "<pre>";
        print_r($rows);
        print "</pre>";
}

function register_postmapper_options_callback() {
    
    if ( isset($_POST['action']) && $_POST['action'] == 'update' ) {
		update_option('postmapper_default_city', $_POST['postmapper_default_city']);
		update_option('postmapper_gmaps_key', $_POST['postmapper_gmaps_key']);
		update_option('postmapper_map_height', $_POST['postmapper_map_height']);
		update_option('postmapper_map_width', $_POST['postmapper_map_width']);
		update_option('postmapper_property_types', serialize($_POST['postmapper_property_types']));
		update_option('postmapper_neighborhoods', serialize($_POST['postmapper_neighborhoods']));
		
		echo '<div class="updated"><p>Print Tags Settings Updated</p></div>';
		$ch = curl_init();
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . str_replace(" ", "+", $_POST['postmapper_default_city'])
			. "&sensor=false";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	
		$result = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($result);
		$location = "Unknown";
		if ($data->{'results'}) {
			if (in_array('political', $data->{'results'}[0]->{'types'})) {
				$lat = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
				$lng = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
				$location = $lat . "," . $lng;
			}
		}
		update_option('postmapper_default_city_geocode', $location);
	}
?>
    <div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
    <h2>Post Mapper Options</h2>
    
    <form method="post">
    <?php wp_nonce_field('update-options'); ?>
	<table class="form-table">
		<tbody>
		<tr valign="top">
			<th scope="row"><label for="postmapper_default_city"> Default City</label></th>
			<td><input name="postmapper_default_city" type="text" id="postmapper_default_city" value="<?php echo get_option( 'postmapper_default_city', "" ); ?>" class="regular-text ltr"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="postmapper_default_city_geocode"> Default City Geocode</label></th>
			<td><input name="postmapper_default_city_geocode" type="text" id="postmapper_default_city_geocode" value="<?php echo get_option( 'postmapper_default_city_geocode', "" ); ?>" class="regular-text ltr" disabled="disabled"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="postmapper_gmaps_key"> Google Maps Key</label></th>
			<td><input name="postmapper_gmaps_key" type="text" id="postmapper_gmaps_key" value="<?php echo get_option( 'postmapper_gmaps_key', "" ); ?>" class="regular-text ltr"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="postmapper_map_width"> Map Width</label></th>
			<td><input name="postmapper_map_width" type="text" id="postmapper_map_width" value="<?php echo get_option( 'postmapper_map_width', "500" ); ?>" class="regular-text ltr"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="postmapper_map_height"> Map Height</label></th>
			<td><input name="postmapper_map_height" type="text" id="postmapper_map_height" value="<?php echo get_option( 'postmapper_map_height', "350" ); ?>" class="regular-text ltr"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="postmapper_property_types">Property Type</label></th>
			<th>
                <?php
                $ptypes = get_option('postmapper_property_types', array('Apartment', 'House', 'Room'));
		        if (!is_array($ptypes)) { $ptypes = unserialize($ptypes); }
		        ?>
			    <select name="postmapper_property_types[]" id="postmapper_property_types" multiple="multiple" size="6" style="width: 150px;">
			        <?php
			        $c = 1;
			        foreach ($ptypes as $p) {
			            if ($c <= 3) { $p = "* " . $p; }
			            print '<option value="' . $c . '">' . $p . '</option>\n';
			            $c++;
			        }
			        ?>
			    </select>

			    </br><a href="" id="add_property">+ add property type</a>
			    </br><em>Double click to remove non-default types.</em>
			</th>
		</tr>		
		<tr valign="top">
			<th scope="row"><label for="postmapper_neighborhoods">Neighborhoods</label></th>
			<th>
                <?php
                $neighborhoods = get_option('postmapper_neighborhoods', array('West Davis', 'University', 'Downtown', 'El Macero'));
		        if (!is_array($neighborhoods)) { $neighborhoods = unserialize($neighborhoods); }
		        ?>
			    <select name="postmapper_neighborhoods[]" id="postmapper_neighborhoods" multiple="multiple" size="6" style="width: 150px;">
			        <?php
			        $c = 1;
			        foreach ($neighborhoods as $p) {
			            if ($c <= 4) { $p = "* " . $p; }
			            print '<option value="' . $c . '">' . $p . '</option>\n';
			            $c++;
			        }
			        ?>
			    </select>

			    </br><a href="" id="add_neighborhood">+ add neighborhood</a>
			    </br><em>Double click to remove non-default types.</em>
			</th>
		</tr>
	</table>
	<input type="hidden" name="action" value="update" />
    
	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"></p>
	</form>
	<script>
    function toTitleCase(str)
    {
        return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    }
	    var $j = jQuery.noConflict();
    
	    $j(document).ready(function(){
	        $j("#add_property").click(function() {
	            var prop = prompt("Enter new property type name:");
	            var ok = true;
	            if (prop != "") {
                    $j("#postmapper_property_types option").each(function() {
	                   if (prop.toUpperCase().replace("* ", "") == $j(this).html().toUpperCase().replace("* ", "")) {
	                       alert("Cannot add duplicate property type.");
                           ok = false;
	                   } 
	                });
	                if (ok) {
        	            var last_idx = parseInt($j("#postmapper_property_types option").last().val()) + 1;
        	            $j("#postmapper_property_types").append('<option value="' + last_idx + '">' + toTitleCase(prop) + '</option>');
        	            $j("#postmapper_property_types option").off('dblclick');
            	        $j("#postmapper_property_types option").dblclick(function() {
                            var idx = parseInt($j(this).val());
                            if (idx > 3) { 
                                $j("#postmapper_property_types option[value='" + idx + "']").remove(); 
                                var c = 1;
                                $j("#postmapper_property_types option").each(function() {
                                    $j(this).val(c);
                                    c++;
                                });
                            }
            	        });
            	    }
    	        } else {
    	            alert("Cannot add blank property type.");
    	        }
	            return false;
	        });
	        $j("#postmapper_property_types option").dblclick(function() {
                var idx = parseInt($j(this).val());
                if (idx > 3) { $j("#postmapper_property_types option[value='" + idx + "']").remove(); }
	        });
	        $j("input[type=submit]").click(function() {
	           $j("#postmapper_property_types option").attr("selected", "selected"); 
	           $j("#postmapper_property_types option").each(function() {
	              $j(this).val($j(this).html() .replace("* ", "")); 
   	           });
	        });

	        $j("#add_neighborhood").click(function() {
	            var prop = prompt("Enter new neighborhood name:");
	            var ok = true;
	            if (prop != "") {
                    $j("#postmapper_neighborhoods option").each(function() {
	                   if (prop.toUpperCase().replace("* ", "") == $j(this).html().toUpperCase().replace("* ", "")) {
	                       alert("Cannot add duplicate neighborhood.");
                           ok = false;
	                   } 
	                });
	                if (ok) {
        	            var last_idx = parseInt($j("#postmapper_neighborhoods option").last().val()) + 1;
        	            $j("#postmapper_neighborhoods").append('<option value="' + last_idx + '">' + toTitleCase(prop) + '</option>');
        	            $j("#postmapper_neighborhoods option").off('dblclick');
            	        $j("#postmapper_neighborhoods option").dblclick(function() {
                            var idx = parseInt($j(this).val());
                            if (idx > 4) { 
                                $j("#postmapper_neighborhoods option[value='" + idx + "']").remove(); 
                                var c = 1;
                                $j("#postmapper_neighborhoods option").each(function() {
                                    $j(this).val(c);
                                    c++;
                                });
                            }
            	        });
            	    }
    	        } else {
    	            alert("Cannot add blank neighborhood.");
    	        }
	            return false;
	        });
	        $j("#postmapper_neighborhoods option").dblclick(function() {
                var idx = parseInt($j(this).val());
                if (idx > 4) { $j("#postmapper_neighborhoods option[value='" + idx + "']").remove(); }
	        });
	        $j("input[type=submit]").click(function() {
	           $j("#postmapper_property_types option").attr("selected", "selected"); 
	           $j("#postmapper_property_types option").each(function() {
	              $j(this).val($j(this).html() .replace("* ", "")); 
   	           });
	           $j("#postmapper_neighborhoods option").attr("selected", "selected"); 
	           $j("#postmapper_neighborhoods option").each(function() {
	              $j(this).val($j(this).html() .replace("* ", "")); 
   	           });

	        });
	    });
	</script>
<?php

}

function postmapper_sc($atts, $content = null) {
	$width = get_option('postmapper_map_width', '750');
	$height = intval(get_option('postmapper_map_height', '500')) + 350;
	$src = plugins_url() . '/postmapper/postmapper-map.php';
   return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'?output=embed"></iframe>';
}
add_shortcode("postmapper", "postmapper_sc");


