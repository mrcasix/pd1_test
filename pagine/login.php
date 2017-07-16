<?php
session_start();
session_unset();
session_destroy();
session_start();

//require_once '../lib/session.php';
require_once '../build/p_lib/dbconnect.php';

if (isset($_COOKIE['un'])):
	 $un = $_COOKIE['un'];
	 $ps = base64_decode($_COOKIE['ps']);
	 $ps = substr($ps,0,-6);
else:
	 $un = "";
	 $ps = "";
endif;

if(isset($_GET["login"])):
	
 	$username = $_POST["username"];
 	$pass = md5($_POST["pass"]);
	if ($_POST["remember"]=="on"):
		$durata = 1577833200; // 2020
		setcookie("un",$_POST["username"],$durata);
		$token_password = rand(100000, 199999);
		setcookie("ps",base64_encode($_POST["pass"].$token_password),$durata);
	else:
		setcookie("un","",$durata);
		setcookie("ps","",$durata);
	endif;
	
 	$check = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM anagrafica WHERE userid = '$username'")or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$info = mysqli_fetch_array($check);
	$password_db = $info['password'];
	$status = $info['status'];
	if ($password_db != $pass or $status==0):
		header("Location: login.php?lerr");
	else:
		$_SESSION['sito'] = $info['idst'];
		/////////// DOCEBO
		/*
		$cr_pass = base64_encode($_POST["pass"]."*09hsdf76we6"); login docebo
		$cr_user = base64_encode($_POST["username"]."*09hsdf76we6"); 
		$_SESSION['info'] = array($info['userid'], $info['password'], $info['codice_fiscale'], $cr_user, $cr_pass);
		*/
		$_SESSION['info'] = array($info['userid'], $info['password'], $info['codice_fiscale'], $info['SSO']);

		$res_perm_pers = mysqli_query($GLOBALS["___mysqli_ston"], "select * from permessi_utenti where idst=".$info['idst']);
		$exists = mysqli_num_rows($res_perm_pers);
		$is_god_admin = 0;
		if($exists > 0):
			$res = mysqli_query($GLOBALS["___mysqli_ston"], "select a.permessi, a.ownership, a.id_gruppo, b.permission
								from permessi_utenti a 
								join permessi_gruppo b
								on a.id_gruppo = b.id_gruppo
								where a.idst = ".$info['idst']);
			$info_perm = mysqli_fetch_assoc($res);
			$perm = $info_perm["permessi"];
			if ($perm != "") $permessi = $info_perm["permessi"];
			else $permessi = $info_perm["permission"];
			$ownership = $info_perm["ownership"];
			if($info_perm["id_gruppo"]==1):
				$is_god_admin = 1;
			endif;
		else:
			$res = mysqli_query($GLOBALS["___mysqli_ston"], "select permission from permessi_gruppo where id_gruppo = 2");
			$info_perm = mysqli_fetch_assoc($res);
			$permessi = $info_perm["permission"];
			$ownership = 1000;
		endif;
		$_SESSION["is_owner"] = explode("*",$ownership);
		///
		$arrayize = explode("*",$permessi);
		$level = array();
		for($a=0; $a<count($arrayize); $a++):
			$sub_array = explode(",", $arrayize[$a]);
			$level[] = $sub_array;
		endfor;
		
		if($info['SSO']!=''):
			$res_hr = mysqli_query($GLOBALS["___mysqli_ston"], "select idst from anagrafica where status=1 and sso_hr_manager = '".$info['SSO']."'");
			$num_hr = mysqli_num_rows($res_hr);
			/// stabilisco se è un capo
			$res_c = mysqli_query($GLOBALS["___mysqli_ston"], "select idst from anagrafica where status=1 and sso_org_manager = '".$info['SSO']."'");
			$num = mysqli_num_rows($res_c);
			/// stabilisco se è un supervisor
			$res_s = mysqli_query($GLOBALS["___mysqli_ston"], "select idst from anagrafica where status=1 and sso_supervisore1 = '".$info['SSO']."'");
			$num_s = mysqli_num_rows($res_s);
		else:
			$num_hr = 0;
			$num = 0;
			$num_c = 0;
		endif;

		if($num_hr > 0 and ($num  >0 or $num_s>0)):
			$level[] = "both"; // sia capo, sia hr manager
		elseif($num_hr > 0 and $num == 0 and $num_s ==0 ):
			$level[] = "research_key_2"; // hr manager
		elseif($num_hr == 0 and ($num > 0 or $num_s > 0)): 
			$level[] = "research_key_1"; // è capo o supervisore
		elseif($num_hr == 0 and $num == 0 and $num_s==0): 
			$level[] = "no"; // lavoratore generico
		endif;
		$level[] = $is_god_admin;
		//////////////////////////////////////////////////////
		$_SESSION["is_admin"] = $level;
		
		$myip = $_SERVER['REMOTE_ADDR'];
		$sql_log = mysqli_query($GLOBALS["___mysqli_ston"], "insert into track_session set idst=".$_SESSION['sito'].", data_in='".date('Y-m-d H:i:s')."', op='_login', ip='".$myip."'");
		
 		header("Location: index.php?page=home");
	endif;
endif;

?>

<!DOCTYPE html>
<html lang="it">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="../assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../assets/css/ace.min.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.min.css" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.min.js"></script>
		<![endif]-->
        <style>
		.login-container {
		  width: 701px;
  		  margin: 0 auto;
		}

		</style>
	</head>

	<body class="login-layout light-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-12">
						<div class="login-container">
							<div class="center">
                            <!--
								<h1>
									<i class="ace-icon fa fa-leaf green"></i>
									<span class="red">Ace</span>
									<span class="white" id="id-text2">Application</span>
								</h1>
								<h4 class="blue" id="id-company-text">&copy; Company Name</h4>
                            -->
                            <img src="../assets/img_p/head.jpg">
							</div>
							<div class="space-6"></div>
							<div class="position-relative">
       							<p class="bg-primary">&nbsp;</p>
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<!--<i class="ace-icon fa fa-coffee green"></i>-->
												Inserisci i tuoi dati per la login
											</h4>

											<div class="space-6"></div>

											<form action="login.php?login=1" method="post">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $un; ?>" required />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="pass" class="form-control" placeholder="Password" value="<?php echo $ps; ?>" required />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
                                                  
														<label class="inline">
															<input type="checkbox"  name="remember" checked/>
															<span class="lbl"> Ricorda i miei dati</span>
														</label>
													
														<button type="submit" name="submit" class="width-100 btn btn-sm btn-primary" >
														<!--	<i class="ace-icon fa fa-key"></i>-->
															<span class="bigger-110">Login</span>
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>
										
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->
                                <?php if(isset($_GET["lerr"])): ?>
								<div class="alert alert-block alert-danger">
									<strong class="red">Userid o password errati. Riprova</strong>
								</div>
								<?php endif;?>
								<?php if(isset($_GET["lout"])): ?>
								<div class="alert alert-block alert-success">
									<strong class="green">Arrivederci</strong>
								</div>
								<?php endif;?>

							</div><!-- /.position-relative -->
<!--
							<div class="navbar-fixed-top align-right">
								<br />
								&nbsp;
								<a id="btn-login-dark" href="#">Dark</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-blur" href="#">Blur</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-light" href="#">Light</a>
								&nbsp; &nbsp; &nbsp;
							</div>
						</div>
-->                        
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->
</div>
	</body>
</html>
