<?php require("../../../wp-load.php"); ?>
<?php
header('Content-Type: application/octet-stream');
header("Content-disposition: attachment; filename=postmapper-export.txt");

global $wpdb;

if (isset($_GET['export_by'])) {
    $sql = "SELECT p.ID, p.post_title,
        pm1.meta_value AS rent,
        pm2.meta_value AS beds,
        pm3.meta_value AS address,
        pm4.meta_value AS phone,
        pm5.meta_value AS property_type,
        pm6.meta_value AS neighborhood,
         pm7.meta_value AS website,
         pm8.meta_value AS email,
         pm9.meta_value AS pets
        FROM " . $wpdb->posts . " p
        JOIN " . $wpdb->postmeta . " pm1 ON (pm1.post_id = p.ID AND pm1.meta_key='postmapper_rent')
        JOIN " . $wpdb->postmeta . " pm2 ON (pm2.post_id = p.ID AND pm2.meta_key='postmapper_beds')
        JOIN " . $wpdb->postmeta . " pm3 ON (pm3.post_id = p.ID AND pm3.meta_key='postmapper_address')
        JOIN " . $wpdb->postmeta . " pm4 ON (pm4.post_id = p.ID AND pm4.meta_key='postmapper_phone')
        JOIN " . $wpdb->postmeta . " pm5 ON (pm5.post_id = p.ID AND pm5.meta_key='postmapper_property_type')
        JOIN " . $wpdb->postmeta . " pm6 ON (pm6.post_id = p.ID AND pm6.meta_key='postmapper_neighborhoods')
        LEFT JOIN " . $wpdb->postmeta . " pm7 ON (pm7.post_id = p.ID and pm7.meta_key='postmapper_website')
        JOIN " . $wpdb->postmeta . " pm8 ON (pm8.post_id = p.ID AND pm8.meta_key='postmapper_email')
        JOIN " . $wpdb->postmeta . " pm9 ON (pm9.post_id = p.ID AND pm9.meta_key='postmapper_pets')

        WHERE post_type = 'postmapper' AND post_status = 'publish' ";

    if ($_GET['export_by'] == "type") { $sql .= " ORDER BY length(pm5.meta_value)"; }
    if ($_GET['export_by'] == "price") { $sql .= " ORDER BY CAST(pm1.meta_value AS SIGNED)"; }
    if ($_GET['export_by'] == "location") { $sql .= " ORDER BY pm6.meta_value"; }
    if ($_GET['export_by'] == "size") { $sql .= " ORDER BY pm2.meta_value"; }
    // print "\n$sql\n";
    $rows = $wpdb->get_results($sql);
    $header = "";
    foreach ($rows as $row) {
        if ($_GET['export_by'] == 'size') {
            // if ($header != $row->beds) {
            //     if ($row->beds == 0) { print "Studio\n"; }
            //     else { print $row->beds . " Bedroom\n"; }
            //     $header = $row->beds;
            // }
            if ($row->beds == 0) { print "Studio"; }
            else {
                print $row->beds . " Bedroom";
                if ($row->property_type != '---') {
                    print " - " . $row->property_type;
                }
            }
            print "\n";
            print $row->address . "\n";
            print $row->post_title . "\n";
            print "$" . $row->rent . "\n";
            print "Pets: " . ($row->pets ? "Yes" : "No") . "\n";
            print ($row->website ? $row->website : "No webite") . "\n";
            print ($row->email ? $row->email : "No Email") . "\n";
            print ($row->phone ? $row->phone : "No Phone") . "\n\n";
        }

        if ($_GET['export_by'] == 'price') {
            print "$" . $row->rent . "\n";
            print $row->address . "\n";
            if ($row->beds == 0) { print "Studio"; }
            else {
                print $row->beds . " Bedroom";
                if ($row->property_type != '---') {
                    print " - " . $row->property_type;
                }
            }
            print "\n";
            print $row->post_title . "\n";
            print "Pets: " . ($row->pets ? "Yes" : "No") . "\n";
            print ($row->website ? $row->website : "No webite") . "\n";
            print ($row->email ? $row->email : "No Email") . "\n";
            print ($row->phone ? $row->phone : "No Phone") . "\n\n";
        }

        if ($_GET['export_by'] == 'location') {
            if ($header != $row->neighborhood) {
                if ($row->neighborhood == '---') { print "No Neighborhood\n"; }
                else { print $row->neighborhood . "\n"; }
                $header = $row->neighborhood;
            }
            print $row->address . "\n";
            if ($row->beds == 0) { print "Studio"; }
            else {
                print $row->beds . " Bedroom";
                if ($row->property_type != '---') {
                    print " - " . $row->property_type;
                }
            }
            print "\n";
            print "$" . $row->rent . "\n";
            print $row->post_title . "\n";
            print "Pets: " . ($row->pets ? "Yes" : "No") . "\n";
            print ($row->website ? $row->website : "No webite") . "\n";
            print ($row->email ? $row->email : "No Email") . "\n";
            print ($row->phone ? $row->phone : "No Phone") . "\n\n";
        }

        if ($_GET['export_by'] == 'type') {
            if ($row->beds == 0) { print "Studio"; }
            else {
                print $row->beds . " Bedroom";
                if ($row->property_type != '---') {
                    print " - " . $row->property_type;
                }
            }
            print "\n";
            print "$" . $row->rent . "\n";
            print $row->address . "\n";
            print $row->post_title . "\n";
            print "Pets: " . ($row->pets ? "Yes" : "No") . "\n";
            print ($row->website ? $row->website : "No webite") . "\n";
            print ($row->email ? $row->email : "No Email") . "\n";
            print ($row->phone ? $row->phone : "No Phone") . "\n\n";
        }
    }

}

?>