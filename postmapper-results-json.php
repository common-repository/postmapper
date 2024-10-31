<?php require("../../../wp-load.php"); ?>

  <?php

    
    $qvals = array();
    $qvals['rent_min'] = $_GET['rent_min'] ? $_GET['rent_min'] : 0;
    $qvals['rent_max'] = $_GET['rent_max'] ? $_GET['rent_max'] : 0;
    $qvals['beds_min'] = $_GET['beds_min'] ? $_GET['beds_min'] : 0;
    $qvals['beds_max'] = $_GET['beds_max'] ? $_GET['beds_max'] : 0;
    $qvals['bath_min'] = $_GET['bath_min'] ? $_GET['bath_min'] : 0;
    $qvals['bath_max'] = $_GET['bath_max'] ? $_GET['bath_max'] : 0;
    $qvals['pets'] = $_GET['pets'] ? $_GET['pets'] : 0;
    
    $postmapper_results = array();
    $args = array('posts_per_page'=>50, 'orderby' => 'post_date', 'post_type' => 'postmapper');
        //'meta_query' => array(array('key'=>'postmapper_rent', 'value'=>array(800, 1000), 'type'=>'numeric', 'compare'=>'BETWEEN')));
    
    $mquery = array();
    if ($qvals['rent_max']) {
        $q = array('key'=>'postmapper_rent', 'value' => array($qvals['rent_min'], $qvals['rent_max']), 'type'=>'numeric', 'compare' => 'BETWEEN');
        array_push($mquery, $q);
    } 
    
    if ($qvals['beds_max']) {
        $q = array('key'=>'postmapper_beds', 'value' => array($qvals['beds_min'], $qvals['beds_max']), 'type'=>'numeric', 'compare' => 'BETWEEN');
        array_push($mquery, $q);        
    }
    if ($qvals['bath_max']) {
        $q = array('key'=>'postmapper_baths', 'value' => array($qvals['bath_min'], $qvals['bath_max']), 'type'=>'numeric', 'compare' => 'BETWEEN');
        array_push($mquery, $q);        
    }
    if (is_array($_GET['property_type'])) {
        $q = array('key' => 'postmapper_property_type', 'value' => $_GET['property_type'], 'compare' => 'IN');
        array_push($mquery, $q);
    }
    if (is_array($_GET['neighborhoods'])) {
        $q = array('key' => 'postmapper_neighborhoods', 'value' => $_GET['neighborhoods'], 'compare' => 'IN');
        array_push($mquery, $q);
    }
    if ($qvals['pets']) {
        $q = array('key' => 'postmapper_pets', 'value' => $qvals['pets'], 'compare' => 'EQUAL');
        array_push($mquery, $q);
    }
    
    $args['meta_query'] = $mquery;
        
	$gabquery = new WP_Query($args);
	if ($gabquery->post_count) {
            while ($gabquery->have_posts()) : $gabquery->the_post();
			    $custom = get_post_custom( get_the_ID() );
                $rent = intval($custom['postmapper_rent'][0]);
                if ($min == 0) { $min = $rent; }
                if ($rent > $max) { $max = $rent; }
                if ($rent < $min) { $min = $rent; }
                $beds = intval($custom['postmapper_beds'][0]);
                if ($bd_min == 0) { $bd_min = $beds; }
                if ($beds > $bd_max) { $bd_max = $beds; }
                if ($beds < $bd_min) { $bd_min = $beds; }
                $baths = intval($custom['postmapper_baths'][0]);
                if ($ba_min == 0) { $ba_min = $baths; }
                if ($baths > $ba_max) { $ba_max = $baths; }
                if ($baths < $ba_min) { $ba_min = $baths; }
                
			    $realty = array('title' => get_the_title());
                foreach (array_keys($custom) as $k) {                    
                    $realty[$k] = $custom[$k][0];
                }
			    $desc = "$" . $custom['postmapper_rent'][0] . " - " . $custom['postmapper_beds'][0] . "BR/" . 
			    $custom['postmapper_baths'][0] . "BA " . $custom['postmapper_address'][0]; 
                array_push($postmapper_results, $realty);
		endwhile;
	}			
    		print json_encode($postmapper_results);
  ?>
