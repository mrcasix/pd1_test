<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";
	
	if(isset($_REQUEST['crea'])){
		$id_valutazione = save_tb_db(array("fk_id_dom_profili_x_utenti" =>0,
						    "fk_id_dom_profili_utenti_blocco_valutazioni"=>0,
						    "data"=>date("Y-m-d"),
						    "fk_idst_valutatore_finale"=>0,
						    "deleted"=>0,
						    "fk_idst_valutatore"=>0),"dom_profili_utenti_valutazioni",0);
						    
		$result = update_tb_db(array("valutazione_finale"=>$id_valutazione),"dom_profili_utenti_blocco_valutazioni",array("id_dom_profili_utenti_blocco_valutazioni"=>base64_decode($_REQUEST['id_blocco'])));
	
	}
	else $id_valutazione = base64_decode($_REQUEST['idval']);
	
	
	
	
	
	$tipo_valutazione = $_REQUEST['t'];
	$id_profilo = base64_decode($_REQUEST['idp']);
	
	
	$info_valutazione = get_table('dom_profili_utenti_valutazioni', '*', 'id_dom_profili_utenti_valutazioni='.$id_valutazione);
			
	
	if($tipo_valutazione=='a') $idst_valutare = $_SESSION['sito'];
	else $idst_valutare = base64_decode($_REQUEST['idst']);
	
	
	$id_profilo = base64_decode($_REQUEST['idp']);
	$info_profilo = get_table('dom_profili_ruolo', '*', 'id_dom_profili_ruolo='.$id_profilo);
	$nome_profilo = $info_profilo[0]['profilo'];
	$descrizione = $info_profilo[0]['descrizione'];
	$tab_esplicativa = get_table('dom_profili_ruolo dp,dom_descrizione_livelli dl', '*', 'dp.fk_id_dom_criteri_valutazione=dl.fk_id_dom_criteri_valutazione and dl.deleted=0 and dp.deleted=0 and dp.id_dom_profili_ruolo='.$id_profilo);
	$elenco_competenze = get_profili_info_full($id_profilo);
	
	$livelli = array();
	$labels = array();
	$livelli_id = array();
	$livelli_id[] = 0;
	$livelli [] = "NAP";
	$labels [] = "NAP";
	$livelli_profilo =$tab_esplicativa;
	$numerico = true;
			

	
	for($i=0;$i<count($livelli_profilo);$i++) {
		if(!is_numeric($livelli_profilo[$i]['livello']))
			$numerico = false;
	}
	for($i=0;$i<count($livelli_profilo);$i++) {
		if($numerico)
			$livelli [] = $livelli_profilo[$i]['livello'];
		else $livelli [] = $livelli_profilo[$i]['valore_numerico'];
		$labels [] = $livelli_profilo[$i]['livello'];
		$livelli_id [] = $livelli_profilo[$i]['id_dom_descrizione_livelli'];
	}
	
//	$livelli = array(0,1,2,3,4);
//	print_r($elenco_competenze);

?>
<style>
.table-striped-main > tbody > tr:nth-child(2n+1) > td, .table-striped-main > tbody > tr:nth-child(2n+1) > th {
   background-color: #f2f3fb;
}

@media print{
	.vertical-90 {
		transform:rotate(90deg);
		-moz-transform:rotate(90deg);
		-webkit-transform:rotate(90deg);
		-o-transform:rotate(90deg);
		-ms-transform:rotate(90deg);
	}
}
</style>
<div class="alert alert-block alert-info">
    Ciao <b><?php echo $nome; ?></b>,<br />
    <?php
		if($tipo_valutazione=='a') $who = 'te stesso';
		else $who = name_surname($idst_valutare);
	?>
    grazie per aver accettato l'invito a valutare <b><?php echo $who ?></b> sulle competenze del seguente profilo di ruolo.
	<br />
    Di seguito troverai la tabella con i criteri di valutazione che ti aiuteranno a scegliere il valore da assegnare come valutazione.
</div>
<div class="col-xs-12">
	<div class="row">
		<div class="col-sm-12 area_stampa">
	        <!--<a href="#" role="button" class="tooltip-info stampa hidden-print" data-placement="bottom" title="Stampa profilo"><i class="ace-icon fa fa-print bigger-180"></i></a>-->
	        <h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Profilo di ruolo</h5>
       		<h2 style='margin-top:10px;'><?php echo strtoupper($nome_profilo) ?></h2><br />
			
            <h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Descrizione</h5>
       		<?php echo $descrizione ?><br /><br />
			<p STYLE="page-break-before: always"></p>

			<div class="vertical-90">
                <h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Criteri di valutazione</h5>
                <div class="row">        
                       <div class="col-sm-12">
                        <table class="table table-striped-main table-bordered no-margin-bottom" style="font-size:10px;">
                            <thead>
                                <tr>
                                    <th style="width:5% !important;">Livello</th>
                                    <th style="width:15% !important;">Definizione</th>
                                    <th style="width:15% !important;">Descrizione</th>
                                    <th style="width:20% !important;">Conoscenze</th>
                                    <th style="width:20% !important;">Abilità</th>
                                    <th style="width:20% !important;">Competenze</th>
                                 </tr>
                            </thead>
                          	<tbody>
                                <?php
                                $tb = '';
                                for($a=0; $a<count($tab_esplicativa); $a++):
                                    $tb .= '<tr>';
                             
                                    $tb .= '<td><b>'.$tab_esplicativa[$a]['livello'].'</b><br /></td><td>'.$tab_esplicativa[$a]['definizione'].'</td>';
                                    $tb .= '<td>'.$tab_esplicativa[$a]['descrizione'].'</td>';
                                    $tb .= '<td>'.$tab_esplicativa[$a]['conoscenze'].'</td>';
                                    $tb .= '<td>'.$tab_esplicativa[$a]['abilita'].'</td>';
                                    $tb .= '<td>'.$tab_esplicativa[$a]['competenze'].'</td>';																												
                                    $tb .= '</tr>';
                                endfor;
                                echo $tb;
                                ?>
                          	</tbody>
                       </table>
                    </div><!-- /.widget-main -->
                </div><!-- /.widget-body -->
            </div>
			<br /><br />
			<?php if($info_valutazione[0]["completata"]==0):?>
			<div class="col-sm-12">
				<div class="pull-right">
				<button class="btn btn-success segna_valutazione_completata">Segna come completato</button>
				</div>
			</div>
			<?php endif; ?>
			<br /><br />
            <p STYLE="page-break-before: always"></p>
            <h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Competenze</h5>
       		<?php
			$info_valutazione_valori = get_table('dom_profili_utenti_valutazioni_valori', '*', 'fk_id_dom_profili_utenti_valutazioni='.$id_valutazione);
			
			
			
			
			
			$array_valori = array();
			for($c=0; $c<count($info_valutazione_valori); $c++):
				$array_valori[$info_valutazione_valori[$c]['fk_id_dom_competenze']] = array('id_valutazione_valori'=>$info_valutazione_valori[$c]['id_dom_profili_utenti_valutazioni_valori'], 'valore'=>$info_valutazione_valori[$c]['valore']);
			endfor;
			
			foreach($elenco_competenze[$id_profilo] as $id_tipo=>$competenze):
				$info_tipo = get_table('dom_competenze_tipologie', '*', 'id_dom_competenze_tipologie='.$id_tipo);
				echo '<div class="well well-sm green center"><b>'.strtoupper($info_tipo[0]['desc_tipo']).'</b></div>';
				foreach($competenze as $info_competenza):
					$id_competenza = $info_competenza['id_comp'];
					if(isset($array_valori[$id_competenza])):
						$valore = $array_valori[$id_competenza]['valore'];
						$valore_numerico = $valore;
						$id_valutazione_valori = $array_valori[$id_competenza]['id_valutazione_valori'];
					else:
						$valore = '';$valore_numerico = 0;
						$id_valutazione_valori = 0;
					endif;
					echo '<h6 style=\'border-bottom: 1px solid #CCC; margin-bottom:10px;\' ><p><b>'.ucfirst(strtolower($info_competenza['competenze'])).'</b></p></h6>';
					echo '<div class="row">';
					echo '<div class="col-sm-1"></div>';
					echo '<div class="col-sm-6">
						 	<table class="table table-striped-main table-bordered no-margin-bottom no-border-top" style="font-size:12px;">';
							/*if($tipo_valutazione=='e'):
					echo '			
								<tr>
									<td width="20%">LIVELLO ATTESO:</td>
									<td>'.$info_competenza['atteso'].'</td>
								</tr>';
							endif;*/	
					echo '		<tr>
									<td>DESCRIZIONE:</td>
									<td>'.$info_competenza['descrizione'].'</td>
								</tr>
								<tr>
									<td>CONOSCENZE:</td>
									<td>'.$info_competenza['conoscenze'].'</td>
								</tr>
								<tr>
									<td>ABILIT&Agrave;:</td>
									<td>'.$info_competenza['abilita'].'</td>
								</tr>								
							</table>
						  </div>';
						  
					echo '<div class="col-sm-2">
						 	<table class="table table-striped-main table-bordered no-margin-bottom no-border-top" style="font-size:12px;">';
						
					echo '		<tr>
									<td>VALUTAZIONE 1</td>
							</TR>		
									<td>A</td>
								</tr>
																
							</table>
						  </div>';echo '<div class="col-sm-2">
						 	<table class="table table-striped-main table-bordered no-margin-bottom no-border-top" style="font-size:12px;">';
						
					echo '		<tr>
									<td>VALUTAZIONE 1</td>
							</TR>		
									<td>A</td>
								</tr>
																
							</table>
						  </div>';
					echo '<div class="col-sm-3">
						 <table class="table table-striped-main table-bordered no-margin-bottom no-border-top" style="font-size:12px;">
						 	<tr><th>Livello posseduto</th></tr>
							<tr><td>';
							
							$checked = array();
							
							for($l=0; $l<count($livelli); $l++):
							
							if($numerico){	
								if($livelli[$l] == $valore && $valore!='') $checked[] = 'checked';
								else $checked[] = '';
							}
							else {
								if($livelli[$l] == $valore_numerico && $valore_numerico!='') $checked[] = 'checked';
								else $checked[] = '';
							}
							
							endfor;
							
							
							
							
							if(!in_array("checked", $checked)){
								$checked[0] = 'checked';
							}
							
							
							for($l=0; $l<count($livelli_id); $l++):
								
								///echo $livelli[$l].' - '.$valore.' - '.$checked.'<br >';
								echo		'<input type="radio" '.$checked[$l].' class="ace valutazione" name="valore_'.$id_competenza.'" id_valutazione_valori='.$id_valutazione_valori.' id_competenza='.$id_competenza.' value='.$livelli[$l].' /><span class="lbl">&nbsp;'.$labels[$l].'</span>&nbsp;&nbsp;&nbsp;<BR>';
							
							endfor;
							
							
							
							
					echo '	</td></tr>
						 </table>
						 </div>';
					echo '</div><br /><br />';
				endforeach;				
			endforeach;
			
			?>
<!-- -->
			
		</div><!-- /.col -->
	</div><!-- /.row -->     
                            
                            
    <link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
    <script src="../assets/js/bootbox.min.js"></script>
    <script src="../build/p_lib/jQuery.print.js"></script> 
	<script type="text/javascript">
	$(document).ready(function(){
        $('.stampa').click(function(){ $.print('.area_stampa') });	
	});
			
	jQuery(function($) {
	
			$(document).on('click', '.valutazione', function(){
				//id_competenza = $('input[name=utente]:checked').val();
				valore = $(this).val();
				id_competenza = $(this).attr('id_competenza');
				id_valutazione_valori = $(this).attr('id_valutazione_valori');
				var fk_id_valutazione = <?php echo $id_valutazione ?>;
				
				if(id_valutazione_valori==0){
					var op = 'save_tb';
				}else{
					var op = 'update_tb';
				}
				
				var object = $(this);
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op='+op+'&tb=dom_profili_utenti_valutazioni_valori',
					type: 'POST',
					data: {id_dom_profili_utenti_valutazioni_valori: id_valutazione_valori, fk_id_dom_profili_utenti_valutazioni: fk_id_valutazione, fk_id_dom_competenze: id_competenza, valore: valore},
					success:function(id_generato){
						object.attr('id_valutazione_valori', id_generato);
						check_4_complete(fk_id_valutazione);
						
					},
					error: function(response) {},
					async: false
				});
				
			});
			
			function check_4_complete(fk_id_valutazione){
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=check4_valutation_complete',
					type: 'POST',
					data: {fk_id_valutazione: fk_id_valutazione, id_profilo: <?php echo $id_profilo ?>},
					async: false
				});
			}
			
	});
	
	
	$(document).on('click','.segna_valutazione_completata',function (){
		$.ajax({
			url: '../build/p_lib/ajax_check.php?op=segna_valutazione_completata',
			type: 'POST',
			data: {id_dom_profili_utenti_valutazioni:"<?php echo $id_valutazione ?>"},
			success:function(id_generato){
				$.gritter.add({
					title: 'Valutazione completata correttamente!',
					text: '',
					class_name: 'gritter-success'
				});
				
			},
			async: false
		});
		$(this).parent().hide();
	});
	
	
	if ($('#desktopTest').is(':hidden')) {
		$(".mystato").css({'zoom':'70%'})
		
	} else {
		
//    		$(".mystato").text("tutte");
	}

	</script>
       