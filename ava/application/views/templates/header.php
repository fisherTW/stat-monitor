<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo '可用性監控 - '.$title ?></title>

		<!-- Latest compiled and minified CSS -->
		<!-- 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		-->
		<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.5.0/bootstrap-table.min.css">

		<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/validation.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/main.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap-nav-custom-color.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/sticky-footer.css">

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
		<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.5.0/bootstrap-table.min.js"></script>
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

<nav class="navbar navbar-custom" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<a class="navbar-brand" href="<?=site_url('detail/'.$u_s->city_id); ?>"><span class='glyphicon glyphicon-home'></span> 可用性監控系統</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class='glyphicon glyphicon-calendar'></span> 歷史查詢<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?=site_url('history'); ?>"> 歷史查詢</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class='glyphicon glyphicon-download'></span> 下載<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?=site_url('download'); ?>"> 原始碼 + 操作手冊 + 安裝手冊</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class='glyphicon glyphicon-user'></span> <?= $u_s->fullname ?> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?=site_url('setting'); ?>"><span class='glyphicon glyphicon-cog'></span> 設定</a></li>
<!--						
						<li><a href="<?=site_url('setting/backDefault'); ?>" onclick="alert('請至　「設定」->「回復為原廠預設值」');return false;"><span class='glyphicon glyphicon-exclamation-sign'></span> 回復為原廠預設值</a></li>
 -->
						<li role="separator" class="divider"></li>
						<li><a href='<?=base_url(); ?>ws/dologout'><span class='glyphicon glyphicon-log-out'></span> 登出</a></li>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>

<input type='hidden' id='hid_bb' value='<?=base_url(); ?>'>