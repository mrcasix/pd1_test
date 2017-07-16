<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";

	$id_sp = base64_decode($_REQUEST['id']);

	$info_sp = get_table('man_sottoprocessi', '*', 'deleted=0 and id_sp='.$id_sp);
	$nome_sp = $info_sp[0]['desc_sp'];
	$cod_sp = $info_sp[0]['cod_sp'];
	$id_p = $info_sp[0]['id_processo'];

	$info_p = get_table('man_processi', '*', 'deleted=0 and id='.$id_p);
	$nome_p = $info_p[0]['processo'];
	$cod_p = $info_p[0]['cod_processo'];

	$elenco = get_table('man_fasi', '*', 'deleted=0 and id_sp='.$id_sp); // tab, order eg array(array("data_validita", "desc")), where

	$data = "[\n";
		for($x=0; $x<count($elenco); $x++):
			$data .= "{";
			$data .= ' "cod_fase": "'.$elenco[$x]['cod_fase'].'",';
			$data .= ' "fase": "'.$elenco[$x]['fase'].'",';
			$data .= ' "button": "<a href=\'index.php?page=sf_tree&id='.base64_encode($elenco[$x]['id_fase']).'\' title=\'Visualizza Sotto Fasi\'><i class=\'ace-icon fa fa-sitemap bigger-140 purple\'></i></a>&nbsp;&nbsp;<a href=# id_fase='.$elenco[$x]['id_fase'].' class=\'new_element\'><i class=\'ace-icon fa fa-pencil bigger-140\' title=\'Modifica fase\'></i></a>&nbsp;&nbsp;<a href=# id_fase='.$elenco[$x]['id_fase'].' class=\'del_element\' title=\'Elimina fase\'><i class=\'ace-icon fa fa-trash-o bigger-140 red\'></i></a>"';			
			$data .= "},\n";
		endfor;
	$data .= "],\n";	
	

	 
	//
?>

<div class="col-xs-12">
	<!-- PAGE CONTENT BEGINS -->
	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box transparent">
                <div class="col-sm-6">
                    <div class="widget-header widget-header-flat dark">
                        <small>Processo: </small><br /><b><?php echo $nome_p ?></b>&nbsp;<small>(<?php echo $cod_p ?>)</small>
                        <div class="widget-toolbar">
                            <a href="index.php?page=processi" role="button" data-rel='tooltip' class="tooltip-success" data-placement="bottom" title="Torna ai processi">
                                &nbsp;<i class="ace-icon fa fa-arrow-left bigger-160 green"></i>&nbsp;
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="widget-header widget-header-flat dark">
                        <small>Sottoprocesso: </small><br /><b><?php echo $nome_sp ?></b>&nbsp;<small>(<?php echo $cod_sp ?>)</small>
                        <div class="widget-toolbar">
                            <a href="index.php?page=sottoprocessi&id=<?php echo base64_encode($id_sp)?>" role="button" data-rel='tooltip' class="tooltip-success" data-placement="bottom" title="Torna ai sottoprocessi">
                                &nbsp;<i class="ace-icon fa fa-arrow-left bigger-160 green"></i>&nbsp;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	
	<div class="row">
		<div class="col-sm-12">
			<div class="widget-box transparent">
				<div class="widget-header widget-header-flat">
					<h2 class="widget-title lighter">
						<small>FASI:</small>
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
									<th style="width:15% !important;">input</th>
									<th style="width:75% !important;">input</th>
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
									{data: "fase"},
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
						var cod = $(this).closest('td').prev('td').prev('td').text();
						var title = $(this).closest('td').prev('td').text();
						//var output = '';
					}else{
						var cod = "<?php echo $cod_sp ?>"+"_";
						var title = "";
						//var output = '';
					}

					/// form
					var form;					
					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=form_fasi',
						type: 'POST',
						data: {id_fase: id,
							   cod_fase: cod,
							   fase: title
							   //output: output
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
										url: '../build/p_lib/ajax_check.php?op=save_tb&tb=man_fasi',
										type: 'POST',
										data: {id_fase: id,
											   cod_fase: cod,
											   fase: fase,
											   id_sp: <?php echo $id_sp ?>
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
										url: '../build/p_lib/ajax_check.php?op=update_tb&tb=man_fasi',
										type: 'POST',
										data: {id_fase: id, deleted: '1'},
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
       