<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";

	$id = base64_decode($_REQUEST['id']);

	$info_processo = get_table('macroaree', '*', 'deleted=0 and id='.$id);
	$nome_processo = $info_processo[0]['macroarea'];
	$cod_processo = $info_processo[0]['cod_macroarea'];
	
	$elenco = get_table('man_sottoprocessi', '*', 'deleted=0 and id_macro='.$id); // tab, order eg array(array("data_validita", "desc")), where

	$data = "[\n";
		for($x=0; $x<count($elenco); $x++):
			$data .= "{";
			$data .= ' "cod_sp": "'.$elenco[$x]['cod_sp'].'",';
			$data .= ' "desc_sp": "'.$elenco[$x]['desc_sp'].'",';
			$data .= ' "norme": "'.$elenco[$x]['norme'].'",';
			$data .= ' "button": "<a href=# role=\'button\' id_sp='.$elenco[$x]['id_sp'].' class=\'btn btn-xs btn-purple\'><i class=\'ace-icon fa fa-sitemap bigger-80\'></i></a>&nbsp;&nbsp;<a href=# role=\'button\' id_sp='.$elenco[$x]['id_sp'].' class=\'new_element btn btn-xs btn-info\'><i class=\'ace-icon fa fa-pencil bigger-80\'></i></a>&nbsp;&nbsp;<a href=# role=\'button\' id_sp='.$elenco[$x]['id_sp'].' class=\'del_element btn btn-xs btn-danger\'><i class=\'ace-icon fa fa-trash-o bigger-80\'></i></a>"';			
			$data .= "},\n";
		endfor;
	$data .= "],\n";	
	

	 
	//
?>

<div class="col-xs-12">
	<!-- PAGE CONTENT BEGINS -->
	  <div class="modal" id="edition-dates">
		  <div class="modal-dialog modal-lg">
			<div class="modal-content">
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
	  </div><!-- /.modal#modal-save-dialog -->

	
	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box transparent">
				<div class="widget-header widget-header-flat">
					<h2 class="widget-title lighter">
						<small>Gestisci i SOTTOPROCESSI del PROCESSO:</small>
                        <br><b><?php echo $nome_processo ?></b>&nbsp;<small>(<?php echo $cod_processo ?>)</small>
					</h2>
                    <div class="widget-toolbar">
                        <div class="btn-group">
                        	<a href="#" role="button" id_proc=0 class="btn btn-sm btn-info tooltip-info new_element" data-placement="bottom" title="Aggiungi nuovo">
                            	<i class="ace-icon fa fa-plus bigger-120"></i>&nbsp;&nbsp;Nuovo Sottoprocesso
                            </a>
                        </div>
                    </div>   
				</div>

				<div class="widget-body">
					<div class="widget-main no-padding">
						<table id="table-1" class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
							<thead>
								<tr>
									
									<th>Cod. SP</th>
									<th>Sottoprocesso</th>
                                    <th>Norme</th>
                                    <th>*</th>
								 </tr>
							</thead>
							<tfoot>
								<tr>
									<th style="width:10% !important;">input</th>
									<th style="width:30% !important;">input</th>
                                    <th style="width:50% !important;">input</th>
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
    
	<script type="text/javascript">
		
	jQuery(function($) {
			
				// DataTable
			var table1 = $('#table-1').DataTable({
								bAutoWidth: false,
								data: <?php echo $data ?>
								columns: [
									{data: "cod_sp"},
									{data: "desc_sp"},
									{data: "norme"},									
									{data: "button"}
								],
								order: [],
								aLengthMenu: [
									[10, 20, 50, 100, -1],
									[10, 20, 50, 100, "Mostra tutti"]
								],
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
					var id = $(this).attr("id_sp");
					if(id>0){
						var title = $(this).closest('td').next('td').next('td').text();
						var cod = $(this).closest('td').next('td').text();
						var norme = $(this).closest('td').next('td').next('td').next('td').text();
					}else{
						var cod = "";
						var title = "";
						var norme = "";
					}

					/// form
					var form;					
					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=form_sottoprocessi',
						type: 'POST',
						data: {id_sp: id,
							   cod_sp: cod,
							   desc_sp: title,
							   norme: norme
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
									macroarea = $("#macroarea").val();
									cod_macroarea =  $("#cod_macroarea").val();
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=save_tb&tb=man_sottoprocessi',
										type: 'POST',
										data: {id_sp: id,
											   cod_sp: cod,
											   desc_sp: title,
											   norme: norme
											   id_macro: <?php echo $info_processo ?>
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
					});
			
			});
			
			$(document).on('click', '.del_element', function(){
				var id = $(this).attr("id_sp");
				var box = bootbox.dialog({
						message: "Sei sicuro di voler eliminare il sottoprocesso?",
						className: "modal70",
						title:   "Attenzione!",
						buttons: {
							"success" : {
								"label" : "Elimina",
								"className" : "btn-sm btn-success",
								"callback": function() {
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=del_tb&tb=man_sottoprocessi',
										type: 'POST',
										data: {id: id},
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
			
	 });
   
		if ($('#desktopTest').is(':hidden')) {
			$(".mystato").css({'zoom':'70%'})
    		
		} else {
			
//    		$(".mystato").text("tutte");
		}
	
	</script>
       