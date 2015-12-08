<?php
/*
* File:			job.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		-
*/
class Job extends CI_Controller {
	public function __construct() {
		parent::__construct();
		//$this->lang->load("message","english");
		$this->load->helper('url');
		$this->load->helper('language');

		$this->load->model('city_model');
		$this->load->model('log_model');

		$this->nowTableName = $this->log_model->getNowTableName();
		$this->jobTime		= date("Y-m-d H:i:s");
		$this->log_model->createLogTable();

		$this->ary_citySetting = $this->city_model->getCitySetting(true);

		$this->MIB_CPU = 'HOST-RESOURCES-MIB::hrProcessorLoad';
		$this->MIB_MEM_DESC = 'HOST-RESOURCES-MIB::hrStorageDescr';
		$this->MIB_MEM_SIZE = 'HOST-RESOURCES-MIB::hrStorageSize';
		$this->MIB_MEM_USED = 'HOST-RESOURCES-MIB::hrStorageUsed';
		$this->SMS_URL = 'http://smexpress.mitake.com.tw:9600/SmSendGet.asp?username=xx&password=zz';
		$this->SEP_RED_TYPE = '[-F-]';
	}

	public function index() {
	}

	public function execJob() {
		$this->exec_ping();
		$this->exec_web_service();
		$this->exec_snmp();
		$this->exec_session();

		$this->updateLight();
		$this->sendNotify();
	}

	public function exec_ping() {
		/*
		$command = '/bin/ping 168.95.1.1 -n 3';
		$output = shell_exec($command);
		echo $output;
		*/
		foreach ($this->ary_citySetting as $key => $value) {
			$url = str_replace('http://', '', $value['url']);
			$url = str_replace('https://', '', $url);
			$ary_tmp = explode('/', $url);
			$url = $ary_tmp[0];

			$pingresult = exec('sudo /bin/ping -q -c 2 '.$url, $output, $status);
			//if (0 == $status) "alive" else "dead"
			$str = $output[count($output) - 2];
			$ary = explode(',', $str);
			$ary_2 = explode(' ', $ary[2]);
			$ping_loss = str_replace('%', '', $ary_2[1]);
			$ary_ret[] = array(
				'city_id'		=> $value['city_id'],
				'create_date'	=> $this->jobTime,
				'icmp'			=> $ping_loss
			);
		}

		$this->log_model->updateLog($this->nowTableName, $this->jobTime, $ary_ret);
	}

	public function exec_web_service() {
		/*
		curl -I http://openid.kh.edu.tw
		HTTP/1.1 200 OK
		Date: Thu, 27 Aug 2015 05:36:14 GMT
		Server: Apache/2.2.15 (CentOS)
		Content-Length: 740
		Content-Type: application/xrds+xml; charset=utf-8
		Connection: close
		*/
		foreach ($this->ary_citySetting as $key => $value) {
			$execres = exec('sudo /usr/bin/curl -I '.$value['url'], $output, $status);
			$str = $output[0];
			$pos1 = strpos($str, '200 OK');
			$pos2 = strpos($str, '403 Forbidden');
			if( ($pos1 !== FALSE) || ($pos2 !== FALSE) ) {
				$str_status = 1;
			} else {
				$str_status = 0;
			}

			$ary_ret[] = array(
				'web_service'	=> $str_status,
				'city_id'		=> $value['city_id'],
				'create_date'	=> $this->jobTime
			);
		}

		$this->log_model->updateLog($this->nowTableName, $this->jobTime, $ary_ret);
	}

	public function exec_snmp() {
		foreach ($this->ary_citySetting as $key => $value) {
			$output0 = array();
			$output1 = array();
			$output2 = array();
			$output3 = array();
			$ary_cpu = array();
			$is_live = false;
			$version = $value['snmp_version'];
			$cs = $value['snmp_cs'];

			$url = str_replace('http://', '', $value['url']);
			$url = str_replace('https://', '', $url);
			$ary_tmp = explode('/', $url);
			$url = $ary_tmp[0];

			// CPU usage
			$execres = exec('sudo /usr/bin/snmpwalk -v '.$version.' '.'-c '.$cs.' '.$url.' '.$this->MIB_CPU, $output0, $status);
			for($i=0; $i < count($output0); $i++) {
				list($x, $tmp) = explode('=', $output0[$i]);
				$ary_cpu[] = str_replace(' INTEGER: ', '', $tmp);
				$is_live = true;
			}
			$max_cpu = (count($ary_cpu) > 0 ? max($ary_cpu) : -1);

			// MEM usage
			if($is_live) {
				$execres = exec('sudo /usr/bin/snmpwalk -v '.$version.' '.'-c '.$cs.' '.$url.' '.$this->MIB_MEM_DESC, $output1, $status);
				for($i=0; $i < count($output1); $i++) {
					list($x, $tmp) = explode('=', $output1[$i]);
					if(strpos($tmp, 'Physical Memory') !== FALSE) {
						$phyId = str_replace('HOST-RESOURCES-MIB::hrStorageDescr.', '', $x);
						$phyId = str_replace(' ', '', $phyId);
					}
				}

				$execres = exec('sudo /usr/bin/snmpwalk -v '.$version.' '.'-c '.$cs.' '.$url.' '.$this->MIB_MEM_SIZE, $output2, $status);
				$size_mem = $this->getSnmpValueById($phyId, $output2);
				$execres = exec('sudo /usr/bin/snmpwalk -v '.$version.' '.'-c '.$cs.' '.$url.' '.$this->MIB_MEM_USED, $output3, $status);
				$used_mem = $this->getSnmpValueById($phyId, $output3);

				$mem_usage = floor((intval($used_mem)/intval($size_mem)) * 100);
			}
			$mem_usage = ($is_live ? $mem_usage : -1);

			$ary_ret_cpu[] = array(
				'cpu'			=> $max_cpu,
				'city_id'		=> $value['city_id'],
				'create_date'	=> $this->jobTime
			);
			$ary_ret_mem[] = array(
				'mem'			=> $mem_usage,
				'city_id'		=> $value['city_id'],
				'create_date'	=> $this->jobTime
			);
		}

		$this->log_model->updateLog($this->nowTableName, $this->jobTime, $ary_ret_cpu);
		$this->log_model->updateLog($this->nowTableName, $this->jobTime, $ary_ret_mem);
	}

	public function exec_session() {
		foreach ($this->ary_citySetting as $key => $value) {
			//if( ($value['city_id'] == 1) || ($value['city_id'] == 2) || ($value['city_id'] == 5) || ($value['city_id'] == 6) ) {
			if( ($value['city_id'] == 5) ) {
				$execres = exec('sudo /usr/bin/curl '.$value['url'].'/server-status', $output, $status);
				if( count($output) >= 16 ) {
				echo $output[16];
					if(strpos($output[16], 'idle workers') === FALSE) {
						$str_status = 0;
					} else {
						$str = $output[16];
						//1 requests currently being processed, 6 idle workers
						$ary = explode(',', $str);
						$ary = explode(' ', $ary[1]);
						$str_status = $ary[1];
						echo $str_status;
					}
				} else {
					$str_status = 0;
				}
			} else {
				$str_status = 0;
			}

			$ary_ret[] = array(
				'sess'			=> $str_status,
				'city_id'		=> $value['city_id'],
				'create_date'	=> $this->jobTime
			);
		}

		//$this->log_model->updateLog($this->nowTableName, $this->jobTime, $ary_ret);
	}

	public function getSnmpValueById($id, $ary) {
		for($i=0; $i < count($ary); $i++) {
			list($key, $val) = explode(' = ', $ary[$i]);
			list($mibname, $nowid) = explode('.', $key);
			if(strval($nowid) == strval($id)) {
				list($x, $nowval) = explode(': ', $val);
				return $nowval;
			}
		}

		return '';
	}

	public function updateLight() {
		$this->load->model('status_model');

		$ary_statusAll = $this->status_model->getStatus();
		foreach ($this->ary_citySetting as $key => $ary_s) {
			//if(!array_key_exists($key, $ary_status)) { continue;}

			$red_type = '';
			$ary_status = $ary_statusAll[$key];

			// icmp
			if(($ary_status['icmp'] > $ary_s['thr_icmp'])) {
				$html_icmp = 3;
				$red_type = 'icmp'.$this->SEP_RED_TYPE.$ary_status['icmp'];
			} else {
				$html_icmp = 1;
			}

			// web_service
			if(($ary_status['web_service'] != 1)) {
				$html_web_service = 3;
				$red_type = 'web_service'.$this->SEP_RED_TYPE.'100';
			} else {
				$html_web_service = 1;
			}

			// cpu
			if( ($ary_status['cpu'] == -1) || (is_null($ary_status['cpu'])) ) {
				$html_cpu = 2;
			} else {
				if(($ary_status['cpu'] > $ary_s['thr_cpu'])) {
					$html_cpu = 3;
					$red_type = 'cpu'.$this->SEP_RED_TYPE.$ary_status['cpu'];
				} else {
					$html_cpu = 1;
				}
			}

			// mem
			if( ($ary_status['mem'] == -1) || (is_null($ary_status['mem'])) ) {
				$html_mem = 2;
			} else {
				if(($ary_status['mem'] > $ary_s['thr_mem'])) {
					$html_mem = 3;
					$red_type = 'mem'.$this->SEP_RED_TYPE.$ary_status['mem'];
				} else {
					$html_mem = 1;
				}
			}

			$status_worst = max($html_icmp,$html_web_service,$html_cpu,$html_mem);

			if($status_worst == 3) {
				$this->db->set('light', $status_worst);
				$this->db->set('red_type', $red_type);
				$this->db->set('count_red', '(CASE WHEN count_red = 100 THEN 100 ELSE (count_red +1) END)', FALSE);

				$this->db->where('city_id', $ary_s['city_id']);
				$this->db->update('light'); 
			} else {
				$ary_ret[] = array(
					'city_id'	=> $ary_s['city_id'],
					'light'		=> $status_worst,
					'count_red'	=> 0
				);
			}
		}

		$this->db->update_batch('light', $ary_ret, 'city_id');
	}

	public function sendNotify() {
		$this->load->library('email');
		$this->load->model('status_model');

		$ary_citySetting = $this->city_model->getCitySetting();
		$ary_tmp = $this->status_model->getLight(true);

		if(count($ary_tmp) > 0) {
			foreach ($ary_tmp as $key => $value) {
				list($type, $val_out) = explode($this->SEP_RED_TYPE, $value['red_type']);

				switch ($type) {
					case 'icmp':
						$type_txt = '網路封包遺失率';break;
					case 'web_service':
						$type_txt = 'openID 服務狀態';break;
					case 'cpu':
						$type_txt = '中央處理單元使用率';break;
					case 'mem':
						$type_txt = '記憶體使用率';break;
				}
				$msg = '可用性監控 -【'.$type_txt.'】- 失敗 -【'.$val_out.'%】';

				// SMS
				if(strlen($ary_citySetting[$key]['send_sms_1']) > 0) {
					$url = $this->SMS_URL;
					$phone_no = $ary_citySetting[$key]['send_sms_1'];	
					$dst_name = 'xx';
					$body = $msg;

					$url .= '&dstaddr='.$phone_no;
					$url .= '&DestName='.$dst_name;
					$url .= '&smbody='.$body;
					$url .= '&encoding=UTF8';

					$execres = exec("sudo /usr/bin/curl '".$url."'", $output, $status);
				}
				if(strlen($ary_citySetting[$key]['send_sms_2']) > 0) {
					$url = $this->SMS_URL;
					$phone_no = $ary_citySetting[$key]['send_sms_2'];
					$dst_name = 'xx';
					$body = $msg;

					$url .= '&dstaddr='.$phone_no;
					$url .= '&DestName='.$dst_name;
					$url .= '&smbody='.$body;
					$url .= '&encoding=UTF8';

					$execres = exec("sudo /usr/bin/curl '".$url."'", $output, $status);
				}

				$ary_mail = explode("\n", $ary_citySetting[$key]['send_mail']);
				$this->email->from('service@fisherliao.com', '可用性監控');
				$this->email->to($ary_mail); 
				$this->email->subject('可用性監控');
				$this->email->message($msg); 
				$this->email->send();
			}
		}

	}

}