<?php
include 'includes.php';

if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
     header("location:".__LINK_SITO__);
}
	
$id_articolo = $_GET["id"];
$gia_commentato = gia_commentato($id_articolo);


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
		
		<?php 
				
			$articolo = select_articolo($id_articolo);
			$row_cnt = count($articolo);
			
		?>
		
				<div id = 'contenuto'>
					
				<?php 
					if (!$row_cnt>0) {
				?>
					<div class="alert_panel">
						<div style="top_panel">
							<h2>Articolo inesistente</h2> 
							
						</div>
					</div>	
				<?php 
					}
					else {
						
				?>
						<div class="titolo_articolo">
							<h2><?php echo $articolo["nome"]; ?></h2> 
							<hr/>
						</div>
						<div class="blocco_immagine">
							<div class="top_immagine">
								<img class="immagine" src="./image/<?php echo $articolo["immagine"]; ?>" alt="Immagine prodotto" />
								<div>
									<h3>Descrizione:</h3> 
								
									<?php echo $articolo["descrizione"]; ?>
								</div>
							</div>
						</div>	
						<div style="blocco_main">
						
							<div class="top_commenti">
								<div style="margin-left:20px;">
									<h3>Commenti:</h3>
									<hr/>
								</div>	
									<?php 
									$commenti = commenti_articolo($id_articolo);
									
									foreach ($commenti as $index=>$commento) {
										$btn_positive="success_button";
										$btn_negative="danger_button";
										$btn_blank="blank_button";
										$disabled_button="";
										
										if($commento["giudizi_utente"]>=3){
											$button_class_positive=$btn_blank;
											$button_class_negative=$btn_blank;
											$disabled_button="disabled";
										}
										else {
											$button_class_positive=$btn_positive;
											$button_class_negative=$btn_negative;
											$disabled_button="";
										}
										$formatted_giudizio="0";
										if($commento["giudizio_totale"]<0){
											$formatted_giudizio = $commento["giudizio_totale"];
										}
										else if($commento["giudizio_totale"]>0){
											$formatted_giudizio = "+".$commento["giudizio_totale"];
										}
										
										
									?>
									
										<div class="blocco_commenti">
											<div class="commento">
												<div class="blocco_top">
												<?php if(isset($_SESSION['logged']) && isset($_SESSION['id_utente']) && $_SESSION['id_utente'] == $commento["fk_id_utente"]){ ?>
													<div class="punteggio floating_left"><b><i>Punteggio :<?php echo $commento["punteggio"]; ?></i></b></div>
													<form id="elimina_commento" action="<?php echo __LINK_SITO__."articolo.php?id=".$id_articolo ?>&controlla_cookies" method="post" >
														<input type="hidden" name="action" value="elimina_commento" />
														<input type="hidden" name="id_commento" value="<?php echo $commento["id_commento"]; ?>" />
														<span class="span_button comment_button">
															<button class="danger_button" alt="elimina commento" id="elimina_commento" name="elimina_commento" type="submit">Elimina commento</button>
														</span>
													</form>
												<?php }
												else{?>
													<div class="punteggio"><b><i>Punteggio :<?php echo $commento["punteggio"]; ?></i></b></div>
												
												<?php }?>
												</div>
												<div class="autore">di <b><?php echo $commento["mail"]; ?> :</b></div>
												
												<p>
													<?php echo $commento["testo"]; ?>
												</p>
												<br/>
												<div class="blocco_giudizio">
													<?php if(isset($_SESSION['logged'])){?>
													<form id="aggiungi_giudizio" action="<?php echo __LINK_SITO__."articolo.php?id=".$id_articolo ?>&controlla_cookies" method="post" >
														<input type="hidden" name="action" value="aggiungi_giudizio" />
														<input type="hidden" id="tipo_giudizio" name="tipo" value="0" />
														<input type="hidden" name="id_commento" value="<?php echo $commento["id_commento"] ?>" />
														<button class="<?php echo $button_class_positive ?>" type="submit" alt="assegna un giudizio positivo" id="bottone_giudizio_positivo" name="tipo_giudizio"  value="1" type="submit" <?php echo " $disabled_button"; ?>>Aggiungi Giudizio +1</button>
														<button class="<?php echo $button_class_negative ?>" type="submit" alt="aggiungi commento" id="bottone_giudizio_negativo" name="tipo_giudizio" value="-1" type="submit" <?php echo " $disabled_button"; ?>>Aggiungi Giudizio -1</button>
													</form>
													<?php }?>
												</div>
												<div class="print_giudizio">(Giudizio utenti: <?php  echo $formatted_giudizio ?></i>)</div><br>
											</div>
										</div>
									<?php 
									} // end of while #[comments]
									?>
									
									<?php if(isset($_SESSION['logged']) && !$gia_commentato) {?>
									<div class="nuovo_commento">
										<form id="aggiungi_commento" action="<?php echo __LINK_SITO__."articolo.php?id=".$id_articolo ?>&controlla_cookies" method="post" >
										<input type="hidden" name="action" value="aggiungi_commento" />
										<input type="hidden" name="id_articolo" value="<?php echo $id_articolo ?>" />
										<h3 style="lbl_comment"><i>Aggiungi un commento:</i></h3>
										<div class="lbl_comment">
											
											<select id="punteggio_commento" class="punteggio" name="punteggio_commento">
												<option value="">Selezionare punteggio</option>
											<?php 
												for($i=0;$i<=5;$i++){
											?>
													<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
											<?php 
												}
											?>
											</select>
										</div>
										<div class="testo_commento">
											<textarea style="" id="testo_commento" name="testo_commento" cols="55" rows="5" alt="Inserire qui il testo del commento" placeholder="Inserire qui il testo del commento"></textarea>
										
											<span class="span_button">
												<button class="primary_button" alt="aggiungi commento" id="bottone_aggiungi_commento" name="bottone_aggiungi_commento" type="submit">Invia</button>
											</span>
										</div>
										
										
										</form>
									</div>
									<?php } ?>
								
							</div>
						</div>
		<?php 
						} // end of condition 
		
		?>	
				</div>
			</div>
        </div>
	
    </div>
	<script>
	
	$(document).ready(function() {
		
		
		$(document).on('submit','#aggiungi_commento',function(){
			
			punteggio = $("#punteggio_commento");
			testo = $("#testo_commento");
			
			if(punteggio.val()!="" && punteggio.length>0 && testo.val()!="" && testo.length>0){
				return true;
			}
			else {
				if(!(punteggio.val()!="" && punteggio.length>0))
					punteggio.addClass("obbligo_compilazione");
				else 
					punteggio.removeClass("obbligo_compilazione");
					
				if(!(testo.val()!="" && testo.length>0))
					testo.addClass("obbligo_compilazione");
				else 
					testo.removeClass("obbligo_compilazione");
				
			}
			return false;
			
		});
		$(document).on('submit','#elimina_commento',function(){
			if(confirm("Sicuro di voler eliminare il commento ?")){
				return true;
			}
			else {
				return false;
			}
			
		});
		
	});
	
	</script>
	
	
  </body>
</html>
