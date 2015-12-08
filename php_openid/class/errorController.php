<?php 
	class ErrorController{
		
		private $error_message = "";
		public function setError($message){
			$this->error_message = $message;
		}
		
		public function getError(){
			return $this->error_message;
		}
	}
?>