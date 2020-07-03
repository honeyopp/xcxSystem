<?php

namespace app\weixin\controller;
use app\common\model\setting\ComponentVerifyTicketModel;
class Auth extends Common {

    public function index() {
        $text = file_get_contents("php://input");
        require ROOT_PATH . 'weixinsign/pkcs7Encoder.php';
        $Prpcrypt = new \Prpcrypt(config('weixin.encodingaeskey'));
      	//提取密文
        $xml = new \DOMDocument();
        $xml->loadXML($text);
		
        $array_e = $xml->getElementsByTagName('Encrypt');
        $encrypt = $array_e->item(0)->nodeValue;
        $data = $Prpcrypt->decrypt($encrypt, config('weixin.appid'));
        if (!empty($data[1]) && $data[0] == 0) {
            $xml = new \DOMDocument();
            $xml->loadXML($data[1]);
            $ComponentVerifyTicket = $xml->getElementsByTagName('ComponentVerifyTicket')->item(0)->nodeValue;
            if(!empty($ComponentVerifyTicket)){
                $ComponentVerifyTicketModel = new ComponentVerifyTicketModel();
                $ComponentVerifyTicketModel->save([
                'component_verify_ticket' => $ComponentVerifyTicket
                ]);
            }
        }
        echo 'success';
    }
}
