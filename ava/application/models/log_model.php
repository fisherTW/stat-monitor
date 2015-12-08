<?php
/*
* File:			log_model.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		-
*/
class Log_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();

		$this->nowTableName = $this->getNowTableName();
	}

	public function getNowTableName() {
		$today = date("Ym");

		return 'log_'.$today;
	}

	public function createLogTable() {
		$this->load->dbforge();

		if($this->db->table_exists($this->nowTableName)) return;

		// drop old table
		$table_name_old = 'log_'.date("Ym" , mktime(0,0,0,date("m")-6,date("d"),date("Y")));
		$this->dropLogTable($table_name_old);

		$fields = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 9,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'city_id' => array(
				'type' => 'tinyint',
				'constraint' => '4'
			),
			'create_date' => array(
				'type' =>'timestamp',
				'null' => TRUE
			),
			'icmp' => array(
				'type' => 'tinyint',
				'constraint' => '4',
				'null' => TRUE
			),
			'web_service' => array(
				'type' => 'tinyint',
				'constraint' => '4',
				'null' => TRUE
			),
			'cpu' => array(
				'type' => 'tinyint',
				'constraint' => '4',
				'null' => TRUE
			),
			'mem' => array(
				'type' => 'tinyint',
				'constraint' => '4',
				'null' => TRUE
			),
			'sess' => array(
				'type' => 'int',
				'constraint' => '9',
				'null' => TRUE
			)

		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id',TRUE);
		$this->dbforge->create_table($this->nowTableName ,TRUE);
	}

	public function dropLogTable($table_name) {
		$this->load->dbforge();

		if(!$this->db->table_exists($table_name)) return;

		$this->dbforge->drop_table($table_name);
	}

	public function updateLog($nowTableName, $jobTime, $ary) {
		$array = array('city_id' => $ary[0]['city_id'], 'create_date' => $jobTime);
		$this->db->where($array); 
		$this->db->from($nowTableName);
		$recordCount = $this->db->count_all_results();

		if($recordCount == 0) {
			$this->db->insert_batch($nowTableName, $ary); 
		} else {
			//$this->db->update_batch($nowTableName, $ary, array('city_id','create_date'));
			$ary_key = array_keys($ary[0]);
			$name_key = $ary_key[0];
			foreach($ary as $key => $value) {
				$data = array($name_key => $value[$name_key]);

				$this->db->where(array('city_id' => $value['city_id'], 'create_date' => $value['create_date']));
				$this->db->update($nowTableName, $data); 
			}

		}
	}

	public function getTable() {
		$u_s = json_decode($this->session->userdata('u_s'));

		$tbName = $this->getNowTableName();
		
		$datetime1 = date_create($this->input->get('date_start'));
		$datetime2 = date_create($this->input->get('date_end'));
		$interval = date_diff($datetime1, $datetime2);
		$count_table =  ($interval->format('%m') + 2);

		$where = "city_id=".$u_s->city_id." AND create_date BETWEEN '".$this->input->get('date_start')." 00:00:00' AND '".$this->input->get('date_end')." 23:59:59'";

		// total
		$str = '';
		for($i=0; $i < $count_table; $i++) {
			$date = new DateTime(date("Ym",strtotime($this->input->get('date_end'))));
			$date->sub(new DateInterval('P'.$i.'M'));
			$tbName = 'log_'.$date->format('Ym');
			$str .= (strlen($str) > 0 ? ' UNION ' : '')
				." SELECT `id`, `city_id`, `create_date`, `icmp`, `web_service`, `cpu`, `mem` , `sess` FROM ".$tbName
				." WHERE ".$where;
		}
		$query = $this->db->query($str);
		$total = $query->num_rows();
	
		// real
		$str = '';
		for($i=0; $i < $count_table; $i++) {
			$date = new DateTime(date("Ym",strtotime($this->input->get('date_end'))));
			$date->sub(new DateInterval('P'.$i.'M'));
			$tbName = 'log_'.$date->format('Ym');
			$str .= (strlen($str) > 0 ? ' UNION ' : '')
				." SELECT id, city_id, create_date, icmp, web_service, cpu, mem, sess FROM ".$tbName
				." WHERE ".$where;
		}

		$query = $this->db->query('SELECT * from ('.$str.') as a'." ORDER BY ".$this->input->get('sort').' '.$this->input->get('order').' LIMIT '.$this->input->get('limit').' OFFSET '.$this->input->get('offset'));

		$ary_ret['rows'] = $query->result_array();
		$ary_ret['total'] = $total;

		return json_encode($ary_ret);
	}
}