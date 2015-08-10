<!doctype html>
<html class="no-js">
	<head>
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="<?php echo config_item('assets'); ?>img/favicon.ico">
		<title>CMS | Transcosmos Indonesia</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/bootstrap.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/font-awesome.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/main.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/metisMenu.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/prism.min.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/jquery.gritter.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/dataTables.bootstrap.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/daterangepicker-bs3.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/fileinput.css">
		<link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/chosen.min.css">
        <link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/datepicker.css">
        <link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/bootstrap-timepicker.css">
        <link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/fullcalendar.css">
        <link rel="stylesheet" href="<?php echo config_item('assets'); ?>css/timeline.css">        
		<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
		<!--[if lt IE 9]>
		<script src="<?php echo config_item('assets'); ?>js/html5shiv.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/respond.min.js"></script>
		<![endif]-->
		<!--Modernizr-->
		<script src="<?php echo config_item('assets'); ?>js/modernizr.min.js"></script>
		<!-- tcid add -->
		<style>
			#fork {
			    position: absolute;
			    top: 0;
			    right: 0;
			    border: 0;
			}
			
			.legend {
			    width: 100%;
				height: auto;
			}
			
			.legend .title {
			    display: block;
			    margin-bottom: 0.5em;
			    line-height: 1.2em;
			    padding: 0 0.3em;
			}
			
			.legend .color-sample {
			    display: block;
			    float: left;
			    width: 1em;
			    height: 1em;
			    border: 2px solid; /* Comment out if you don't want to show the fillColor */
			    border-radius: 0.5em; /* Comment out if you prefer squarish samples */
			    margin-right: 0.5em;
			}
            
            div[class*="tooltip"]
            {
                color: #81CFE0 !important;   
            }
            
            button.btn-default
            {
                color: #000000 !important;   
            }
            
            .fc-content span.fc-title
            {
                color: #000000 !important;
            }
			.mr10{
				margin-right: 10px;
			}
		</style>
	</head>
	<body class="">
		<div  id="wrap" class="bg-dark dk">
			<div id="top">
				<!-- .navbar -->
				<nav class="navbar <?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ echo 'navbar-inverse';}else{ echo 'bg-light dker';} ?> navbar-fixed-top">
					<div class="container-fluid">
						<!-- Brand and toggle get grouped for better mobile display -->
						<header class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#left">
								<span class="sr-only">Toggle navigation</span> 
								<span class="icon-bar"></span> 
								<span class="icon-bar"></span> 
								<span class="icon-bar"></span> 
							</button>
							<a href="<?php echo base_url(); ?>dashboard" class="navbar-brand"><img width="105" src="<?php echo config_item('assets'); ?>img/logo_tci_01.png" alt=""> </a>
						</header>
						<div class="topnav">
							<div class="btn-group">
							  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							    <img style="width:25px;height:auto;" src="<?php echo config_item('assets').'img/user_photo/'.$this->session->userdata('image'); ?>" class="replaced-image img-<?php echo $this->session->userdata('nip')?>"/>
							    <span><?php echo anchor('members/profile', $this->session->userdata('name')); ?></span>
							    <span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php if($this->session->userdata('division')!=""){?>
							    <!-- <li><a href=""><?php //echo anchor('members/profile', 'Edit Profile'); ?></a></li> -->
								<li><a href="<?php echo site_url('members/profile')?>"><i class="fa fa-pencil"></i> Edit profile</a></li>
								<?php if(isCheckOut()){?>
								<li><a  href="#" class="checkOut"><i class="fa fa-sign-out"></i> Check out </a></li>
								<?php }?>
								<li role="presentation" class="divider"></li>
								<?php }?>
							    <li><a data-placement="bottom" href="<?php echo site_url('auth/logout')?>" class="btn btn-metis-1 btn-sm" title="Logout"> <i class="fa fa-power-off"></i></a></li>
							  </ul>
							</div>
							<div class="btn-group" id="fullscreen">
								<a data-placement="bottom" class="btn btn-primary btn-sm" id="toggleFullScreen" title="Full Screen"><i class="fa fa-expand fa-2x"></i> </a>
							</div>
						</div>
					</div><!-- /.container-fluid -->
				</nav><!-- /.navbar -->
			</div><!-- /#top -->
			<div id="left" class="<?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ echo 'bg-dark dk';}else{ echo 'bg-light dk';} ?>">
				<div class="media user-media <?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ echo 'bg-dark dker';}else{ echo 'bg-light lt';} ?>">
					<div class="user-media-toggleHover">
						<span class="fa fa-user"></span>
					</div>
					<div class="user-wrapper <?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ echo 'bg-dark';}else{ echo 'bg-light';} ?>">
						<div class="media-body">
							<h5 class="media-heading">Welcome</h5>
							<div class="image-edit">
								<img style="width:100px;height:auto;" src="<?php echo config_item('assets').'img/user_photo/'.$this->session->userdata('image'); ?>?cachebuster=<?php echo time();?>" class="replaced-image img-<?php echo $this->session->userdata('nip')?>"/>								<div class="overlay-image">
									<button class="btn btn-default" type="button" id="chgPic" data-url='<?php echo site_url('members/avatar'); ?>'>Change Picture</button>
								</div>
							</div>
							<ul class="list-unstyled user-info">
								<li>
									<?php 
									
									if($this->session->userdata('division')!=""){
										echo anchor('members/profile', $this->session->userdata('name'));
									}
									else{
										echo $this->session->userdata('name');
									} ?>
								</li>
								<li><h4>Login as <?php echo $this->session->userdata('level'); ?></h4></li>
								<li>
									<?php if($this->session->userdata('division')&&$this->session->userdata('level')!='Administrator'){?>
									<?php echo "Div. ".$this->session->userdata('division');}else{
									 ?>I am an Administrator <?php } ?>
								</li>
								<li>
								    <small><?php echo date('D d, M Y'); ?></small>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- #menu -->
				<ul id="menu" class="<?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ echo 'bg-dark dker';}else{ echo 'bg-light dker';} ?>">
					<li class="nav-divider"></li>
					<li class="<?php echo (($this->uri->segment(1) == 'dashboard') ? 'active' : null); ?>">
						<a href="<?php echo site_url('dashboard')?>"> <i class="fa fa-dashboard"></i> <span class="link-title">Dashboard</span> </a>
					</li>
					<?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ ?>
					<li class="<?php echo (($this->uri->segment(1) == 'daily_attendance') ? 'active' : null); ?>">
						<a href="<?php echo site_url('daily_attendance')?>"><i class="fa fa-clock-o"></i> <span class="link-title">Daily Attendance</span></a>						
					</li>
					<?php } ?>
					<li class="<?php echo (($this->uri->segment(1) == 'user_request') ? 'active' : null); ?>">
						<a href="<?php echo site_url('user_request')?>"><i class="fa fa-gavel"></i> <span class="link-title"> Member Request</span>
					<?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Manager"){ if( $this->notification->get_number() > 0 ){?>
						<span class="badge pull-right mr10" title="<?php echo $this->notification->get_number(); ?> not yet approved"><?php echo $this->notification->get_number(); ?></span>
					<?php }else{} }elseif($this->session->userdata('level')=="Leader"){ if( $this->notification->get_number_leader() > 0 ){?>
						<span class="badge pull-right mr10" title="<?php echo $this->notification->get_number_leader(); ?> not yet approved"><?php echo $this->notification->get_number_leader(); ?></span>
					<?php }else{} } ?>
						</a>						
					</li>
					<li class="<?php echo ((($this->uri->segment(1) == 'daily_reports')||($this->uri->segment(1) == 'weekly_reports')||($this->uri->segment(1) == 'project_assignment')) ? 'active' : null); ?>">
						<a href="javascript:;"><i class="fa fa-book"></i> <span class="link-title"> Report</span><span class="fa arrow"></span></a>
						<ul>
							<?php if($this->session->userdata('level')!="Admin"){ ?>
							<li class="<?php echo (($this->uri->segment(1) == 'daily_reports') ? 'active' : null); ?>">
								<a href="<?php echo site_url('daily_reports')?>"><i class="fa fa-angle-right"></i> <span class="link-title">Daily report</span> </a>
							</li>
							<?php } ?>
							<?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ ?>
							<li class="<?php echo (($this->uri->segment(1) == 'weekly_reports') ? 'active' : null); ?>">
								<a href="<?php echo site_url('weekly_reports')?>"><i class="fa fa-angle-right"></i> <span class="link-title">Weekly Report</span> </a>
							</li>
							<li class="<?php echo (($this->uri->segment(1) == 'project_assignment') ? 'active' : null); ?>">
								<a href="<?php echo site_url('project_assignment')?>"><i class="fa fa-angle-right"></i> <span class="link-title">Project Assignment</span> </a>
							</li>
							<?php } ?>
						</ul>
					</li>
					<?php if($this->session->userdata('level')=="Admin") { ?>
					<li class="<?php echo ((($this->uri->segment(1) == 'members')||($this->uri->segment(1) == 'users')||($this->uri->segment(1) == 'privileges')) ? 'active' : null); ?>">
						<a href="javascript:;"><i class="fa fa-list"></i> <span class="link-title">Member List</span><span class="fa arrow"></span></a>
						<ul>
							<li class="<?php echo (($this->uri->segment(1) == 'members') ? 'active' : null); ?>">
								<a href="<?php echo site_url('members')?>"> <i class="fa fa-angle-right"></i> Members </a>
							</li>
							<li class="<?php echo ((($this->uri->segment(1) == 'privileges')||($this->uri->segment(1) == 'users')) ? 'active' : null); ?>">
                                <a href="<?php echo site_url('privileges')?>"><i class="fa fa-angle-right"></i> Users & Privileges </a>
                            </li>
						</ul>
					</li>
					<li class="<?php echo ((($this->uri->segment(1) == 'divisions')||($this->uri->segment(1) == 'work_classifications')||($this->uri->segment(1) == 'work_categories')||($this->uri->segment(1) == 'job_codes')) ? 'active' : null); ?>">
						<a href="javascript:;"><i class="fa fa-tasks"></i> <span class="link-title"> Division & Job</span><span class="fa arrow"></span></a>
						<ul>
							<li class="<?php echo (($this->uri->segment(1) == 'divisions') ? 'active' : null); ?>">
								<a href="<?php echo site_url('divisions')?>"><i class="fa fa-angle-right"></i> <span class="link-title">Division</span> </a>
							</li>
							<li class="<?php echo (($this->uri->segment(1) == 'work_classifications') ? 'active' : null); ?>">
								<a href="<?php echo site_url('work_classifications')?>"><i class="fa fa-angle-right"></i> <span class="link-title">Work Classification</span> </a>
							</li>
							<li class="<?php echo (($this->uri->segment(1) == 'work_categories') ? 'active' : null); ?>">
								<a href="<?php echo site_url('work_categories')?>"><i class="fa fa-angle-right"></i> <span class="link-title">Work Categories</span> </a>
							</li>
							<li class="<?php echo (($this->uri->segment(1) == 'job_codes') ? 'active' : null); ?>">
								<a href="<?php echo site_url('job_codes')?>"><i class="fa fa-angle-right"></i> <span class="link-title">Job Code</span> </a>
							</li>
						</ul>
					</li>
					<li class="<?php echo (($this->uri->segment(1) == 'agendas') ? 'active' : null); ?>">
                        <a href="<?php echo site_url('agendas')?>"> <i class="fa fa-calendar"></i> <span class="link-title">Project Calendar</span> </a>
                    </li>
					<li class="<?php echo (($this->uri->segment(1) == 'holiday') ? 'active' : null); ?>">
						<a href="<?php echo site_url('holiday')?>"> <i class="fa fa-beer"></i> <span class="link-title">Holiday Setting</span> </a>
					</li>
					<?php } ?>
					<li class="<?php echo (($this->uri->segment(1) == 'help') ? 'active' : null); ?>">
						<a href="<?php echo site_url('help')?>"> <i class="fa fa-info-circle"></i> <span class="link-title">Help</span> </a>
					</li>
				</ul><!-- /#menu -->
			</div><!-- /#left -->
			<div id="content">
				<div class="outer">
					<div class="inner bg-light2 lter">
						<?php
							if(isset($content))
							{
								echo $content;

							}
							
						?>
					</div><!-- /.inner -->
				</div><!-- /.outer -->
			</div><!-- /#content -->
		</div><!-- /#wrap -->
		<footer class="Footer <?php if($this->session->userdata('level')=="Admin" || $this->session->userdata('level')=="Leader"){ echo 'bg-dark dker';}else{ echo 'bg-light dker';} ?>">
			<p>
				2015 &copy; <a href="http://www.trans-cosmos.co.id/" target="_blank">PT. Transcosmos Indonesia</a> All rights reserved.
			</p>
		</footer><!-- /#footer -->

		<!-- #myModal -->
		<div id="myModal" class="modal fade"></div>
		<div id="myModal-lg" class="modal fade bs-example-modal-lg"></div>
		<div id="myModal-sm" class="modal fade bs-example-modal-sm"></div>
		<!-- /#myModal -->

		<!--jQuery -->
		<script src="<?php echo config_item('assets'); ?>js/jquery.min.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/prism.min.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/bootstrap.min.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/fileinput.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/metisMenu.min.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/screenfull.min.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/jquery.gritter.min.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/core.js"></script>
		<script src="<?php echo config_item('assets'); ?>js/app.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
		<?php  if(isset($script)) echo $script; ?>
		<script>
			<?php if($this->session->flashdata('alert')) : ?>
				gritter_alert('<?php echo $this->session->flashdata('alert') ?>');
			<?php endif; ?>
			$(function(){
				$('.checkOut').on('click',function(e){
					_this = $(this);
					e.preventDefault();
					var r = confirm('Are you sure to record your checkout time?');
					if(r){
					$.ajax({
						url: '<?php echo site_url('auth/checkout')?>',
						method: 'POST',
						success: function(data){
							var res = $.parseJSON(data);
							if(res.status == 1){
								gritter_alert(res.result);
								_this.parent().remove();
							}else{
								gritter_alert(res.result);
							}
						}
					});
					}
				});
				
				
			});
			
			/*setTimeout(function(){
				$(".panel-collapse.collapse.in").slideUp(500);
			},3500);
			setTimeout(function(){
				$(".panel-collapse.collapse.in").removeClass("in");
				$(".panel-collapse.collapse").slideDown();
				$(".panel-collapse.collapse").removeAttr("style");
			},4200);*/
		</script>
	</body>
</html>