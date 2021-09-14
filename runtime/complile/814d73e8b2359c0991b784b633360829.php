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
<style>
	.navbar-nav .active .nav-link{
		color: #3786FF !important;
		font-weight: bold;
		border-bottom: 3px solid #3786FF;
	}
	.navbar {
		padding: 0 !important;
	}
	.navbar-nav{
		height: 53px;
	}
	.navbar-nav li,.navbar-nav li a{
		height: 100%;
		display: flex;
		align-items: center;
	}
	.login{
		color: #3786FF !important;
		margin: 0px 10px;
	}
	.regiset{
		background: #3786FF !important;
		color: #ffffff !important;
		padding: 5px 10px;
		border-radius: 3px;
	}
</style>
<!-- 头部导航 -->
<nav class="navbar navbar-light bg-light fixed-top navbar-expand-lg shadow-sm">
  <div class="container">
	  	<a class=" my-1" href="{pboot:sitepath}/">
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
			  	{pboot:2if({pboot:loginstatus})}
					<a class="text-secondary login" href="{pboot:login}" >登录</a>
				{/pboot:2if}
		     	{pboot:2if({pboot:registerstatus})}
					<a class="text-secondary regiset" href="{pboot:register}" >注册</a>
				{/pboot:2if}
		     {/pboot:if}
	      </span>
	      
	    </div>
    </div>
</nav>

<!--占位导航栏-->
<div style="height:57px;" class="head-sm-height"></div>



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
  	 
  	<!-- 分类导航 -->
<div class="sonsort mt-4 mb-2">
    {pboot:nav num=10 parent={sort:tcode}}
    	{pboot:if([nav:i]==1)}
            {pboot:2if('{sort:scode}'=='{sort:tcode}')}
                <a href="{sort:toplink}" class="btn btn-info mb-2">全部 <span class="badge badge-light">{sort:toprows}</a>
            {2else}
                <a href="{sort:toplink}" class="btn btn-light mb-2">全部 <span class="badge btn-info">{sort:toprows}</a>
            {/pboot:2if}
        {/pboot:if}
        
        {pboot:if('[nav:scode]'=='{sort:scode}')}
            <a href="[nav:link]" class="btn btn-info mb-2">[nav:name] <span class="badge badge-light">[nav:rows]</span></a>
        {else}
            <a href="[nav:link]" class="btn btn-light mb-2">[nav:name] <span class="badge btn-info">[nav:rows]</span></a>
        {/pboot:if}
    {/pboot:nav}
</div>

    <h2 class="text-center fs-sm-28 fs-20 mt-3">{content:title}</h2>
    <div class="text-center border-bottom text-secondary pb-2 mb-3">
    	  时间：{content:date style=Y-m-d}&nbsp;&nbsp;&nbsp;访问量：{content:visits operate=+1000}
    </div>
	<div class="content mb-3">{content:content}</div>
	<div class="text-secondary lh-2">
		<p>上一篇：{content:precontent}</p>
		<p>下一篇：{content:nextcontent}</p>
	</div>
</div>

{pboot:if({pboot:commentstatus})}
<div class="container">
	<div class="shadow-sm p-3 my-5 bg-white rounded">
		<h4>发表评论：</h4>
		<form class="my-4" onsubmit="return submitcomment(this);" data-action="{pboot:commentaction}" >
		        <div class="form-group">
		            <textarea name="comment" id="comment" class="form-control" placeholder="请输入评论内容"></textarea>
		        </div>
		        
		        {pboot:2if({pboot:commentcodestatus})}
		        <div class="form-group">
		            <div class="row">
		                <div class="col-6 col-md-3">
		                    <input type="text" name="checkcode" required id="checkcode" class="form-control" placeholder="请输入验证码">
		                </div>
		                <div class="col-6  col-md-3">
		                    <img title="点击刷新" class="codeimg"  style="height:33px;" src="{pboot:checkcode}" onclick="this.src='{pboot:checkcode}?'+Math.round(Math.random()*10);" />
		                </div>
		            </div>
		        </div>
		        {/pboot:2if}
		        
		        <div class="form-group">
		           <button type="submit" class="btn btn-info mb-2">提交评论</button>
		        </div>
		    </form>
		    
		    
		    <h4>评论记录：</h4>
		      <a name="comment"></a>
		     {pboot:comment contentid={content:id} num=20}
		    <div class="media border-bottom pb-3 pt-3">
			  <img class="mr-3" src="[comment:headpic]" height="25">
			  <div class="media-body">
			    <h6 class="mt-0">
			    	<strong>[comment:nickname]</strong> 
			    	<small style="color:#999">[comment:date]</small>
			    </h6>
			    <p>[comment:comment] 
			    	<span class="badge badge-light text-secondary font-weight-normal replybtn"  data-action="[comment:replyaction]">回复</span>
			    </p>
			    
			    {pboot:commentsub} <!-- 调用子评论 -->
			    <div class="media mt-3 border-top-dashed pt-3">
			      <img class="mr-3" src="[commentsub:headpic]" height="25">
			      <div class="media-body">
			        <h6 class="mt-0"><strong>[commentsub:nickname]</strong> <small style="color:#999">[commentsub:date]</small></h6>
			        <p>
			        	@[commentsub:pnickname] [commentsub:comment] 
			        	<span class="badge badge-light text-secondary font-weight-normal replybtn"  data-action="[commentsub:replyaction]">回复</span>
			        </p>
			      </div>
			    </div>
			    {/pboot:commentsub}
			    
			  </div>
			</div>
		  {/pboot:comment}
		  
		  <!-- 分页 -->
			{pboot:2if({page:rows}>0)}
			   <nav aria-label="page navigation" class="my-4">
			     <div class="pagination justify-content-center">
			     	<a class="page-item page-link" href="{page:index}#comment">首页</a>
			     	<a class="page-item page-link" href="{page:pre}#comment">上一页</a>
			      	<a class="page-item page-link" href="{page:next}#comment">下一页</a>
			      	<a class="page-item page-link" href="{page:last}#comment">尾页</a>
			     </div>
			   </nav>	
			{2else}
			   	<div class="text-center my-5 text-secondary">未查询到任何数据！</div>
			{/pboot:2if}
	</div>
</div>

<!-- 评论回复弹框 -->
<div class="modal" tabindex="-1" role="dialog" id="reply">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title">回复评论：</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form  onsubmit="return submitcomment(this);" data-action=""  id="replyform">
	      <div class="modal-body">
		         <div class="form-group">
		             <textarea name="comment" id="comment" class="form-control" placeholder="请输入评论内容"></textarea>
		         </div>
		         {pboot:2if({pboot:commentcodestatus})}
		         <div class="form-group">
		             <div class="row">
		                 <div class="col-6">
		                     <input type="text" name="checkcode" required id="checkcode" class="form-control" placeholder="请输入验证码">
		                 </div>
		                 <div class="col-6">
		                     <img title="点击刷新" class="codeimg" style="height:33px;" src="{pboot:checkcode}" onclick="this.src='{pboot:checkcode}?'+Math.round(Math.random()*10);" />
		                 </div>
		             </div>
		         </div>
		         {/pboot:2if}
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
	        <button type="submit" class="btn btn-info">提交评论</button>
	      </div>
       </form>
    </div>
  </div>
</div>


<script>

//评论回复弹框
$('.replybtn').on("click",function(){ 
	var url=$(this).data("action");
	$("#reply").modal("show");	
	$("#replyform").data("action",url);
});

//提交评论
function submitcomment(obj){
  var url= $(obj).data("action");
  var comment=$(obj).find("#comment").val();
  var checkcode=$(obj).find("#checkcode").val();
  
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    data: {
    	comment: comment,
    	checkcode: checkcode
    },
    success: function (response, status) {
      if(response.code){
    	 alert(response.data);
		 $(obj)[0].reset(); 
		 $(".modal").modal("hide");	
		 window.location.reload()
      }else{
    	 if(response.tourl!=""){
    		 if(confirm(response.data+'是否立即跳转登录？')){
    			 location.href=response.tourl; 
   	   	     }
    	 }
    	 $('.codeimg').click();//更新验证码
      }
    },
    error:function(xhr,status,error){
      alert('返回数据异常！');
    }
  });
  return false;
}
</script>
{/pboot:if}





<style>
    .copyright{
        background: #0B101D;
        color: #ffffff;
    }
</style>
<div class="footer mt-3 border-top text-secondary bg-light">
<!--	<div class="container">-->
<!--&lt;!&ndash;    	<div class="row pb-5">&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="col-12 col-md-5">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            	<h5>{pboot:companyname}</h5>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <ul class="lh-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                	<li>营业执照：{pboot:companyblicense}  </li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>备案号码：<a href="http://beian.miit.gov.cn/" target="_blank">{pboot:siteicp}</a> </li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>地址：{pboot:companyaddress} </li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </ul>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="col-12 col-md-5">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            	<div class="mt-4 d-block d-md-none"></div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            	<h5>联系我们</h5>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <ul class="lh-2">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>电话：{pboot:companyphone} </li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>邮箱：{pboot:companyemail} </li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                    <li>Q&nbsp;&nbsp;Q：{pboot:companyqq} </li>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                </ul>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            <div class="col-12 col-md-2 text-center d-none d-md-block">&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            	<p class="code">{pboot:qrcode string={pboot:httpurl}<?php echo URL;?>} </p>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;                <p class="small">扫一扫 手机访问</p>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;&lt;!&ndash;            </div>&ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->
<!--	</div>-->
    <div class="copyright border-top lh-3 text-center  d-none d-md-block">
    	{pboot:sitecopyright}
    </div>
</div>

<!-- 占位 -->
<!--<div style="height:49px;" class="d-block d-sm-none"></div>-->

<!-- 手机底部导航 -->
<!--<div class="container-fluid bg-info fixed-bottom d-block d-sm-none">-->
<!--    <div class="row">-->
<!--        <div class="col-4 p-0 text-center border-right">-->
<!--            <a href="tel:{pboot:companymobile}" class="text-light d-block pt-3 pb-3"><i class="fa fa-phone" aria-hidden="true"></i> 电话咨询</a>-->
<!--        </div>-->
<!--        <div class="col-4 p-0 text-center border-right">-->
<!--            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={pboot:companyqq}&site=qq&menu=yes" class="text-light d-block pt-3 pb-3"><i class="fa fa-qq" aria-hidden="true"></i> 在线咨询</a>-->
<!--        </div>-->
<!--        <div class="col-4 p-0 text-center">-->
<!--			{pboot:sort scode=1}-->
<!--            <a href="[sort:link]" class="text-light d-block pt-3 pb-3"><i class="fa fa-location-arrow" aria-hidden="true"></i> [sort:name]</a>-->
<!--			{/pboot:sort}-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!-- 在线客服 -->
<!--<div class="online d-none d-md-block">-->
<!--	<dl>-->
<!--		<dt style="width:150px;">-->
<!--        	<h3><i class="fa fa-commenting-o"></i>在线咨询<span class="remove"><i class="fa fa-remove"></i></span></h3>-->
<!--            <p>-->
<!--            	<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={pboot:companyqq}&site=qq&menu=yes">-->
<!--                	<img border="0" src="{pboot:sitetplpath}/images/qq.jpg" alt="点击这里给我发消息" title="点击这里给我发消息"/>-->
<!--               		 售前咨询专员-->
<!--                </a>-->
<!--            </p>-->
<!--             <p>-->
<!--                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={pboot:companyqq}&site=qq&menu=yes">-->
<!--                	<img border="0" src="{pboot:sitetplpath}/images/qq.jpg" alt="点击这里给我发消息" title="点击这里给我发消息"/>-->
<!--               		 售后服务专员-->
<!--                </a>-->
<!--            </p>-->
<!--        </dt>-->
<!--		<dd><i class="fa fa-commenting-o"></i></dd>-->
<!--		<dd>在线咨询</dd>-->
<!--	</dl>-->
<!--    -->
<!--	<dl>-->
<!--		<dt style="width:300px;">-->
<!--        	<h3><i class="fa fa-volume-control-phone"></i>免费通话<span class="remove"><i class="fa fa-remove"></i></span></h3>-->
<!--            <p>24小时免费咨询</p>-->
<!--            <p>请输入您的联系电话，座机请加区号</p>-->
<!--            <form  onsubmit="return subform(this);">-->
<!--            	<p><input type="text" name="tel" id="tel" autocomplete="off"  placeholder="请输入您的电话号码" required maxlength="30"></p>-->
<!--                <p><button type="submit">免费通话</button></p>-->
<!--            </form>-->
<!--        </dt>-->
<!--		<dd><i class="fa fa-volume-control-phone" aria-hidden="true"></i></dd>-->
<!--		<dd>免费通话</dd>-->
<!--	</dl>-->
<!--    -->
<!--    <dl>-->
<!--		<dt style="width:200px;">-->
<!--        	<h3><i class="fa fa-weixin" aria-hidden="true"></i>微信扫一扫<span class="remove"><i class="fa fa-remove"></i></span></h3>-->
<!--           	<p><img src="{pboot:companyweixin} " width="100%"></p>-->
<!--        </dt>-->
<!--		<dd><i class="fa fa-weixin" aria-hidden="true"></i></dd>-->
<!--		<dd>微信联系</dd>-->
<!--	</dl>-->
<!--    -->
<!--	<dl class="scroll-top">-->
<!--		<dd><i class="fa fa-chevron-up"></i></dd>-->
<!--		<dd>返回顶部</dd>-->
<!--	</dl>-->
<!--</div>-->

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
  0 => 'G:/github/platform/template/default/html/comm/head.html',
  1 => 'G:/github/platform/template/default/html/comm/top.html',
  2 => 'G:/github/platform/template/default/html/comm/position.html',
  3 => 'G:/github/platform/template/default/html/comm/sortnav.html',
  4 => 'G:/github/platform/template/default/html/comm/comment.html',
  5 => 'G:/github/platform/template/default/html/comm/foot.html',
); ?>