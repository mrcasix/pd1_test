<?php
$idst = $_SESSION['sito'];

?>

<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="navbar-brand">
						<small>
							<!--<i class="fa fa-leaf"></i>-->
							<?php echo $GLOBALS["header"] ?>
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue">
							<a data-toggle="dropdown" class="dropdown-toggle">
								<!--<img class="nav-user-photo" src="../assets/avatars/user.jpg" alt="Jason's Photo" />-->
                            
								<span class="user-info">
									
								</span>

								<small>Ciao,</small>
									<b><?php 
									$nome = fiendly_name($idst);
									echo ucwords(strtolower($nome));
									
									?></b>
							</a>
						</li>
						
						<li class="green">
							<a class="dropdown-toggle tooltip-success" data-rel="tooltip" data-placement="bottom" title="Utenti on-line">
								<span><b><?php echo $onlineIP->get_count_online(); ?></b></span>
                                <i class="ace-icon fa fa-users icon-animated-bell"></i>
							</a>
						</li>
 
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>
        
<script type="text/javascript">
	jQuery(function($) {
		$('[data-rel=tooltip]').tooltip();
		$('[data-rel=popover]').popover({html:true});
	});
</script>