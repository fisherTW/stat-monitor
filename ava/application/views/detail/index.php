<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/detail.css">

<script src="<?php echo base_url(); ?>/assets/js/detail.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/common.js"></script>

<div class='container-fluid'>
	<div class='col-md-5'>
		<input type='hidden' id='hid_imgurl' value='<?= base_url(); ?>assets/img/map_2x.png'>
		<input type='hidden' id='hid_json_light' value='<?= $json_light ?>'>
		<input type='hidden' id='hid_baseurl' value='<?= base_url(); ?>'>
		<div class="rowx">
			<p class="absolute-example">
				<canvas id="myCanvas"></canvas>
			</p>
			<p class="absolute-example">
				<img id='img_map' src="<?= base_url(); ?>assets/img/trans.gif" class="wh" alt="" usemap="#Map">
				<map name="Map" id="Map">
<?php foreach ($ary_citySetting as $item): ?>
					<area href='void(0);' title="<?= $ary_city[$item['city_id']] ?>" onclick="onclkarea($(this).attr('city_id'),$(this).attr('enabled'));return false;" url='<?= $item['url'] ?>' id='area_<?= $item['city_id'] ?>' city_id="<?= $item['city_id'] ?>" shape="<?= $item['shape'] ?>" coords="<?= $item['coords'] ?>" dot='<?= $item['dot'] ?>' enabled="<?= $item['enabled'] ?>"/>
<?php endforeach; ?>
				</map>
			</p>
		</div>
	</div>
	<div class='col-md-7'>
		<div class='row'>
			<h1><?= $ary_city[$city_id] ?>
				<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> 圖例</button></h1>
		</div>

		<div class="collapse" id="collapseExample">
			<div class="well">
				<h3>地圖上燈號的意義</h3>
				<ul>
					<li>紅燈 - 測得值 高於 設定失敗臨界值</li>
					<li>黃燈 - 未取得資料</li>
					<li>綠燈 - 測得值 低於 設定失敗臨界值</li>
				</ul>
				各單位取狀況最嚴重之測試項目做為代表燈號
			</div>
		</div>


<?php if(($city_id == $u_s->city_id) || ($u_s->is_super)): ?>
		<div class='row'>
			<div class='col-md-8'><h4>資料時間</h4></div>
			<div><h4><?= $ary_status['create_date'] ?></h4></div>
		</div>	<div class='row'>
			<div class='col-md-8'><h4>網路封包遺失率 (ICMP Packet Loss %)</h4></div>
			<?= $lightHtml[0] ?>
		</div>
		<div class='row'>
			<div class='col-md-8'><h4>openID 服務狀態 (openID Web Service Status)</h4></div>
			<?= $lightHtml[1] ?>
		</div>
		<div class='row'>
			<div class='col-md-8'><h4>中央處理單元使用率 (CPU Usage %)</h4></div>
			<?= $lightHtml[2] ?>
		</div>
		<div class='row'>
			<div class='col-md-8'><h4>記憶體使用率 (Memory Usage %)</h4></div>
			<?= $lightHtml[3] ?>
		</div>
		<div class='row'>
			<div class='col-md-8'><h4>工作階段數 (Session)</h4></div>
			<?= $lightHtml[4] ?>
		</div>
<?php endif; ?>
<?php if(($city_id != $u_s->city_id) || ($u_s->is_super)): ?>
		<div class='row'>
			<div class='col-md-8'><h4>聯絡窗口資訊</h4></div>
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
							<pre><?= $ary_citySetting[$city_id]['contact_name_1'] ?></pre>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"> 聯絡方式</label>
						<div class='col-md-9'>
							<pre><?= $ary_citySetting[$city_id]['contact_1'] ?></pre>
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
							<pre><?= $ary_citySetting[$city_id]['contact_name_2'] ?></pre>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"> 聯絡方式</label>
						<div class='col-md-9'>
							<pre><?= $ary_citySetting[$city_id]['contact_2'] ?></pre>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php endif; ?>
	</div>

</div>