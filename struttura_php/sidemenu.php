<style>
.char {
	font-size:12px !important;
}
</style>

<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

			<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						
						<a href="mailto:assistenza@consulman.it" role="button" class="btn btn-success"><i class="ace-icon fa fa-envelope"></i></a>
						
						<a role="button" class="btn btn-info form_password" title="Modifica Password">
							<i class="ace-icon fa fa-key " ></i>
						</a>
						<!--
						 #section:basics/sidebar.layout.shortcuts 
						<button class="btn btn-info">
							<i class="ace-icon fa fa-envelope"></i>
						</button>
						-->
						<a href="../includes/logout.php" role="button" class="btn btn-danger"><i class="ace-icon fa fa-power-off"></i></a>
						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li <?php if($_SESSION["active"] == 0) echo "class='active'"; ?>>
						<a href="../public/index.php?page=home">
							<i class="menu-icon fa fa-home"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][0];?> </span>
						</a>
						<b class="arrow"></b>
					</li>
                    
                    <li <?php if($_SESSION["active"] == 1) echo "class='active'"; ?>>
						<a href="../public/index.php?page=processi">
							<i class="menu-icon fa fa-exchange"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][1];?> </span>
						</a>

						<b class="arrow"></b>
					</li> 
					<li <?php if($_SESSION["active"] == 13) echo "class='active'"; ?>>
						<a href="../public/index.php?page=f_tree">
							<i class="menu-icon fa fa-clone"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][13];?> </span>
						</a>

						<b class="arrow"></b>
					</li> 
					<li <?php if($_SESSION["active"] == 14) echo "class='active'"; ?>>
						<a href="../public/index.php?page=attivita">
							<i class="menu-icon fa fa-cog"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][14];?> </span>
						</a>

						<b class="arrow"></b>
					</li>
                    <li <?php if($_SESSION["active"] == 2) echo "class='active'"; ?>>
						<a href="../public/index.php?page=competence">
							<i class="menu-icon fa fa-bookmark"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][2];?> </span>
						</a>

						<b class="arrow"></b>
					</li>
                    <li <?php if($_SESSION["active"] == 3) echo "class='active'"; ?>>
						<a href="../public/index.php?page=role-profile">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][3];?> </span>
						</a>

						<b class="arrow"></b>
					</li>
                    <li <?php if($_SESSION["active"] == 11) echo "class='active'"; ?>>
						<a href="../public/index.php?page=valutation">
							<i class="menu-icon fa fa-area-chart"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][11];?> </span>
						</a>

						<b class="arrow"></b>
					</li>
                    
					<li <?php if($_SESSION["active"] == 7) echo "class='active'"; ?>>
						<a href="../public/index.php?page=repo">
							<i class="menu-icon fa fa-bar-chart-o"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][7];?> </span>
						</a>

						<b class="arrow"></b>
					</li>
					
					
                    
                    <!--------------------------------------------------------------------------------------------------------->                    
                    <?php  
					if ($_SESSION["is_admin"][5][0] == 1): ?>
                    <li <?php if($_SESSION["active"] == 8 or $_SESSION["active"] == 9) echo "class='active'"; ?>>
                        <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-cogs"></i>
                                <span class="menu-text char"> <?php echo $GLOBALS['sidebar'][8];?> </span>
                                <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        
                        <ul class="submenu">
                            <li <?php if($_SESSION["active"] == 9) echo "class='active'"; ?>>
                                <a href="../admin1903/index.php?page=people">
                                    <i class="menu-icon fa fa-users"></i>
                                    <span class="menu-text char"> <?php echo $GLOBALS['sidebar'][9];?> </span>
                                </a>
        
                                <b class="arrow"></b>
                            </li> 
							<li <?php if($_SESSION["active"] == 10) echo "class='active'"; ?>>
                                <a href="../admin1903/index.php?page=tools">
                                    <span class="menu-text char"> <?php echo $GLOBALS['sidebar'][10];?> </span>
                                </a>
        
                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                   
                    
                    
                    <!--------------------------------------------------------------------------------------------------------->
                    
                    <li>
						<a href="../includes/logout.php">
							<i class="menu-icon fa fa-power-off"></i>
							<span class="menu-text char"> <?php echo $GLOBALS['sidebar'][4];?> </span>
						</a>
						<b class="arrow"></b>
					</li>
					
				</ul><!-- /.nav-list -->
				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>                