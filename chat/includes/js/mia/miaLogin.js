$(document).ready(function(){$("#username").focus();$("#newUser").bind("click",{},function(){$("#registration").removeClass("hideme");$("#login").addClass("hideme");$("#regUsername").focus()});$(".loginUser").bind("click",{},function(){$("#login").removeClass("hideme");$("#registration").addClass("hideme");$("#passwordReset").addClass("hideme");$("#username").focus()});$("#reset").bind("click",{},function(){$("#passwordReset").removeClass("hideme");$("#login").addClass("hideme");$("#resetUsername").focus()});$("#loginFrm").validate({rules:{username:"required",password:"required"}});$("#regFrm").validate({rules:{regFullname:{required:true},regUsername:{required:true,minlength:5},regPassword:{required:true,minlength:6},verifyPassword:{required:true,minlength:6,equalTo:"#regPassword"},regEmail:{required:true,email:true},spamcode:"required",regAcceptTermsConditions:"required"}});$("#resetFrm").validate({rules:{resetUsername:{required:true,minlength:5},resetEmail:{required:true,email:true}}});$("#rerequest-captcha").click(function(){var a=new Date();$("#spamimage").empty();$("#spamimage").attr({src:"getCaptchaImage.php?rndmixzer="+a.getTime()})})});