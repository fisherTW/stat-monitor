function onclkarea(e,t){return"0"==t?(alert("該縣市未參加"),0):void(window.location=$("#hid_baseurl").val()+"detail/"+e)}$(function(){$("area").popover({trigger:"hover",placement:"mouse",template:"<div class='popover' role='tooltip'><div class='arrow'></div><div class='popover-title' style='font-size:50px'></div></div>"});var e=document.getElementById("myCanvas");t=new Image,ctx=e.getContext("2d"),e.width=$("#img_map").prop("width"),e.height=$("#img_map").prop("height");var t=new Image;t.addEventListener("load",function(){ctx.drawImage(t,0,0,e.width,e.height),obj=JSON.parse($("#hid_json_light").val()),Object.keys(obj).forEach(function(e){drawStatus(e,obj[e])})},!1),t.src=$("#hid_imgurl").val()});