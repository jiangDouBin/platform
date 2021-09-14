<!doctype html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<title>{pboot:pagetitle}</title>
	<meta name="keywords" content="{pboot:pagekeywords}">
	<meta name="description" content="{pboot:pagedescription}">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,shrink-to-fit=no">
	<link rel="stylesheet" href="{pboot:sitetplpath}/bootstrap/css/bootstrap.min.css" >
	<link rel="stylesheet" href="{pboot:sitetplpath}/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{pboot:sitetplpath}/css/aoyun.css?v=v1.3.5" >
	<link rel="stylesheet" href="{pboot:sitetplpath}/swiper-4.3.5/css/swiper.min.css">
	<link rel="stylesheet" href="{pboot:sitetplpath}/css/animate.css">
	<link rel="shortcut icon" href="{pboot:sitepath}/favicon.ico" type="image/x-icon">
	<script src="{pboot:sitetplpath}/js/jquery-1.12.4.min.js" ></script>
</head>
<body>

<!-- 头部导航 -->
<nav class="navbar navbar-light bg-light fixed-top navbar-expand-lg shadow-sm">
  <div class="container">
	  	<a class="navbar-brand my-1" href="{pboot:sitepath}/">
	      <img src="{pboot:sitelogo}" class="logo-sm-height"  height="50">
	    </a>
	    
	    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	    	<span class="navbar-toggler-icon"></span>
	    </button>
	    
	    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
	        <ul class="navbar-nav">
	       	  <li class="nav-item {pboot:if(0=='{sort:scode}')}active{/pboot:if}">
				 <a class="nav-link" href="{pboot:sitepath}/" >首页</a>
	          </li>
	          {pboot:nav num=10 parent=0}
	              <li class="nav-item {pboot:if('[nav:scode]'=='{sort:tcode}')}active{/pboot:if}">
	                <a class="nav-link" href="[nav:link]">[nav:name]</a>
	              </li>
	          {/pboot:nav}
	      </ul>
	      
	      <span class="navbar-text">
	     	 {pboot:if({pboot:islogin}==1)}
				<a class="text-secondary" href="{pboot:ucenter}" >个人中心</a>
		     {else}
		     
		     	{pboot:2if({pboot:registerstatus})}
					<a class="text-secondary" href="{pboot:register}" >注册</a>
				{/pboot:2if}
				
				{pboot:2if({pboot:registerstatus} && {pboot:loginstatus})}
					/
				{/pboot:2if}
				
				{pboot:2if({pboot:loginstatus})}
					<a class="text-secondary" href="{pboot:login}" >登录</a>
				{/pboot:2if}
				
		     {/pboot:if}
	      </span>
	      
	    </div>
    </div>
</nav>

<!--占位导航栏-->
<div style="height:71px;" class="head-sm-height"></div>



<!-- 头部大屏 -->
<div class="jumbotron jumbotron-fluid mb-2" style="{pboot:if('{sort:pic}')}background:#e9ecef url({sort:pic}){else}background:#e9ecef url({pboot:sitepath}/static/upload/image/20180412/1523501459462835.jpg){/pboot:if};background-size:cover;no-repeat">
  <div class="container text-light">
    <h1 class="fs-18 fs-sm-26 wow fadeInDown">{sort:name}</h1>
    <p class="fs-14  fs-sm-18 wow fadeInDown">{sort:subname}</p>
  </div>
</div>

<div class="container pages">

	<!-- 当前位置 -->
<div class="text-right position mb-3 d-none d-md-block">当前位置：{pboot:position separator='>'}</div>

    <!-- 用户注册 -->
    <div class="row">
    	<div class="col-lg-3"></div>
    	<div class="col-12 col-lg-6">
        	<form class="my-4" onsubmit="return register(this);">
                <div class="form-group">
                    <label for="username">账　号</label>
                    <input type="text" name="username" required id="username" onblur="isRegister()" class="form-control" placeholder="请输入登录账号">
                    <div id="usernote"></div>
                </div>
                
                <div class="form-group">
                    <label for="nickname">昵　称</label>
                    <input type="text" name="nickname" required id="nickname" class="form-control" placeholder="请输入账号昵称">
                </div>
                
                <div class="form-group">
                    <label for="password">密　码</label>
                    <input type="password" name="password" required id="password" class="form-control" placeholder="请输入登录密码">
                </div>
                
                <div class="form-group">
                    <label for="rpassword">确认密码</label>
                    <input type="password" name="rpassword" required id="rpassword" class="form-control" placeholder="请再次输入登录密码">
                </div>
                
                {pboot:if({pboot:registercodestatus}==1)}
                <div class="form-group">
                    <label for="checkcode">验证码</label>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="checkcode" required id="checkcode" class="form-control" placeholder="请输入验证码">
                        </div>
                        <div class="col-6">
                            <img title="点击刷新" style="height:33px;" id="codeimg" src="{pboot:checkcode}" onclick="this.src='{pboot:checkcode}?'+Math.round(Math.random()*10);" />
                        </div>
                    </div>
                </div>
                {/pboot:if}
                
                {pboot:if({pboot:registercodestatus}==2)}
                <div class="form-group">
                    <label for="checkcode">邮箱验证码</label>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" name="checkcode" required id="checkcode" class="form-control" placeholder="请输入验证码">
                        </div>
                        <div class="col-6">
                            <span  class="btn btn-info mb-2" onclick="sendEmail();">发送</span>
                        </div>
                    </div>
                </div>
                {/pboot:if}
                
                <div class="form-group">
                   <button type="submit" class="btn btn-info mb-2">立即注册</button>
                   <span class="text-secondary ml-3">已有账号？<a href="{pboot:login}">马上登录</a></span>
                </div>
            </form>
        </div>
        <div class="col-lg-3"></div>
    </div> 
</div>

<script>

//ajax注册
function register(obj){
  var url='{pboot:register}';
  var username=$(obj).find("#username").val();
  var nickname=$(obj).find("#nickname").val();
  var password=$(obj).find("#password").val();
  var rpassword=$(obj).find("#rpassword").val();
  var sex=$(':radio[name="sex"]:checked').val();
  var checkcode=$(obj).find("#checkcode").val();

  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: {
    	username: username,
    	nickname: nickname,
    	password: password,
    	rpassword: rpassword,
    	sex: sex,
    	checkcode: checkcode
    },
    success: function (response, status) {
      if(response.code){
    	 alert("注册成功！");
		 location.href= response.tourl; 
      }else{
    	 $('#codeimg').click();
    	 alert(response.data);
      }
    },
    error:function(xhr,status,error){
      alert('返回数据异常！');
    }
  });
  return false;
}

//发送邮件验证码
function sendEmail(){
  var url='{pboot:sendemail}';
  var username=$("#username").val();
  if(!username){
	 $("#username").addClass("is-invalid");
 	 $("#usernote").addClass("invalid-feedback");
 	 $("#username").removeClass("is-valid");
 	 $("#usernote").removeClass("valid-feedback");
 	 $("#usernote").text("请输入需要注册的账号！");
	 return false;
  }
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: {
    	to: username
    },
    success: function (response, status) {
      if(response.code){
    	 alert(response.data);
      }else{
    	 alert(response.data);
      }
    },
    error:function(xhr,status,error){
      alert('返回数据异常！');
    }
  });
  return false;
}

//检查注册账号
function isRegister(){
  var url='{pboot:isregister}';
  var username=$("#username").val();
  if(!username){
	 $("#username").addClass("is-invalid");
 	 $("#usernote").addClass("invalid-feedback");
 	 $("#username").removeClass("is-valid");
 	 $("#usernote").removeClass("valid-feedback");
 	 $("#usernote").text("请输入需要注册的账号！");
	 return false;
  }
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: {
    	username: username
    },
    success: function (response, status) {
      if(response.code){//已经被注册
    	 $("#username").addClass("is-invalid");
    	 $("#usernote").addClass("invalid-feedback");
    	 
    	 $("#username").removeClass("is-valid");
    	 $("#usernote").removeClass("valid-feedback");
    	
      }else{//未被注册
    	 $("#username").addClass("is-valid");
    	 $("#usernote").addClass("valid-feedback");
    	 
    	 $("#username").removeClass("is-invalid");
    	 $("#usernote").removeClass("invalid-feedback");
      }
      $("#usernote").text(response.data);
    },
    error:function(xhr,status,error){
      alert('返回数据异常！');
    }
  });
  return false;
}
</script>


<div class="footer mt-3 pt-5 border-top text-secondary bg-light">
	<div class="container">
    	<div class="row pb-5">
            <div class="col-12 col-md-5">
            	<h5>{pboot:companyname}</h5>
                <ul class="lh-2">
                	<li>营业执照：{pboot:companyblicense}  </li>
                    <li>备案号码：<a href="http://beian.miit.gov.cn/" target="_blank">{pboot:siteicp}</a> </li>
                    <li>地址：{pboot:companyaddress} </li>
                </ul>
            </div>
            <div class="col-12 col-md-5">
            	<div class="mt-4 d-block d-md-none"></div>
                
            	<h5>联系我们</h5>
                <ul class="lh-2">
                    <li>电话：{pboot:companyphone} </li>
                    <li>邮箱：{pboot:companyemail} </li>
                    <li>Q&nbsp;&nbsp;Q：{pboot:companyqq} </li>
                </ul>
            </div>
            <div class="col-12 col-md-2 text-center d-none d-md-block">
            	<p class="code">{pboot:qrcode string={pboot:httpurl}<?php echo URL;?>} </p>
                <p class="small">扫一扫 手机访问</p>
            </div>
        </div>
	</div>
    <div class="copyright border-top lh-3 text-center  d-none d-md-block">
    	{pboot:sitecopyright}
    </div>
</div>

<!-- 占位 -->
<div style="height:49px;" class="d-block d-sm-none"></div>

<!-- 手机底部导航 -->
<div class="container-fluid bg-info fixed-bottom d-block d-sm-none">
    <div class="row">
        <div class="col-4 p-0 text-center border-right">
            <a href="tel:{pboot:companymobile}" class="text-light d-block pt-3 pb-3"><i class="fa fa-phone" aria-hidden="true"></i> 电话咨询</a>
        </div>
        <div class="col-4 p-0 text-center border-right">
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={pboot:companyqq}&site=qq&menu=yes" class="text-light d-block pt-3 pb-3"><i class="fa fa-qq" aria-hidden="true"></i> 在线咨询</a>
        </div>
        <div class="col-4 p-0 text-center">
			{pboot:sort scode=1}
            <a href="[sort:link]" class="text-light d-block pt-3 pb-3"><i class="fa fa-location-arrow" aria-hidden="true"></i> [sort:name]</a>
			{/pboot:sort}
        </div>
    </div>
</div>

<!-- 在线客服 -->
<div class="online d-none d-md-block">
	<dl>
		<dt style="width:150px;">
        	<h3><i class="fa fa-commenting-o"></i>在线咨询<span class="remove"><i class="fa fa-remove"></i></span></h3>
            <p>
            	<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={pboot:companyqq}&site=qq&menu=yes">
                	<img border="0" src="{pboot:sitetplpath}/images/qq.jpg" alt="点击这里给我发消息" title="点击这里给我发消息"/>
               		 售前咨询专员
                </a>
            </p>
             <p>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={pboot:companyqq}&site=qq&menu=yes">
                	<img border="0" src="{pboot:sitetplpath}/images/qq.jpg" alt="点击这里给我发消息" title="点击这里给我发消息"/>
               		 售后服务专员
                </a>
            </p>
        </dt>
		<dd><i class="fa fa-commenting-o"></i></dd>
		<dd>在线咨询</dd>
	</dl>
    
	<dl>
		<dt style="width:300px;">
        	<h3><i class="fa fa-volume-control-phone"></i>免费通话<span class="remove"><i class="fa fa-remove"></i></span></h3>
            <p>24小时免费咨询</p>
            <p>请输入您的联系电话，座机请加区号</p>
            <form  onsubmit="return subform(this);">
            	<p><input type="text" name="tel" id="tel" autocomplete="off"  placeholder="请输入您的电话号码" required maxlength="30"></p>
                <p><button type="submit">免费通话</button></p>
            </form>
        </dt>
		<dd><i class="fa fa-volume-control-phone" aria-hidden="true"></i></dd>
		<dd>免费通话</dd>
	</dl>
    
    <dl>
		<dt style="width:200px;">
        	<h3><i class="fa fa-weixin" aria-hidden="true"></i>微信扫一扫<span class="remove"><i class="fa fa-remove"></i></span></h3>
           	<p><img src="{pboot:companyweixin} " width="100%"></p>
        </dt>
		<dd><i class="fa fa-weixin" aria-hidden="true"></i></dd>
		<dd>微信联系</dd>
	</dl>
    
	<dl class="scroll-top">
		<dd><i class="fa fa-chevron-up"></i></dd>
		<dd>返回顶部</dd>
	</dl>
</div>

<script src="{pboot:sitetplpath}/js/popper.min.js"></script>
<script src="{pboot:sitetplpath}/bootstrap/js/bootstrap.min.js"></script>
<script src="{pboot:sitetplpath}/js/wow.min.js"></script>
<script src="{pboot:sitetplpath}/js/aoyun.js?v=v1.2.2"></script>
<script>
//ajax提交表单
function subform(obj){
  var url='{pboot:form fcode=2}';
  var tel=$(obj).find("#tel").val();
  
  var reg = /^(1|0)[\d\-]+$/;   
  if (!reg.test(tel)) {
	  alert('电话号码错误！');
	  return false;
  }
  
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: {
    	tel: tel
    },
    success: function (response, status) {
      if(response.code){
		 alert("您的来电已收到，我们会尽快联系您！");
		 $(obj)[0].reset(); 
      }else{
    	 alert(response.data);
      }
    },
    error:function(xhr,status,error){
      alert('返回数据异常！');
    }
  });
  return false;
}
</script>

{pboot:sitestatistical}

</body>
</html>
<?php return array (
  0 => 'E:/phpstudy_pro/WWW/PbootCMS/template/default/html/comm/head.html',
  1 => 'E:/phpstudy_pro/WWW/PbootCMS/template/default/html/comm/top.html',
  2 => 'E:/phpstudy_pro/WWW/PbootCMS/template/default/html/comm/position.html',
  3 => 'E:/phpstudy_pro/WWW/PbootCMS/template/default/html/comm/foot.html',
); ?>