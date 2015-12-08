// city: id
// status: 1,2,3
function drawStatus(city, status) {
	switch(status.toString()) {
		case '1': var color = '#00FF00';break;
		case '2': var color = '#FFBF00';break;
		case '3': var color = '#FF0000';break;
	}
	ctx.fillStyle = color;
	ctx.beginPath();
	// context.arc(x,y,r,sAngle,eAngle,counterclockwise);
	var tmp = $('#area_' + city).attr('dot');
	var ary_tmp = tmp.split(',');
	ctx.arc(ary_tmp[0],ary_tmp[1],5,0,Math.PI*2,true);
	ctx.closePath();
	ctx.fill();
	ctx.lineWidth = 2;
	ctx.strokeStyle = '#003300';
	ctx.stroke();
}