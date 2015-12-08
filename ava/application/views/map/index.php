<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $title ?></title>

		<!-- Latest compiled and minified CSS -->
		<!-- 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		-->
		<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/sticky-footer.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/map.css">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>/assets/js/map.js"></script>
		<script src="<?php echo base_url(); ?>/assets/js/common.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/jquery.raptorize.1.0.js"></script>
		<script type="text/javascript">
		$(function () {
		    var code1 = String.fromCharCode(38, 38, 40, 40, 37, 39, 37, 39, 66, 65);
		    var code2 = String.fromCharCode(38, 38, 40, 40, 37, 39, 37, 39, 65, 66);
		    var codeBuffer = "";
		    $(document).keyup(function (e) {
		        codeBuffer += String.fromCharCode(e.which);
		        if (code1.substring(0, codeBuffer.length) == codeBuffer) {
		            if (code1.length == codeBuffer.length) {
		                toggle1();
		                codeBuffer = String.fromCharCode(38, 38, 40, 40, 37, 39, 37, 39, 66);
		            }
		        } else if (code2.substring(0, codeBuffer.length) == codeBuffer) {
		            if (code2.length == codeBuffer.length) {
		                toggle2();
		                codeBuffer = "";
		            }
		        } else {
		            codeBuffer = "";
		        }
		    });

		    function toggle1() {
		        var $body = $("body");
	            $body.raptorize();
		    }
		});
		</script>


	</head>

	<body>
	<input type='hidden' id='hid_ourl' value='<?= $ourl ?>'>
	<input type='hidden' id='hid_json_light' value='<?= $json_light ?>'>
	<input type='hidden' id='hid_imgurl' value='<?= base_url(); ?>assets/img/map_2x.png'>
	<input type='hidden' id='hid_bb' value='<?=base_url(); ?>'>
	<form method="get" action="<?= $ourl ?>">
		<input type="hidden" type="text" name="action" value="varify" />
		<input type="hidden" type="text" name="openid_identifier" value="" />
	</form>

	<div id="with-container-fluid">
		<h1> 可用性監控系統</h1>
		<div class="container-fluid">
			<div class='col-md-2'>
				<div class="well">
					<h3>燈號的意義</h3>
					<ul>
						<li>紅燈 - 測得值 高於 設定失敗臨界值</li>
						<li>黃燈 - 未取得資料</li>
						<li>綠燈 - 測得值 低於 設定失敗臨界值</li>
					</ul>
					各單位取狀況最嚴重之測試項目做為代表燈號
				</div>
			</div>
			<div class='col-md-10'>

			<div class="row">
				<p class="absolute-example">
					<canvas id="myCanvas"></canvas>
				</p>
				<p class="absolute-example">
					<img id='img_map' src="<?= base_url(); ?>assets/img/trans.gif" class="wh" alt="" usemap="#Map">
					<map name="Map" id="Map">
<?php foreach ($ary_citySetting as $item): ?>
						<area href='void(0);' title="<?= $ary_city[$item['city_id']] ?>" onclick="onclkarea($(this).attr('url'),$(this).attr('enabled'));return false;" url='<?= $item['url'] ?>' id='area_<?= $item['city_id'] ?>' city_id="<?= $item['city_id'] ?>" shape="<?= $item['shape'] ?>" coords="<?= $item['coords'] ?>" dot='<?= $item['dot'] ?>' enabled="<?= $item['enabled'] ?>"/>
<?php endforeach; ?>
					</map>
				</p>
			</div>

			</div>

		</div>
	</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body text-center">
				<h1><strong>等待 OpenID 伺服器回應...</strong></h1>
				<div class="progress">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


	<footer class="footer1">
		<div class="container">
			<div class='col-xs-12 text-center'>
				<h2>點選地圖以登入</h2>
			</div>
		</div>
	</footer>

	</body>
</html>