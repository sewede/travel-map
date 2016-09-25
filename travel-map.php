<?php
/**
 * Plugin Name: Travel Map
 * Plugin URI: https://sewede.de/wordpress/plugins/travel-map
 * Description: Mark countries you have traveled on a Google Map
 * Version: 0.1.0
 * Author: Sebastian Wetzel
 * Author URI: http://sebastianwetzel.org
 * License: GPL2
 */

 function travel_map_install() {
   global $wpdb;
   $table_name = $wpdb->prefix . "travelmap_countries";

   $charset_collate = $wpdb->get_charset_collate();

   $sql = "CREATE TABLE $table_name (
     id mediumint(9) NOT NULL AUTO_INCREMENT,
     time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
     country varchar(100) DEFAULT '' NOT NULL,
     PRIMARY KEY (id)
   ) $charset_collate;";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta( $sql );
 }

 register_activation_hook(__FILE__, 'travel_map_install');

 add_action('admin_menu', 'travel_map_menu');
 function travel_map_menu() {
   add_menu_page('Travel Map', 'Travel Map', 'administrator', 'travel-map-settings', 'travel_map_settings_page', 'dashicons-admin-generic');

 }

 function travel_map_settings_page() {
   if (filter_input(INPUT_GET, 'add_countries') === 'success') {
     ?>
     <div class="notice notice-success">
       <p><strong>Land gespeichert</strong></p>
     </div>
     <?php
   }
   ?>
   <div class="wrap">
     <h2>Travel Map Einstellungen</h2>

     <form method="post" action="options.php">
       <?php settings_fields('travel-map-settings-group'); ?>
       <?php do_settings_sections('travel-map-settings-group'); ?>
       <table class="form-table">
         <tr valign="top">
           <th scope="row">Höhe der Karte (in px)</th>
           <td><input type="text" name="google_maps_height" value="<?php echo esc_attr( get_option('google_maps_height'));?>"></td>
         </tr>
       </table>
       <?php submit_button(); ?>

       </form>
       <form method="post" action="<?php echo admin_url('admin-post.php');?>">
         <input type="hidden" name="action" value="add_countries">
         <table class="form-table">
         <tr valign="top">
           <th scope="row">Füge ein Land hinzu</th>
           <td>
             <select name="travel_map_country">
               <?php
               $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
               for ($i = 0; $i < count($countries); $i++) { ?>
                 <option value="<?= $countries[$i]?>"><?= $countries[$i]; ?></option>
               <?php }

               ?>

             </select>
           </td>
         </tr>
       </table>
       <?php submit_button(); ?>
      </form>
      <div class="travel-map-countries">
        <h2>Deine bereisten Länder</h2>
        <?php
        global $wpdb;
        $countries = $wpdb->get_results("SELECT country from {$wpdb->prefix}travelmap_countries");

          foreach ($countries as $country){
            echo $country->country . "<br>";
          }
         ?>
      </div>
    </div>
   <?php
 }

add_action('admin_post_add_countries', 'add_countries');
// include(plugin_dir_url(__FILE__) . 'addCountries.php');
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
  $url = admin_url('admin.php?page=travel-map-settings');
  $redirect = add_query_arg('add_countries', 'success', $url);
  wp_redirect($redirect);
  exit;
}


 add_action('admin_init', 'travel_map_settings');
 function travel_map_settings() {
   register_setting( 'travel-map-settings-group', 'google_maps_height' );
   register_setting( 'travel-map-settings-group', 'travel_map_countries');
 }

 add_action('wp_enqueue_scripts', 'travel_map_scripts');
 function travel_map_scripts() {
   wp_enqueue_style('travel-map-css', plugin_dir_url(__FILE__) . '/css/travelmap.css');
   wp_enqueue_script('travel-map-js', plugin_dir_url(__FILE__) . '/js/travelmap.js', array(), '1.0', true);
   wp_enqueue_script('google-charts', 'https://www.gstatic.com/charts/loader.js', array(), false, true);
 }

 // function add_async_attribute($tag, $handle) {
 //   if ('google-maps' !== $handle) {
 //     return $tag;
 //   }
 //   return str_replace( ' src', ' async defer src', $tag);
 // }
 // add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

 function travel_map_shortcode( $atts ) {
   $height = get_option('google_maps_height');
   $mapCanvas = '<div id="travel-map" style="height: ' . $height . 'px"></div>';
   return $mapCanvas;
 }
 add_shortcode('travel-map', 'travel_map_shortcode');

 ?>
