<?php
/**
 * Plugin Name: Tekserve Vendors
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Custom Post Type for Vendors; Includes Custom Fields
 * Version: 1.1
 * Author: Chad A. Carino
 * Author URI: http://www.chadacarino.com
 * License: MIT
 */
/*
The MIT License (MIT)
Copyright (c) 2015 Chad A. Carino
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/



//used for conditional enqueing. standard method for all of our plugins.
$tekserve_vendor_queue = array();


//include css to format vendor(s) 
function register_tekserve_vendors_styles() {

	wp_register_style ( 'tekserve_vendors_css', plugins_url() . '/tekserve-vendors/tekserve_vendors.css', array(), '1.1' );

}	//end include_tekserve_vendors_style()

add_action( 'wp_enqueue_scripts', 'register_tekserve_vendors_styles' );

function tekserve_vendor_enqueue() {

	
	global $tekserve_vendor_queue;
	$tekserve_vendor_queue['tekserve_vendors_css'] = 'css';
	foreach( $tekserve_vendor_queue as $item => $type ) {
	
		if( $type == 'css' ) {
		
			wp_enqueue_style( $item );
		
		}	//end if( $type == 'css' )
		
		if( $type == 'js' ) {
		
			wp_enqueue_script( $item );
		
		}	//end if( $type == 'js' )
		
	}	//end foreach( $tekserve_vendor_queue as $item => $type )
	
}	//end tekserve_vendor_enqueue()



//create custom post type
add_action( 'init', 'create_post_type_tekserve_vendor' );

function create_post_type_tekserve_vendor() {

	register_post_type( 'tekserve_vendors',
		array(
			'labels' => array(
				'name' => __( 'Vendors' ),
				'singular_name' => __( 'Vendor' ),
				'add_new' => 'Add New',
            	'add_new_item' => 'Add New Vendor',
            	'edit' => 'Edit',
            	'edit_item' => 'Edit Vendor',
            	'new_item' => 'New Vendor',
            	'view' => 'View',
            	'view_item' => 'View Vendor',
            	'search_items' => 'Search Vendors',
            	'not_found' => 'No Vendors found',
            	'not_found_in_trash' => 'No Vendors found in Trash',
            	'parent' => 'Parent Vendors',
			),
			$rewrite = array(
				'slug'                => 'vendor',
				'with_front'          => true,
				'pages'               => true,
				'feeds'               => true,
			),
			'public' => true,
			'has_archive' => true,
            'supports' => array( 'editor', 'thumbnail', 'title', 'post-formats', ),
		)
	);	//end register_post_type

}	//end function create_post_type_tekserve_vendor()




//create custom fields for name and organization
add_action( 'admin_init', 'tekserve_vendors_custom_fields' );

function tekserve_vendors_custom_fields() {

    add_meta_box( 'tekserve_vendors_meta_box', 'Vendor Details', 'display_tekserve_vendors_meta_box', 'tekserve_vendors', 'normal', 'high' );

}	//end tekserve_vendors_custom_fields()




// Retrieve current details based on vendor ID
function display_tekserve_vendors_meta_box( $tekserve_vendors ) {

	//get saved data and create nonce field
	wp_nonce_field( 'tekserve_vendors_meta_box', 'tekserve_vendors_nonce' );
	$tekserve_vendors_quote_bgcolor = esc_html( get_post_meta( $tekserve_vendors->ID, 'tekserve_vendors_quote_bgcolor', true ) );
    $tekserve_vendors_quote = esc_html( get_post_meta( $tekserve_vendors->ID, 'tekserve_vendors_quote', true ) );
	$tekserve_vendors_cs_link = esc_html( get_post_meta( $tekserve_vendors->ID, 'tekserve_vendors_cs_link', true ) );
	$tekservevendorsboilerplate = get_post_meta( $tekserve_vendors->ID, 'tekservevendorsboilerplate', true );

	$settings = array( 
		'textarea_rows' => 20
	);
	//input form
	?>
	
    <table>
    	<tr>
            <td style="width: 100%">Quote Background&nbsp;&nbsp;
            <select name="tekserve_vendors_quote_bgcolor">
				<option value="white" <?php selected( $tekserve_vendors_quote_bgcolor, 'white' ); ?>>White</option>
				<option value="darkblue" <?php selected( $tekserve_vendors_quote_bgcolor, 'darkblue' ); ?>>Dark Blue</option>
				<option value="lightblue" <?php selected( $tekserve_vendors_quote_bgcolor, 'lightblue' ); ?>>Light Blue</option>
				<option value="orange" <?php selected( $tekserve_vendors_quote_bgcolor, 'orange' ); ?>>Orange</option>
			</select></td>
		</tr>
        <tr>
            <td style="width: 100%">Quote</td>
        </tr>
        <tr>
            <td style="width: 100%; margin-bottom: 2em;"><input type="text" size="60" name="tekserve_vendors_quote" value="<?php echo $tekserve_vendors_quote; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Link to Case Study</td>
        </tr>
        <tr>
            <td style="width: 100%; margin-bottom: 2em;"><input type="url" size="60" name="tekserve_vendors_cs_link" value="<?php echo $tekserve_vendors_cs_link; ?>" /></td>
        </tr>
         <tr>
            <td style="width: 100%">Vendor Provided Boilerplate</td>
        </tr>
        <tr>
            <td style="width: 100%; margin-bottom: 2em;"><?php wp_editor( $tekservevendorsboilerplate, 'tekservevendorsboilerplate', $settings ); ?></td>
        </tr>
    </table>
    
    <?php

}	//end display_tekserve_vendors_meta_box( $tekserve_vendors )




//store custom field data
add_action( 'save_post', 'add_tekserve_vendors_fields', 5, 2 );

function add_tekserve_vendors_fields( $tekserve_vendors_id, $tekserve_vendors ) {

	//check security
	if ( ! isset( $_POST['tekserve_vendors_nonce'] ) ) {
	
    	return $tekserve_vendors_id;
    
    }	//end if ( ! isset( $_POST['tekserve_vendors_nonce'] ) )
    $nonce = $_POST['tekserve_vendors_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'tekserve_vendors_meta_box' ) ) {
	
	  return $tekserve_vendors_id;
	
	}	//end if ( ! wp_verify_nonce( $nonce, 'tekserve_vendors_meta_box' ) )
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	
	  return $tekserve_vendors_id;
	
	}	//end if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    // Check post type for 'tekserve_vendors'
    if ( $tekserve_vendors->post_type == 'tekserve_vendors' ) {
    
        // Store data in post meta table if present in post data
        if ( isset( $_POST['tekserve_vendors_quote_bgcolor'] ) ) {
        
            update_post_meta( $tekserve_vendors_id, 'tekserve_vendors_quote_bgcolor', $_REQUEST['tekserve_vendors_quote_bgcolor'] );
            
        }	//end if ( isset( $_POST['tekserve_vendors_quote_bgcolor'] ) )
        if ( isset( $_POST['tekserve_vendors_quote'] ) && $_POST['tekserve_vendors_quote'] != '' ) {
        
            update_post_meta( $tekserve_vendors_id, 'tekserve_vendors_quote', sanitize_text_field( $_REQUEST['tekserve_vendors_quote'] ) );
        
        }	//end if ( isset( $_POST['tekserve_vendors_quote'] ) && $_POST['tekserve_vendors_quote'] != '' )
        if ( isset( $_POST['tekserve_vendors_cs_link'] ) && $_POST['tekserve_vendors_cs_link'] != '' ) {
        
            update_post_meta( $tekserve_vendors_id, 'tekserve_vendors_cs_link', sanitize_text_field( $_REQUEST['tekserve_vendors_cs_link'] ) );
    	
    	}	//end if ( isset( $_POST['tekserve_vendors_cs_link'] ) && $_POST['tekserve_vendors_cs_link'] != '' )
		if ( isset( $_POST['tekservevendorsboilerplate'] ) && $_POST['tekservevendorsboilerplate'] != '' ) {
		
            update_post_meta( $tekserve_vendors_id, 'tekservevendorsboilerplate', $_REQUEST['tekservevendorsboilerplate'] );
    	
    	}	//end if ( isset( $_POST['tekservevendorsboilerplate'] ) && $_POST['tekservevendorsboilerplate'] != '' )
    	if ( isset( $_POST['tekservevendorsbesideform'] ) && $_POST['tekservevendorsbesideform'] != '' ) {
    	
            update_post_meta( $tekserve_vendors_id, 'tekservevendorsbesideform', $_REQUEST['tekservevendorsbesideform'] );
    	
    	}	//end if ( isset( $_POST['tekservevendorsbesideform'] ) && $_POST['tekservevendorsbesideform'] != '' )
	
	}	//end if ( $tekserve_vendors->post_type == 'tekserve_vendors' )

}	//end add_tekserve_vendors_fields( $tekserve_vendors_id, $tekserve_vendors )




if ( ! function_exists('tekserve_vendors_type') ) {

	// register vendor type taxonomy
	function tekserve_vendors_type()  {

		$labels = array(
			'name'                       => 'Vendor Types',
			'singular_name'              => 'Vendor Type',
			'menu_name'                  => 'Vendor Type',
			'all_items'                  => 'All Vendor Types',
			'parent_item'                => 'Parent Vendor Type',
			'parent_item_colon'          => 'Parent Vendor Type:',
			'new_item_name'              => 'New Vendor Type',
			'add_new_item'               => 'Add New Vendor Type',
			'edit_item'                  => 'Edit Vendor Type',
			'update_item'                => 'Update Vendor Type',
			'separate_items_with_commas' => 'Separate Vendor Types with commas',
			'search_items'               => 'Search Vendor Types',
			'add_or_remove_items'        => 'Add or remove Vendor Types',
			'choose_from_most_used'      => 'Choose from the most used Vendor Types',
		);
		
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'query_var'                  => 'tekserve-vendor-type',
			'rewrite'                    => false,
		);
		
		register_taxonomy( 'tekserve-vendors-type', 'tekserve_vendors', $args );

	}	//end function tekserve_vendors_type()

	// Hook into the 'init' action
	add_action( 'init', 'tekserve_vendors_type', 0 );

}




//use custom template when displaying single entry
add_filter( 'template_include', 'tekserve_vendors_include_templates_function', 1 );

function tekserve_vendors_include_templates_function( $template_path ) {

    if ( get_post_type() == 'tekserve_vendors' && is_single() ) {
    
		// checks if the file exists in the theme first, otherwise serve the file from the plugin
		if ( $theme_file = locate_template( array ( 'single-tekserve_vendors.php' ) ) ) {
		
			$template_path = $theme_file;
		
		} 
		else {
		
			$template_path = plugin_dir_path( __FILE__ ) . 'single-tekserve_vendors.php';
		
		}	//end if ( $theme_file = locate_template( array ( 'single-tekserve_vendors.php' ) ) )
    
    }	//end if ( get_post_type() == 'tekserve_vendors' && is_single() )
    return $template_path;

}	//end tekserve_vendors_include_templates_function( $template_path )




//display vendor type in post manager
function tekserve_vendors_type_filter() {

	global $typenow;
 
	// array of all the taxonomies to display, using taxonomy name or slug
	$taxonomies = array('tekserve-vendors-type');
 
	// check for post type before creating menu
	if( $typenow == 'tekserve_vendors' ) {
 
		foreach ($taxonomies as $tax_slug) {
		
			$tax_obj = get_taxonomy( $tax_slug );
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms( $tax_slug );
			if( count( $terms ) > 0 ) {
			
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Show All $tax_name</option>";
				foreach ( $terms as $term ) {
				
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
				
				}	//end foreach ( $terms as $term )
				echo "</select>";
			
			}	//end if( count( $terms ) > 0 )
		
		}	//end foreach ($taxonomies as $tax_slug)
	
	}	//end if( $typenow == 'tekserve_vendors' )

}	//end tekserve_vendors_type_filter()

add_action( 'restrict_manage_posts', 'tekserve_vendors_type_filter' );