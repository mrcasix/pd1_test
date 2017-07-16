<?php
require_once("../build/p_lib/session.php");
require_once("../build/p_lib/dbconnect.php");
require_once("../build/p_lib/query.php");
require_once("../build/p_lib/functions.php");
require_once("../build/p_lib/conf.php");
require_once("../build/p_lib/online_users.php");
require_once("../includes/modal.php");

$onlineIP = New OnlineUsers;

if(!isset($_GET["page"])) $page = "error";
else $page = $_GET["page"];

require_once("../includes/dispatcher.php");
$loadpage = dispatch($page);
?>

<!DOCTYPE html>
<html lang="it">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo $GLOBALS['default_name'];?></title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css" />
		<link rel="stylesheet" href="../assets/css/datepicker.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="../assets/css/daterangepicker.css" />
		<link rel="stylesheet" href="../assets/css/bootstrap-datetimepicker.css" />
		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="../assets/css/select2.css" />
        <link rel="stylesheet" href="../assets/css/jquery.gritter.css" />
		<!-- text fonts -->
		<link rel="stylesheet" href="../assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
        
		<style>
        	.datepicker{z-index:20000 !important;}
        </style>
		<!-- ace settings handler -->
        <!--[if !IE]> -->
        <script type="text/javascript">
           window.jQuery || document.write("<script src='../assets/js/jquery.min.js'>"+"<"+"/script>");
        </script>
		<script src="../assets/js/jquery.gritter.min.js"></script>
        <!-- <![endif]-->
	</head>

	<body class="no-skin">
		<!-- BLOCCO HEADER, SIDEBAR, BREADCRUMBS
		<!-- #section:basics/navbar.layout -->
		<?php require_once("../includes/header.php") ?>
		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<!-- #section:basics/sidebar -->
			<?php require_once("../includes/sidemenu.php") ?>
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<?php require_once("../includes/breadcrumbs.php"); ?>
				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">
					<!-- #section:settings.box -->
				<?php //require_once("includes/settingsbar.php")?>
        		 <!-- FINE BLOCCO -->
					<!-- /section:settings.box -->
					<div class="page-content-area">
						<div class="page-header">
							<!--<h1>
								<?php echo $GLOBALS['nometool'] ?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<?php echo $GLOBALS['sidebar'][$_SESSION["active"]]; ?>
								</small>
							</h1>-->
						</div><!-- /.page-header -->
						<script src="../assets/js/ace-extra.min.js"></script>

                        <script type="text/javascript">
							if('ontouchstart' in document.documentElement) document.write("<script src='../assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
						</script>
						<script src="../assets/js/bootstrap.min.js"></script>
						<script src="../assets/js/jquery-ui.custom.min.js"></script>
						<script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
						<script src="../assets/js/bootbox.min.js"></script>
						<script src="../assets/js/date-time/bootstrap-datepicker.min.js"></script>
						<script src="../assets/js/date-time/bootstrap-datetimepicker.min.js"></script> 
						<!--<script src="../assets/js/date-time/daterangepicker.min.js"></script>-->
						<!--<script src="../build/p_lib/daterange-it.js"></script>-->
						<script src="../build/p_lib/bootstrap-datepicker.it.js"></script>                  
						<script src="../assets/js/select2.min.js"></script>        
						<script src="../assets/js/jquery.maskedinput.min.js"></script>
						<script src="../assets/js/ace-elements.min.js"></script>
						<script src="../assets/js/ace.min.js"></script>
						<script src="../build/p_lib/jquery.jPrintArea.js"></script>

						<div class="row main">
    						<?php include($loadpage); ?>
						</div><!-- /.row -->
					</div><!-- /.page-content-area -->
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->
			<?php require_once("../includes/footer.php")?>
		</div><!-- /.main-container -->
        <div id="edit_password" class="modal fade" tabindex="-1">
            <div class="modal-dialog" style="width:40% !important;">
                <div class="modal-content">
                    <div class="modal-header no-padding">
                        <div class="table-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <span class="white">&times;</span>
                            </button>Modifica password
                        </div>
                    </div>
                    <div class="modal-body">
                         <div class='finestra'></div>
                         <div class="messaggio"></div>
                    </div>
                    <div class="modal-footer no-margin-top">
                        <button class="btn btn-sm btn-success pull-left" id="cambia_password">
                            <i class="ace-icon fa fa-check"></i>
                            Salva
                        </button>
                        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                            <i class="ace-icon fa fa-times"></i>
                            Chiudi
                        </button>
                    </div>
                </div>
            </div>
        </div>  
	</body>
</html>


<script>
jQuery(function($) {
		$('.form_password').on('click', function() {
			$(".messaggio").html("");
			$(".finestra").html(" Nuova Password:<br /><input id='password' type='password' style='width: 80%'></input><br />Riscrivi Password:<br /><input id='conferma' type='password' style='width: 80%'></input>");
			$('#edit_password').modal('show');
			$("#cambia_password").removeClass("disabled");
		});
		
		$('#cambia_password').on('click', function() {
			password = $("#password").val();
			conf_password = $("#conferma").val();
			if(password==conf_password && password!="" && conf_password != ""){
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=change_password',
					type: 'POST',
					data: {password: password},
					success:function(msg){
						$(".messaggio").html("<b>Password modificata correttamente</b>");
						$(".finestra").html("");
						$("#cambia_password").addClass("disabled");
					},
					error: function(response) {},
					async: false
				});
			}else{
				$(".messaggio").html("<div><b>Le password non coincidono</b></div>");
			}
		});
});
	
</script>