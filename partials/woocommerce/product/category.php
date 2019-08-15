<?php
$terms = get_the_terms( $post->ID, 'product_cat' );
$separator = ', ';
$categories_link = '';
if($terms){
	$output = '<p class="product-category">';

	foreach($terms as $term) {
		$categories_link .= '<a href="' . esc_url( get_term_link( $term ) ) . '" title="'
						. esc_attr( sprintf( __( 'View all products in %s', 'salt' ), $term->name ) ) . '">'
						. $term->name . '</a>' . $separator;
	}
	
	$output .= trim($categories_link, $separator);
	$output .= '</p>';
	echo $output;
}
