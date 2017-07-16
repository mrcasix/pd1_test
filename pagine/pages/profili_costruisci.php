<?php
	
	include_once("../build/p_lib/query.php");

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])==0) echo "<script>window.location.href = 'index.php?page=404';</script>";
	
	$blocco_menu=false; 
	
	
	$id_profilo = base64_decode($_REQUEST['id']);
	//$livelli = array(0,1,2,3,4);
	$livelli = array();
	$livelli_id = array();
	$livelli_valori_numerici = array();
	$livelli_id [] = 0;
	$livelli_valori_numerici [] = 0;
	$livelli [] = "NAP";
	
	$livelli_profilo =get_table('dom_profili_ruolo dp,dom_descrizione_livelli dl', 'dl.id_dom_descrizione_livelli,dl.livello,dl.valore_numerico', 'dp.fk_id_dom_criteri_valutazione=dl.fk_id_dom_criteri_valutazione and dl.deleted=0 and dp.deleted=0 and dp.id_dom_profili_ruolo='.$id_profilo);
	
	for($i=0;$i<count($livelli_profilo);$i++) {
		$livelli [] = $livelli_profilo[$i]['livello'];
		$livelli_id [] = $livelli_profilo[$i]['id_dom_descrizione_livelli'];
		$livelli_id [] = $livelli_profilo[$i]['id_dom_descrizione_livelli'];
		$livelli_valori_numerici [] = $livelli_profilo[$i]['valore_numerico'];
	}
	
	$profili_competenze = get_profili_competenze($id_profilo);
	$profili_sottoprocessi = get_profili_sottoprocessi($id_profilo);
	$sottoprocessi = get_table('dom_sottoprocessi', '*', 'deleted=0');
	$elenco_criteri_valutazione = elenco_criteri_valutazione();	
	
	$data = "[\n";
		for($x=0; $x<count($sottoprocessi); $x++):
			$id_processo = $sottoprocessi[$x]['fk_id_dom_processi'];
			$id_sp =  $sottoprocessi[$x]['id_dom_sottoprocessi'];
			$processo = get_table('dom_processi', 'processo', 'deleted=0 and id_dom_processi='.$id_processo);
			$processo_label = $processo[0]['processo'];

			if(count($profili_sottoprocessi)>0):
				if(in_array($id_sp, $profili_sottoprocessi[$id_profilo])):
					$checked = 'checked';
					$flag = 'Incluso';
					$blocco_menu=true;
				else:
					$checked = '';
					$flag = 'Non incluso';
				endif;
			else:
				$checked = '';
				$flag = 'Non incluso';
			endif;
			
			$data .= "{";
			$data .= ' "button": "<input type=\"checkbox\" '.$checked.' name=\"profili\" class=\"ace save_processi\" id=\"profili\" value=\"'.$sottoprocessi[$x]['id_dom_sottoprocessi'].'\" /><span class=\"lbl\"></span>",';
			$data .= ' "flag": "'.$flag.'",';
			$data .= ' "processo": "'.$processo_label.'",';
			$data .= ' "cod_sp": "'.$sottoprocessi[$x]['cod_sp'].'",';
			$data .= ' "desc_sp": "'.str_replace("\n", "", $sottoprocessi[$x]['desc_sp']).'"';
			$data .= "},\n";
		endfor;
	$data .= "],\n";	
	
	$competenze = get_table('dom_competenze', '*', 'deleted=0');
	
	$data_competenze = "[\n";
		for($x=0; $x<count($competenze); $x++):
		
			$area = get_table('dom_competenze_area', '*', 'deleted=0 and id_dom_competenze_area='.$competenze[$x]['fk_id_dom_competenze_area']);
			$area_label = $area[0]['area'];
			$tipologia = get_table('dom_competenze_tipologie', 'desc_tipo', 'deleted=0 and id_dom_competenze_tipologie='.$area[0]['fk_id_dom_competenze_tipologie']);
			$tipo_label = $tipologia[0]['desc_tipo'];
			
			$profilo = get_profilo($id_profilo);
			
			if(isset($profilo[0]['fk_id_dom_criteri_valutazione']))$criterio_valutazione = $profilo[0]['fk_id_dom_criteri_valutazione'];
				else $criterio_valutazione = '';
			if(count($profili_competenze)>0):
				if(in_array($competenze[$x]['id_dom_competenze'], $profili_competenze[$id_profilo])):
					$checked = 'checked';
					$blocco_menu=true;
					$disabled = '';
					$flag = 'Incluso';
					$cman_profili_competenze = get_table('dom_profili_x_competenze', 'atteso', 'fk_id_dom_competenze='.$competenze[$x]['id_dom_competenze'].' and fk_id_dom_profili_ruolo='.$id_profilo);
					$atteso = $cman_profili_competenze[0]['atteso'];
					
					
					
				else:
					$checked = '';
					$disabled = 'display:none';
					$flag = 'Non incluso';
					
					$atteso = '';
				endif;
				
				
			
				
			else:
				$checked = '';
				$disabled = 'display:none';
				$flag = 'Non incluso';
				$atteso = '';
			endif;
			
			$data_competenze .= "{";
			$data_competenze .= ' "button": "<input type=\"checkbox\" '.$checked.' name=\"profili\" class=\"ace save_competenze\" id=\"profili_'.$competenze[$x]['id_dom_competenze'].'\" value=\"'.$competenze[$x]['id_dom_competenze'].'\" /><span class=\"lbl\"></span>",';
			$data_competenze .= ' "flag": "'.$flag.'",';
			$data_competenze .= ' "atteso": "<select class=\'livello\' style=\''.$disabled.'\' id-comp='.$competenze[$x]['id_dom_competenze'].' id=\"atteso_'.$competenze[$x]['id_dom_competenze'].'\">"+';
											 
										//	 die(var_dump($livelli));
											 
											 for($t=0; $t<count($livelli); $t++):
											 	if($livelli_valori_numerici[$t]==$atteso) $selected = 'selected';
												else $selected = '';
			$data_competenze .= '				"<option value=\"'.$livelli_valori_numerici[$t].'\" '.$selected.'>'.$livelli[$t].'</option>"+';
											 endfor;
			$data_competenze .= '			"</select>",';
			
			$data_competenze .= ' "tipo": "'.str_replace("\n", "", $tipo_label).'",';
			$data_competenze .= ' "area": "'.str_replace("\n", "", $area_label).'",';
			$data_competenze .= ' "cod_comp": "'.str_replace("\n", "", $competenze[$x]['cod_comp']).'",';
			$data_competenze .= ' "competenza": "'.str_replace("\n", "", $competenze[$x]['competenza']).'"';
			$data_competenze .= "},\n";
		endfor;
	$data_competenze .= "],\n";	
	 
	//
?>
<style>
.table-striped-main > tbody > tr:nth-child(2n+1) > td, .table-striped-main > tbody > tr:nth-child(2n+1) > th {
   background-color: #f2f3fb;
}
</style>

<div class="col-xs-12">
	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box transparent">
				<div class="widget-header widget-header-flat">
                    <div class="widget-toolbar">
                        <div class="btn-group">
                        	<a href="index.php?page=role-profile" role="button" class="tooltip-info back" data-placement="bottom" title="Torna ai profili di ruolo">
                            	<i class="ace-icon fa fa-arrow-left bigger-160 info"></i>
                            </a>
                        </div>
                    </div>   
				</div>
              </div>
         </div>
    </div>
	
	<h3 class="header blue lighter smaller">
		<i class="ace-icon fa fa-bookmark smaller-90 green"></i>
		...seleziona criterio di valutazione
	</h3>
	<div class="row">
		<div class="col-sm-12" >
			 <div class="center">
				<select class="form-control" id="criterio-di-valutazione">
				<option value=""></option>
				<?php foreach($elenco_criteri_valutazione  as $key=>$value):?>
				
					<option value="<?=$key?>" <?php if($criterio_valutazione == $key) echo "selected";?>><?=$value?></option>
				<?php endforeach;?>
				</select>
			</div>
		</div>
	</div>
	
	<h3 class="header blue lighter smaller">
		<i class="ace-icon fa fa-exchange smaller-90 green"></i>
		Scegli i sottoprocessi da associare al profilo di ruolo...
	</h3>
	<div class="row">
		<div class="col-sm-12">
            <table id="table-1" class="table table-striped-main table-bordered no-margin-bottom">
                <thead>
                    <tr>
                        <th>*</th>
                        <th>*</th>
                        <th>Processo</th>
                        <th>Cod. Sottoprocesso</th>
                        <th>Sottoprocesso</th>
                     </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width:5% !important;"></th>
                        <th style="width:10% !important;">select</th>
                        <th style="width:30% !important;">input</th>
                        <th style="width:20% !important;">input</th>
                        <th style="width:30% !important;">input</th>
                     </tr>
                </tfoot>
              <tbody>
              </tbody>
           </table>
		</div><!-- /.widget-main -->
	</div><!-- /.widget-body -->
	<h3 class="header blue lighter smaller">
		<i class="ace-icon fa fa-bookmark smaller-90 green"></i>
		...seleziona le competenze assegnando un valore atteso
	</h3>
	<div class="row">
		<div class="col-sm-12">
            <table id="table-2" class="table table-striped-main table-bordered no-margin-bottom">
                <thead>
                    <tr>
                        <th>*</th>
                        <th>*</th>
                        <th>Atteso</th>
                        <th>Tipologia</th>
                        <th>Area</th>
                        <th>Cod. Competenza</th>
                        <th>Competenza</th>
                     </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width:5% !important;"></th>
                        <th style="width:10% !important;"></th>
                        <th style="width:5% !important;"></th>
                        <th style="width:15% !important;">input</th>
                        <th style="width:15% !important;">input</th>
                        <th style="width:10% !important;">input</th>
                        <th style="width:45% !important;">input</th>
                     </tr>
                </tfoot>
              <tbody>
              </tbody>
           </table>
        </div><!-- /.widget-main -->
    </div><!-- /.widget-body -->
	<h3 class="header blue lighter smaller">
		<i class="ace-icon fa fa-bookmark smaller-90 green"></i>
		Criteri di valutazione
	</h3>
	<div class="row">
		<div class="col-sm-12">
            <table id="tabella-descrizione-livelli" class="table table-striped-main table-bordered no-margin-bottom" style="font-size:10px;">
                <thead>
                    <tr>
                        <th style="width:5% !important;">Cod. Livello</th>
                        <th style="width:15% !important;">Definizione</th>
                        <th style="width:15% !important;">Descrizione</th>
                        <th style="width:20% !important;">Conoscenze</th>
                        <th style="width:20% !important;">Abilità</th>
                        <th style="width:20% !important;">Competenze</th>
                     </tr>
                </thead>
               
              <tbody>
              		
              </tbody>
           </table>
        </div><!-- /.widget-main -->
    </div><!-- /.widget-body -->
     <!-- -->                        
	<!-- CSS adjustments for browsers with JavaScript disabled -->
    <link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
   	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/jquery.dataTables.bootstrap.js"></script>
    <script src="../assets/js/bootbox.min.js"></script>
    <script src="../assets/js/jquery.maskedinput.min.js"></script>
	<script type="text/javascript">
		
	jQuery(function($) {
			//$('#data').mask('99-99-9999');
				// DataTable
			
			render_table_descrizione_livelli('<?=$criterio_valutazione?>');		
			
			<?php if($blocco_menu):?>
			$(document).ready(function(){
				$("#criterio-di-valutazione").prop('disabled', 'disabled');
			});
			<?php endif; ?>
			
				
			var table1 = $('#table-1').DataTable({
				bAutoWidth: false,
				data: <?php echo $data ?>
				columns: [
					{data: "button"},
					{data: "flag"},
					{data: "processo"},
					{data: "cod_sp"},
					{data: "desc_sp"}
				],
				columnDefs: [ { visible: false, targets: [1] }],
				order: [[ 1, "asc" ], [ 2, "asc" ], [ 3, "asc" ]],
				"iDisplayLength": 5,
				"bLengthChange": false,
				"info": false,
				"oLanguage": {oPaginate:{sFirst:"Prima",sLast:"Ultima",sNext:"Avanti",sPrevious:"Indietro"}}
			});
			
			$('#table-1 tfoot th').each( function (colIdx) {
				var title = $('#table-1 tfoot th').eq( $(this).index() ).text();
				switch(title){
					case '':
						$(this).html( '' );
						break;
					case 'select':
						var select = $('<select style="width: 100%; height:20px; font-size:10px;"><option value="">Seleziona</option></select>')
							.appendTo( $(table1.column(colIdx).footer()).empty());
						table1.column(colIdx).data().unique().sort().each(function(d,j){
							select.append( '<option value="'+d+'">'+d+'</option>');
						});
						$(this).html(select);
						break;
					case 'input':
						$(this).html( '<input type="text" style="width: 100%; height:20px;" />' );
						break;
					default:
						$(this).html('UNSET FIELD');
				}
			});
	
			table1.columns().eq(0).each( function ( colIdx ) {
				$('input', table1.column(colIdx).footer()).on('keyup change',function(){
					table1
						.column(colIdx)
						.search(this.value, false, true, true)
						.draw();
				});
					
				$('select', table1.column( colIdx ).footer()).on( 'change', function (){
					var val = $(this).val();
					table1
						.column( colIdx )
						.search( val ? '^'+val+'$' : '', true, false )
						.draw();
				});

			});

			$(document).ready(function(){
				$('#table-1 thead').append($('#table-1 tfoot tr'));
			});
			
			$(document).on('click', '.save', function(){
				id_user = $('input[name=utente]:checked').val();
				id_course = $('input[name=corso]:checked').val();
				data = String($('#data').val());
								
				if($('input[name="corso"]').is(':checked') && $('input[name="utente"]').is(':checked') && data.length > 0) {
					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=save_certificazione',
						type: 'POST',
						data: {id_user: id_user, id_course: id_course, data: data, id_certificatore: <?php echo $_SESSION['sito'] ?>},
						success:function(id_cert){
							location.href='index.php?page=new-cert-attach&id_cert='+id_cert;
						},
						error: function(response) {},
						async: false
					});
				}else{
					var box = bootbox.dialog({
						message: "<h3>Scegli una data, un corso e un persona</h3>",
						className: "modal20",
						title:   "Attenzione!",
						buttons: {
							"success" : {
								"label" : "OK",
								"className" : "btn-sm btn-success"
							}
						}
					});
				}
			});
			
			
			var table2 = $('#table-2').DataTable({
							bAutoWidth: false,
							data: <?php echo $data_competenze ?>
							columns: [
								{data: "button"},
								{data: "flag"},
								{data: "atteso"},
								{data: "tipo"},
								{data: "area"},
								{data: "cod_comp"},
								{data: "competenza"}
							],
							columnDefs: [ { visible: false, targets: [1] }],
							order: [[ 1, "asc" ], [ 3, "asc" ], [ 4, "asc" ]],
							"iDisplayLength": 5,
							"bLengthChange": false,
							"info": false,
							"oLanguage": {oPaginate:{sFirst:"Prima",sLast:"Ultima",sNext:"Avanti",sPrevious:"Indietro"}}
			});
			
			$('#table-2 tfoot th').each( function (colIdx) {
				var title = $('#table-2 tfoot th').eq( $(this).index() ).text();
				switch(title){
					case '':
						$(this).html( '' );
						break;
					case 'select':
						var select = $('<select style="width: 100%; height:20px; font-size:10px;"><option value="">Seleziona</option></select>')
							.appendTo( $(table2.column(colIdx).footer()).empty());
						table2.column(colIdx).data().unique().sort().each(function(d,j){
							select.append( '<option value="'+d+'">'+d+'</option>');
						});
						$(this).html(select);
						break;
					case 'input':
						$(this).html( '<input type="text" style="width: 100%; height:20px;" />' );
						break;
					default:
						$(this).html('UNSET FIELD');
				}
			});
	
			table2.columns().eq(0).each( function ( colIdx ) {
				$('input', table2.column(colIdx).footer()).on('keyup change',function(){
					table2
						.column(colIdx)
						.search(this.value, false, true, true)
						.draw();
				});
					
				$('select', table2.column( colIdx ).footer()).on( 'change', function (){
					var val = $(this).val();
					table2
						.column( colIdx )
						.search( val ? '^'+val+'$' : '', true, false )
						.draw();
				});

			});
			
			$(document).ready(function(){
				$('#table-2 thead').append($('#table-2 tfoot tr'));
			
			
			//$(".dataTables_paginate").hide(); 
			$("#table-1_filter").hide();
			$("#table-1_wrapper").removeClass();
			$(".dataTables_paginate").css('padding-top', '10px');
			$("#table-2_filter").hide();
			$("#table-2_wrapper").removeClass();
			});
		
		
			$(document).on('click', '.save_processi', function(){
				if($(this).is(':checked')) {
					//$(this).closest('td').next().text('Incluso');
					var action = 'aggiungi';
				}else{
					//$(this).closest('td').next().text('Non incluso');
					var action = 'rimuovi';					
				}
				id = $(this).attr('value');
				
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=save_profilo_processi',
					type: 'GET',
					data: {id_sp: id, id_prof: <?php echo $id_profilo ?>, action: action},
					success:function(){
						//location.href='index.php?page=new-cert-attach&id_cert='+id_cert;
						
						
						<?php if(!$blocco_menu):?>
							location.reload();
						<?php endif; ?>
						
					},
					error: function(response) {},
					async: false
				});
				
			});	
			
			$(document).on('click', '.save_competenze', function(){
				id = $(this).attr('value');
				if($(this).is(':checked')) {
					//$(this).closest('td').next().text('Incluso');
					
					$('#atteso_'+id).css('display','block');
					save_livello_atteso(id, 0);
					
					
					
					//var action = 'aggiungi';
				}else{
					//$(this).closest('td').next().text('Non incluso');
	//				$('#atteso_'+id).attr('disabled', 'disabled');
					$('#atteso_'+id).css('display','none');
					save_livello_atteso(id, -1);
					//var action = 'rimuovi';					
				}
				
					<?php if(!$blocco_menu):?>
							location.reload();
						<?php endif; ?>
			
				
			});

			$(document).on('change', '.livello', function(){
				id = $(this).attr('id-comp');
				var atteso = $(this).val(); 
				save_livello_atteso(id, atteso);
			});	
			
			$(document).on('change', '#criterio-di-valutazione', function(){
				var id_criterio = $(this).val();
				var id_profilo = "<?php echo base64_decode($_REQUEST['id']) ?>";
				save_criterio_profilo(id_profilo, id_criterio);
				render_table_descrizione_livelli(id_criterio);
				refresh_valori_attesi(id_criterio);
				
			});	
			
			function refresh_valori_attesi(id_criterio){
				if(id_criterio=='')return;
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=refresh_valori_attesi',
					type: 'POST',
					data: {id_profilo: "<?php echo $id_profilo ?>"},
					success:function(msg){
						$(table2).dataTable().fnUpdate("new value", 0, 2)
						$('select.livello').empty();
						$('select.livello').html(msg);
					},
					error: function(response) {},
					async: false
				});
			}
			function render_table_descrizione_livelli(id_criterio){
				if(id_criterio=='')return;
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=render_table_descrizione_livelli',
					type: 'POST',
					data: {fk_id_dom_criteri_valutazione: id_criterio},
					success:function(msg){
						$('#tabella-descrizione-livelli tbody').html(msg);
					},
					error: function(response) {},
					async: false
				});
			}
			function save_criterio_profilo(id_profilo, id_criterio){
				
				if(id_criterio=="") id_criterio="0"; 

				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_profili_ruolo',
					type: 'POST',
					data: {id_dom_profili_ruolo: id_profilo, fk_id_dom_criteri_valutazione: id_criterio},
					success:function(msg){
						console.log(msg);
					},
					error: function(response) {},
					async: false
				});
				
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=get_descrizioni',
					type: 'POST',
					data: {fk_id_dom_criteri_valutazione: id_criterio},
					success:function(msg){
						
					},
					error: function(response) {},
					async: false
				});
				
				
				

			}
			function save_livello_atteso(id, atteso){
				
				if($('#profili_'+id).is(':checked')) var action = 'aggiungi';
				else var action = 'rimuovi';
				
				
				
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=save_profilo_competenze',
					type: 'POST',
					data: {id_comp: id, id_prof: <?php echo $id_profilo ?>, action: action, atteso: atteso},
					success:function(msg){
						console.log(msg);
						//location.href='index.php?page=new-cert-attach&id_cert='+id_cert;
					},
					error: function(response) {},
					async: false
				});

			}
		
		
		});	
			////////////////////////////////////////////////////
			
		
		
		
		if ($('#desktopTest').is(':hidden')) {
			$(".mystato").css({'zoom':'70%'})
    		
		} else {
			
//    		$(".mystato").text("tutte");
		}
	
	</script>
 
