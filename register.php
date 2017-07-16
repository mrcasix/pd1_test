<?php 
	include 'includes.php';
	if(isset($_POST['registrati'])){
		$mail=$_POST['mail'];
		$password= $_POST['password'];
		$password= $_POST['password'];
		
		$dati=controlla_login($mail,$password);
		
		
		if($dati['esito']===true){
			$_SESSION['logged']=1;
			$_SESSION['ultima_attivita']=time();
			$_SESSION['id_utente']=$dati['id_utente'];
			header("Location: http://".__LINK_INTERO_SITO__);
		}
	}
	
	

?>

<?php

if(isset($_SESSION["logged"])){
     header("location:".__LINK_SITO__);
}



?>

<!doctype html>
<html>
  <head>
    <title><?php echo __NOME_SITO__ ?></title>
    <link rel = 'stylesheet' type = 'text/css' href = './css/general_style.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  </head>
  <body>
  <?php ?>
    
    <div id = 'contenitore'>
        
        <div id = 'intestazione'>
            <a href="<?php echo __LINK_SITO__ ?>"><?php echo __NOME_SITO__ ?><a/>
        </div>
	<div id='parte_centrale'>
		<?php
			include_once "./barra_laterale.php";
		?>
		
		
				<div id = 'contenuto'>
					<div class="centeredDiv">
					
					<div class="intestazione_blocco">
						<h2>Inserire dati per la registrazione</h2> 
						<hr/>
					</div>
					<form id="form_registrazione" method="post" action='<?php echo $_SERVER["PHP_SELF"] ?>?controlla_cookies'>
						<div class="blocco_main">
							<label class="form_label">Nome</label>
							<input alt="Inserire nome" name="nome" id="mail" placeholder="Inserire mail" class="full_width" type="text"/>
							<span id="msg_below_mail" class="alert_below" ></span>
						</div>
						<div class="blocco_main">
							<label class="form_label">Cognome</label>
							<input alt="Inserire cognome" name="cognome" id="cognome" placeholder="Inserire mail" class="full_width" type="text"/>
							<span id="msg_below_mail" class="alert_below" ></span>
						</div>
						<div class="blocco_main">
							<label class="form_label">Mail</label>
							<input alt="Inserire mail" name="mail" id="mail" placeholder="Inserire mail" class="full_width" type="text"/>
							<span id="msg_below_mail" class="alert_below" ></span>
						</div>
						<div class="blocco_main">
							<label class="form_label">Password</label>
							<input alt="Inserire password" name="password" id="password" placeholder="Inserire password" class="full_width" type="password"/>
							<span id="msg_below_pwd" class="alert_below"></span>
						</div><div class="blocco_main">
							<label class="form_label">Ripeti Password</label>
							<input alt="Inserire password" name="ripassword" id="ripassword" placeholder="Inserire password" class="full_width" type="password"/>
							<span id="msg_below_pwd" class="alert_below"></span>
						</div>
						<div class="blocco_main">
							<span class="span_button ">
								<input type="submit" name="logga" class="primary_button" value="Registrati"/>
							</span>
						</div>
					</form>
					</div>
				</div>
	</div>
       
	
    </div>
	<script>
	
	$(document).ready(function() {
		
		
		$(document).on('submit','#form_accesso',function(){
			
			mail = $("#mail");
			password = $("#password");
			
			if(password.val()!="" && password.length>0 && mail.val()!="" && mail.length>0){
				return true;
			}
			else {
				if(!(mail.val()!="" && mail.length>0)){
					mail.addClass("obbligo_compilazione");
					$("#msg_below_mail").text("Inserire mail");
				}
				else {
					mail.removeClass("obbligo_compilazione");
					$("#msg_below_mail").text("");
				}	
				if(!(password.val()!="" && password.length>0)){
					password.addClass("obbligo_compilazione");
					$("#msg_below_pwd").text("Inserire password");
				}
				else { 
					password.removeClass("obbligo_compilazione");
					$("#msg_below_pwd").text("");
				}
			}
			return false;
			
		});
		
	});
	
	</script>
	
	
  </body>
</html>