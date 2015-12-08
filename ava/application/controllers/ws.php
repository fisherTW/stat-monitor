<?php
/*
* File:			ws.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		AVA
*/
class Ws extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
	}

	public function index() {
		$this->load->library('user_agent');
		$this->load->model('user_model');

		$referrer = '';

		if ($this->agent->is_referral()) {
			$referrer = $this->agent->referrer();
		}

		$ary = json_decode(urldecode($this->input->get('f')),true);

		$ary_user = $this->user_model->getUser($ary[0]['email']);
		if(count($ary_user) == 0) {
			show_error('',401);
		}
		$ary_user = $ary_user[0];
		
		$x = array(
			'city_id'	=> $ary_user['city_id'],
			'is_super'	=> $ary_user['is_super'],
			'fullname'	=> $ary[0]['fullname'],
			'email'		=> $ary[0]['email'],
			'guid'		=> $ary[1]['http://axschema.edu.tw/person/guid'][0],
			'titles'	=> $ary[1]['http://openid.edu.tw/axschema/school/titleStr'][0],
			'affairStr'	=> $ary[1]['http://axschema.kh.edu.tw/school/affairStr'][0]
		);

		$xx = json_encode($x);

		$this->session->unset_userdata();
		$this->session->set_userdata('u_s', $xx);

		redirect(base_url().'detail/'.$ary_user['city_id'], 'refresh');
	}

	public function dologout() {
		$this->session->sess_destroy();
		redirect(base_url(), 'refresh');
	}
}