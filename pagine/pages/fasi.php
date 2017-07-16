<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";

	

	
	//$elenco = get_table('dom_sottoprocessi', '*', 'deleted=0 and fk_id_dom_processi='.$id); // tab, order eg array(array("data_validita", "desc")), where
	$elenco = get_table('dom_fasi', '*', 'deleted=0'); // tab, order eg array(array("data_validita", "desc")), where
	
	$data = "[\n";
	
	
		for($x=0; $x<count($elenco); $x++):
			$data .= "{";
			$data .= ' "cod_fase": "'.$elenco[$x]['cod_fase'].'",';
			$data .= ' "desc_fase": "'.$elenco[$x]['fase'].'",';
			$data .= ' "button": "<a href=# id_fase='.$elenco[$x]['id_dom_fasi'].' class=\'new_element\'><i class=\'ace-icon fa fa-pencil bigger-140\' title=\'Modifica sottoprocesso\'></i></a>&nbsp;&nbsp;<a href=# id_fase='.$elenco[$x]['id_dom_fasi'].' class=\'del_element\' title=\'Elimina sottoprocesso\'><i class=\'ace-icon fa fa-trash-o bigger-140 red\'></i></a>"';			
			$data .= "},\n";
		endfor;
	$data .= "],\n";	
	

	 
	//
?>

<div class="col-xs-12">
	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box transparent">
				<div class="widget-header widget-header-flat">
					<h2 class="widget-title lighter">
						Mappatura delle fasi
					</h2>
                    <div class="widget-toolbar">
                        <div class="btn-group">
                        
                            <a href="#" role="button" id_fase=0 data-rel='tooltip' class="tooltip-success new_element" data-placement="bottom" title="Nuova Fase">
                            	<i class="ace-icon fa fa-plus-square bigger-180 green"></i>
                            </a>
                        </div>
                    </div>   
				</div>

				<div class="widget-body">
					<div class="widget-main no-padding">
						<table id="table-1" class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
							<thead>
								<tr>
									
									<th>Cod. Fase</th>
									<th>Fase</th>
									<th>*</th>
								 </tr>
							</thead>
							<tfoot>
								<tr>
									<th style="width:10% !important;">input</th>
									<th style="width:35% !important;">input</th>
                                   <th style="width:10% !important;"></th>                                    
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
    <script src="../assets/js/jquery.hotkeys.min.js"></script>
    <script src="../assets/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../assets/js/x-editable/bootstrap-editable.min.js"></script>
    
	<script type="text/javascript">
		
	jQuery(function($) {
			
				// DataTable
			var table1 = $('#table-1').DataTable({
								bAutoWidth: false,
								data: <?php echo $data ?>
								columns: [
									{data: "cod_fase"},
									{data: "desc_fase"},
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
					var id = $(this).attr("id_fase");
					if(id>0){
						var fase = $(this).closest('td').prev('td').text();
						var cod_fase = $(this).closest('td').prev('td').prev('td').text();
					}else{
						var cod_fase = "";
						var fase = "";
					}
					var form;					
					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=form_fasi',
						type: 'POST',
						data: {id_fase: id,
							   cod_fase: cod_fase,
							   fase: fase
							   },
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
									cod_fase = $("#cod_fase").val();
									fase =  $("#fase").val();
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_fasi',
										type: 'POST',
										data: {id_dom_fasi: id,
											   cod_fase: cod_fase,
											   fase: fase
										},
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
							}
						}
					});
			
			});
			
			$(document).on('click', '.del_element', function(){
				var id = $(this).attr("id_fase");
				var box = bootbox.dialog({
						message: "Sei sicuro di voler eliminare la fase?",
						className: "modal70",
						title:   "Attenzione!",
						buttons: {
							"success" : {
								"label" : "Elimina",
								"className" : "btn-sm btn-success",
								"callback": function() {
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_fasi',
										type: 'POST',
										data: {id_dom_fasi: id, deleted: '1'},
										success:function(msg){
											//console.log(msg);
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
       