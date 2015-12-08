$(function(){
	$('area').popover({
		trigger: 'hover',
		placement: 'mouse',
		template: "<div class='popover' role='tooltip'><div class='arrow'></div><div class='popover-title' style='font-size:50px'></div></div>"
	});

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

function onclkarea(city_id, enabled) {
	if(enabled == '0') { alert('該縣市未參加');return 0; }
	window.location = $('#hid_baseurl').val() + 'detail/' + city_id;
}