<?php

include 'includes.php';

//die(var_dump($_POST));

/*
if(isset($_POST['send_form'])){
	
	die("inviato");
	$id_utente=$_SESSION["id_utente"];
	$durata=$_POST["durata"];
	save_prenotazione($id_utente,$durata);
}
*/


?>

<!doctype html>
<html>
  <head>
    <link rel = 'stylesheet' type = 'text/css' href = './css/general_style.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script type = 'text/javascript' href = './js/util.js'></script>
   </head>
  <body>
     <div id = 'contenitore'>
            <div id = 'intestazione'>
            <a href="<?php echo __LINK_SITO__ ?>"><?php echo __NOME_SITO__ ?><a/>
        </div>
	<div id='parte_centrale'>
		<?php
			include_once "./barra_laterale.php";
		?>
		
		<div id = 'contenuto'>
			<?php if(!isset($_SESSION['logged'])){ ?>	
			
			<div class="success_panel">
				Benvenuto in <?php echo __NOME_SITO__ ?>,il sito web per la gestione delle consulenze.
 Per poter effetturare una prenotazione  &egrave; necessario <a href="<?php echo __LINK_SITO__?>registrati.php">registrarsi</a> oppure effettuare il <a href="<?php echo __LINK_SITO__?>login.php">login</a>.
			</div>
			
			<?php } else {
				$prenotazioni = get_prenotazioni($_SESSION['id_utente']);
			?>
			<form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>?controlla_cookies" onsubmit="return check_prenotazione(); " >			
			
	<input type="hidden" name="action" value="save_prenotazione"/>

	<?php if(count($prenotazioni)==0){?>
	<h3 class="pre_text" >Effettua una prenotazione:</h3>
				<div class="input_durata">
					<input  type="text" placeholder="Inserire durata" name="durata" value=""/> 


					<input type="submit" name="send_form" value="Invia"/>

				</div>
			</form>	
		

	<?php } else { ?>	
				<h3 class="pre_text" >La tua prenotazioni</h3>
				<table id="elenco_prenotazioni">

					<tr>
						<th>Fascia oraria</th>
						<th>Durata</th>
						<th>#</th>
					</tr>
					<?php

					
 
					foreach($prenotazioni as $index=>$values){

?>
					<tr>
						<td><?php echo $values["ora_inizio"]." ".$values["ora_fine"] ?></td>
						<td><?php echo $values["durata"] ?></td>
	
<td>

	<form name="elimina_prenotazione_<?php echo $values["id_prenotazione"] ?>" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>?controlla_cookies" onsubmit="return pre_sending(); ">
<!--
	<a href="javascript:document.forms['elimina_prenotazione_<?php echo $values["id_prenotazione"] ?>'].submit()" class="elimina_prenotazione" >Elimina</a>
-->
	<input type="hidden" name="action" value="delete_prenotazione"/>	
	<input type="hidden" name="id_prenotazione" value="<?php echo $values["id_prenotazione"] ?>" />
	<button type="submit">Elimina</button>
	
	</form>
</td>
					</tr>
<?php					
}
					?>
				</table>

			<?php } 
		}
          ?>


	<?php 
				
				$query = "SELECT * from prenotazioni p,utenti u,orario_prenotazioni o where p.fk_id_utente=u.id_utente and o.id_orario_prenotazioni=p.fk_id_orario_prenotazione";
				$res = mysqli_query($db_conn,$query) or die("Errore nella query: " . mysqli_error($db_conn));
			 

			?>
			
				<div class="panel_reservation">
			<?php 	
				
				if(mysqli_num_rows($res)>0){
			?>
					<div style="margin-top:20px;margin-left:20px;">
					
						<h3 class="pre_text" >Prenotazioni</h3>
							<table id="elenco_prenotazioni">
							<tr>
								<th>Nominativo</th>
								<th>Fascia oraria</th>
								<th>Durata</th>
							</tr>
							<?php  while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){ ?>
							<tr>
								<td><?php echo $row["cognome"]." ".$row["nome"] ?></td>
								<td><?php echo $row["ora_inizio"]." - ".$row["ora_fine"]  ?></td>
								<td><?php echo $row["durata"]  ?></td>
							</tr>
							<?php } ?>
						</table>
					
			
					</div>
			<?php   }  ?>

				</div>
		</div>
        </div>
	
    </div>
  <script>

	function pre_sending() {
		

		if(confirm("Sei sicuro di voler eliminare la prenotazione ?")){
			return true;	
		} else {
			return false;
		}

		
	}
	
	function check_prenotazione(){
		var durata = parseInt($("").val());
  
		if(durata>180 || durata<=0){
			alert("Inserire un valore corretto (1-180)");
			return false;
		}
	}


  </script>


    </body>
</html>
