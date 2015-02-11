<?php
 
/**
 * Template Name: Tekserve Vendors - Single
 * Description: Used as a page template to contents of a vendor's entry.  Genesis only for now...
 */



//enqueue style sheet
if ( function_exists( 'tekserve_vendor_enqueue' ) ) {

	add_action( 'wp_enqueue_scripts', 'tekserve_vendor_enqueue');

}	//end if ( function_exists( 'tekserve_vendor_enqueue' ) )
 



//customize the post info function to display custom fields:

//add text to the title
add_action( 'genesis_post_title', 'tekserve_vendor_title' );
function tekserve_vendor_title() {

	$vendor_custom_title = get_the_title() . ' Solutions from Tekserve';
	echo '<h1 class="entry-title vendor-solutions">' . $vendor_custom_title . '</h1>';

}	//end function tekserve_vendor_title()




//add boilerplate, quote, cs link, form, and footer
add_action( 'genesis_after_post', 'tekserve_vendor_content' );
function tekserve_vendor_content() {

	$vendorquote = '';
	$bgcolor = '';
	$quote = '';
	$vendortext = '';
	$besideform = '';
	if ( is_single() && genesis_get_custom_field( 'tekserve_vendors_quote' ) ) {
	
		$vendorquote = genesis_get_custom_field( 'tekserve_vendors_quote' );
		$bgcolor = genesis_get_custom_field( 'tekserve_vendors_quote_bgcolor');
		if ( genesis_get_custom_field( 'tekserve_vendors_cs_link' ) ) {
		
			$quote = '<div class="tekserve-vendor-quote-wrapper ' . $bgcolor . '"><div class="tekserve-vendor-quote">' . $vendorquote . '<a href="' . genesis_get_custom_field( 'tekserve_vendors_cs_link' ) . '"><span class="tekserve-vendor-quote-cta button">Read Full Case Study</span></a></div></div>';
		
		}
		else {
		
			$quote = '<div class="tekserve-vendor-quote-wrapper ' . $bgcolor . '"><div class="tekserve-vendor-quote">' . $vendorquote . '</div></div>';
		
		}	//end if ( genesis_get_custom_field( 'tekserve_vendors_cs_link' ) )
	
	}	//end if ( is_single() && genesis_get_custom_field( 'tekserve_vendors_quote' ) )
	if ( is_single() && genesis_get_custom_field( 'tekservevendorsboilerplate' ) ) {
	
		$vendortext = '<h1 class="entry-title about-vendor">About ' . get_the_title() . '</h1>' . genesis_get_custom_field( 'tekservevendorsboilerplate' );
	
	}	//if ( is_single() && genesis_get_custom_field( 'tekservevendorsboilerplate' ) )
	
	echo $quote . '<div class="tekserve-vendors-boilerplate-wrapper"><div class="tekserve-vendors-boilerplate">' . $vendortext . '</div></div>';

}	//end function tekserve_vendor_content()




//display featured image before title
add_action( 'genesis_before_post_title', 'tekserve_vendor_logo' );
function tekserve_vendor_logo() {

	$vendor_logo = get_the_post_thumbnail( $post_id, 'thumbnail' );
	echo $vendor_logo;

}	//end tekserve_vendor_logo()

//Remove Post Info
remove_action( 'genesis_after_post_title', 'genesis_post_meta' );
remove_action( 'genesis_post_title', 'genesis_do_post_title' );

//begin rendering genesis
genesis();