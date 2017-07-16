<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";


	if(isset($_GET["idq"])) $is_to_open = $_GET["idq"];
	else $is_to_open = 0;

	$id_sp = base64_decode($_REQUEST['id']);

	$info_sp = get_table('dom_sottoprocessi', '*', 'id_dom_sottoprocessi='.$id_sp);
	$nome_sp = $info_sp[0]['desc_sp'];
	$cod_sp = $info_sp[0]['cod_sp'];
	$id_p = $info_sp[0]['fk_id_dom_processi'];


	$info_p = get_table('dom_processi', '*', 'id_dom_processi='.$id_p);
	$nome_p = $info_p[0]['processo'];
	$cod_p = $info_p[0]['cod_processo'];
	$elenco = get_table('dom_fasi,dom_sottoprocessi_x_fasi', '*', 'dom_fasi.deleted=0 and dom_sottoprocessi_x_fasi.deleted=0 and id_dom_fasi=dom_sottoprocessi_x_fasi.fk_id_dom_fasi and dom_sottoprocessi_x_fasi.fk_id_dom_sottoprocessi='.$id_sp.''); // tab, order eg array(array("data_validita", "desc")), where

	$elenco_fasi_non_associate = get_table('dom_fasi', '*', 'deleted=0 and id_dom_fasi not in (select fk_id_dom_fasi from dom_sottoprocessi_x_fasi where fk_id_dom_sottoprocessi='.$id_sp.' and deleted=0)'); // tab, order eg array(array("data_validita", "desc")), where
	$data1="[";
	for($i=0;$i<count($elenco_fasi_non_associate);$i++){
		$fase = $elenco_fasi_non_associate[$i];
		$data1 .= "{";
		$data1 .= "\"select\": \"".
				"<center><label class='position-relative'>".
					"<input type='checkbox' id='".$fase["id_dom_fasi"]."' class='ace seleziona' />".
					"<span class='lbl'></span>".
				"</label></center>\",\n";
		$data1 .= ' "id_dom_fasi": "'.$fase['id_dom_fasi'].'",';
		$data1 .= ' "cod_fase": "'.$fase['cod_fase'].'",';
		$data1 .= ' "fase": "'.$fase['fase'].'"';
		$data1 .= "},\n";
	}
	$data1.="],";
	//
?>
<style>
.table-striped-main > tbody > tr:nth-child(2n+1) > td, .table-striped-main > tbody > tr:nth-child(2n+1) > th {
   background-color: #f2f3fb;
}
</style>

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
                    </div><br />
                </div>

            </div>
        </div>
    </div>
	<br />

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box transparent">
                    <div class="widget-header widget-header-flat">
                        <h2 class="widget-title lighter">
                            FASI
                        </h2>
                        <div class="widget-toolbar">
                            <div class="btn-group">
                                <a href="#" role="button" id_fase=0 data-rel='tooltip' class="tooltip-info new_element" data-placement="bottom" title="Aggiungi fase">
                                    <i class="ace-icon fa fa-plus-square bigger-180"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                	<div class="widget-body">
                        <div class="widget-main no-padding">

                            <div class="widget-box transparent ui-sortable-handle">
                            <table id="tb2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th align="center" style="width: 15%">Cod. Fase</th>
                                        <th align="">Fase</th>
                                        <th style="width: 13%"></th>
                                    </tr>
                                </thead>
	                            <tbody>
                                 <?php
                                 $tab = "";

								 if(isset($elenco))
                                 for($el=0; $el<count($elenco); $el++):
                                    $id_fase = $elenco[$el]['id_dom_fasi'];
                                    $id_dom_sottoprocessi_x_fasi = $elenco[$el]['id_dom_sottoprocessi_x_fasi'];
                                    $cod_fase = $elenco[$el]['cod_fase'];
                                    if($id_fase!=$is_to_open) $collapsed_state = "collapsed";
                                    else $collapsed_state = "collapse in";

									$tab = "";
									$elenco_attivita = get_table('dom_attivita', '*', 'deleted=0 and fk_id_dom_fasi='.$id_fase);
                                 ?>

                                    <tr>
                                    	<td>
                                        	 <?php echo '<span id="cod_fase'.$id_fase.'">'.$elenco[$el]['cod_fase'].'</span>'; ?>
                                        </td>
                                        <td>
                                            <div class="widget-title">
                                                    <!--<a class="accordion-toggle dark inizializza" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $id_fase ?>">-->
                                                        <!--<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-right" data-icon-show="ace-icon fa fa-angle-down"></i>-->
                                                       <?php echo '<span id="fase'.$id_fase.'"><b>'.$elenco[$el]['fase'].'</b></span>' ?>
                                                    <!--</a>-->
                                            </div>

                                            <div class="panel-collapse collapse <?php echo $collapsed_state ?>" id="collapse<?php echo $id_fase ?>">
                                                <div class="panel-body">
                                                    <div class="row">

                                                    <div class="btn-group btn-corner paginazione" id_paginazione='<?php echo $id_fase ?>'>
                                                     	 	<button type="button" id_fase='<?php echo $id_fase ?>' class="btn btn-info btn-sm  pager-first idf<?php echo $id_fase ?>"><i class="fa fa-fast-backward"></i></button>
                                                     	 	<button type="button" id_fase='<?php echo $id_fase ?>' class="btn btn-info btn-sm pager-previous idf<?php echo $id_fase ?>"><i class="fa fa-backward"></i></button>
                                                     		<button type="button" id_fase='<?php echo $id_fase ?>' class="btn btn-info btn-sm" disabled="disabled"><span id="pager-count-from<?php echo $id_fase ?>"></span>-<span id="pager-count-to<?php echo $id_fase ?>"></span></button>
                                                    		<button type="button" id_fase='<?php echo $id_fase ?>' class="btn btn-info btn-sm" disabled="disabled"><span id="pager-count-total<?php echo $id_fase ?>">Totale: <?php echo count($elenco_attivita) ?></span></button>
                                                    		<button type="button" id_fase='<?php echo $id_fase ?>' class="btn btn-info btn-sm pager-reload-current"><i class="fa fa-repeat"></i></button>
                                                     		<button type="button" id_fase='<?php echo $id_fase ?>' class="btn btn-info btn-sm pager-next idf<?php echo $id_fase ?>"><i class="fa fa-forward"></i></button>
                                                            <input type="hidden" id_fase='<?php echo $id_fase ?>' class="limit-table_<?php echo $id_fase ?>" value="5" />
                                                            <input type="hidden" id_fase='<?php echo $id_fase ?>' class="offset-table_<?php echo $id_fase ?>" value="0"/>
                                                            <input type="hidden" id_fase='<?php echo $id_fase ?>' class="total-table_<?php echo $id_fase ?>" value="<?php echo count($elenco_attivita) ?>"/>
                                                     		<span class="input-icon" >
                                                                <input type="text"  id_fase='<?php echo $id_fase ?>' class="search-table_<?php echo $id_fase ?> nav-search-input input-sm" placeholder="Search ..." autocomplete="off" />
                                                                <i class="ace-icon fa fa-search nav-search-icon"></i>
															</span>
														    <a role='button' id_fase='<?php echo $id_fase ?>' class="btn btn-info btn-sm text-search"><i class="ace-icon fa fa-search"></i></a>
                                                     </div><br /><br />
                                                     <div id="elenco_attivita_dest_<?php echo $id_fase ?>">
                                                 		<i class="ace-icon fa fa-spinner fa-spin orange bigger-250"></i>
                                                 	 </div>
                                                     <div id="elenco_attivita_<?php echo $id_fase ?>" style="display:none">
                                                     	<table id="tb1_<?php echo $id_fase?>" class="table table-striped-main table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th align="center" style="width: 15%">Cod. Attività</th>
                                                                    <th>Attività</th>
                                                                    <th style="width: 10%">Cadenza</th>
                                                                    <th style="width: 12%">*</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            ?>
		                                                            <!-- {{#Records}} -->
                                                                    <tr>
                                                                        <td>{{cod_attivita}}</td>
                                                                        <td>
																			<a style='cursor:pointer;' class='tooltip-success view_sub_element' data-rel='tooltip' id_att="{{id_dom_attivita}}" data-placement='bottom' title='Visualizza attori manutenzione'>
                                                                            {{attivita}}
                                                                            </a>
                                                                        </td>
                                                                        <td>{{cadenza}}</td>
                                                                        <td>
                                                                           <a style='cursor:pointer;' class='tooltip-success view_sub_element' data-rel='tooltip' id_att="{{id_dom_attivita}}" data-placement='bottom' title='Visualizza attori manutenzione'><i class='ace-icon fa fa-search bigger-120 green'></i></a>&nbsp;&nbsp;
                                                                           <!--
																		   <a style='cursor:pointer;' class='tooltip-info add_sub_element' data-rel='tooltip' id_att="{{id_dom_attivita}}" idq="<?php echo $id_fase ?>" data-placement='bottom' title='Modifica attività'><i class='ace-icon fa fa-pencil bigger-120'></i></a>&nbsp;&nbsp;
                                                                           -->
																		   <a style='cursor:pointer;' class='tooltip-error del_att' data-rel='tooltip' id_dom_fasi_x_attivita="{{id_dom_fasi_x_attivita}}"idq="<?php echo $id_fase ?>" id_att="{{id_dom_attivita}}" data-placement='bottom' title='Elimina attività'><i class='ace-icon fa fa-trash-o red bigger-120'></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <!-- {{/Records}} -->
                                                            <?php

                                                            ?>
                                                            </tbody>
                                                         </table>
                                                        </div>
                                                     </div><!-- /.col -->

                                                </div>

                                        </div>
                                            </td>
                                            <td>
                                     		   <a class="accordion-toggle dark tooltip-warning inizializza" data-rel='tooltip' data-placement='bottom' title='Visualizza attività' data-toggle="collapse" data-parent="#accordion" id_fase='<?php echo $id_fase ?>' href="#collapse<?php echo $id_fase ?>"><i  class="ace-icon fa fa-search bigger-120 orange"></i></a>&nbsp;&nbsp;
												<!--
											   <a style='cursor:pointer;' class='tooltip-info new_element' data-rel='tooltip' data-placement='bottom' title='Modifica fase' id_fase='<?php echo $id_fase ?>'><i class="ace-icon fa fa-pencil bigger-120"></i></a>&nbsp;&nbsp;
                                                -->
												
												<a style='cursor:pointer;' class='tooltip-success add_sub_element' data-rel='tooltip' data-placement='bottom' title='Aggiungi attività' id_att='0' idq='<?php echo $id_fase ?>'><i class="ace-icon fa fa-plus-square green bigger-120"></i></a>&nbsp;&nbsp;
                                               
											   <a style='cursor:pointer;' class='tooltip-error del_fase' data-rel='tooltip' data-placement='bottom' title='Elimina fase e attività' id_dom_sottoprocessi_x_fasi='<?php echo $id_fase ?>'><i class="ace-icon fa fa-trash-o red bigger-120"></i></a>
											</td>
                                        </tr>
                                 <?php
                                 endfor;
                                 ?>
                                 </tbody></table>
                            </div><!-- /.col -->
                        </div><!-- /.widget-main -->
                    </div><!-- /.widget-body -->
                </div><!-- /.widget-box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
	</div>
</div>

    <link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
    <script src="../build/p_lib/mustache.js"></script>
      	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/jquery.dataTables.bootstrap.js"></script>
	<script type="text/javascript">

		function render_table_fasi(){
			jQuery(function($){

					if(typeof table1 !== 'undefined') return;


						table1 = $('#table-1').DataTable({
							bAutoWidth: false,
							data: <?php echo $data1 ?>
							columns: [
								{data: "select", bSortable:false},
								{data: "id_dom_fasi"},
								{data: "cod_fase"},
								{data: "fase"}
							],
							aLengthMenu: [
								[10, 20, 50, 100, -1],
								[10, 20, 50, 100, "Mostra tutti"]
							],
							"oLanguage": {
								"sLengthMenu": 	"<button class='filtro btn btn-sm btn-dark' title='Filtra Corsi'>"+
										"<i class='ace-icon fa fa-filter white bigger-120'></i>"+
										"</button>&nbsp;&nbsp;&nbsp;"+
												"<button class='rimuovi-filtri btn btn-sm btn-dark' title='Rimouovi Filtri Di Ricerca'>"+
										"<i class='ace-icon fa fa-times white bigger-120'></i>"+
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
							  case 'multiple_select':
										var in_html = '<select class="ms" multiple="multiple">';
										op_html = '';
										var _arr=new Array();
										table1.column(colIdx).data().unique().sort().each(function(d,j){
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
						table1.columns().eq(0).each( function ( colIdx ) {
								$('input[type=text]', table1.column(colIdx).footer()).on('keyup change',function(){
										if(old_val==this.value)return;
										old_val=this.value;
										table1
											.column(colIdx)
											.search(this.value, false, true, true)
											.draw();
								});
								$('select:not(select[name=area], select[name=sottoarea])', table1.column( colIdx ).footer()).on( 'change', function (){
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
										table1
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

				$('#table-1 thead').append($('#table-1 tfoot tr'));
			});

			$(document).on('draw.dt', '#table-1', function(){
				var flag = true;
				$('.seleziona').each(function(){
					if(!$(this).prop('checked')){ flag = false; return false;}
				});
				$('#scelta_corsi_selectall').prop('checked', flag);
			});


			$(document).on('draw.dt', '#table-1', function(){
				var flag = true;
				$('.seleziona').each(function(){
					if(!$(this).prop('checked')){ flag = false; return false;}
				});
				$('#scelta_corsi_selectall').prop('checked', flag);
			});

			$(document).on('draw.dt', '#table-1', function(){
				var flag = true;
				$('.seleziona').each(function(){
					if(!$(this).prop('checked')){ flag = false; return false;}
				});
				$('#scelta_corsi_selectall').prop('checked', flag);
			});

			$(document).on('click', '#conferma_scelta_fase', function(){

				//
				var fk_id_dom_sottoprocessi="<?php echo $id_sp ?>";
				var fk_id_dom_fasi=[];
				table1.$('.seleziona:checked').each(function(){
					fk_id_dom_fasi.push($(this).attr('id'));
				});

				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_sottoprocessi_x_fasi',
					type: 'GET',
					data: {
						fk_id_dom_fasi: fk_id_dom_fasi,
						fk_id_dom_sottoprocessi: <?php echo $id_sp ?>
					},
					success:function(msg){
								location.reload();

					},
					error: function(response) {},
					async: false
				});

				$('#scelta_fasi').modal('hide');
			});

			$(document).on('click', '#annulla_scelta_fase', function(){
				$('#scelta_fasi').modal('hide');
			});

		}



		function render_table_attivita(record_set){


			jQuery(function($){

				if(typeof table2 !== 'undefined') {
					table2.destroy();
					 $('#table-2 thead').html('');
					}



				table2 = $('#table-2').DataTable({
					bAutoWidth: false,
					data: record_set,
					columns: [
						{data: "select", bSortable:false},
						{data: "id_dom_attivita"},
						{data: "cod_attivita"},
						{data: "attivita"},
						{data: "cadenza"}
					],
					 "bDestroy": true,
					aLengthMenu: [
						[10, 20, 50, 100, -1],
						[10, 20, 50, 100, "Mostra tutti"]
					],
					"oLanguage": {
						"sLengthMenu": "Visualizza _MENU_ elementi per pagina ",
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
					if($('#table-2 thead').find('input[type="text"]').length==0 && $('#table-2 thead').find('select').length==0)
				$('#table-2 thead').append($('#table-2 tfoot tr'));
			});

			$(document).on('draw.dt', '#table-2', function(){
				var flag = true;
				$('.seleziona').each(function(){
					if(!$(this).prop('checked')){ flag = false; return false;}
				});
				$('#scelta_attivita_selectall').prop('checked', flag);
			});

			$(document).on('click', '#conferma_scelta_attivita', function(){
				var fk_id_dom_attivita = [];
				table2.$('.seleziona:checked').each(function(){
					fk_id_dom_attivita.push($(this).attr('id'));
				});

				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_fasi_x_attivita',
					type: 'GET',
					data: {
						fk_id_dom_attivita: fk_id_dom_attivita,
						fk_id_dom_fasi: old_fase
					},
					success:function(msg){
						refresh_attivita(old_fase);
						old_fase=""; // refresho la tabella
						
						
					},
					error: function(response) {},
					async: false
				});

				$('#scelta_attivita').modal('hide');
			});


			$(document).on('click', '#annulla_scelta_attivita', function(){
				$('#scelta_attivita').modal('hide');
			});

		}



	jQuery(function($) {

				// DataTable



			$(document).on('click', '.new_element', function(){
					var id = $(this).attr("id_fase");
					if(id>0){
						var fase = $('#fase'+id).text();
						var cod_fase = $('#cod_fase'+id).text();
					}else{
						var cod_fase = ""+"_";
						var fase = "";

							if(!$('#scelta_fasi').length ){
								var form;

								$.ajax({
									url: '../build/p_lib/ajax_check.php?op=form_importa_fasi',
									type: 'POST',
									data: {id_fase: id,
										   cod_fase: cod_fase,
										   fase: fase
										   },
									success:function(msg){
										form = msg;

										$('body').append(form);
										$('#scelta_fasi').modal('show');
										render_table_fasi();


									},
									error: function(response) {},
									async: false
								});

							}
							else {
								$('#scelta_fasi').modal('show');
							}
						return;

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
											   fase: fase,
											   fk_id_dom_sottoprocessi: <?php echo $id_sp ?>
											   },
										success:function(msg){
											if(id>0){
												$('#fase'+id).html('<b>'+fase+'</b>');
												$('#cod_fase'+id).html(cod_fase);
												refresh_attivita(msg);
											}else{

											}
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

			
			
			$(document).on('click', '.del_fase', function(){
				var id = $(this).attr("id_dom_sottoprocessi_x_fasi");
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
										url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_sottoprocessi_x_fasi',
										type: 'POST',
										data: {id_dom_sottoprocessi_x_fasi: id, deleted: '1'},
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

			$(document).on('click', '.del_att', function(){
				var id = $(this).attr("id_att");
				var id_fase = $(this).attr("idq");
				var id_dom_fasi_x_attivita = $(this).attr("id_dom_fasi_x_attivita");
				var box = bootbox.dialog({
						message: "Sei sicuro di voler eliminare l'attività?",
						className: "modal70",
						title:   "Attenzione!",
						buttons: {
							"success" : {
								"label" : "Elimina",
								"className" : "btn-sm btn-success",
								"callback": function() {
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_fasi_x_attivita',
										type: 'POST',
										data: {id_dom_fasi_x_attivita: id_dom_fasi_x_attivita, deleted: '1'},
										success:function(msg){
											refresh_attivita(id_fase);
											//location.href='index.php?page=sp_tree&id=<?php echo base64_encode($id_sp)?>&idq='+id_fase;
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
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var old_fase="";
   $(document).on('click', '.add_sub_element', function(){


			var id = $(this).attr("id_att");
			var id_fase = $(this).attr('idq');
			if(id>0){
				// TODO
			}else{
				var cod_fase = ""+"_";
				var fase = "";
				if(!$('#scelta_attivita').length || old_fase!=id_fase){
					var form;
					var recordset_attivita;
					old_fase=id_fase;

					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=form_importa_attivita',
						type: 'POST',
						data: {
					   },
						success:function(msg){
							form = msg;
							$('body').append(form);
						},
						error: function(response) {},
						async: false
					});

					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=get_recordset_attivita',
						type: 'POST',
						data: {
							id_fase : id_fase
						},
						success:function(msg){
							recordset_attivita=JSON.parse(msg);
						},
						error: function(response) {},
						async: false
					});
					$('#scelta_attivita').modal('show');
					render_table_attivita(recordset_attivita);
					table2.page(0).draw(false);
				}
				else {
					$('#scelta_attivita').modal('show');
				}
				return;
			}


	});


   $(document).on('click', '.view_sub_element', function(){
			var id = $(this).attr("id_att");
			var form;
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=tab_attivita_attori_responsabilita',
				type: 'POST',
				data: {id_att: id},
				success:function(value){
					var box_attori = bootbox.dialog({
						message:  value,
						className: "modal70",
						title: "Attori"
					});
				},
				error: function(response) {},
				async: false
			});
	});


	$(document).on('click', '.pager-first', function(){
		var id_fase = $(this).attr('id_fase');
		var offset = $('.offset-table_'+id_fase);
		var limit = $('.limit-table_'+id_fase);
		if(parseFloat(offset.val())<parseFloat(limit.val()))
			return false;
		offset.val(0);
		refresh_attivita(id_fase);

	});

	$(document).on('click', '.pager-previous', function(){
		var id_fase = $(this).attr('id_fase');
		var offset = $('.offset-table_'+id_fase);
		var limit = $('.limit-table_'+id_fase);
		var total =   $('.total-table_'+id_fase);
		if(parseFloat(offset.val())<parseFloat(limit.val())) return false;
		offset.val(parseFloat(offset.val()) - parseFloat(limit.val()));
		refresh_attivita(id_fase);
	});

	$(document).on('click', '.pager-reload-current', function(){
		var id_fase = $(this).attr('id_fase');
		//var table =  $(this).closest('table');
		refresh_attivita(id_fase);
	});

	$(document).on('click', '.pager-next', function(){
		var id_fase = $(this).attr('id_fase');
		var offset = $('.offset-table_'+id_fase);
		var limit = $('.limit-table_'+id_fase);
		var total =   $('.total-table_'+id_fase);
			//var table =  $(this).closest('table');

		if((parseFloat(offset.val())+parseFloat(limit.val()))>=parseFloat(total.val()))
			return false;
		offset.val(parseFloat(limit.val())+parseFloat(offset.val()));
		refresh_attivita(id_fase);
	});


	$(document).on('click', '.text-search', function(){
		var id_fase = $(this).attr('id_fase');
		$('.offset-table_'+id_fase).val(0);
		refresh_attivita(id_fase);

	});

	$(document).on('click', '.inizializza', function(){
		id_fase = $(this).attr('id_fase');
		refresh_attivita(id_fase);
	});


	function refresh_attivita(id_fase){
		var offset = $('.offset-table_'+id_fase);
		var limit = $('.limit-table_'+id_fase);
		var search = $('.search-table_'+id_fase);
		var total = $('.total-table_'+id_fase);

		var table = $('#tb1_'+id_fase);
		var _data = {
			op:'attivita_pag',
			offset: offset.val(),
			limit: limit.val(),
			search: search.val(),
			total: total.val(),
			table: table.attr('id'),
			id_fase: id_fase
		};
		var ap = $('#elenco_attivita_dest_'+id_fase);
		var tpl = $('#elenco_attivita_'+id_fase).html();

		$.ajax({
				url: '../build/p_lib/ajax_check.php?op=attivita_pag',
				type: 'POST',
				data: _data,
				success:function(value){
					//console.log(value);
					results = JSON.parse(value);
					mydata2 = {
						Records: results['dati']
					};
					totale = results['totale'];
					total.val(totale);
					var output = Mustache.render(tpl, mydata2);
					ap.html(output);
					new_offset = offset+limit;
					$(this).closest('div').find('.offset-table_'+id_fase).val(new_offset);

					$('#pager-count-from'+id_fase).text((parseFloat(totale)==0)? 0: parseFloat(offset.val())+1 );
					$('#pager-count-to'+id_fase).text(parseFloat(offset.val())+parseFloat(results['dati'].length));
					$('#pager-count-total'+id_fase).html('Totale: '+parseFloat(totale));
					
					
					

				},
				error: function(response) {},
				async: false
			});

			if(parseFloat(offset.val())==0){
				$('.pager-first.idf'+id_fase).attr("disabled", true);
				$('.pager-previous.idf'+id_fase).attr("disabled", true);
			}
			else{
				$('.pager-first.idf'+id_fase).removeAttr("disabled");
				$('.pager-previous.idf'+id_fase).removeAttr("disabled");
			}

			if((parseFloat(offset.val())+parseFloat(limit.val()))>=parseFloat(total.val())){
				$('.pager-next.idf'+id_fase).attr("disabled", true);
			}
			else{
				$('.pager-next.idf'+id_fase).removeAttr("disabled");
			}

	}

	url = window.location.href;
    name = 'idq';
	name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (results){
		idq = decodeURIComponent(results[2].replace(/\+/g, " "));
		refresh_attivita(idq);
	}

	//console.log(idq);

		if ($('#desktopTest').is(':hidden')) {
			$(".mystato").css({'zoom':'70%'})

		} else {

//    		$(".mystato").text("tutte");
		}

	</script>
