<?php

	class AuthController{
		
		public $response;
		public function __construct(){
		
			session_start();
			header("Content-Type:text/html; charset=utf-8");
		}
		
		public function getOpenidIdentity(){
			if (empty($_GET['openid_identifier'])) {
				$error = "請輸入openID";
				// include 'test_index.php';
				exit(0);
			}

			return $_GET['openid_identifier'];
		}
		public function getRPSREG(){
			$SREG = array();
			$required_sreg = array();
			$optional_sreg = array();
			$result_sreg = array();
			
			if (!empty($_GET['sreg_tag'])) {
				$SREG = $_GET['sreg_tag'];
			}
			foreach($SREG as $attr){
				$required = $_GET[$attr];
				if ($required == "required")
					array_push($required_sreg, $attr);
				else
					array_push($optional_sreg, $attr);
					
			}
			$result_sreg[0] = $required_sreg;
			$result_sreg[1] = $optional_sreg;
			return $result_sreg;
		}
		
		public function getRPAX(){
			$AX_result = array();
			$type_uri = empty($_GET['type_uri'][0]) ? array() : $_GET['type_uri'];
			$count = empty($_GET['count']) ? array() : $_GET['count'];
			$required = empty($_GET['required']) ? array() : $_GET['required'];
			$alias = empty($_GET['alias']) ? array() : $_GET['alias'];
			
			if (!empty($type_uri)) {
				for($i=0; $i < count($type_uri); $i++){
					$ax = array('type_uri' => $type_uri[$i], 'count' => $count[$i], 'required' => $required[$i], 'alias' => $alias[$i]);
					array_push($AX_result, $ax);
				}
			}

			return $AX_result;
		}
		public function getSregRequest($required, $optional){
			$sregService =  new Auth_OpenID_SRegRequest();
			$sregRequest = $sregService->build($required, $optional);
			
			if ($sregRequest) {
				return $sregRequest;			
			}
		}
		
		public function getAXRequest($axAttributes){
			$axRequest = new Auth_OpenID_AX_FetchRequest();
			foreach($axAttributes as $attr){
				//$axInfo = new Auth_OpenID_AX_AttrInfo($attr);
				$attrInfo = Auth_OpenID_AX_AttrInfo::make($attr['type_uri'],$attr['count'],$attr['required'], $attr['alias'] );
				
				$axRequest->add($attrInfo);
			}
			return $axRequest;
		}
		
		public function sendRedirect($authRequest){
			if ($authRequest->shouldSendRedirect()) {
			
				$redirect_url = $authRequest->redirectURL($this->getTrustRoot(),
														   $this->getReturnTo());
				// If the redirect URL can't be built, display an error
				// message.
				if (Auth_OpenID::isFailure($redirect_url)) {
					displayError("無法導向server: " . $redirect_url->message);
				} else {
					// Send redirect.
					header("Location: ".$redirect_url);
				}
			} else {//var_dump(123444);die;
				// Generate form markup and render it.
				$form_id = 'openid_message';
				$form_html = $authRequest->htmlMarkup($this->getTrustRoot(), $this->getReturnTo(),
													   false, array('id' => $form_id));

				// Display an error if the form markup couldn't be generated;
				// otherwise, render the HTML.
				if (Auth_OpenID::isFailure($form_html)) {
					displayError("無法導向server: " . $form_html->message);
				} else {
					print $form_html;
				}
			}
		}
		
		public function getResponse($consumer, $return_to){
			$this->response = $consumer->complete($return_to);
		//	return $this->response;
		}
		public function getResponseSREG(){
			if ($this->response->message == "Server denied check_authentication")
			{
				$error = "驗證失敗! 請更新憑證，在此下載最新憑證 http://curl.haxx.se/ca/cacert.pem，並於php.ini加入此設定
curl.cainfo = 'your_path\cacert.pem'";
				// include "test_index.php";
				exit(0);
			}
			$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($this->response);
			$sreg = $sreg_resp->contents();
			
			return $sreg;
		}
		public function getResponseAx(){
			$ax_resp = Auth_OpenID_AX_FetchResponse::fromSuccessResponse($this->response);
			
			return $ax_resp;
		}
		public function getCanonicalID(){
			$canonicalID = $this->response->endpoint->canonicalID;
			return empty($canonicalID) ? null : '  (XRI CanonicalID: ' . $canonicalID .') ';
		}
		public function checkResponseStatus(){
			if ($this->response->status == Auth_OpenID_CANCEL) {
				$msg = '驗證取消';
			} else if ($this->response->status == Auth_OpenID_FAILURE) {
				$msg = "驗證失敗: " . $this->response->message;
			} else if ($this->response->status == Auth_OpenID_SUCCESS)
				$msg = 1;
			return $msg;
		}
		public function parseAx($ax){
			foreach( $ax->data as $k=>$v){
			  $krpos= strrpos( $k, '/');
			  if( $krpos === false) $arr[$k] = $v[0];
			  else{ 
				$newk = substr( $k, $krpos+1);
				if(isset($v[0]))$arr[$newk] = $v[0];
				else $arr[$newk] ="";
			  }
			}
			
			return $arr;
		}
		public function verifyCanonicalID(){
			 if ($response->endpoint->canonicalID) {
				$escaped_canonicalID = escape($response->endpoint->canonicalID);
				$success .= '  (XRI CanonicalID: '.$escaped_canonicalID.') ';
			}
		}
		public function getReturnTo() {
			return sprintf("%s://%s:%s%s/openidOauthcallback.php",
						   $this->getScheme(), $_SERVER['SERVER_NAME'],
						   $_SERVER['SERVER_PORT'],
						   dirname($_SERVER['PHP_SELF']));
		}

		public function getTrustRoot() {
			return sprintf("%s://%s:%s%s/",
						   $this->getScheme(), $_SERVER['SERVER_NAME'],
						   $_SERVER['SERVER_PORT'],
						   dirname($_SERVER['PHP_SELF']));
		}
		public function getScheme() {
			$scheme = 'http';
			if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
				$scheme .= 's';
			}
			return $scheme;
		}
	}
?>