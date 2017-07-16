<?php
require_once("../build/p_lib/session.php");
require_once("../build/p_lib/dbconnect.php");
require_once("../build/p_lib/query.php");
require_once("../build/p_lib/functions.php");
require_once("../build/p_lib/conf.php");
require_once("../public/includes/modal.php");

$_SESSION["active"] = 3;
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo $GLOBALS['default_name'];?></title>
		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css" />
		<link rel="stylesheet" href="../assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.min.css" />
		<![endif]-->
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.min.css" />
		<![endif]-->
		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.min.js"></script>
		<script src="../assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

<?php
	

?>
	<body class="no-skin">
		<!-- BLOCCO HEADER, SIDEBAR, BREADCRUMBS
		<!-- #section:basics/navbar.layout -->
		<?php require_once("includes/header.php") ?>
		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<!-- #section:basics/sidebar -->
			<?php require_once("includes/sidemenu.php") ?>
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<?php require_once("includes/breadcrumbs.php"); ?>
				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">
					<!-- #section:settings.box -->
				<?php //require_once("includes/settingsbar.php")?>
         <!-- FINE BLOCCO -->
					<div class="page-content-area">
						<div class="page-header">
							<h1>Nuovo corso</h1>
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="alert alert-block alert-success">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
									Tip: Compila tutti i campi per creare un nuovo corso.
								</div>
								<div class="hr hr8 hr-dotted"></div>                              
								<div class="row">

								</div><!-- /.row -->

								<div class="hr hr32 hr-dotted"></div>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content-area -->
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->
			<?php require_once("includes/footer.php")?>
		</div><!-- /.main-container -->
	</body>
</html>
