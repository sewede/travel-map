<?php
// require_once('../../../wp-config.php');
function add_countries() {
  global $wpdb;
  global $errors;
  $table_name = $wpdb->prefix . "travelmap_countries";
  $wpdb->insert($table_name, array('country' => $_POST['travel_map_country']));
  ?>
  <div class="notice notice-success">
    <p><strong>Länder gespeichert</strong></p>
  </div>
  <?php
  die();
}


 ?>
