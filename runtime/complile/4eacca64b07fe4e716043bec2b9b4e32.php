<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="renderer"  content="webkit">
  <title>PbootCMS管理中心-V<?php echo APP_VERSION;?>-<?php echo RELEASE_TIME;?></title>
  <link rel="shortcut icon" href="<?php echo SITE_DIR;?>/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo APP_THEME_DIR;?>/layui/css/layui.css?v=v2.5.4">
  <link rel="stylesheet" href="<?php echo APP_THEME_DIR;?>/font-awesome/css/font-awesome.min.css?v=v4.7.0" type="text/css">
  <link rel="stylesheet" href="<?php echo APP_THEME_DIR;?>/css/comm.css?v=v2.0.7">
  <link href="<?php echo APP_THEME_DIR;?>/css/jquery.treetable.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="<?php echo APP_THEME_DIR;?>/js/jquery-1.12.4.min.js"></script>
  <script type="text/javascript" src="<?php echo APP_THEME_DIR;?>/js/jquery.treetable.js"></script>
</head>

<body class="layui-layout-body">

<!--定义部分地址方便JS调用-->
<div style="display: none">
	<span id="controller" data-controller="<?php echo C;?>"></span>
	<span id="url" data-url="<?php echo URL;?>"></span>
	<span id="preurl" data-preurl="<?php echo url('/admin',false);?>"></span>
	<span id="sitedir" data-sitedir="<?php echo SITE_DIR;?>"></span>
	<span id="mcode" data-mcode="<?php echo get('mcode');?>"></span>
</div>

<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo">
    <a href="<?php echo \core\basic\Url::get('/admin/Index/home');?>">
    <img src="<?php echo APP_THEME_DIR;?>/images/logo.png" height="30">
	    PbootCMS 
	   	<?php if (LICENSE==3) {?>
	   		<span class="layui-badge">SVIP</span>
	   	<?php } else { ?>
	   		<span class="layui-badge layui-bg-gray">V<?php echo APP_VERSION;?></span>	
	   	<?php } ?>
    </a>
    </div>
    
    <ul class="menu">
    	<li class="menu-ico" title="显示或隐藏侧边栏"><i class="fa fa-bars" aria-hidden="true"></i></li>
	</ul>
	<?php if (!$this->getVar('one_area')) {?>
	<form method="post" action="<?php echo \core\basic\Url::get('/admin/Index/area');?>" class="area-select">
		<input type="hidden" name="formcheck" value="<?php echo $this->getVar('formcheck');?>" > 
		<div class="layui-col-xs8">
		   <select name="acode">
		       <?php echo $this->getVar('area_html');?>
		   </select>
		</div>
		<div class="layui-col-xs4">
		 	<button type="submit" class="layui-btn layui-btn-sm">切换</button>
		</div>
   	</form>
 	<?php } ?>

    <ul class="layui-nav layui-layout-right">
    
       <li class="layui-nav-item layui-hide-xs">
      	 <a href="<?php echo SITE_DIR;?>/" target="_blank"><i class="fa fa-home" aria-hidden="true"></i> 网站主页</a>
       </li>

       <li class="layui-nav-item layui-hide-xs">
       		<a href="<?php echo \core\basic\Url::get('/admin/Index/clearCache');?>"><i class="fa fa-trash-o" aria-hidden="true"></i> 清理缓存</a>
       </li>
       
       <li class="layui-nav-item layui-hide-xs">
	        <a href="javascript:;">
	          <i class="fa fa-user-circle-o" aria-hidden="true"></i> <?php echo session('realname');?>
	        </a>
	        <dl class="layui-nav-child">
	          <dd><a href="<?php echo \core\basic\Url::get('/admin/Index/ucenter');?>"><i class="fa fa-address-card-o" aria-hidden="true"></i> 资料修改</a></dd>
	          <dd><a href="<?php echo \core\basic\Url::get('/admin/Index/loginOut');?>"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出登录</a></dd>
	        </dl>
      </li>
    </ul>
  </div>
  
  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree" id="nav" lay-shrink="all">
	   <?php $num = 0;foreach ($this->getVar('menu_tree') as $key => $value) { $num++;?>
        <li class="layui-nav-item nav-item <?php if ($this->getVar('primary_menu_url')==$value->url) {?>layui-nav-itemed<?php } ?>">
          <a class="" href="javascript:;"><i class="fa <?php echo $value->ico; ?>" aria-hidden="true"></i><?php echo $value->name; ?></a>
          <dl class="layui-nav-child">
			<?php if ($value->mcode=='M130') {?>
				 <?php $num3 = 0;foreach ($this->getVar('menu_models') as $key3 => $value3) { $num3++;?>
				 	<?php if ($value3->type==1) {?>
						<dd><a href="<?php echo \core\basic\Url::get('/admin/Single/index/mcode/'.$value3->mcode.'');?>"><i class="fa fa-file-text-o" aria-hidden="true"></i><?php echo $value3->name; ?>内容</a></dd>
					<?php } ?>
					<?php if ($value3->type==2) {?>
						<dd><a href="<?php echo \core\basic\Url::get('/admin/Content/index/mcode/'.$value3->mcode.'');?>"><i class="fa fa-file-text-o" aria-hidden="true"></i><?php echo $value3->name; ?>内容</a></dd>
					<?php } ?>
				 <?php } ?>
			<?php } else { ?>
				<?php $num2 = 0;foreach ($value->son as $key2 => $value2) { $num2++;?>
					<?php if (!isset($value2->status)|| $value2->status==1) {?>
	            		<dd><a href="<?php echo \core\basic\Url::get(''.$value2->url.'');?>"><i class="fa <?php echo $value2->ico; ?>" aria-hidden="true"></i><?php echo $value2->name; ?></a></dd>
	            	<?php } ?>
				<?php } ?>
				
				<?php if ($value->mcode=='M101' && session('ucode')==10001) {?>
					<dd><a href="<?php echo \core\basic\Url::get('/admin/Upgrade/index');?>"><i class="fa fa-cloud-upload" aria-hidden="true"></i>在线更新</a></dd>
				<?php } ?>
		    <?php } ?>
          </dl>
        </li>
		<?php } ?>
		
		<li style="height:1px;background:#666" class="layui-hide-sm"></li>
		
		<li class="layui-nav-item layui-hide-sm">
		 <a href="<?php echo SITE_DIR;?>/" target="_blank"><i class="fa fa-home" aria-hidden="true"></i> 网站主页</a>
		</li>
		
		<li class="layui-nav-item layui-hide-sm">
          <a href="<?php echo \core\basic\Url::get('/admin/Index/ucenter');?>"><i class="fa fa-address-card-o" aria-hidden="true"></i> 资料修改</a>
        </li>
        
        <li class="layui-nav-item layui-hide-sm">
         <a href="<?php echo \core\basic\Url::get('/admin/Index/clearCache');?>"><i class="fa fa-trash-o" aria-hidden="true"></i> 清理缓存</a>
        </li>
        
        <li class="layui-nav-item layui-hide-sm">
         <a href="<?php echo \core\basic\Url::get('/admin/Index/loginOut');?>"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出登录</a>
        </li>

      </ul>
    </div>
  </div>

<div class="layui-body">
	<?php if ($this->getVar('list')) {?>
	<div class="layui-tab layui-tab-brief" lay-filter="tab">
	  <ul class="layui-tab-title">
	    <li class="layui-this" lay-id="t1">会员列表</li>
	    <li lay-id="t2">会员新增</li>
	  </ul>
	  <div class="layui-tab-content">
	  	<div class="layui-tab-item layui-show">
	  		<form action="<?php echo \core\basic\Url::get('/admin/Member/index');?>" method="get" class="layui-form">
	  	   		<div class="layui-form-item nospace">
	  	   			<div class="layui-input-inline">
	  	   			  <?php echo $this->getVar('pathinfo');?>
				       <select name="field" lay-verify="required">
				       		<option value="username" <?php if (get('field')=='username') {?>selected="selected" <?php } ?>>会员用户名</option>
				       		<option value="useremail" <?php if (get('field')=='useremail') {?>selected="selected" <?php } ?>>会员邮箱</option>
                            <option value="usermobile" <?php if (get('field')=='usermobile') {?>selected="selected" <?php } ?> >会员昵称</option>
                       </select>
				    </div>
                     <div class="layui-input-inline">
                     	<input type="text" name="keyword"  value="<?php echo get('keyword');?>"  placeholder="请输入搜索关键字" class="layui-input">
                     </div>
                     <div class="layui-input-inline">
                     	<button class="layui-btn" lay-submit>搜索</button>
                     	<a class="layui-btn layui-btn-primary"  href="<?php echo \core\basic\Url::get('/admin/Member/index');?>">清除搜索</a>
                     </div>
                </div>
            </form>
            
            <form action="<?php echo \core\basic\Url::get('/admin/Member/mod');?>" method="post" id="contentForm" name="contentForm" class="layui-form" onkeydown="if(event.keyCode==13) return false;">
		    	<input type="hidden" name="formcheck" value="<?php echo $this->getVar('formcheck');?>" > 
		  		<table class="layui-table">
		  			<thead>
	                    <tr>
	                    	<th><input type="checkbox" class="checkbox" lay-ignore id="checkall" title="全选"></th>
	                    	<th>编号</th>
	                        <th>用户名</th>
	                        <th>昵称</th>
	                        <th>状态</th>
	                        <th>等级</th>
	                        <th>积分</th>
	                        <th>注册时间</th>
	                        <th>最后登录时间</th>
	                        <th>操作</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php $num = 0;foreach ($this->getVar('members') as $key => $value) { $num++;?>
	                    <tr>
	                    	<td>
	                    		<input type="checkbox" class="checkbox checkitem" lay-ignore name="list[]" value="<?php echo $value->id; ?>">
	                    		<input type="hidden" name="listall[]" value="<?php echo $value->id; ?>">
	                    	</td>
	                    	<td><?php echo $value->ucode; ?></td>
	                        <td><?php echo $value->username; ?></td>
	                        <td><?php echo $value->nickname; ?></td>
	                        <td>
	                       		<?php if ($value->status) {?>
		                        <a href="<?php echo \core\basic\Url::get('/admin/'.C.'/mod/id/'.$value->id.'/field/status/value/0');?>"><i class='fa fa-toggle-on' title="点击禁用"></i></a>
		                        <?php } else { ?>
		                        <a href="<?php echo \core\basic\Url::get('/admin/'.C.'/mod/id/'.$value->id.'/field/status/value/1');?>"><i class='fa fa-toggle-off' title="点击启用"></i></a>
		                        <?php } ?>
	                        </td>
	                        <td><?php echo $value->gname; ?></td>
	                        <td><?php echo $value->score; ?></td>
	                        <td><?php echo $value->register_time; ?></td>
	                        <td><?php echo $value->last_login_time; ?></td>
	                        <td>
	                       		<?php echo get_btn_more($value->id);?>
	                            <?php echo get_btn_del($value->id);?>
	                            <?php echo get_btn_mod($value->id);?>
	                        </td>
	                    </tr>
	                    <?php } ?>
	                </tbody>
		  		</table>
		  		
		  		<?php if ($this->getVar('members')) {?>
		  		<div class="layui-btn-group">
                   
                    <?php if (check_level('del')) {?>
			     		<button type="submit" name="submit" onclick="return setDelAction();" class="layui-btn layui-btn-sm">删除</button>
			     	<?php } ?>
			     	
			     	<?php if (check_level('mod')) {?>
			     		<button type="submit" name="submit" value="verify1" class="layui-btn layui-btn-sm">启用</button>
			     		<button type="submit" name="submit" value="verify0" class="layui-btn layui-btn-sm">禁用</button>
			     	 <?php } ?>
		     	 </div>
		     	<script>
		     		function setDelAction(){
		     			document.contentForm.action = "<?php echo \core\basic\Url::get('/admin/Member/del');?>"; 
		     			return confirm("您确定要删除选中的会员么？");
		     		}
		     	</script>
		     	<?php } ?>
		  	</form> 
	  		<div class="page"><?php echo $this->getVar('pagebar');?></div>
	  	</div>
	  	<div class="layui-tab-item">
	  		 <form action="<?php echo \core\basic\Url::get('/admin/Member/add');?>" method="post" class="layui-form">
	  		 	<input type="hidden" name="formcheck" value="<?php echo $this->getVar('formcheck');?>" > 
				
				<div class="layui-form-item">
                     <label class="layui-form-label">用户名 <span class="layui-text-red">*</span></label>
                     <div class="layui-input-block">
                     	<input type="text" name="username" required  lay-verify="required"  autocomplete="off"  placeholder="请输入会员用户名" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">邮箱</label>
                     <div class="layui-input-block">
                     	<input type="text" name="useremail"  placeholder="请输入会员邮箱,可登录用" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">手机</label>
                     <div class="layui-input-block">
                     	<input type="text" name="usermobile"  placeholder="请输入会员手机,可登录用" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">昵称</label>
                     <div class="layui-input-block">
                     	<input type="text" name="nickname"  placeholder="请输入会员昵称" autocomplete="off"  class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">登录密码  <span class="layui-text-red">*</span></label>
                     <div class="layui-input-block">
                     	<input type="password" name="password"  required lay-verify="required" placeholder="请输入登录密码" autocomplete="off" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">头像</label>
                     <div class="layui-input-inline">
                     	<input type="text" name="headpic" id="headpic" placeholder="请上传头像"  class="layui-input">
                     </div>
                     <button type="button" class="layui-btn upload" data-des="headpic">
					 	 <i class="layui-icon">&#xe67c;</i>上传图片
					 </button>
					 <div id="headpic_box" class="pic addedit"></div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">状态</label>
                     <div class="layui-input-block">
                     	<input type="radio" name="status" value="1" title="启用" checked>
						<input type="radio" name="status" value="0" title="禁用">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">等级</label>
                     <div class="layui-input-block">
                     	<select name="gid">
	                        <?php $num = 0;foreach ($this->getVar('groups') as $key => $value) { $num++;?>
	                            <option value="<?php echo $value->id; ?>"><?php echo $value->gname; ?></option>
	                        <?php } ?>
	                    </select>
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">积分</label>
                     <div class="layui-input-block">
                     	<input type="text" name="score" value="0" maxlength="10" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
					 <div class="layui-input-block">
					    <button class="layui-btn" lay-submit>立即提交</button>
					    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
					 </div>
				</div>
	  		 </form>
	  	</div>
	  </div>
	</div>
	<?php } ?> 
	
	<?php if ($this->getVar('more')) {?>
	<div class="layui-tab layui-tab-brief" lay-filter="tab">
	  <ul class="layui-tab-title">
	    <li class="layui-this">会员详情</li>
	  </ul>
	  <div class="layui-tab-content">
	  	<div class="layui-tab-item layui-show">
	  		<table class="layui-table table-two">

				 <tr>
					<th width="100">编号</th>
					<td><?php echo @$this->getVar('member')->ucode;?></td>
				 </tr>

				 <tr>
					<th>用户名</th>
					<td><?php echo @$this->getVar('member')->username;?></td>
				 </tr>
				 
				 <tr>
					<th>邮箱</th>
					<td><?php echo @$this->getVar('member')->useremail;?></td>
				 </tr>
				 
				 <tr>
					<th>手机</th>
					<td><?php echo @$this->getVar('member')->usermobile;?></td>
				 </tr>
				 
				 <tr>
					<th>昵称</th>
					<td><?php echo @$this->getVar('member')->nickname;?></td>
				 </tr>
				 
				  <tr>
					<th>头像</th>
					<td><img src="<?php echo @$this->getVar('member')->headpic;?>" style="height:30px;"></td>
				 </tr>
				 
				  <tr>
					<th>等级</th>
					<td><?php echo @$this->getVar('member')->gname;?></td>
				 </tr>
				 
				  <tr>
					<th>积分</th>
					<td><?php echo @$this->getVar('member')->score;?></td>
				 </tr>
				 
				 <tr>
					<th>状态</th>
					<td>
						<?php if ($this->getVar('member')->status==1) {?>启用<?php } ?>
						<?php if ($this->getVar('member')->status==0) {?>禁用<?php } ?>
					</td>
				</tr>
				 
				<?php $num2 = 0;foreach ($this->getVar('fields') as $key2 => $value2) { $num2++;?>
	  	   			<tr>
				      	<th><?php echo $value2->description; ?></th>
				      	<?php  $field=$value2->name?>
				      	<td><?php echo @$this->getVar('member')->$field;?></td>
				    </tr>
  	   			 <?php } ?>
  	   			 
				 <tr>
					<th>注册时间</th>
					<td><?php echo @$this->getVar('member')->register_time;?></td>
				 </tr>
				 
				 <tr>
					<th>登录次数</th>
					<td><?php echo @$this->getVar('member')->login_count;?></td>
				 </tr>
				 
				 <tr>
					<th>最后登录IP</th>
					<td><?php echo long2ip($this->getVar('member')->last_login_ip);?></td>
				 </tr>
				 
				 <tr>
					<th>最后登录时间</th>
					<td><?php echo @$this->getVar('member')->last_login_time;?></td>
				 </tr>
  	   	    </table>
	  		<?php echo get_btn_back();?>
	  	</div>
	  </div>
	 </div>
	<?php } ?> 
	
	<?php if ($this->getVar('mod')) {?>
	<div class="layui-tab layui-tab-brief" lay-filter="tab">
	  <ul class="layui-tab-title">
	    <li class="layui-this">会员修改</li>
	  </ul>
	  <div class="layui-tab-content">
	  	<div class="layui-tab-item layui-show">
	  		 <form action="<?php echo \core\basic\Url::get('/admin/Member/mod/id/'.get('id').'');?><?php echo $this->getVar('backurl');?>" method="post" class="layui-form">
	  		 	<input type="hidden" name="formcheck" value="<?php echo $this->getVar('formcheck');?>" > 

               <div class="layui-form-item">
                     <label class="layui-form-label">用户名 <span class="layui-text-red">*</span></label>
                     <div class="layui-input-block">
                     	<input type="text" name="username" required value="<?php echo @$this->getVar('member')->username;?>" lay-verify="required"  autocomplete="off"  placeholder="请输入会员用户名" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">邮箱</label>
                     <div class="layui-input-block">
                     	<input type="text" name="useremail" value="<?php echo @$this->getVar('member')->useremail;?>" placeholder="请输入会员邮箱,可登录用" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">手机</label>
                     <div class="layui-input-block">
                     	<input type="text" name="usermobile" value="<?php echo @$this->getVar('member')->usermobile;?>" placeholder="请输入会员手机,可登录用" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">昵称</label>
                     <div class="layui-input-block">
                     	<input type="text" name="nickname" value="<?php echo @$this->getVar('member')->nickname;?>"  placeholder="请输入会员昵称" autocomplete="off"  class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">密码 </label>
                     <div class="layui-input-block">
                     	<input type="password" name="password" placeholder="请输入登录密码" autocomplete="off" class="layui-input">
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">头像</label>
                     <div class="layui-input-inline">
                     	<input type="text" name="headpic" value="<?php echo @$this->getVar('member')->headpic;?>" id="headpic" placeholder="请上传头像"  class="layui-input">
                     </div>
                     <button type="button" class="layui-btn upload" data-des="headpic">
					 	 <i class="layui-icon">&#xe67c;</i>上传图片
					 </button>
					 <div id="headpic_box" class="pic addedit">
					 	<?php if ($this->getVar('member')->headpic) {?><dl><dt><img src="<?php echo SITE_DIR;?><?php echo @$this->getVar('member')->headpic;?>" data-url="<?php echo @$this->getVar('member')->headpic;?>"></dt><dd>删除</dd></dl><?php } ?>
					 </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">状态</label>
                     <div class="layui-input-block">
                     	<input type="radio" name="status" value="1" title="启用" <?php if ($this->getVar('member')->status==1) {?>checked<?php } ?>>
						<input type="radio" name="status" value="0" title="禁用" <?php if ($this->getVar('member')->status==0) {?>checked<?php } ?>>
                     </div>
                </div>
                
                 <div class="layui-form-item">
                     <label class="layui-form-label">等级</label>
                     <div class="layui-input-block">
                     	<select name="gid">
	                        <?php $num = 0;foreach ($this->getVar('groups') as $key => $value) { $num++;?>
	                            <option value="<?php echo $value->id; ?>" <?php if ($value->id==$this->getVar('member')->gid) {?>selected<?php } ?>><?php echo $value->gname; ?></option>
	                        <?php } ?>
	                    </select>
                     </div>
                </div>
                
                <div class="layui-form-item">
                     <label class="layui-form-label">积分</label>
                     <div class="layui-input-block">
                     	<input type="text" name="score"  value="<?php echo @$this->getVar('member')->score;?>" maxlength="10" class="layui-input">
                     </div>
                </div>
                
                 <div class="layui-form-item">
					 <div class="layui-input-block">
					    <button class="layui-btn" lay-submit>立即提交</button>
					    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
					    <?php echo get_btn_back();?>
					 </div>
				</div>
				
	  		 </form>
	  	</div>
	  </div>
	 </div>
	<?php } ?> 
</div>
  <div class="layui-footer">
    <!-- 底部固定区域 -->
    &copy; <a href="http://www.pbootcms.com" target="_blank">2018-<?php echo date('Y');?> PbootCMS版权所有.</a>
  	<span class="layui-hide-xs">{pboot:runtime}</span>
  </div>
</div>

<script type="text/javascript" src="<?php echo APP_THEME_DIR;?>/layui/layui.all.js?v=v2.5.4"></script>
<script type="text/javascript" src="<?php echo APP_THEME_DIR;?>/js/comm.js?v=v2.0.0"></script>
<script type="text/javascript" src="<?php echo APP_THEME_DIR;?>/js/mylayui.js?v=v3.0.2"></script>


<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
<!--[if lt IE 9]>
  <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
  <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</body>
</html>

<?php return array (
  0 => 'G:/github/platform/apps/admin/view/default/common/head.html',
  1 => 'G:/github/platform/apps/admin/view/default/common/foot.html',
); ?>