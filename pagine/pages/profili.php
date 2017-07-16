<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";

	$elenco = get_table('dom_profili_ruolo', '*', 'deleted=0 ');

	$data = "[\n";
		for($x=0; $x<count($elenco); $x++):
			$area = get_table('dom_aree_aziendali', 'area', 'id_dom_aree_aziendali='.$elenco[$x]['fk_id_dom_aree_aziendali']);
			if(isset($area[0]['area'])) $area_label = $area[0]['area'];
			else $area_label = 'Non definita';
			$data .= "{";
			$data .= ' "cod_prof": "'.$elenco[$x]['cod_profilo'].'",';
			$data .= ' "profilo": "'.$elenco[$x]['profilo'].'",';
			$data .= ' "area": "'.$area_label.'",';
			$data .= ' "button": "<a href=\'index.php?page=view-profile&id='.base64_encode($elenco[$x]['id_dom_profili_ruolo']).'&v=c\' data-rel=\'tooltip\' class=\'tooltip-info\' data-placement=\'bottom\' title=\'Visualizza profilo di ruolo completo\'><i class=\'ace-icon fa fa-search-plus bigger-140\'></i></a>&nbsp;&nbsp;"+
								 "<a href=\'index.php?page=view-profile&id='.base64_encode($elenco[$x]['id_dom_profili_ruolo']).'&v=s\' data-rel=\'tooltip\' class=\'tooltip-info\' data-placement=\'bottom\' title=\'Visualizza profilo di ruolo sintetico\'><i class=\'ace-icon fa fa-search-minus bigger-140\'></i></a>&nbsp;&nbsp;"+
								 "<a href=\'index.php?page=people-profile&id='.base64_encode($elenco[$x]['id_dom_profili_ruolo']).'\' class=\'tooltip-warning\' data-placement=\'bottom\' title=\'Associa il profilo alle persone\'><i class=\'ace-icon fa fa-user bigger-140\'></i></a>&nbsp;&nbsp;"+			
								 "<a href=\'index.php?page=ask-valutations&id='.base64_encode($elenco[$x]['id_dom_profili_ruolo']).'\' class=\'tooltip-warning\' data-placement=\'bottom\' title=\'Richiedi valutazioni\'><i class=\'ace-icon fa fa-users bigger-140\'></i></a>&nbsp;&nbsp;"+			
								 "<a href=\'index.php?page=gest-attivita-profilo&id='.base64_encode($elenco[$x]['id_dom_profili_ruolo']).'\' class=\'tooltip-warning\' data-placement=\'bottom\' title=\'Gestisci attività legate al profilo\'><i class=\'ace-icon fa fa-exchange bigger-140\'></i></a>&nbsp;&nbsp;"+
								 "<a href=\'index.php?page=build-profile&id='.base64_encode($elenco[$x]['id_dom_profili_ruolo']).'\' class=\'tooltip-warning\' data-placement=\'bottom\' title=\'Associa processi e competenze al profilo\'><i class=\'ace-icon fa fa-bookmark bigger-140\'></i></a>&nbsp;&nbsp;"+
								 "<a href=# id_proc='.$elenco[$x]['id_dom_profili_ruolo'].' class=\'new_element tooltip-info\' data-placement=\'bottom\' title=\'Modifica descrizione profilo\'><i class=\'ace-icon fa fa-pencil bigger-140\'></i></a>&nbsp;&nbsp;"+
								 "<a href=\'index.php?page=people-profile-detail&id='.base64_encode($elenco[$x]['id_dom_profili_ruolo']).'\' class=\'tooltip-warning\' data-placement=\'bottom\' title=\'Gestisci Valutazione\'><i class=\'ace-icon fa fa-calendar-plus-o bigger-140\'></i></a>&nbsp;&nbsp;"+
								 "<a href=# id_proc='.$elenco[$x]['id_dom_profili_ruolo'].' data-rel=\'tooltip\' class=\'del_element tooltip-danger\' data-placement=\'bottom\' title=\'Cancella profilo\'><i class=\'ace-icon fa fa-trash-o bigger-140\'></i></a>"';
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
						Profili di Ruolo
                    </h2>
                    <div class="widget-toolbar">
                        <div class="btn-group">
                        	<a href="#" role="button" id_proc=0 data-rel='tooltip' class="tooltip-info new_element" data-placement="bottom" title="Nuovo profilo di ruolo">
                            	<i class="ace-icon fa fa-plus-square bigger-180"></i>
                            </a>
                        </div>
                    </div>   
				</div>

				<div class="widget-body">
					<div class="widget-main no-padding">
						<table id="table-1" class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
							<thead>
								<tr>
									<th>Cod. Profilo</th>
									<th>Profilo di ruolo</th>
                                    <th>Area</th>
                                    <th>*</th>
								 </tr>
							</thead>
							<tfoot>
								<tr>
									<th style="width:15% !important;">input</th>
									<th style="width:45% !important;">input</th>
                                    <th style="width:20% !important;">select</th>
									<th style="width:20% !important;"></th>                                    
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
									{data: "cod_prof"},
									{data: "profilo"},
									{data: "area"},
									{data: "button"}
								],
								order: [],
								"iDisplayLength": 10,
								"bLengthChange": false,
								"info": false,
								//"aaSorting": [[0,'asc']],
								"oLanguage": {
									"sLengthMenu": "Visualizza _MENU_ elementi per pagina",
									"sZeroRecords": "Nessun elemento trovato",
									"sInfo": "_START_ - _END_ di _TOTAL_ Elementi",
									"sInfoEmpty": "0 - 0 di 0 Elementi",
									"sInfoFiltered": "(risultanti da un totale di _MAX_ Elementi)",
									"sSearch": "Ricerca",
									oPaginate:{sFirst:"Prima",sLast:"Ultima",sNext:"Avanti",sPrevious:"Indietro"}
								}
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
       