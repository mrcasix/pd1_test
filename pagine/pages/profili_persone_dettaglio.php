<?php
	
	include_once("../build/p_lib/query.php");

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])==0) echo "<script>window.location.href = 'index.php?page=404';</script>";

	$id_profilo = base64_decode($_REQUEST['id']);
	$livelli = array(0,1,2,3,4);
	$profili_competenze = get_profili_competenze($id_profilo);
	$utenti_in_profilo = get_table('dom_profili_x_utenti', '*', 'fk_id_dom_profili_ruolo='.$id_profilo.' and deleted=0');
	
	$idsts = array();
	$array_results = array();
	for($a=0; $a<count($utenti_in_profilo); $a++):
	
		$array_results[$utenti_in_profilo[$a]['fk_idst']] = array('id_profili_utenti'=>$utenti_in_profilo[$a]['id_dom_profili_x_utenti'], 'deleted'=>$utenti_in_profilo[$a]['deleted']);
	
	endfor;
	$idsts = array_keys($array_results);
	$idsts_string = implode(',',$idsts);
	$anagrafica = get_table('anagrafica', 'idst, matricola, cognome, nome', 'idst IN ('.$idsts_string.')');
	
	$data = "[\n";
		for($x=0; $x<count($anagrafica); $x++):
			$idst = $anagrafica[$x]['idst'];
			$data .= "{";
			$data .= ' "id": "'.$anagrafica[$x]['idst'].'",';
			$data .= ' "nome": "<a href=\"#\" class=\"show_valutazioni\" data-id-dom-profili-utenti=\"'.$array_results[$idst]['id_profili_utenti'].'\" data-id=\"'.$idst.'\">'.$anagrafica[$x]['cognome'].' '.$anagrafica[$x]['nome'].'</a>",';
			$data .= ' "matricola": "'.$anagrafica[$x]['matricola'].'"';
			$data .= "},\n";
		endfor;
	$data .= "],\n";	
	$data2 = "[\n";
	$data2 .= "],\n";	
	 
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
	
	
	
	  
	<div class="row">
		<div class="col-sm-12">
			

		   <table id="table-1" class="table table-striped-main table-bordered no-margin-bottom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Matricola</th>
                    
                     </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width:5% !important;">input</th>
                        <th style="width:10% !important;">input</th>
                        <th style="width:30% !important;">input</th>
                      
                     </tr>
                </tfoot>
              <tbody>
              </tbody>
           </table>
		 
		   <?php
				
		   ?>
		   
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
			var table1 = $('#table-1').DataTable({
								bAutoWidth: false,
								data: <?php echo $data ?>
								columns: [
									{data: "id"},
									{data: "nome"},
									{data: "matricola"}
								],
								columnDefs: [ { orderable: false, targets: [0,1,2] }],
								order: [[ 1, "asc" ]],
								"iDisplayLength": 10,
								"orderable": false,
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
				$("#table-1_filter").hide();
				//$("#table-1_wrapper").removeClass();
				$(".dataTables_paginate").css('padding-top', '10px');
			});
			
			
			$(document).on('click', '.save_people_autovalutazione', function(){
				id = $(this).attr('value');
				var id_valutazione = $(this).attr("id_valutazione");
				var fk_id_profili_utenti = $(this).attr('fk_id_profili_utenti');
				
				if($(this).is(':checked')) {
					var deleted = 0;
					if(id_valutazione==0){
						var op = 'save_tb';
						var data = {id_dom_profili_utenti_valutazioni: 0, fk_id_dom_profili_x_utenti: fk_id_profili_utenti, fk_idst_valutatore: id, deleted: deleted};
					}else{
						var op = 'update_tb';
						var data = {id_dom_profili_utenti_valutazioni: id_valutazione, deleted: deleted};
					}
				}else{
					var op = 'update_tb';					
					var deleted = 1;
					var id_profili_utenti = $(this).attr("id_checked_auto");
					var data = {id_dom_profili_utenti_valutazioni: id_valutazione, deleted: deleted}
				}
				
				var object = $(this);
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op='+op+'&tb=dom_profili_utenti_valutazioni',
					type: 'POST',
					data: data,
					success:function(id_generato){
						object.attr('id_valutazione', id_generato);
						//location.reload();
						//location.href='index.php?page=new-cert-attach&id_cert='+id_cert;
					},
					error: function(response) {},
					async: false
				});
				
			});	
		
		});
		
		
		$(document).on('click', '.show_valutazioni', function(){
			$("#dettaglio_valutazioni").remove();
			var id = $(this).attr("data-id");
			var profili_utenti = $(this).attr("data-id-dom-profili-utenti");
			var nome = $(this).html();
			var form;
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=form_dettaglio_valutazioni',
				type: 'POST',
				data: {
					
					nome : nome
					
				},
				success:function(msg){
					form = msg;

					$('body').append(form);
					$('#dettaglio_valutazioni').modal('show');
					render_table_dettaglio_valutazioni(id,profili_utenti);
					$(".aggiungi-valutazione").attr("data-id-dom-profili-utenti",profili_utenti);

				},
				error: function(response) {},
				async: false
			});
		});
		
		function render_table_dettaglio_valutazioni(id,profili_utenti){
			jQuery(function($){

						if(typeof table2 !== 'undefined') {
							table2.destroy();
						
						}
						var recordset_dettaglio_valutazione;
						$.ajax({
							url: '../build/p_lib/ajax_check.php?op=get_recordset_dettaglio_valutazione',
							type: 'POST',
							data: {
								idst : id,
								profili_utenti : profili_utenti 
							},
							success:function(msg){
								recordset_dettaglio_valutazione=JSON.parse(msg);
							},
							error: function(response) {},
							async: false
						});
						
					
						
						
						table2 = $('#table-2').DataTable({
							bAutoWidth: false,
							data: recordset_dettaglio_valutazione,
							columns: [
								{data: "select", bSortable:false},
								{data: "valutazione"},
								{data: "stato"},
								{data: "data"},
								{data: "gestione"}
							],
							order: [[ 4, "desc" ]],
							aLengthMenu: [
								[10, 20, 50, 100, -1],
								[10, 20, 50, 100, "Mostra tutti"]
							],
							"bDestroy": true,
							"oLanguage": {
								"sLengthMenu": 	"<button class='filtro btn btn-sm btn-success aggiungi-valutazione' data-id='"+id+"' title='Aggiungi valutazione'>"+
										"<i class='ace-icon fa fa-plus white bigger-120'></i> Aggiungi Valutazione"+
										"</button>&nbsp;&nbsp;&nbsp;"+
										"Visualizza _MENU_ elementi per pagina ",
								"sZeroRecords": "Nessun elemento trovato",
								"sInfo": "_START_ - _END_ di _TOTAL_ richieste",
								"sInfoEmpty": "0 - 0 di 0 richieste",
								"sInfoFiltered": "(risultanti da un totale di _MAX_ richieste)",
								"sSearch": "Ricerca",
								oPaginate:{sFirst:"Prima",sLast:"Ultima",sNext:"Avanti",sPrevious:"Indietro"}
							}
						});


						$('#table-2 tfoot th').each( function (colIdx) {
							var title = $('#table-2 tfoot th').eq( $(this).index() ).text();
							switch(title){
								case '':
									$(this).html( '' );
									break;
								case 'select':
									var select = $('<select style="width: 100%;"><option value="">Seleziona</option></select>')
										.appendTo( $(table2.column(colIdx).footer()).empty());
									table2.column(colIdx).data().unique().sort().each(function(d,j){
										select.append( '<option value="'+d+'">'+d+'</option>');
									});
									$(this).html(select);
									break;
								case 'input':
									$(this).html( '<input type="text" style="width: 100%;" />' );
									break;
							  case 'multiple_select':
										var in_html = '<select class="ms" multiple="multiple">';
										op_html = '';
										var _arr=new Array();
										table2.column(colIdx).data().unique().sort().each(function(d,j){
												var _elm = $(d).find('li');
												$.each(_elm, function(index, value) {
													if(jQuery.inArray($(value).text(),_arr)<0)
														_arr.push($(value).text());
												});
										});
										$.each(_arr, function(index, value) {
												op_html+= '<option value="'+value+'">'+value+'</option>';
												in_html+='<option value="'+value+'">'+value+'</option>';
										});
										in_html+='<option value="">Tutte le schede</option>';
										in_html+='</select>';
										se_multiple.push(op_html);
										console.log(in_html);
										$(this).html(in_html);
										$('.ms').multipleSelect({
												filter: true,
												placeholder: "Seleziona",
												selectAllText: "Seleziona Tutto",
										});
										$('.ms-parent').removeClass('form-control');
										$('.ms-choice').addClass('form-control');
										$('.ms-parent').attr('style', 'width: 100% !important;');
										$('.ms-choice').attr('style', 'width: 100% !important;');
										break;

								default:
									$(this).html('UNSET FIELD');
							}
						});
						var old_val="";
						table2.columns().eq(0).each( function ( colIdx ) {
								$('input[type=text]', table2.column(colIdx).footer()).on('keyup change',function(){
										if(old_val==this.value)return;
										old_val=this.value;
										table2
											.column(colIdx)
											.search(this.value, false, true, true)
											.draw();
								});
								$('select:not(select[name=area], select[name=sottoarea])', table2.column( colIdx ).footer()).on( 'change', function (){
										var val='';
										if($(this).attr("multiple")=="multiple"){
											if($(this).val()!== null)
												val = $(this).val().join('|');
											else
												val='';
										}
										else {
											val = $(this).val();
										}
										if(old_val==val && val!='')return;
										old_val=val;
										table2
											.column(colIdx)
											.search('',false, true, true)
											.draw()
											.column(colIdx)
											.search(val ? val : '',true, false)
											.draw();
								});

						});
			});
			$(document).ready(function(){
				
				
				if($('#table-2 thead tr input[type="text"]').length==0)
					$('#table-2 thead').append($('#table-2 tfoot tr'));
				
				
			});

			$(document).on('draw.dt', '#table-2', function(){
				var flag = true;
				$('.seleziona').each(function(){
					if(!$(this).prop('checked')){ flag = false; return false;}
				});
				$('#scelta_corsi_selectall').prop('checked', flag);
			});


			$(document).on('draw.dt', '#table-2', function(){
				var flag = true;
				$('.seleziona').each(function(){
					if(!$(this).prop('checked')){ flag = false; return false;}
				});
				$('#scelta_corsi_selectall').prop('checked', flag);
			});

			$(document).on('draw.dt', '#table-2', function(){
				var flag = true;
				$('.seleziona').each(function(){
					if(!$(this).prop('checked')){ flag = false; return false;}
				});
				$('#scelta_corsi_selectall').prop('checked', flag);
			});
			
			$(document).on('click', '#annulla_dettaglio_valutazione', function(){
				$('#dettaglio_valutazioni').modal('hide');
			});
			
		}
		
		
		$(document).on('click', '.aggiungi-valutazione', function(){
			
			id=$(this).attr("data-id");
			id_dom_profilo = $(this).attr("data-id-dom-profili-utenti");
			
			var d = new Date;
			dformat = [	d.getFullYear(),
					   d.getMonth()+1,
					    d.getDate()
						].join('-')+' '+
					  [d.getHours(),
					   d.getMinutes(),
					   d.getSeconds()].join(':');
			
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_utenti_valutazioni',
				type: 'POST',
				data: {
						id_dom_profili_utenti_valutazioni : 0 ,
						fk_idst_valutatore: id,
					   data: dformat,
					   fk_id_dom_profili_x_utenti:id_dom_profilo,
					   deleted: 0
				},
				success:function(msg){
				//	location.reload();
					render_table_dettaglio_valutazioni(id,id_dom_profilo);
					$(".aggiungi-valutazione").attr("data-id-dom-profili-utenti",id_dom_profilo);
				
				},
				error: function(response) {},
				async: false
			});	
			return false;
			
		});
		
			
				$(document).on('click', '.del_valutazione', function(){
				
				
				var id_dom_profili_utenti_valutazioni = $(this).attr("id_dom_profili_utenti_valutazioni");
				id=$(this).attr("data-id");
				id_dom_profilo = $(this).attr("data-id-dom-profili-utenti");
				
				$('#dettaglio_valutazioni').css( "z-index", "2" );	
				
				var box = bootbox.dialog({
						message: "Sei sicuro di voler eliminare la valutazione ?",
						className: "modal70",
						title:   "Attenzione!",
						  onEscape: function() {
							$('#dettaglio_valutazioni').css( "z-index", "" );
							$('.modal').css( "overflow-y", "auto" );
							$('.modal').css( "overflow-x", "hidden" );
							$('.modal-open').css( "overflow-y", "auto" );
							$('.modal-open').css( "overflow-x", "hidden" );
													
						},
						buttons: {
							"success" : {
									"label" : "Elimina",
									"className" : "btn-sm btn-success",
									"callback": function() {
										$.ajax({
											url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_profili_utenti_valutazioni',
											type: 'POST',
											data: {id_dom_profili_utenti_valutazioni: id_dom_profili_utenti_valutazioni,deleted:'1'},
											success:function(msg){
											
												
												
													$('#dettaglio_valutazioni').css( "z-index", "" );
													$('.modal').css( "overflow-y", "auto" );
													$('.modal').css( "overflow-x", "hidden" );
													$('.modal-open').css( "overflow-y", "auto" );
													$('.modal-open').css( "overflow-x", "hidden" );
													
													render_table_dettaglio_valutazioni(id,id_dom_profilo);
													$(".aggiungi-valutazione").attr("data-id-dom-profili-utenti",id_dom_profilo);	
											
												
											},
											error: function(response) {
													$('#dettaglio_valutazioni').css( "z-index", "" );
													$('.modal').css( "overflow-y", "auto" );
													$('.modal').css( "overflow-x", "hidden" );
													$('.modal-open').css( "overflow-y", "auto" );
													$('.modal-open').css( "overflow-x", "hidden" );
												
											},
											async: false
										});	
										
									}
							},
							"danger" : {
								"label" : "Annulla",
								"className" : "btn-sm btn-danger",
								"callback": function() {
									
									$('#dettaglio_valutazioni').css( "z-index", "" );
									$('.modal').css( "overflow-y", "auto" );
									$('.modal').css( "overflow-x", "hidden" );
									$('.modal-open').css( "overflow-y", "auto" );
									$('.modal-open').css( "overflow-x", "hidden" );
									
								}
							},
						}
					});
			});
			
			
			
		
		
		
		</script>