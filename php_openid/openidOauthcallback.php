<?php
	require_once "class/consumerService.php";
	require_once "class/authController.php";
	require_once "class/errorController.php";	//產生錯誤訊息
	
	$consumerService = new consumerService();
	$authController = new AuthController();
	$errorController = new errorController();

	$consumer = $consumerService->getConsumer();
	$return_to = $authController->getReturnTo();

	$ary_sreg = array();
	$ary_axAttr = array();
	
	// 產生consumer後取得OP的回應
	$authController->getResponse($consumer, $return_to);
	
	// 回應的狀態為true表示OP有回應
	if ($msg = $authController->checkResponseStatus()){

		$sreg = $authController->getResponseSREG();		//取得SREG

/*
姓名：王大同
電郵：test1234@test.mail.kh.edu.tw
性別：M
生日：1980-01-01
個人代碼：92d4fe71eaae036ff7209b87cb36a74200dc64a1fb792beebfcdf622d838dbbf
學校職務：{"sid":"000000","titles":["教師","資訊組長"]}　
學校業務：{"sid":"000000","affair":[{"affairTitle":"資訊業務","affairType":"負責人"}]}　
*/

		if ($sreg){
			$ary_sreg = $sreg;
			echo "姓名：" . $sreg["fullname"] . "<br>";
            echo "電郵：" . $sreg["email"] . "<br>";
		}
        
		$ax = $authController->getResponseAx();			//取得AX

		if ($ax){

			$axAttr = $ax->data ;
			$ary_axAttr =$axAttr;
/* $axAttr
Array
(
    [http://axschema.edu.tw/person/guid] => Array
        (
            [0] => 92d4fe71eaae036ff7209b87cb36a74200dc64a1fb792beebfcdf622d838dbbf
        )

    [http://openid.edu.tw/axschema/school/titleStr] => Array
        (
            [0] => {"sid":"000000","titles":["教師","資訊組長"]}
        )

    [http://axschema.kh.edu.tw/school/affairStr] => Array
        (
            [0] => {"sid":"000000","affair":[{"affairTitle":"資訊業務","affairType":"負責人"}]}
        )

)
*/
			echo "個人代碼：" . $axAttr["http://axschema.edu.tw/person/guid"][0] . "<br>";
            
            echo "學校職務：";
            foreach($axAttr["http://openid.edu.tw/axschema/school/titleStr"] as $value)
               echo $value . "　";
            echo "<br>";
            
            echo "學校業務：";
            foreach($axAttr["http://axschema.kh.edu.tw/school/affairStr"] as $value)
               echo $value . "　";;
            echo "<br>";

            //redirect(json_encode(array($ary_sreg,$ary_axAttr)));
		}
		redirect(json_encode(array($ary_sreg,$ary_axAttr)));
	}else{

		//file_put_contents("log.txt", $msg . '\n',FILE_APPEND);
	}
	
function redirect($json) {
	header("Location: /ava/ws?f=".urlencode($json));
	die();
}

?>