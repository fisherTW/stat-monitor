<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap-slider.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/setting.css">

<div class='container'>
	<div class='row'>
		<h1>設定</h1>
	</div>
	<div class='row'>
		<h1><?= $ary_city[$u_s->city_id] ?></h1>
	</div>

<form class="form-horizontal" id='form1'>
	<input type='hidden' name='hid_cityId' value='<?= $u_s->city_id ?>'>
	<div class='row'>
		<h3>失敗臨界值 (Fail Threshold)</h3>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label"> 網路封包遺失率 (ICMP Packet Loss %)</label>
		<div class='col-md-3'>
			<input id="ex1" class='slider' data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?= $ary_thisCitySetting['thr_icmp'] ?>"/>
		</div>
		<div class='col-md-2'>
			<input class="form-control" type='number' id='txt_icmp' name='txt_icmp' value='<?= $ary_thisCitySetting['thr_icmp'] ?>' required>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label"> 中央處理單元使用率 (CPU Usage %)</label>
		<div class='col-md-3'>
			<input id="ex2" class='slider' data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?= $ary_thisCitySetting['thr_cpu'] ?>"/>
		</div>
		<div class='col-md-2'>
			<input class="form-control" type='number' id='txt_cpu' name='txt_cpu' value='<?= $ary_thisCitySetting['thr_cpu'] ?>' required>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label"> 記憶體使用率 (Memory Usage %)</label>
		<div class='col-md-3'>
			<input id="ex3" class='slider' data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?= $ary_thisCitySetting['thr_mem'] ?>"/>
		</div>
		<div class='col-md-2'>
			<input class="form-control" type='number' id='txt_mem' name='txt_mem' value='<?= $ary_thisCitySetting['thr_mem'] ?>' required>
		</div>
	</div>

	<div class='row'>
		<button type="button" id='btn_default' class="btn btn-danger">回復為原廠預設值</button>
	</div>

	<div class='row'>
		<h3>聯絡窗口資訊</h3>
	</div>

	<div class='col-md-6'>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">聯絡人1</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label"> 聯絡人</label>
					<div class='col-md-9'>
						<input class="form-control" name='contact_name_1' type='text' value='<?= $ary_thisCitySetting['contact_name_1'] ?>' required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"> 聯絡方式</label>
					<div class='col-md-9'>
						<textarea class="form-control" name='contact_1' required><?= $ary_thisCitySetting['contact_1'] ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='col-md-6'>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">聯絡人2</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label"> 聯絡人</label>
					<div class='col-md-9'>
						<input class="form-control" name='contact_name_2' type='text' value='<?= $ary_thisCitySetting['contact_name_2'] ?>' required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"> 聯絡方式</label>
					<div class='col-md-9'>
						<textarea class="form-control" name='contact_2' required><?= $ary_thisCitySetting['contact_2'] ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class='row'>
		<h3>異常通知</h3>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label"> 簡訊通知</label>
		<div class='col-md-4'>
			<input class="form-control" type='text' name='send_sms_1' value='<?= $ary_thisCitySetting['send_sms_1'] ?>' pattern="09[0-9]{8}" placeholder='09xxabcdef ( 10 碼勿加任何符號 )' required>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label"> </label>
		<div class='col-md-4'>
			<input class="form-control" type='text' name='send_sms_2' value='<?= $ary_thisCitySetting['send_sms_2'] ?>' pattern="09[0-9]{8}" placeholder='09xxabcdef ( 10 碼勿加任何符號 )' required>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label"> 郵件通知</label>
		<div class='col-md-4'>
			<textarea class="form-control" placeholder='一行一個郵件信箱' name='send_mail' required><?= $ary_thisCitySetting['send_mail'] ?></textarea>
		</div>
	</div>

</form>

<div class='row'>
	<button type="button" id='btn_submit' class="btn btn-primary">儲存</button>
	<button type="button" class="btn btn-default btn_back">取消</button>
</div>
</div>

<script src="<?php echo base_url(); ?>/assets/js/bootstrap-slider.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/setting.js"></script>