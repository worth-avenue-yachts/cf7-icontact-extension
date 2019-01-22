<?php
/*
Plugin Name: Contact Form 7 iContact Extension
Plugin URI: http://renzojohnson.com/contributions/contact-form-7-icontact-extension
Description: Integrate Contact Form 7 with iContact. Automatically add form submissions to predetermined lists in iContact, using its latest API.
Author: Renzo Johnson
Author URI: http://renzojohnson.com
Text Domain: contact-form-7
Domain Path: /languages/
Version: 0.1.06
*/

/*  Copyright 2013-2017 Renzo Johnson (email: renzojohnson at gmail.com)
c
    This prograe is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define( 'SPARTAN_VCIC_VERSION', '0.1.06' );
define( 'SPARTAN_VCIC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPARTAN_VCIC_PLUGIN_NAME', trim( dirname( SPARTAN_VCIC_PLUGIN_BASENAME ), '/' ) );
define( 'SPARTAN_VCIC_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'SPARTAN_VCIC_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

require_once( SPARTAN_VCIC_PLUGIN_DIR . '/lib/iContact.php' );


function vcic_meta_links( $links, $file ) {
    if ( $file === 'integrate-contact-form-7-and-icontact/cf7-ic-extension.php' ) {
        // $links[] = '<a href="'.VCIC_URL.'" target="_blank" title="Documentation">Documentation</a>';
        $links[] = '<a href="'.VCIC_URL.'" target="_blank" title="Starter Guide">Starter Guide</a>';
        $links[] = '<a href="//www.paypal.me/renzojohnson" target="_blank" title="Donate"><strong>Donate</strong></a>';
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'vcic_meta_links', 10, 2 );


function vcic_settings_link( $links ) {
    $url = get_admin_url() . 'admin.php?page=wpcf7&post='.vcic_get_latest_item().'&active-tab=4' ;
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'textdomain') . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}


function vcic_after_setup_theme() {
     add_filter('plugin_action_links_' . SPARTAN_VCIC_PLUGIN_BASENAME, 'vcic_settings_link');
}
add_action ('after_setup_theme', 'vcic_after_setup_theme');



function vcic_activation() {

  add_option('vcic_show_notice', true);

  update_site_option('vcic_show_notice', 1);

}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'vcic_activation');