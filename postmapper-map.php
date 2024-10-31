<?php require("../../../wp-load.php"); ?>
<?php
global $wpdb;

$nav_sql = "SELECT 
    MIN(CAST(rent.meta_value AS UNSIGNED)) AS rent_min,
    MAX(CAST(rent.meta_value AS UNSIGNED)) AS rent_max,
    MIN(CAST(beds.meta_value AS UNSIGNED)) AS beds_min,
    MAX(CAST(beds.meta_value AS UNSIGNED)) AS beds_max,
    MIN(CAST(baths.meta_value AS UNSIGNED)) AS baths_min,
    MAX(CAST(baths.meta_value AS UNSIGNED)) AS baths_max
FROM
    " . $wpdb->posts . " AS p
        JOIN
    " . $wpdb->postmeta . " AS rent ON p.ID = rent.post_id
        JOIN
    " . $wpdb->postmeta . " AS beds ON p.ID = beds.post_id
        JOIN
    " . $wpdb->postmeta . " AS baths ON p.ID = baths.post_id
WHERE
    beds.meta_key = 'postmapper_beds'
        AND rent.meta_key = 'postmapper_rent'
        AND baths.meta_key = 'postmapper_baths'
        AND p.post_status = 'publish'";
$nav = $wpdb->get_row($nav_sql);

/*
  $rent = $wpdb->get_row("select min(cast(meta_value as UNSIGNED)) as min, 
      max(cast(meta_value as UNSIGNED)) as max from " . $wpdb->postmeta . " where meta_key='postmapper_rent'");
  $beds = $wpdb->get_row("select min(cast(meta_value as UNSIGNED)) as min, 
      max(cast(meta_value as UNSIGNED)) as max from " . $wpdb->postmeta . " where meta_key='postmapper_beds'");
  $baths = $wpdb->get_row("select min(cast(meta_value as UNSIGNED)) as min, 
      max(cast(meta_value as UNSIGNED)) as max from " . $wpdb->postmeta . " where meta_key='postmapper_baths'");
*/
?>

<!DOCTYPE html>
<html>
  <head>
  <style type="text/css">body{font:62.5% Verdana,Arial,sans-serif}</style>
  
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>
  
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }

      #properties, #rent, #beds, #baths { margin: 4px 10px; }
      .slider { width: <?php $w = get_option('postmapper_map_width', '500'); echo $w/2; ?>px; margin: 4px 10px; }
      .right { text-align: right; float: right; }
    </style>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=<?php echo get_option('postmapper_gmaps_key', ''); ?>&sensor=false">
    </script>
    <script type="text/javascript">
      function initialize() {         
         load_markers(false);
      }
      
      function load_markers(qs, initial) {
          var mapOptions = {
             center: new google.maps.LatLng(<?php echo get_option('postmapper_default_city_geocode'); ?>),
             zoom: 13,
             mapTypeId: google.maps.MapTypeId.ROADMAP
           };
          var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
          
          var bounds=new google.maps.LatLngBounds();
          infowindow = new google.maps.InfoWindow({content: 'oi'});
          var url = '<?php echo plugins_url() . "/postmapper/postmapper-results-json.php"; ?>';
          
          if (qs) { url = url + qs;}
          
          var i = 0;
        	$.getJSON(url, function(data) {
        	    $.each(data, function(idx, c) {
                    ll = c.postmapper_geocode.split(',');
                    if (ll.length == 2) {
                        var latlng = new google.maps.LatLng(ll[0], ll[1]);
                        //console.log(c.title);
                        var marker = new google.maps.Marker({
                                        map: map,
                                        position: latlng,
                                        title:c.title
                        });        	        
                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.close();
                            var content = '<div id="content"><b>' + c.title + "- $" + c.postmapper_rent + ' (' + c.postmapper_beds + 'BR/' + c.postmapper_baths + 'BA)</b></br>';
                            if (c.postmapper_details) {
                                content += c.postmapper_details + '</br>';
                            }
                            content += c.postmapper_address + '</br>';
                            content += c.postmapper_city + ', ' + c.postmapper_state + ' ' + c.postmapper_zipcode + '</br>';
                            content += c.postmapper_phone + '</br>';
                            if (c.postmapper_email) {
                                content += '<a href="mailto:' + c.postmapper_email +'">' + c.postmapper_email + '</a></br>';
                            }   
                            if (c.postmapper_website) {
                                content += '<a href="' + c.postmapper_website +'">' + c.postmapper_website + '</a></br>';
                            }
                            content += '</div>';
                            infowindow.setContent(content);
                            infowindow.open(map, marker);
                        });  
                        bounds.extend(latlng);
                        i += 1;
                    }
        	    })
        	    if (i > 1) { map.fitBounds(bounds); } 
        	    else if (i != 0) { map.panToBounds(bounds); }
        	    $("#properties").html("Found " + i + " listing(s)");
        	});
        	
          
      }
    </script>
  </head>
  <body onload="initialize()">
  <div id="map_canvas" style="width:<?php echo get_option('postmapper_map_width', '500'); ?>px; height:<?php echo get_option('postmapper_map_height', '350'); ?>px;"></div>
  <div id="properties"></div>
  

<div>  
<div id="options" style="float: right; width: <?php $w = get_option('postmapper_map_width', '500'); echo ($w/3); ?>px;">
    <table>
    <tr>
    <td width="<?php $w = get_option('postmapper_map_width', '500'); echo $w/5; ?>"><b>Property Type</b></td>
    <td width="<?php $w = get_option('postmapper_map_width', '500'); echo $w/5; ?>"><b>Neighborhood</b></td>
    </tr>
    <tr>
    <td valign="top">
    <?php 
    $ptypes = get_option('postmapper_property_types', array('Apartment', 'House', 'Room'));
    if (!is_array($ptypes)) { $ptypes = unserialize($ptypes); }
    foreach ($ptypes as $p) {
        echo '<input type="checkbox" name="property_type[]" value="' . $p . '" class="options"/>&nbsp;' . $p . '</br>';
    }
    ?>
    <hr/>
    <input type="checkbox" name="pets" id="pets" class="options" value="1"/> Pets OK? 
    
    </td>
    <td valign="top">
    <?php 
    $neighborhoods = get_option('postmapper_neighborhoods', array('West Davis', 'University', 'Downtown', 'El Macero'));
    if (!is_array($neighborhoods)) { $neighborhoods = unserialize($neighborhoods); }
    foreach ($neighborhoods as $p) {
        echo '<input type="checkbox" name="neighborhoods[]" value="' . $p . '" class="options"/>&nbsp;' . $p . '</br>';
    }
    ?>
    </td>

    </tr>

    </table>
</div>

<div style="float: left;">
    <div id="rent">
        Rent:
        <div id="slider-rent" class="slider"></div>
        <div id="slider-rent-values" class="slider">
            <span id="rent-value1">$<?php echo $nav->rent_min ?></span>
            <span id="rent-value2" class="right">$<?php echo $nav->rent_max ?></span>
        </div>
    </div>
    <div id="beds">
        Bedrooms:
        <div id="slider-beds" class="slider"></div>
        <div id="slider-beds-values" class="slider">
            <span id="beds-value1">0</span>
            <span id="beds-value2" class="right"><?php echo $nav->beds_max ?></span>
        </div>
    </div>
    <div id="baths">
        Bathrooms:
        <div id="slider-baths" class="slider"></div>
        <div id="slider-baths-values" class="slider">
            <span id="baths-value1">0</span>
            <span id="baths-value2" class="right"><?php echo $nav->baths_max ?></span>
        </div>
    </div>
</div>
</div>

  <script>
  var max = <?php echo $nav->rent_max; ?>;
  var min = <?php echo $nav->rent_min; ?>;
  $("#slider-rent").slider({
      min: min,
      max: max,
      values: [min, max],
      range: true,
    
      slide: function(event, ui) {
          $("#rent-value1").html("$" + ui.values[0]);
          $("#rent-value2").html("$" + ui.values[1]);
      }, 
      change: function(event, ui) {
          var qs = "?rent_min=" + $("#rent-value1").text().replace("$", "");
          qs += "&rent_max=" + $("#rent-value2").text().replace("$", "");
          qs += "&beds_min=" + $("#beds-value1").text();
          qs += "&beds_max=" + $("#beds-value2").text();
          qs += "&bath_min=" + $("#baths-value1").text();
          qs += "&bath_max=" + $("#baths-value2").text();
          $(".options").each(function() {
             if ($(this).attr('checked')) {
                 qs += "&" + $(this).attr('name') + "=" + $(this).val();
             }
          });
          load_markers(qs);          
      }
  });
  
  var bd_max = <?php echo $nav->beds_max; ?>;
  var bd_min = <?php echo $nav->beds_min; ?>;
  $("#slider-beds").slider({
      min: 0,
      max: bd_max,
      values: [0, bd_max],
      range: true,
    
      slide: function(event, ui) {
          $("#beds-value1").html(ui.values[0]);
          $("#beds-value2").html(ui.values[1]);
      }, 
      change: function(event, ui) {
          var qs = "?rent_min=" + $("#rent-value1").text().replace("$", "");
          qs += "&rent_max=" + $("#rent-value2").text().replace("$", "");
          qs += "&beds_min=" + $("#beds-value1").text();
          qs += "&beds_max=" + $("#beds-value2").text();
          qs += "&bath_min=" + $("#baths-value1").text();
          qs += "&bath_max=" + $("#baths-value2").text();
          $(".options").each(function() {
             if ($(this).attr('checked')) {
                 qs += "&" + $(this).attr('name') + "=" + $(this).val();
             }
          });

          load_markers(qs);          
      }
  });
  
  var ba_max = <?php echo $nav->baths_max; ?>;
  var ba_min = <?php echo $nav->baths_min; ?>;
  $("#slider-baths").slider({
      min: 0,
      max: ba_max,
      values: [0, ba_max],
      range: true,
    
      slide: function(event, ui) {
          $("#baths-value1").html(ui.values[0]);
          $("#baths-value2").html(ui.values[1]);
      }, 
      change: function(event, ui) {
          var qs = "?rent_min=" + $("#rent-value1").text().replace("$", "");
          qs += "&rent_max=" + $("#rent-value2").text().replace("$", "");
          qs += "&beds_min=" + $("#beds-value1").text();
          qs += "&beds_max=" + $("#beds-value2").text();
          qs += "&bath_min=" + $("#baths-value1").text();
          qs += "&bath_max=" + $("#baths-value2").text();
          $(".options").each(function() {
             if ($(this).attr('checked')) {
                 qs += "&" + $(this).attr('name') + "=" + $(this).val();
             }
          });

          load_markers(qs);          
      }
  });
  
  $(".options").click(function () {
      var qs = "?rent_min=" + $("#rent-value1").text().replace("$", "");
      qs += "&rent_max=" + $("#rent-value2").text().replace("$", "");
      qs += "&beds_min=" + $("#beds-value1").text();
      qs += "&beds_max=" + $("#beds-value2").text();
      qs += "&bath_min=" + $("#baths-value1").text();
      qs += "&bath_max=" + $("#baths-value2").text();
      $(".options").each(function() {
         if ($(this).attr('checked')) {
             qs += "&" + $(this).attr('name') + "=" + $(this).val();
         }
      });
      load_markers(qs);
    });
  
 
  </script>
  </body>
</html>