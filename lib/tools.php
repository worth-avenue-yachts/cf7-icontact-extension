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

    *1: By default the key label for the name must be FNAME
    *2: parse first & last name
    *3: ensure we set first and last name exist
    *4: otherwise user provided just one name
    *5: By default the key label for the name must be FNAME
    *6: check if subscribed
    *bh: email_type
    *aw: double_optin
    *xz: update_existing
    *rd: replace_interests
    *gr: send_welcome
*/



function vcic_author() {

	$author_pre = 'Contact form 7 Icontact extension by ';
	$author_name = 'Renzo Johnson';
	$author_url = '//renzojohnson.com';
	$author_title = 'Renzo Johnson - Web Developer';

	$vcic_author = '<p style="display: none !important">';
  $vcic_author .= $author_pre;
  $vcic_author .= '<a href="'.$author_url.'" ';
  $vcic_author .= 'title="'.$author_title.'" ';
  $vcic_author .= 'target="_blank">';
  $vcic_author .= ''.$author_title.'';
  $vcic_author .= '</a>';
  $vcic_author .= '</p>'. "\n";

  return $vcic_author;
}



function vcic_referer() {

  // $vcic_referer_url = $THE_REFER=strval(isset($_SERVER['HTTP_REFERER']));

  if(isset($_SERVER['HTTP_REFERER'])) {

    $vcic_referer_url = $_SERVER['HTTP_REFERER'];

  } else {

    $vcic_referer_url = 'direct visit';

  }

	$vcic_referer = '<p style="display: none !important"><span class="wpcf7-form-control-wrap referer-page">';
  $vcic_referer .= '<input type="hidden" name="referer-page" ';
  $vcic_referer .= 'value="'.$vcic_referer_url.'" ';
  $vcic_referer .= 'size="40" class="wpcf7-form-control wpcf7-text referer-page" aria-invalid="false">';
  $vcic_referer .= '</span></p>'. "\n";

  return $vcic_referer;
}



function vcic_getRefererPage( $form_tag ) {

  if ( $form_tag['name'] == 'referer-page' ) {

    $form_tag['values'][] = $_SERVER['HTTP_REFERER'];

  }

  return $form_tag;

}



if ( !is_admin() ) {

  add_filter( 'wpcf7_form_tag', 'vcic_getRefererPage' );

}



define( 'VCIC_URL', '//renzojohnson.com/contributions/contact-form-7-icontact-extension' );
define( 'VCIC_AUTH', '//renzojohnson.com' );
define( 'VCIC_AUTH_COMM', '<!-- campaignmonitor extension by Renzo Johnson -->' );
define( 'VCIC_NAME', 'Icontact Contact Form 7 Extension' );
define( 'VCIC_SETT', admin_url( 'admin.php?page=wpcf7&post='.vcic_get_latest_item().'&active-tab=4' ) );
define( 'VCIC_DON', 'https://www.paypal.me/renzojohnson' );


function vcic_get_latest_item(){
    $args = array(
            'post_type'         => 'wpcf7_contact_form',
            'posts_per_page'    => -1,
            'fields'            => 'ids',
        );
    // Get Highest Value from CF7Forms
    $form = max(get_posts($args));
    $out = '';
    if (!empty($form)) {
        $out .= $form;
    }
    return $out;
}


function wpcf7_form_vcic_tags() {
  $manager = WPCF7_FormTagsManager::get_instance();
  $form_tags = $manager->get_scanned_tags();
  return $form_tags;
}


function vcic_mail_tags() {

  $listatags = wpcf7_form_vcic_tags();
  $tag_submit = array_pop($listatags);
  $tagInfo = '';

    foreach($listatags as $tag){

      $tagInfo .= '<span class="mailtag code used">[' . $tag['name'].']</span>';

    }

  return $tagInfo;

}




