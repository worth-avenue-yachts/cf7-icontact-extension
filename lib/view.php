<?php
/*  Copyright 2013-2017 Renzo Johnson (email: renzojohnson at gmail.com)

    This program is free software; you can redistribute it and/or modify
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

global $wpdb;

function vcic_utm() {

  global $wpdb;

  $utms  = '?utm_source=iContact';
  $utms .= '&utm_campaign=w' . get_bloginfo( 'version' ) .'c' . WPCF7_VERSION . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . '';
  $utms .= '&utm_medium=cme-' . SPARTAN_VCIC_VERSION . '';
  $utms .= '&utm_term=F' . ini_get( 'allow_url_fopen' ) . 'C' . ( function_exists( 'curl_init' ) ? '1' : '0' ) . 'P' . PHP_VERSION . 'S' . $wpdb->db_version() . '';
  // $utms .= '&utm_content=';

  return $utms;

}

?>



<h2>iContact Extension <span class="mc-code"><?php echo SPARTAN_VCIC_VERSION . '.' . ini_get( 'allow_url_fopen' ) . '.' . ( function_exists( 'curl_init' ) ? '1' : '0' ) . '.' . WPCF7_VERSION . '.' . get_bloginfo( 'version' ) . '.' . PHP_VERSION . '.' . $wpdb->db_version() ?></span></h2>

<div class="vcic-main-fields">

  <p class="mail-field">
    <label for="wpcf7-icontact-api"><?php echo esc_html( __( 'iContact API Application ID:', 'wpcf7' ) ); ?> </label><br />
    <input type="text" id="wpcf7-icontact-api" name="wpcf7-icontact[api]" class="wide" size="70" placeholder=" " value="<?php echo (isset($cf7_vcic['api']) ) ? esc_attr( $cf7_vcic['api'] ) : ''; ?>" />
    <small class="description">6283ef9bdef6755f8fe686ce53bdf75a-us9 <-- A number like this <a href="<?php echo VCIC_URL ?>/icontact-api-key<?php echo vcic_utm() ?>MC-api" class="helping-field" target="_blank" title="get help with iContact API Key:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>


  <p class="mail-field">
    <label for="wpcf7-icontact-AppPwd"><?php echo esc_html( __( 'iContact API application password:', 'wpcf7' ) ); ?> </label><br />
    <input type="text" id="wpcf7-icontact-AppPwd" name="wpcf7-icontact[AppPwd]" class="wide" size="70" placeholder=" " value="<?php echo (isset($cf7_vcic['AppPwd']) ) ? esc_attr( $cf7_vcic['AppPwd'] ) : ''; ?>" />
    <small class="description">6283ef9bde <-- A number like this <a href="<?php echo VCIC_URL ?>/icontact-AppPwd<?php echo vcic_utm() ?>MC-api" class="helping-field" target="_blank" title="get help with iContact API Key:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>

<p class="mail-field">
    <label for="wpcf7-icontact-ApiUser"><?php echo esc_html( __( 'iContact email:', 'wpcf7' ) ); ?> </label><br />
    <input type="text" id="wpcf7-icontact-ApiUser" name="wpcf7-icontact[ApiUser]" class="wide" size="70" placeholder=" " value="<?php echo (isset($cf7_vcic['ApiUser']) ) ? esc_attr( $cf7_vcic['ApiUser'] ) : ''; ?>" />
    <small class="description">6283ef9bde <-- A number like this <a href="<?php echo VCIC_URL ?>/icontact-ApiUser<?php echo vcic_utm() ?>MC-api" class="helping-field" target="_blank" title="get help with iContact API Key:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
</p>


  <p class="mail-field">
    <label for="wpcf7-icontact-list"><?php echo esc_html( __( 'iContact List ID:', 'wpcf7' ) ); ?></label><br />
    <input type="text" id="wpcf7-icontact-list" name="wpcf7-icontact[list]" class="wide" size="70" placeholder=" " value="<?php echo (isset( $cf7_vcic['list']) ) ?  esc_attr( $cf7_vcic['list']) : '' ; ?>" />
    <small class="description">b52d12804a <-- A number like this <a href="<?php echo VCIC_URL ?>/icontact-list-id<?php echo vcic_utm() ?>MC-list-id" class="helping-field" target="_blank" title="get help with iContact List ID:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>

  <p class="mail-field">
    <label for="wpcf7-icontact-email"><?php echo esc_html( __( 'Subscriber Email:', 'wpcf7' ) ); ?></label><br />
    <input type="text" id="wpcf7-icontact-email" name="wpcf7-icontact[email]" class="wide" size="70" placeholder="" value="<?php echo (isset ( $cf7_vcic['email'] ) ) ? esc_attr( $cf7_vcic['email'] ) : ''; ?>" />
    <small class="description"><?php echo vcic_mail_tags(); ?> <-- you can use these mail-tags <a href="<?php echo VCIC_URL ?>/icontact-contact-form<?php echo vcic_utm() ?>MC-email" class="helping-field" target="_blank" title="get help with Subscriber Email:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>


  <p class="mail-field">
    <label for="wpcf7-icontact-name"><?php echo esc_html( __( 'Subscriber Name:', 'wpcf7' ) ); ?></label><br />
    <input type="text" id="wpcf7-icontact-name" name="wpcf7-icontact[name]" class="wide" size="70" placeholder="" value="<?php echo (isset ($cf7_vcic['name'] ) ) ? esc_attr( $cf7_vcic['name'] ) : ''; ?>" />
    <small class="description"><?php echo vcic_mail_tags(); ?>  <-- you can use these mail-tags <a href="<?php echo VCIC_URL ?>/icontact-contact-form<?php echo vcic_utm() ?>MC-name" class="helping-field" target="_blank" title="get help with Subscriber name:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
  </p>


  <div class="cme-container vcic-support" style="display:none">

      <p class="mail-field mt0">
        <label for="wpcf7-icontact-accept"><?php echo esc_html( __( 'Required Acceptance Field:', 'wpcf7' ) ); ?> </label><br />
        <input type="text" id="wpcf7-icontact-accept" name="wpcf7-icontact[accept]" class="wide" size="70" placeholder="[opt-in] <= Leave Empty if you are NOT using the checkbox or read the link above" value="<?php echo (isset($cf7_vcic['accept'])) ? $cf7_vcic['accept'] : '';?>" />
        <small class="description"><?php echo vcic_mail_tags(); ?>  <-- you can use these mail-tags <a href="<?php echo VCIC_URL ?>/icontact-opt-in-checkbox<?php echo vcic_utm() ?>MC-opt-in-checkbox" class="helping-field" target="_blank" title="get help with Subscriber name:"> Get more help <span class="red-icon dashicons dashicons-admin-links"></span></a></small>
      </p>

      <p class="mail-field">
        <input type="checkbox" id="wpcf7-icontact-conf-subs" name="wpcf7-icontact[confsubs]" value="1"<?php echo ( isset($cf7_vcic['confsubs']) ) ? ' checked="checked"' : ''; ?> />
        <label for="wpcf7-icontact-double-opt-in"><?php echo esc_html( __( 'Enable Double Opt-in (checked = true)', 'wpcf7' ) ); ?>  <a href="<?php echo VCIC_URL ?><?php echo vcic_utm() ?>MC-double-opt-in" class="helping-field" target="_blank" title="get help with Custom Fields"> Help <span class="red-icon dashicons dashicons-sos"></span></a></label>
      </p>


      <p class="mail-field">
        <input type="checkbox" id="wpcf7-icontact-cf-active" name="wpcf7-icontact[cfactive]" value="1"<?php echo ( isset($cf7_vcic['cfactive']) ) ? ' checked="checked"' : ''; ?> />
        <label for="wpcf7-icontact-cfactive"><?php echo esc_html( __( 'Use Custom Fields', 'wpcf7' ) ); ?>  <a href="<?php echo VCIC_URL ?>/icontact-custom-fields<?php echo vcic_utm() ?>MC-custom-fields" class="helping-field" target="_blank" title="get help with Custom Fields"> Help <span class="red-icon dashicons dashicons-sos"></span></a></label>
      </p>


      <div class="icontact-custom-fields">
        <p>In the following fields, you can use these mail-tags: <?php echo vcic_mail_tags(); ?></p>

        <div>
          <?php for($i=1;$i<=10;$i++){ ?>

          <div class="col-6">
            <label for="wpcf7-icontact-CustomValue<?php echo $i; ?>"><?php echo esc_html( __( 'Contact Form Value '.$i.':', 'wpcf7' ) ); ?></label><br />
            <input type="text" id="wpcf7-icontact-CustomValue<?php echo $i; ?>" name="wpcf7-icontact[CustomValue<?php echo $i; ?>]" class="wide" size="70" placeholder="[your-mail-tag]" value="<?php echo (isset( $cf7_vcic['CustomValue'.$i]) ) ?  esc_attr( $cf7_vcic['CustomValue'.$i] ) : '' ;  ?>" />
          </div>


          <div class="col-6">
            <label for="wpcf7-icontact-CustomKey<?php echo $i; ?>"><?php echo esc_html( __( 'iContact Custom Field Name '.$i.':', 'wpcf7' ) ); ?></label><br />
            <input type="text" id="wpcf7-icontact-CustomKey<?php echo $i; ?>" name="wpcf7-icontact[CustomKey<?php echo $i; ?>]" class="wide" size="70" placeholder="EXAMPLE" value="<?php echo (isset( $cf7_vcic['CustomKey'.$i]) ) ?  esc_attr( $cf7_vcic['CustomKey'.$i] ) : '' ;  ?>" />
          </div>

          <?php } ?>

        </div>



      </div>


      <p class="mail-field">
        <input type="checkbox" id="wpcf7-icontact-cf-support" name="wpcf7-icontact[cf-supp]" value="1"<?php echo ( isset($cf7_vcic['cf-supp']) ) ? ' checked="checked"' : ''; ?> />
        <label for="wpcf7-icontact-cfactive"><?php echo esc_html( __( 'Developer Backlink', 'wpcf7' ) ); ?> <small><i>( If checked, a backlink to our site will be shown in the footer. This is not compulsory, but always appreciated <span class="spartan-blue smiles">:)</span> )</i></small></label>
      </p>


  </div>




    <table class="form-table mt0 description">
      <tbody>
        <tr>
          <th scope="row">Debug Logger</th>
          <td>
            <fieldset><legend class="screen-reader-text"><span>Debug Logger</span></legend><label for="wpcf7-icontact-cfactive">
            <input type="checkbox"
                   id="wpcf7-icontact-logfileEnabled"
                   name="wpcf7-icontact[logfileEnabled]"
                   value="1" <?php echo ( isset( $cf7_vcic['logfileEnabled'] ) ) ? ' checked="checked"' : ''; ?>
            />
            Enable to troubleshoot issues with the extension.</label>
            </fieldset>
            <p>- View debug log file by clicking <a href="<?php echo esc_textarea( SPARTAN_VCIC_PLUGIN_URL ). '/logs/log.txt'; ?>" target="_blank">here</a>. <br />
               - Reset debug log file by clicking <a href="<?php echo esc_textarea( $urlactual ). '&vcic_reset_log=1'; ?>">here</a>.</p>

          </td>
        </tr>
      </tbody>
    </table>







    <p class="p-author"><a type="button" aria-expanded="false" class="vcic-trigger a-support ">Show advanced settings</a> &nbsp; <a class="cme-trigger-sys a-support ">Get System Information</a></p>

  <script>
    jQuery(".cme-trigger-sys").click(function() {

      jQuery( "#toggle-sys" ).slideToggle(250);

    });

    function toggleDiv() {

      setTimeout(function () {
          jQuery(".vcic-cta").slideToggle(450);
      }, 9000);

    }
    toggleDiv();

  </script>
    <?php include SPARTAN_VCIC_PLUGIN_DIR . '/lib/system.php'; ?>
    <!-- <hr class="p-hr"> -->

    <div class="dev-cta vcic-cta welcome-panel" style="display: none;">

    <div class="welcome-panel-content">

      <p class="about-description">Hello. My name is Renzo, I <span alt="f487" class="dashicons dashicons-heart red-icon"> </span> WordPress and I develop this tiny FREE plugin to help users like you. I drink copious amounts of coffee to keep me running longer <span alt="f487" class="dashicons dashicons-smiley red-icon"> </span>. If you've found this plugin useful, please consider making a donation.</p><br>
      <p class="about-description">Would you like to <a class="button-primary" href="//www.paypal.me/renzojohnson" target="_blank">buy me a coffee?</a></p>

    </div>

    </div>



</div>





