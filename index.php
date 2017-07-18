<?php

include 'includes.php';


if(isset($_POST['send_form'])){
	
	die("inviato");
	$id_utente=$_SESSION["id_utente"];
	$durata=$_POST["durata"];
	save_prenotazione($id_utente,$durata);
}



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
			<form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">			
				<h3 class="pre_text" >Effettua una prenotazione:</h3>
				<div class="input_durata">
					<input  type="text" placeholder="Inserire durata" name="durata" value=""/> 


					<input type="submit" name="send_form" value="Invia"/>

				</div>
			</form>	
		

		
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

						<td>1</td>
						<td>2</td>
						<td>3</td>

<?php					
}
					?>
				</table>

			<?php } ?>
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
  </body>
</html>
