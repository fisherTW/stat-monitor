<?php
	require_once "class/consumerService.php";
	require_once "class/authController.php";
	require_once "class/errorController.php";	//產生錯誤訊息
	
	$authController = new authController();
	$consumerService = new consumerService();
	$errorController = new errorController();
	
	$openid = $authController->getOpenidIdentity();
	$SREG = $authController->getRPSREG();
	$AX = $authController->getRPAX();

	$consumer = $consumerService->getConsumer();
	
	// step1 : 準備好驗證的request
	$authRequest = $consumer->begin($openid);
	$pape_request = new Auth_OpenID_PAPE_Request(array(PAPE_AUTH_PHISHING_RESISTANT));
	//var_dump($authRequest);die;
	// step2 : 準備SREG欄位並加入request
	$_required = array('fullname', 'email');  //取得姓名、電子郵件及性別 , 'gender'
	$_optional = array(); //生日 'dob'
	$authRequest->addExtension($pape_request);
	
	$sregRequest = $authController->getSregRequest($_required,$_optional);
	
	$authRequest->addExtension($sregRequest);	  
	
	// step3 : 準備AX欄位並加入request
    //array(2) { [0]=> array(4) { ["type_uri"]=> string(45) "http://openid.edu.tw/axschema/school/titleStr" ["count"]=> string(1) "1" ["required"]=> string(1) "1" ["alias"]=> string(8) "titleStr" } [1]=> array(4) { ["type_uri"]=> string(41) "http://openid.edu.tw/axschema/school/role" ["count"]=> string(1) "1" ["required"]=> string(1) "0" ["alias"]=> string(4) "role" } }
	$axAttributes = array
    (
       array('type_uri'=>'http://axschema.edu.tw/person/guid', 'count'=>'1', 'required'=>'1', 'alias'=>'guid'),  //個人唯一碼
       array('type_uri'=>'http://openid.edu.tw/axschema/school/titleStr', 'count'=>'1', 'required'=>'1', 'alias'=>'titleStr'),  //學校職務
       array('type_uri'=>'http://axschema.kh.edu.tw/school/affairStr', 'count'=>'1', 'required'=>'1', 'alias'=>'affairStr')  //學校業務
    );   // 陣列元素的格式如後 : array('type_uri'=>'XXX', 'count'=>'1', 'required'=>'1', 'alias'=>'YYY')

	if (!empty($axAttributes)){
		$axRequest = $authController->getAXRequest($axAttributes);
		$authRequest->addExtension($axRequest);
	}
	//die;
	// step4: 向OP發出準備好的request
	$authController->sendRedirect($authRequest);
?>