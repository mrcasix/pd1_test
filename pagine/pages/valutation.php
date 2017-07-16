<style>

.table-striped tr.odd.sessione td, .table-striped tr.even.sessione td{
	background-color: rgb(8,123,224)!important;
	border-color: rgb(8,123,224)!important;
	color: rgb(8,123,224)!important;
}

.table-striped tr.odd.sessione td span.titolo_sessione, .table-striped tr.even.sessione td span.titolo_sessione{
	color: rgb(255,255,255)!important;
	font-weight:bold;
	font-size: 16px;
}

</style>


<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";

	$elenco = get_table('dom_profili_ruolo', '*', 'deleted=0 ');
	$where = 'du.deleted=0 and dv.deleted=0 and an2.idst=du.fk_idst and an.idst=dv.fk_idst_valutatore and dv.fk_id_dom_profili_x_utenti=du.id_dom_profili_x_utenti and dpr.id_dom_profili_ruolo=du.fk_id_dom_profili_ruolo';
	$elenco_dettaglio_valutazione = get_table('dom_profili_utenti_valutazioni dv,dom_profili_x_utenti du,anagrafica an,anagrafica an2, dom_profili_ruolo dpr', 'dv.autovalutata as autovalutata,dpr.profilo,du.fk_id_dom_profili_ruolo,concat(an2.nome," ",an2.cognome) as valutato,an2.idst as id_valutato,concat(an.nome," ",an.cognome) as valutatore,an.idst as id_valutatore,dv.data as data_inizio,dv.data_fine as data_fine,dv.id_dom_profili_utenti_valutazioni,IFNULL((SELECT "Fatta" from dom_profili_utenti_valutazioni where id_dom_profili_utenti_valutazioni IN (select fk_id_dom_profili_utenti_valutazioni from dom_profili_utenti_valutazioni_valori) and dom_profili_utenti_valutazioni.id_dom_profili_utenti_valutazioni=dv.id_dom_profili_utenti_valutazioni),"Non Fatta") as stato,dv.fk_id_dom_profili_utenti_blocco_valutazioni as fk_id_dom_profili_utenti_blocco_valutazioni',$where." ORDER BY fk_id_dom_profili_utenti_blocco_valutazioni DESC"); // tab, order eg array(array("data_validita", "desc")), where 
	for($i=0;$i<count($elenco_dettaglio_valutazione);$i++){
		$valutazione = $elenco_dettaglio_valutazione[$i];
		if(isset($valutazione['data_fine'])) $elenco_dettaglio_valutazione[$i]['stato']="Completata";
		else if($valutazione['stato']=="Fatta") $elenco_dettaglio_valutazione[$i]['stato']="In corso";
		else $elenco_dettaglio_valutazione[$i]['stato']="Non iniziata";
		if($elenco_dettaglio_valutazione[$i]['autovalutata']!='0')
			$elenco_dettaglio_valutazione[$i]['checked']="checked";
		else 
			$elenco_dettaglio_valutazione[$i]['checked']="";
	}
	$old_valutation_block=0;
	
	$data = "[\n";
	for($i=0;$i<count($elenco_dettaglio_valutazione);$i++):
		$valutazione = $elenco_dettaglio_valutazione[$i];
		
		if($old_valutation_block!=$valutazione['fk_id_dom_profili_utenti_blocco_valutazioni'] && isset($valutazione['fk_id_dom_profili_utenti_blocco_valutazioni'])){
		
			$old_valutation_block=$valutazione['fk_id_dom_profili_utenti_blocco_valutazioni'];
			$blocco_valutazione = get_table('dom_profili_utenti_blocco_valutazioni', '*', 'id_dom_profili_utenti_blocco_valutazioni='.$old_valutation_block);
			
			if(!isset($blocco_valutazione) || !isset($blocco_valutazione[0])) $blocco_valutazione[0]=array("data"=>"","autovalutata"=>0,"valutazione_finale"=>0);
			
			$data .= "{";
			$data .= ' "id": "'.$valutazione['id_dom_profili_utenti_valutazioni'].'",';
			$data .= ' "valutato": "'.$valutazione['valutato'].'",';
			$data .= ' "profilo": "<span class=\'titolo_sessione\' style=\'cursor: pointer;\'>Blocco n.'.$valutazione['fk_id_dom_profili_utenti_blocco_valutazioni'].'</span>",';
			$data .= ' "valutatore": "",';
			$data .= ' "data_assegnazione": "<span class=\'titolo_sessione\' style=\'cursor: pointer;\'>'.$blocco_valutazione[0]["data"].'</span>",';
			$data .= ' "data_fine": "",';
			$data .= ' "stato": "",';
			$data .= ' "button": "';
			
			
			if($blocco_valutazione[0]["autovalutata"]!=0)
				$data .='<a href=\'index.php?page=view-profile&v=s&id='.base64_encode($valutazione['fk_id_dom_profili_ruolo']).'&idval='.base64_encode($blocco_valutazione[0]["autovalutata"]).'\' class=\'tooltip-warning white\' data-placement=\'bottom\' title=\'Associa il profilo alle persone\'><i class=\'ace-icon fa fa-external-link bigger-140\'></i></a>&nbsp;&nbsp;';
			
			if($blocco_valutazione[0]["valutazione_finale"]==0){
				$data .='<a href=\'index.php?page=valuta&finale=1&t=a&idp='.base64_encode($valutazione['fk_id_dom_profili_ruolo']).'&idval='.base64_encode(0).'&id_blocco='.base64_encode($old_valutation_block).'&crea=1\' class=\'tooltip-warning white\' data-placement=\'bottom\' title=\'Valutazione Finale\'><i class=\'ace-icon fa fa-users bigger-140\'></i></a>&nbsp;&nbsp;';
			}
			else {
			
				$data .='<a href=\'index.php?page=valuta&finale=1&t=a&idp='.base64_encode($valutazione['fk_id_dom_profili_ruolo']).'&idval='.base64_encode($blocco_valutazione[0]["valutazione_finale"]).'&id_blocco='.base64_encode($old_valutation_block).'\' class=\'tooltip-warning white\' data-placement=\'bottom\' title=\'Valutazione Finale\'><i class=\'ace-icon fa fa-users bigger-140\'></i></a>&nbsp;&nbsp;';
			
			
				//$data .='<a href=\'index.php?page=view-profile&finale=1&v=s&id='.base64_encode($valutazione['fk_id_dom_profili_ruolo']).'&idval='.base64_encode($blocco_valutazione[0]["valutazione_finale"]).'\' class=\'tooltip-warning white\' data-placement=\'bottom\' title=\'Associa il profilo alle persone\'><i class=\'ace-icon fa fa-users bigger-140\'></i></a>&nbsp;&nbsp;';
			}
		
			$data .='",';
			$data .= ' "classe": "sessione"';
			$data .= "},\n";
		}
		
		$data .= "{";
		$data .= ' "id": "'.$valutazione['id_dom_profili_utenti_valutazioni'].'",';
		$data .= ' "valutato": "'.$valutazione['valutato'].'",';
		$data .= ' "profilo": "'.$valutazione['profilo'].'",';
		$data .= ' "valutatore": "'.$valutazione['valutatore'].'",';
		$data .= ' "data_assegnazione": "'.$valutazione['data_inizio'].'",';
		$data .= ' "data_fine": "'.$valutazione['data_fine'].'",';
		$data .= ' "stato": "'.$valutazione['stato'].'",';
		$data .= ' "button": "';
		if($valutazione['stato']=="Completata")
			$data .='<a href=\'index.php?page=view-profile&v=s&id='.base64_encode($valutazione['fk_id_dom_profili_ruolo']).'&idval='.base64_encode($valutazione['id_dom_profili_utenti_valutazioni']).'\' class=\'tooltip-warning\' data-placement=\'bottom\' title=\'Associa il profilo alle persone\'><i class=\'ace-icon fa fa-external-link bigger-140\'></i></a>&nbsp;&nbsp;';
		$data .='",';
		$data .= ' "classe": ""';
		$data .= "},\n";
	endfor;
	$data .= "],\n";

?>

<div class="col-xs-12">
<!--	<div class="alert alert-block alert-success">
		<button type="button" class="close" data-dismiss="alert">
			<i class="ace-icon fa fa-times"></i>
		</button>
		<?php echo $nome; ?>,<br />
		In questa pagina puoi gestire i processi
	</div>-->
	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box transparent">
				<div class="widget-header widget-header-flat">
					<h2 class="widget-title lighter">
						Elenco valutazione
                    </h2>
                   
				</div>

				<div class="widget-body">
					<div class="widget-main no-padding">
						<table id="table-1" class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
							<thead>
								<tr>
									<th>#</th>
									<th>Profilo</th>
									<th>Persona valutata</th>
									<th>Valutatore</th>
									<th>Data Assegnazione</th>
									<th>Data Fine</th>
									<th>Stato</th>
									<th>*</th>
								 </tr>
							</thead>
							<tfoot>
								<tr>
									<th style="width:5% !important;">input</th>
									<th style="width:15% !important;">select</th>	
									<th style="width:15% !important;">input</th>	
									<th style="width:15% !important;">input</th>	
									<th style="width:15% !important;">input</th>
									<th style="width:10% !important;">input</th>
									<th style="width:10% !important;">select</th>
									<th style="width:15% !important;"></th>                                    
								 </tr>
						    </tfoot>
						  <tbody>
						  </tbody>
					   </table>
					</div><!-- /.widget-main -->
				</div><!-- /.widget-body -->
			</div><!-- /.widget-box -->
		</div><!-- /.col -->
	</div><!-- /.row -->     
                            
                            
    <link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
   	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/jquery.dataTables.bootstrap.js"></script>
    <script src="../assets/js/bootbox.min.js"></script>
    
	<script type="text/javascript">
		
	jQuery(function($) {
			
				// DataTable
			var table1 = $('#table-1').DataTable({
								bAutoWidth: false,
								data: <?php echo $data ?>
								columns: [
									{data: "id"},
									{data: "profilo"},
									{data: "valutato"},
									{data: "valutatore"},
									{data: "data_assegnazione"},
									{data: "data_fine"},
									{data: "stato"},
									{data: "button"},
									{data: "classe",visible:false}
								],
								order: [],
								"iDisplayLength": 10,
								"bLengthChange": false,
								"info": false,
								"oLanguage": {
									"sLengthMenu": "Visualizza _MENU_ elementi per pagina",
									"sZeroRecords": "Nessun elemento trovato",
									"sInfo": "_START_ - _END_ di _TOTAL_ Elementi",
									"sInfoEmpty": "0 - 0 di 0 Elementi",
									"sInfoFiltered": "(risultanti da un totale di _MAX_ Elementi)",
									"sSearch": "Ricerca",
									oPaginate:{sFirst:"Prima",sLast:"Ultima",sNext:"Avanti",sPrevious:"Indietro"}
								},
								"fnRowCallback": function (nRow, aData, iDisplayIndex) {
									$(nRow).addClass(aData.classe);
								},
			});
			
			$('#table-1 tfoot th').each( function (colIdx) {
				var title = $('#table-1 tfoot th').eq( $(this).index() ).text();
				switch(title){
					case '':
						$(this).html( '' );
						break;
					case 'select':
						var select = $('<select style="width: 100%;"><option value="">Seleziona</option></select>')
							.appendTo( $(table1.column(colIdx).footer()).empty());
						table1.column(colIdx).data().unique().sort().each(function(d,j){
							select.append( '<option value="'+d+'">'+d+'</option>');
						});
						$(this).html(select);
						break;
					case 'input':
						$(this).html( '<input type="text" style="width: 100%;" />' );
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
			
			$(document).on('click', '.new_element', function(){
					var id = $(this).attr("id_proc");
					/// form
					var form;					
					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=form_profili',
						type: 'POST',
						data: {id: id},
						success:function(msg){
							form = msg;
							
						},
						error: function(response) {},
						async: false
					});	
					
					var box = bootbox.dialog({
						message: form,
						className: "modal70",
						title:   "Gestisci",
						buttons: {
							"success" : {
								"label" : "Salva",
								"className" : "btn-sm btn-success",
								"callback": function() {
									cod_prof = $("#cod_prof").val();
									profilo =  $("#profilo").val();
									descrizione =  $("#descrizione").text();
									fk_id_area =  $('#area').find(":selected").attr('value');
									console.log(fk_id_area);
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_ruolo',
										type: 'POST',
										data: {id_dom_profili_ruolo: id,
											   cod_profilo: cod_prof,
											   profilo:  profilo,
											   descrizione:  descrizione,
											   fk_id_dom_aree_aziendali:  fk_id_area
											   },
										success:function(msg){
											location.reload();
											//console.log(msg);
										},
										error: function(response) {},
										async: false
									});	
								}
							},
							"danger" : {
								"label" : "Annulla",
								"className" : "btn-sm btn-danger",
								"callback": function() {}
							}
						}
					}).on("shown.bs.modal", function() {
			 			$('.select2').select2({ width: '350px' });
					});
			
			});
			
			
			
			$(document).on('click', '.del_element', function(){
				var id = $(this).attr("id_proc");
				var box = bootbox.dialog({
						message: "Sei sicuro di voler eliminare il profilo di ruolo?",
						className: "modal70",
						title:   "Attenzione!",
						buttons: {
							"success" : {
								"label" : "Elimina",
								"className" : "btn-sm btn-success",
								"callback": function() {
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_profili_ruolo',
										type: 'POST',
										data: {id_dom_profili_ruolo: id, deleted: '1'},
										success:function(msg){
											location.reload();
										},
										error: function(response) {},
										async: false
									});	
									
								}
							},
							"danger" : {
								"label" : "Annulla",
								"className" : "btn-sm btn-danger",
								"callback": function() {}
							},
						}
					});
			});
			
			$("#table-1_filter").hide();
			$(".dataTables_paginate").css('padding-top', '10px');
	 });
   
		if ($('#desktopTest').is(':hidden')) {
			$(".mystato").css({'zoom':'70%'})
    		
		} else {
			
//    		$(".mystato").text("tutte");
		}
	
	</script>
       