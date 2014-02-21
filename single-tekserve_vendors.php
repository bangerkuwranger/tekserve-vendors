<?php
 
/**
 * Template Name: Tekserve Vendors - Single
 * Description: Used as a page template to contents of a vendor's entry.  Genesis only for now...
 */
 
//* Customize the post info function to display custom fields

//add text to the title
add_action('genesis_post_title', 'tekserve_vendor_title');
function tekserve_vendor_title() {
	$vendor_custom_title = get_the_title().' Solutions from Tekserve';
	echo "<h1 class='entry-title'>".$vendor_custom_title."</h1>";
}

//enqueue script for form
add_action('genesis_meta', 'gfom_meta');
function gform_meta() {
	gravity_form_enqueue_scripts($form_id, $is_ajax);
}

//add boilerplate, quote, cs link, form, and footer
add_action('genesis_after_post', 'tekserve_vendor_content');
function tekserve_vendor_content() {
	$vendorquote = "";
	$bgcolor = "";
	$quote = "";
	$vendortext = "";
	$besideform = "";
	if ( is_single() && genesis_get_custom_field('tekserve_vendors_quote') ) {
		$vendorquote = genesis_get_custom_field('tekserve_vendors_quote');
		$bgcolor = genesis_get_custom_field('tekserve_vendors_quote_bgcolor');
		if (genesis_get_custom_field('tekserve_vendors_cs_link')) {
			$quote = "<div class='tekserve-vendor-quote-wrapper bgwrapper ".$bgcolor."'><div class='tekserve-vendor-quote'>".$vendorquote."<a href='".genesis_get_custom_field('tekserve_vendors_cs_link')."'><span class='tekserve-vendor-quote-cta'>Read Full Case Study</span></a></div></div>";
		}
		else {
			$quote = "<div class='tekserve-vendor-quote-wrapper ".$bgcolor."'><div class='tekserve-vendor-quote'>".$vendorquote."</div></div>";
		}
	}
	if ( is_single() && genesis_get_custom_field('tekservevendorsboilerplate') ) {
		$vendortext = "<h1 class='entry-title' style='display: block; margin: 0 0 1em;'>About ".get_the_title()."</h1>".genesis_get_custom_field('tekservevendorsboilerplate');
	}
	if (genesis_get_custom_field('tekservevendorsbesideform')) {
		$besideform = genesis_get_custom_field('tekservevendorsbesideform');
	}
 	$formhtml = "<div class='bgwrapper'><div class='beside-form leftside'>".$besideform."</div><div class='gravity-form-wrapper rightside'><h1 class='vendor-contact-title'>Contact Us</h1>";
	$footerfolk = "</div></div><div class='vendor-folk'>".footer_folk()."</div>";
	
	echo $quote."<div class='tekserve-vendors-boilerplate-wrapper bgwrapper'><div class='tekserve-vendors-boilerplate'>".$vendortext."</div></div>";
	echo $formhtml;
	gravity_form(3, false, false, false, '', true);
	echo $footerfolk;
}

//display featured image before title
add_action('genesis_before_post_title', 'tekserve_vendor_logo');
function tekserve_vendor_logo() {
	$vendor_logo = get_the_post_thumbnail($post_id, 'thumbnail');
	echo $vendor_logo;
}

/** Remove Post Info */
remove_action( 'genesis_after_post_title', 'genesis_post_meta' );
remove_action( 'genesis_post_title', 'genesis_do_post_title' );
 
genesis();