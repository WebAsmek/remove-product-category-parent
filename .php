<? php
// Remove product cat base
add_filter('term_link', 'devvn_no_term_parents', 1000, 3);
function devvn_no_term_parents($url, $term, $taxonomy) {
    if($taxonomy == 'product_cat'){
        $term_nicename = $term->slug;
        $url = trailingslashit(get_option( 'home' )) . user_trailingslashit( $term_nicename, 'category' );
    }
    return $url;
}
 
// Add our custom product cat rewrite rules
add_filter('rewrite_rules_array', 'devvn_no_product_cat_parents_rewrite_rules');
function devvn_no_product_cat_parents_rewrite_rules($rules) {
    $new_rules = array();
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'post_type' => 'product',
        'hide_empty' => false,
    ));
    if($terms && !is_wp_error($terms)){
        foreach ($terms as $term){
            $term_slug = $term->slug;
            $new_rules[$term_slug.'/?$'] = 'index.php?product_cat='.$term_slug;
            $new_rules[$term_slug.'/page/([0-9]{1,})/?$'] = 'index.php?product_cat='.$term_slug.'&paged=$matches[1]';
            $new_rules[$term_slug.'/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?product_cat='.$term_slug.'&feed=$matches[1]';
        }
    }
    return $new_rules + $rules;
}
