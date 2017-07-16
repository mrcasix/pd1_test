<?php

include 'includes.php';
/*
$session = new Session();
if ($session->isValid()) {
    redirect ("book.php");
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
			<div style="padding: 20px;">
				<h2>Elenco prenotazioni :</h2> 
				<hr/>
			</div>
			<?php 
				
				$query = "SELECT * from prenotazioni p,utenti u where p.fk_id_utente=u.id_utente";
				$res = mysqli_query($db_conn,$query) or die("Errore nella query: " . mysqli_error($db_conn));
			 

			?>
			
				<div style="margin:20px;padding: 20px;border: dashed 1px #ddd;height:180px;overflow: auto">
			<?php 	
				while ($row = mysqli_fetch_array($res)) {
			?>
					<div style="margin-top:20px;">
					
					
						<table>
							<tr>
								<td><?php echo $row["cognome"]." ".$row["nome"] ?></td>	
								<td><?php echo $row["durata"]  ?></td>
							</tr>
						</table>
					</div>
			<?php }  ?>

				</div>
		</div>
        </div>
	
    </div>
  </body>
</html>
