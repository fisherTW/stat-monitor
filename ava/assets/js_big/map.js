$(function(){
	$('area').popover({
		trigger: 'hover',
		placement: 'mouse',
		template: "<div class='popover' role='tooltip'><div class='arrow'></div><div class='popover-title' style='font-size:50px'></div></div>"
	});
	$('.modal').on('show.bs.modal', centerModals);

	var canvas=document.getElementById("myCanvas");
		img = new Image;
		ctx=canvas.getContext("2d");

	canvas.width = $('#img_map').prop('width');
	canvas.height = $('#img_map').prop('height');


	var img = new Image();   // Create new img element
	img.addEventListener("load", function() {
	  // execute drawImage statements here
		ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

		obj = JSON.parse($('#hid_json_light').val());
		Object.keys(obj).forEach(function(key) {
			drawStatus(key, obj[key]);
		});

	}, false);
	img.src = $('#hid_imgurl').val();


});

function onclkarea(url, enabled) {
	if(enabled == '0') { alert('該縣市未參加');return 0; }
	$('#myModal').modal('show');
	$('input[name=openid_identifier]').val(url);
	document.forms[0].submit();
}

function centerModals(){
	$('.modal').each(function(i){
		var $clone = $(this).clone().css('display', 'block').appendTo('body');
		var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
		top = top > 0 ? top : 0;
		$clone.remove();
		$(this).find('.modal-content').css("margin-top", top);
	});
}