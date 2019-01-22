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

$plugins = get_option('active_plugins');

add_filter( 'wpcf7_editor_panels', 'show_vcic_metabox' );
add_action( 'wpcf7_after_save', 'wpcf7_vcic_save_icontact' );
add_filter('wpcf7_form_response_output', 'spartan_vcic_author_wpcf7', 40,4);
add_action( 'wpcf7_before_send_mail', 'wpcf7_vcic_subscribe' );
add_filter( 'wpcf7_form_class_attr', 'spartan_vcic_class_attr' );


resetlogfile_vcic(); //para resetear



function wpcf7_vcic_add_icontact($args) {
  $cf7_vcic_defaults = array();
  $cf7_vcic = get_option( 'cf7_vcic_'.$args->id(), $cf7_vcic_defaults );

  $host = esc_url_raw( $_SERVER['HTTP_HOST'] );
  $url = $_SERVER['REQUEST_URI'];
  $urlactual = $url;

  include SPARTAN_VCIC_PLUGIN_DIR . '/lib/view.php';

}



function resetlogfile_vcic() {

  if ( isset( $_REQUEST['vcic_reset_log'] ) ) {

    $vcic_debug_logger = new vcic_Debug_Logger();

    $vcic_debug_logger->reset_vcic_log_file( 'log.txt' );
    $vcic_debug_logger->reset_vcic_log_file( 'log-cron-job.txt' );
    echo '<div id="message" class="updated is-dismissible"><p>Debug log files have been reset!</p></div>';
  }

}


/**
 * Sanitize a multidimensional array
 * Autor : Fernando Cossio
 * @uses sanitize_text_field
 *
 * @param (array)
 * @return (array) the sanitized array
 */
function purica_array ($data = array()) {
	if (!is_array($data) || !count($data)) {
		return array();
	}
	foreach ($data as $k => $v) {
		if (!is_array($v) && !is_object($v)) {
			//$data[$k] = htmlspecialchars(trim($v));
      if (is_string ($v)){
          $data[$k] = sanitize_text_field (trim($v)); //Solo se satinize si es string                        
      } else {
          $data[$k] = $v;
      }
       
		}
		if (is_array($v)) {
			$data[$k] = purica_array($v);
		}
	}
	return $data;
}


function wpcf7_vcic_save_icontact($args) {

  if (!empty($_POST)){
    
    $SavePost = purica_array ( $_POST['wpcf7-icontact']  ) ;
    
    update_option( 'cf7_vcic_'.$args->id(), $SavePost );

  }

}



function show_vcic_metabox ( $panels ) {

  $new_page = array(
    'Icontact-Extension' => array(
      'title' => __( 'iContact', 'contact-form-7' ),
      'callback' => 'wpcf7_vcic_add_icontact'
    )
  );

  $panels = array_merge($panels, $new_page);

  return $panels;

}



function spartan_vcic_author_wpcf7( $vcic_supps, $class, $content, $args ) {

  $cf7_vcic_defaults = array();
  $cf7_vcic = get_option( 'cf7_vcic_'.$args->id(), $cf7_vcic_defaults );
  $cfsupp = ( isset( $cf7_vcic['cf-supp'] ) ) ? $cf7_vcic['cf-supp'] : 0;

  if ( 1 == $cfsupp ) {

    $vcic_supps .= vcic_referer();
    $vcic_supps .= vcic_author();

  } else {

    $vcic_supps .= vcic_referer();
    $vcic_supps .= '<!-- Icontact extension by Renzo Johnson -->';
  }
  return $vcic_supps;

}



function cf7_vcic_tag_replace( $pattern, $subject, $posted_data, $html = false ) {

  if( preg_match($pattern,$subject,$matches) > 0)
  {

    if ( isset( $posted_data[$matches[1]] ) ) {
      $submitted = $posted_data[$matches[1]];

      if ( is_array( $submitted ) )
        $replaced = join( ', ', $submitted );
      else
        $replaced = $submitted;

      if ( $html ) {
        $replaced = strip_tags( $replaced );
        $replaced = wptexturize( $replaced );
      }

      $replaced = apply_filters( 'wpcf7_mail_tag_replaced', $replaced, $submitted );

      return stripslashes( $replaced );
    }

    if ( $special = apply_filters( 'wpcf7_special_mail_tags', '', $matches[1] ) )
      return $special;

    return $matches[0];
  }
  return $subject;

}


if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}



function wpcf7_vcic_subscribe($obj) { //Metodo wp_remote
  $cf7_vcic = get_option( 'cf7_vcic_'.$obj->id() );


  $submission = WPCF7_Submission::get_instance();


  $logfileEnabled = isset($cf7_vcic['logfileEnabled']) && !is_null($cf7_vcic['logfileEnabled']) ? $cf7_vcic['logfileEnabled'] : false;


  if( $cf7_vcic ) {
    $subscribe = false;

    $regex = '/\[\s*([a-zA-Z_][0-9a-zA-Z:._-]*)\s*\]/';
    $callback = array( &$obj, 'cf7_vcic_callback' );

    $email = cf7_vcic_tag_replace( $regex, $cf7_vcic['email'], $submission->get_posted_data() );
    $name = cf7_vcic_tag_replace( $regex, $cf7_vcic['name'], $submission->get_posted_data() );

    $lists = cf7_vcic_tag_replace( $regex, $cf7_vcic['list'], $submission->get_posted_data() );
    $listarr = explode(',',$lists);

    $merge_vars = array();

    if( isset($cf7_vcic['accept']) && strlen($cf7_vcic['accept']) != 0 )
    {
      $accept = cf7_vcic_tag_replace( $regex, $cf7_vcic['accept'], $submission->get_posted_data() );
      if($accept != $cf7_vcic['accept'])
      {
        if(strlen($accept) > 0)
          $subscribe = true;
      }
    }
    else
    {
      $subscribe = true;
    }

    for($i=1;$i<=20;$i++){

      if( isset($cf7_vcic['CustomKey'.$i]) && isset($cf7_vcic['CustomValue'.$i]) && strlen(trim($cf7_vcic['CustomValue'.$i])) != 0 )
      {
        $CustomFields[] = array('Key'=>trim($cf7_vcic['CustomKey'.$i]), 'Value'=>cf7_vcic_tag_replace( $regex, trim($cf7_vcic['CustomValue'.$i]), $submission->get_posted_data() ) );
        $NameField=trim($cf7_vcic['CustomKey'.$i]);
        $NameField=strtr($NameField, "[", "");
        $NameField=strtr($NameField, "]", "");
        $merge_vars=$merge_vars + array($NameField=>cf7_vcic_tag_replace( $regex, trim($cf7_vcic['CustomValue'.$i]), $submission->get_posted_data() ) );
      }
    }

    if( isset($cf7_vcic['confsubs']) && strlen($cf7_vcic['confsubs']) != 0 ) {
      $vcic_csu = 'pending';
    } else {
      $vcic_csu = 'subscribed';
    }

    if($subscribe && $email != $cf7_vcic['email']) {

      try {

        $api   = $cf7_vcic['api'];
        $apipwd = $cf7_vcic['AppPwd'];
        $apiuser = $cf7_vcic['ApiUser'];

        $headersArr = array("Accept"=> 'application/json',
                            "Content-Type" => "application/json",
                            "Api-Version" => "2.2",
                            "Api-AppId" => $api,
                            "Api-Username" => $apiuser,
                            "Api-Password" => $apipwd ) ;


        $api_urlAccounts = 'https://app.icontact.com/icp/a/';

        $opts = array(
                // 'method' => 'POST',
                'headers' => $headersArr,
                // 'body' => $body,
                'user-agent' => 'Renzo Coolness'
                    );

        $Accounts = wp_remote_request( $api_urlAccounts, $opts ); // con esto conecto
        $Accounts = json_decode($Accounts["body"], True);
        $AccountId = $Accounts['accounts'][0]['accountId'] ; //por defecto estoy obteniendo la primera cuenta

        $api_urlFolder = 'https://app.icontact.com/icp/a/'.$AccountId."/c";

        $Folders = wp_remote_request( $api_urlFolder, $opts ); // con esto conecto
        $Folders = json_decode($Folders["body"], True);

        $FoldersId = $Folders['clientfolders'][0]['clientFolderId'] ;

        $api_urlList = 'https://app.icontact.com/icp/a/'.$AccountId.'/c/'.$FoldersId.'/'.'lists' ;
        $ListArr = wp_remote_request( $api_urlList, $opts );
        $ListArr = json_decode($ListArr["body"], True);

        $ListArr = array_column($ListArr['lists'], 'listId','name');

        $cadMergeVar=',';
        $cadarray = array();
        foreach($merge_vars as $clave=>$valor)
        {
            $cadMergeVar= $cadMergeVar . '"'. $clave . '":"' . $valor .'",';
            $cadarray[] =  array( 'customFieldId' => $clave, 'value' => array($valor) ) ; //Faltaba el corchete para acumular el array
        }

        if ( strlen( $cadMergeVar )  > 1 ) {
            $cadMergeVar = substr( $cadMergeVar,0,strlen( $cadMergeVar ) - 1 );
        }
        else {
            $cadMergeVar='';
        }

        //$cadarray = json_encode ($cadarray) ; //Transforma el array en json

        $listId  = $ListArr[$lists]  ;

        $body = '[
          {
            "email":"'.$email.'",
            "firstName":"'.$name.'",
            "status":"normal"' . $cadMergeVar . '
          } ]';

        $api_urlContacts = 'https://app.icontact.com/icp/a/'.$AccountId.'/c/'.$FoldersId.'/'.'contacts/'    ;

        $opts = array(
                'method' => 'POST',
                'headers' => $headersArr,
                'body' => $body,
                'user-agent' => 'Renzo Coolness'
                    );

        $getContact = wp_remote_post( $api_urlContacts, $opts ); // con esto conecto

        $getContact = json_decode($getContact["body"], True);

        $ContactId = $getContact['contacts'][0]['contactId'] ;

        $api_urlSubscripLista = 'https://app.icontact.com/icp/a/'.$AccountId.'/c/'.$FoldersId.'/'.'subscriptions/';

        //var_dump('$ContactId: ' . $ContactId );

        $body = '[
                  {
                    "contactId":'. $ContactId .',
                    "listId":' . $listId . ',
                    "status":"normal"
                  }
                ]';

        //var_dump('body subscrip');
        //var_dump($body);

         $opts = array(
                'method' => 'POST',
                'headers' => $headersArr,
                'body' => $body,
                'user-agent' => 'Renzo Coolness'
                    );

        $SetSubscrip = wp_remote_post( $api_urlSubscripLista, $opts );

        //var_dump('Subscribio');
        //var_dump($SetSubscrip);

        $respuesta = wp_remote_retrieve_body( $SetSubscrip );

        $vcic_debug_logger = new vcic_Debug_Logger();
        $vcic_debug_logger->log_vcic_debug( 'Contact Form 7 response: Mail sent OK | iContact.com response: ' . $respuesta  ,1,$logfileEnabled );

      } // end try

       catch (Exception $e) {

        $vcic_debug_logger = new vcic_Debug_Logger();
        $vcic_debug_logger->log_vcic_debug( 'Contact Form 7 response: ' .$e->getMessage(),4,$logfileEnabled );

      }  // end catch
     } //End If
    } // end $subscribe
}




function cf7vcic_use_wpmail($url,$info,$method,$adminEmail){
  $msg = "Attempted to send ".$info." to ".$url." but server doesnt support allow_url_fopen OR cURL";
  $wp_mail_resp = wp_mail( $adminEmail,'CF7 Icontact Extension Problem',$msg);
    if($wp_mail_resp){
      return 'allow_url_fopen & cURL not available, sent details to ' . $adminEmail;
    }else{
      return 'ERROR: Problem with allow_url_fopen/cURL/wp_mail';
    }
}





function spartan_vcic_class_attr( $class ) {

  $class .= ' icontact-ext-' . SPARTAN_VCIC_VERSION;
  return $class;

}