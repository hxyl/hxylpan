<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17-5-4
 * Time: 下午6:05
 */
require_once "../config/config.php";

    if(isset($_POST['data']) && !empty($_POST['data'])){

        $newsInfo = $_POST['data'];

        $url = NEWSPUSHURL;

        $soapClient = new soapclient($url, true);

        $soapClient->soap_defencoding = 'utf-8';
        $soapClient->decode_utf8 = false;
        $soapClient->xml_encoding = 'utf-8';
        $shareType = $newsInfo['share_type'];

        $domainName = $soapClient->call('findDefaultDomain');

        if(true == $newsInfo['type']){
            $_params = array(
                    'domainName'   => $domainName,
                    'userName'     => $newsInfo['share_with'],
                    'title'        => "云盘消息",
                    'content'      => $newsInfo['uname'].'给您分享了：'.substr($newsInfo['file_target'],1),
                    'source'       => SOURCE,
                    'type'         => TYPE,
                    'level'        => '0',
                    'display'      => '0',
                    'isForce'      => '0',
                    'expires'      => '0',
                    'invalidation' => '0',
            );

            //file_put_contents('data.log',json_encode($_params));

           $result = $soapClient->call('sendSystemMessageEx', $_params);

           if($err = $soapClient->getError()){
               file_put_contents('error.log',$err);
           }
        }
        exit;
    }else{
       echo json_encode(array('success' => 'error'));exit;
}





