<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap-table.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap-datetimepicker.min.css">

<script src="<?php echo base_url(); ?>/assets/js/tableExport.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/FileSaver.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/moment.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/moment-zh-tw.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/bootstrap-table.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/bootstrap-table-export.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/bootstrap-table-zh-TW.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class='container'>
	<div class='row'>
		<h1>歷史查詢</h1>
	</div>
	<div class='row'>
		<h1><?= $ary_city[$u_s->city_id] ?></h1>
	</div>
	<div class='row' id='custom-toolbar'>
		<div class='col-sm-5'>
			<div class="form-group">
				<div class='input-group date' id='datetimepicker1'>
					<div class="input-group-addon">起</div>
					<input type='text' class="form-control" id='txt_date1'/>
					<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<div class='col-sm-5'>
			<div class="form-group">
				<div class='input-group date' id='datetimepicker2'>
					<div class="input-group-addon">迄</div>
					<input type='text' class="form-control" id='txt_date2' />
					<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<div class='col-sm-2'>
			<button type="button" class="btn btn-primary" id='btn_go'> GO</button>
		</div>
	</div>
	<table id="table"></table>

</div>

<script type="text/javascript">
$(function () {
	$('#datetimepicker1').datetimepicker({
		locale: 'zh-tw',
		format: 'YYYY-MM-DD',
		defaultDate: moment(1, "DD"),
		minDate: moment().subtract(180, 'days')
	});
	$('#datetimepicker2').datetimepicker({
		locale: 'zh-tw',
		format: 'YYYY-MM-DD',
		defaultDate: moment(),
		maxDate: moment(),
		useCurrent: false //Important! See issue #1075
	});
	$("#datetimepicker1").on("dp.change", function (e) {
		$('#datetimepicker2').data("DateTimePicker").minDate(e.date);
	});
	$("#datetimepicker2").on("dp.change", function (e) {
		$('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
	});
	// Don't allow direct editing
	$('#txt_date1').on('keypress', function(e) {
		e.preventDefault();
	});
	$('#txt_date2').on('keypress', function(e) {
		e.preventDefault();
	});

	$('#table').bootstrapTable({
		toggle:"table",
		idField: 'company,id',
		url: '<?= base_url() ?>history/getTable',
		sortName:"create_date",
		sortOrder:"desc",
		showColumns:"true",
		showExport: 'true',
		exportDataType: 'all',
		exportTypes: ['csv'],
		selectItemName:"toolbar1",
		rowStyle:"rowStyle",
		toolbar: "#custom-toolbar",
		sidePagination:"server",
		pagination:"true",
		pageSize: 50,
		pageList:"[50, 100, 1000, 10000]",
		queryParamsType: 'limit',
		queryParams: function(p) {
			return {
				sort: p.sort,
				order: p.order,
				limit: p.limit,
				offset: p.offset,
				date_start: $("#txt_date1").val(),
				date_end: $("#txt_date2").val()
			};
		},
		columns: [{
			field: 'id',
			title: '序號',
			align: 'right',
			halign:"center"
		}, {
			field: 'create_date',
			title: '資料時間',
			align:"center",
			halign:"center",
			sortable: true
		}, {
			field: 'icmp',
			title: '網路封包遺失率',
			halign:"center",
			align: 'right',
			sortable: true,
			formatter: usageFormatter
		}, {
			field: 'web_service',
			title: '網路服務狀態',
			align:"center",
			sortable: true,
			formatter: statusFormatter,
			width:"10"
		}, {
			field: 'cpu',
			title: '中央處理單元使用率',
			halign:"center",
			align: 'right',
			sortable: true,
			formatter: usageFormatter,
			width:"10"
		}, {
			field: 'mem',
			title: '記憶體使用率',
			halign:"center",
			align: 'right',
			sortable: true,
			formatter: usageFormatter,
			width:"10"
		}]
	});
});

$('#btn_go').click(function() {
	$('#table').bootstrapTable('refresh');
});

function statusFormatter(value, row) {
	if(value === null) { return null; }

	var ret = '';
	
	switch(parseInt(value)) {
		case 1:
			ret = 'up'; break;
		default:
			ret = 'down';break
	}
	return ret;
}

function usageFormatter(value, row) {
	if(value === null) { return null; }

	var ret = '';
	
	switch(parseInt(value)) {
		case -1:
			ret = '未取得資料'; break;
		default:
			ret = value + '%';break
	}
	return ret;
}
</script>