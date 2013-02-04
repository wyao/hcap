<?php
include_once '../../../wp-config.php';

//Accessible here: ~/wp-content/plugins/pagerestrict/admin_data.php

$table = $wpdb->prefix . "alum_members";
$rows = $wpdb->get_results( "SELECT * FROM $table" );
echo json_encode($rows);
?>
