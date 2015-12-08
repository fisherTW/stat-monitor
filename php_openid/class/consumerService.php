<?php
	/**
     * Require the OpenID consumer code.
     */
    require_once "Auth/OpenID/Consumer.php";

    /**
     * Require the "file store" module, which we'll need to store
     * OpenID information.
     */
    require_once "Auth/OpenID/FileStore.php";

    /**
     * Require the Simple Registration extension API.
     */
    require_once "Auth/OpenID/SReg.php";

    /**
     * Require the PAPE extension module.
     */
    require_once "Auth/OpenID/PAPE.php";
	
	require "Auth/OpenID/AX.php";
	class ConsumerService{
	
		public function __construct(){
			//file_put_contents("log.txt", "建立consumer\n",FILE_APPEND);
			
		}

		public function &getStore() {
			$store_path = null;
			if (function_exists('sys_get_temp_dir')) {
				$store_path = sys_get_temp_dir();
			}
			else {
				if (strpos(PHP_OS, 'WIN') === 0) {
					$store_path = $_ENV['TMP'];
					if (!isset($store_path)) {
						$dir = 'C:\Windows\Temp';
					}
				}
				else {
/*
					$store_path = @$_ENV['TMPDIR'];
					if (!isset($store_path)) {
						$store_path = '/tmp';
					}
*/
					$store_path = '/tmp';
				}
			}
			$store_path .= DIRECTORY_SEPARATOR . '_php_consumer_test';
			if (!file_exists($store_path) &&
				!mkdir($store_path)) {
				print "Could not create the FileStore directory '$store_path'. ".
					" Please check the effective permissions.";
				exit(0);
			}
			$r = new Auth_OpenID_FileStore($store_path);

			return $r;
		}

		public function &getConsumer() {
			/**
			 * Create a consumer object using the store object created
			 * earlier.
			 */
			$store = $this->getStore();
			$r = new Auth_OpenID_Consumer($store);
			return $r;
		}

		

		
	}
?>