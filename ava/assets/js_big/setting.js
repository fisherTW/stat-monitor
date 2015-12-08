$(function(){
	$('#txt_mem').hide();
	$('#txt_cpu').hide();
	$('#txt_icmp').hide();

	$("#btn_submit").bind("click",function(){
		if(!$("form")[0].checkValidity()) return;
		
		$.ajax({
			type: 'post',
			url: "setting/submit",
			data: $('.form-horizontal').serialize(),
			statusCode: {
				200: function(data) {
					alert('操作成功');
				}
			},
			error: function() {
				alert('操作失敗');
			}
		});
	});
	$("#btn_default").bind("click",function(){
		if(!confirm('是否回復為原廠預設值?')) return;
		
		$.ajax({
			type: 'post',
			url: "setting/backDefault",
			data: $('.form-horizontal').serialize(),
			statusCode: {
				200: function(data) {
					alert('操作成功');
					location.reload(true);
				}
			},
			error: function() {
				alert('操作失敗');
			}
		});
	});
	$(".btn_back").bind("click",function(){
		history.back();
	});
});



$('.slider').slider({
	formatter: function(value) {
		return value;
	},
	tooltip: 'always'
});

$("#ex1").on("change", function(slideEvt) {
	$("#txt_icmp").val(slideEvt.value.newValue);
});
$("#ex2").on("change", function(slideEvt) {
	$("#txt_cpu").val(slideEvt.value.newValue);
});
$("#ex3").on("change", function(slideEvt) {
	$("#txt_mem").val(slideEvt.value.newValue);
});