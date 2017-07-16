<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";

	$id_profilo = base64_decode($_REQUEST['id']);
	$view_mode = $_REQUEST['v'];
	$info_profilo = get_table('dom_profili_ruolo', '*', 'id_dom_profili_ruolo='.$id_profilo);
	$nome_profilo = $info_profilo[0]['profilo'];
	$descrizione = $info_profilo[0]['descrizione'];
	//$tab_esplicativa = get_table('dom_descrizione_livelli', '*', '');
	
	$tab_esplicativa =get_table('dom_profili_ruolo dp,dom_descrizione_livelli dl', 'dl.*', 'dl.deleted = 0 and dp.deleted=0 and dp.fk_id_dom_criteri_valutazione=dl.fk_id_dom_criteri_valutazione and dp.id_dom_profili_ruolo='.$id_profilo);
	
	
	$elenco_competenze = get_profili_info_full($id_profilo);
	$elenco_sottoprocessi_profilo = get_sottoprocessi_profilo_info_full($id_profilo);
	
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


    
<div class="col-xs-12">
	<div class="row">
		<div class="col-sm-12 area_stampa">
	        <a href="#" role="button" class="tooltip-info stampa hidden-print" data-placement="bottom" title="Stampa profilo"><i class="ace-icon fa fa-print bigger-180"></i></a>
	        <h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Profilo di ruolo</h5>
       		<h2 style='margin-top:10px;'><?php echo strtoupper($nome_profilo) ?></h2><br />
			
            <h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Descrizione</h5>
       		<?php echo $descrizione ?><br /><br />
			<p STYLE="page-break-before: always"></p>

			<!--<div class="vertical-90">-->
            <div>
                <h5 style='border-bottom: 1px solid #CCC; margin-bottom:0px;' class='blue'>Criteri di valutazione</h5><br />
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
            <p STYLE="page-break-before: always"></p>
            <h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Competenze</h5>
       		<?php
			$vertici_radar = array();
			$vertici_radar_due = array();
			$somme_array = array();
			
			
			
			
			
			if(isset($elenco_competenze[$id_profilo])):
				foreach($elenco_competenze[$id_profilo] as $id_tipo=>$competenze):
					$info_tipo = get_table('dom_competenze_tipologie', '*', 'id_dom_competenze_tipologie='.$id_tipo);
					echo '<div class="well well-sm green center"><b>'.strtoupper($info_tipo[0]['desc_tipo']).'</b></div>';
					$tot_atteso = 0;
					$n_comp = 0;
					
					
					
					foreach($competenze as $info_competenza):
						if($id_tipo==3){
						   if(!isset($somme_array[$info_competenza["id_area"]])) {
							$somme_array[$info_competenza["id_area"]]["somma"]=0;
							$somme_array[$info_competenza["id_area"]]["conteggio"]=0;
							$somme_array[$info_competenza["id_area"]]["titolo"]=$info_competenza["id_area"];
						   }
						   
						   $somme_array[$info_competenza["id_area"]]["somma"]+=$info_competenza['atteso'];
						   $somme_array[$info_competenza["id_area"]]["conteggio"]++;
						   
						}
						
					
						$tot_atteso += $info_competenza['atteso'];
						$n_comp++;
						echo '<h6 style=\'border-bottom: 1px solid #CCC; margin-bottom:10px;\' ><p><b>'.ucfirst(strtolower($info_competenza['competenze'])).'</b></p></h6>';
						echo '<div class="row">';
						echo '<div class="col-sm-1"></div>';
						echo '<div class="col-sm-10">
								<table class="table table-striped-main table-bordered no-margin-bottom no-border-top" style="font-size:12px;">
									<tr>
										<td width="20%">LIVELLO ATTESO:</td>
										<td>'.$info_competenza['atteso'].'</td>
									</tr>
									<tr>
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
						echo '<div class="col-sm-1"></div>';
						echo '</div><br /><br />';
					endforeach;
					$media_atteso = round($tot_atteso/$n_comp, 1);
					$vertici_radar[] = array(strtoupper($info_tipo[0]['desc_tipo']), $media_atteso);
				endforeach;
				foreach($somme_array as $id_competenza => $dati_competenza):
					$area = get_table('dom_competenze_area', '*', 'deleted=0 and id_dom_competenze_area='.$dati_competenza['titolo']); // tab, order eg array(array("data_validita", "desc")), where
					$media_atteso = round($dati_competenza['somma']/$dati_competenza['conteggio'], 1);
					$vertici_radar_due[] = array(strtoupper($area[0]["area"]), $media_atteso);
				endforeach;
				
				
			endif;
			?>
            <p STYLE="page-break-before: always"></p>
			<h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Radar</h5>
			<div class="row">
            	<div class="col-sm-12">
		            <div id="chartDiv" style="width:999px;height: 500px; margin: 0px;"></div>
		        </div>
            </div>
			<p STYLE="page-break-before: always"></p>
			
	<h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Radar Tecniche Specialistiche</h5>
			<div class="row">
            	<div class="col-sm-12">
		            <div id="chartDiv2" style="width:999px;height: 500px; margin: 0px;"></div>
		        </div>
            </div>
			<p STYLE="page-break-before: always"></p>
			<h5 style='border-bottom: 1px solid #CCC; margin-bottom:10px;' class='blue'>Sottoprocessi</h5>
       		<?php
			if(isset($elenco_sottoprocessi_profilo[$id_profilo])):
			
				
			
			
				foreach($elenco_sottoprocessi_profilo[$id_profilo] as $id_sp=>$sp_tree):
					$info_sp = get_table('dom_sottoprocessi', '*', 'id_dom_sottoprocessi='.$id_sp);
					echo '<div class="well well-sm red center"><b>'.strtoupper($info_sp[0]['desc_sp']).'</b></div>';
					/////
					$tab = "";
					foreach($sp_tree as $id_fase=>$fase_tree):
						$info_fase = get_table('dom_fasi', '*', 'id_dom_fasi='.$id_fase);
						if($view_mode=='c'):
							$tab .= '<h6 style=\'border-bottom: 1px solid #CCC; margin-bottom:10px;\' ><p>Fase: <b>'.ucfirst(strtolower($info_fase[0]['fase'])).'</b></p></h6>';
							///////////
							$tab .=  '<div class="row">';
							$tab .=  '<div class="col-sm-1"></div>';
							$tab .=  '<div class="col-sm-10">
									<table class="table table-striped-main table-bordered" style="font-size:12px;">
										<tbody>';
										foreach($fase_tree as $id_att=>$attivita_tree):
											$tab .=  '<tr>
													<td width="20%">'.$attivita_tree['cod_attivita'].'</td>
													<td>'.$attivita_tree['attivita'].'</td>
												  </tr>';
											
										endforeach;
							$tab .= 	   '</tbody>							
									 </table>
								  </div>';
							$tab .=  '<div class="col-sm-1"></div>
							</div>';
						else:
							$tab .= '<tr><td>'.ucfirst(strtolower($info_fase[0]['fase'])).'</td></tr>';
						endif;
					endforeach;				
					
					if($view_mode=='c'):
						echo $tab;
					else:
						echo  '<div class="row">';
						echo  '	<div class="col-sm-1"></div>
								<div class="col-sm-10">
									<table class="table table-striped-main table-bordered" style="font-size:12px;">
										<thead><tr><th>Fasi</th></tr></thead>
										<tbody>';
						echo				$tab;
						echo		 	'</tbody>							
									 </table>
							  	</div>
								<div class="col-sm-1"></div>
							  </div>';
					endif;
					
					echo '<br />';
		
				endforeach;
			endif;
			
			
			
			?>
            
		</div><!-- /.col -->
	</div><!-- /.row -->     
                            
                            
    <link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
    <script src="../assets/js/bootbox.min.js"></script>
    <script src="../build/p_lib/jQuery.print.js"></script> 
    <script src="../assets/graph/dhtmlxchart.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="../assets/graph/dhtmlxchart.css">
              
	<script type="text/javascript">
	$(document).ready(function(){
        $('.stampa').click(function(){ $.print('.area_stampa') });	
	});
	<?php

	$lettere = array('A','B','C','D','E','F','G','H','I','J','K','L');
	$values = 'var competences=[';
	for($a=0; $a<count($vertici_radar); $a++):
		$values .= '{ "compA":"'.$vertici_radar[$a][1].'", "comp":"'.$lettere[$a].'" },';
	endfor;
	$values .= '];';
	echo $values;
	
	$values = 'var competences2=[';
	for($a=0; $a<count($vertici_radar_due); $a++):
		$values .= '{ "compA":"'.$vertici_radar_due[$a][1].'", "comp":"'.$lettere[$a].'" },';
	endfor;
	$values .= '];';
	echo $values;

	?>		
		var chart =  new dhtmlXChart({
			container:"chartDiv",
			view:"radar",
			value:"#compA#",
			tooltip:{template:"#compA#"},
			color:"#3399ff",
			line:{color:"#3399ff", width:2},
			fill:"#3399ff",
			xAxis:{ template:"#comp#"},
			origin:0,
			disableItems:false,
			alpha: 0.2,
			legend:{layout:"y",width: 400,align:"center",valign:"middle",marker:{width:5,radius:3},
				values:[
					<?php
						for($a=0; $a<count($vertici_radar); $a++):
							echo'{text:"'.$lettere[$a].') '.$vertici_radar[$a][0]." - ".$vertici_radar[$a][1].'",color:"#3399ff"},';
						endfor;
					?>
				]
			}
		});
		
		var chart2 =  new dhtmlXChart({
			container:"chartDiv2",
			view:"radar",
			value:"#compA#",
			tooltip:{template:"#compA#"},
			color:"#3399ff",
			line:{color:"#3399ff", width:2},
			fill:"#3399ff",
			xAxis:{ template:"#comp#"},
			origin:0,
			disableItems:false,
			alpha: 0.2,
			legend:{layout:"y",width: 400,align:"center",valign:"middle",marker:{width:5,radius:3},
				values:[
					<?php
						for($a=0; $a<count($vertici_radar_due); $a++):
							echo'{text:"'.$lettere[$a].') '.$vertici_radar_due[$a][0]." - ".$vertici_radar_due[$a][1].'",color:"#3399ff"},';
						endfor;
					?>
				]
			}
		});
		/*
		chart.addSeries({
			value:"#companyB#",
			tooltip:{
				template:"#companyB#"
			},
			fill:"#66cc00",
			line:{
				color:"#66cc00",
				width:1
			}
		});
		*/
		chart.parse(competences,"json");
		chart2.parse(competences2,"json");

   
		if ($('#desktopTest').is(':hidden')) {
			$(".mystato").css({'zoom':'70%'})
    		
		} else {
			
//    		$(".mystato").text("tutte");
		}
	
	</script>
       