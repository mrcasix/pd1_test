<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";

	$id_profilo = base64_decode($_REQUEST['id']);
	$info_profilo = get_table('dom_profili_ruolo', '*', 'id_dom_profili_ruolo='.$id_profilo.' and deleted=0');
	$utenti_in_profilo = get_table('dom_profili_x_utenti', '*', 'fk_id_dom_profili_ruolo='.$id_profilo.' and deleted=0');
	$nome_p="";
	if(isset($info_profilo[0]["profilo"]))
		$nome_p=$info_profilo[0]["profilo"];
	
	
	
	$idsts = array();
	$array_results = array();
	
	for($a=0; $a<count($utenti_in_profilo); $a++):
		$array_results[$utenti_in_profilo[$a]['fk_idst']] = array('id_profili_utenti'=>$utenti_in_profilo[$a]['id_dom_profili_x_utenti'], 'deleted'=>$utenti_in_profilo[$a]['deleted']);
	endfor;
	
	$idsts = array_keys($array_results);
	$idsts_string = implode(',',$idsts);
	$anagrafica = get_table('anagrafica', 'idst, matricola, cognome, nome', 'idst IN ('.$idsts_string.')');
	$autovalutazioni_enabled = autovalutazioni_enabled($id_profilo);
	$dom_profili_utenti_blocco_valutazioni=array();
	$data = "[\n";
		for($x=0; $x<count($anagrafica); $x++):
			$idst = $anagrafica[$x]['idst'];
			$fk_id_profili_utenti = $array_results[$idst]['id_profili_utenti'];
			
			$_arr = get_table('dom_profili_utenti_blocco_valutazioni', '*', 'fk_id_dom_profili_x_utenti = "'.$array_results[$idst]['id_profili_utenti'].'"');
			if(count($_arr)>0)
				$dom_profili_utenti_blocco_valutazioni[$idst] = $_arr;
			
			$checked2 = 0;
			$id_valutazione = 0;
			$fk_idst_valutatore = 0;
			$ultima_autovalutazione = '--';
			if (array_key_exists($id_profilo, $autovalutazioni_enabled)):
				
				if(array_key_exists($idst, $autovalutazioni_enabled[$id_profilo])):
					if($autovalutazioni_enabled[$id_profilo][$idst][0]['deleted']==0) $checked2 = 'checked';
					if(substr($autovalutazioni_enabled[$id_profilo][$idst][0]['data'], 0, 4) != '0000'):
						$ultima_autovalutazione = date('d-m-Y', strtotime($autovalutazioni_enabled[$id_profilo][$idst][0]['data']));
					endif;
					$id_valutazione = $autovalutazioni_enabled[$id_profilo][$idst][0]['id_valutazione'];
					if($autovalutazioni_enabled[$id_profilo][$idst][0]['fk_idst_valutatore']!=$idst) $fk_idst_valutatore = $autovalutazioni_enabled[$id_profilo][$idst][0]['fk_idst_valutatore'];
				endif;
			endif;	
			
			$data .= "{";
			$data .= ' "autovalutazione": "<input type=\"checkbox\" '.$checked2.' id_valutazione=\"'.$id_valutazione.'\" fk_id_profili_utenti=\"'.$fk_id_profili_utenti.'\" name=\"people\" class=\"ace save_people_autovalutazione\" value=\"'.$anagrafica[$x]['idst'].'\" /><span class=\"lbl\"></span>",';
			$data .= ' "ultima_autovalutazione": "'.$ultima_autovalutazione.'",';
			$data .= ' "eterovalutazione": "<a href=\'index.php?page=valutatori&id='.base64_encode($id_profilo).'&idst='.base64_encode($idst).'&idpf='.base64_encode($fk_id_profili_utenti).'\' data-rel=\'tooltip\' class=\'tooltip-info\' data-placement=\'bottom\' title=\'Invita valutatori\'><i class=\'ace-icon fa fa-users bigger-140 purple\'></i></a>",';
			$data .= ' "matricola": "'.$anagrafica[$x]['matricola'].'",';
			$data .= ' "nome": "'.$anagrafica[$x]['cognome'].' '.$anagrafica[$x]['nome'].'"';
			$data .= "},\n";
		endfor;
	$data .= "],\n";	
	
	
	
	
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
                <div class="col-sm-6 col-sm-offset-6">
                    <div class="widget-header widget-header-flat dark">
                        <small>Profilo di ruolo: </small><br /><b><?php echo $nome_p ?></b>&nbsp;
                        <div class="widget-toolbar">
                            <a href="index.php?page=role-profile" role="button" data-rel='tooltip' class="tooltip-success" data-placement="bottom" title="Torna ai profili">
                                &nbsp;<i class="ace-icon fa fa-arrow-left bigger-160 green"></i>&nbsp;
                            </a>
                        </div>
                    </div>
                </div>
				
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
                            Anagrafiche
                        </h2>
                        
                        </div>
                    </div>
                	<div class="widget-body">
                        <div class="widget-main no-padding">

                            <div class="widget-box transparent ui-sortable-handle">
                            <table id="tb2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th align="center" style="width: 15%">Matricola</th>
                                        <th align="">Nominativo</th>
                                        <th style="width: 13%"></th>
                                    </tr>
                                </thead>
	                            <tbody>
                                 <?php
                                 $tab = "";
											
								
									
								 if(isset($anagrafica))
                                 for($el=0; $el<count($anagrafica); $el++):
                                    $idst = $anagrafica[$el]['idst'];
									$cod_fase = "";
									$id_fase="";
									$collapsed_state="collapsed";
									
									 ?>

                                    <tr>
                                    	<td>
										   <?php echo '<span ><b>'.$anagrafica[$el]['matricola'].'</b></span>' ?>
                                        </td>
                                        <td>
                                            <div class="widget-title">
                                                 <?php echo '<span id="'.$idst.'">'.$anagrafica[$el]['cognome'].' '.$anagrafica[$el]['nome'].'</span>'; ?>
											</div>

                                            <div class="panel-collapse collapse <?php echo $collapsed_state ?>" id="collapse<?php echo $idst ?>">
                                                <div class="panel-body">
                                                    <div class="row">
													
													<?php 
														
													
															$i=0;
															if(isset($dom_profili_utenti_blocco_valutazioni) && isset($dom_profili_utenti_blocco_valutazioni[$idst]))
															foreach($dom_profili_utenti_blocco_valutazioni[$idst] as $index=>$value):
													?>
													
													 <div class="widget-header widget-header-flat dark">
														<h3>Blocco di valutazione n. <?php 
														
														
														echo $value["id_dom_profili_utenti_blocco_valutazioni"] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														Autovalutazione <input type="checkbox" class="richiedi_autovalutazione" idst="<?php echo $idst ?>" fk_id_dom_profili_utenti="<?php echo $array_results[$idst]['id_profili_utenti'] ?>" id_dom_profili_utenti_blocco_valutazioni="<?php echo $value["id_dom_profili_utenti_blocco_valutazioni"] ?>" id_autovalutata="<?php echo $value["autovalutata"] ?>" <?php if($value["autovalutata"]!=0 && $value["autovalutata"]!=null) echo " checked";?>/></h3>
														<div class="widget-toolbar">
															<a href="#" role="button" fk_id_dom_profili_utenti_blocco_valutazioni="<?php echo $value["id_dom_profili_utenti_blocco_valutazioni"] ?>" fk_id_dom_profili_utenti="<?php echo $array_results[$idst]['id_profili_utenti'] ?>" idst='<?php echo $idst ?>' data-rel='tooltip' class="tooltip-info add-valutation"   title="Aggiungi valutazione">
																&nbsp;<i class="ace-icon fa fa-plus bigger-160 blue"></i>&nbsp;
															</a>
														</div>
													</div>
													 <br/>
													
													<div class="btn-group btn-corner paginazione" id_paginazione='<?php echo $idst ?>'>
                                                     	 	<button type="button" idst='<?php echo $idst ?>' class="btn btn-info btn-sm  pager-first idf<?php echo $idst."_".$i ?>"><i class="fa fa-fast-backward"></i></button>
                                                     	 	<button type="button" idst='<?php echo $idst ?>' class="btn btn-info btn-sm pager-previous idf<?php echo $idst."_".$i ?>"><i class="fa fa-backward"></i></button>
                                                     		<button type="button" idst='<?php echo $idst ?>' class="btn btn-info btn-sm" disabled="disabled"><span id="pager-count-from<?php echo $idst."_".$i ?>"></span>-<span id="pager-count-to<?php echo $idst."_".$i ?>"></span></button>
                                                    		<button type="button" idst='<?php echo $idst ?>' class="btn btn-info btn-sm" disabled="disabled"><span id="pager-count-total<?php echo $idst."_".$i ?>">Totale: <?php echo count($anagrafica) ?></span></button>
                                                    		<button type="button" idst='<?php echo $idst ?>' class="btn btn-info btn-sm pager-reload-current"><i class="fa fa-repeat"></i></button>
                                                     		<button type="button" idst='<?php echo $idst ?>' class="btn btn-info btn-sm pager-next idf<?php echo $idst."_".$i ?>"><i class="fa fa-forward"></i></button>
                                                            <input type="hidden" idst='<?php echo $idst ?>' class="limit-table_<?php echo $idst."_".$i ?>" value="5" />
                                                            <input type="hidden" idst='<?php echo $idst ?>' class="offset-table_<?php echo $idst."_".$i ?>" value="0"/>
                                                            <input type="hidden" idst='<?php echo $idst ?>' class="total-table_<?php echo $idst."_".$i ?>" value="<?php echo count($anagrafica) ?>"/>
                                                     		<span class="input-icon" >
													</div><br /><br />
                                                     <div id="elenco_valutazioni_dest_<?php echo $idst."_".$i ?>">
                                                 		<i class="ace-icon fa fa-spinner fa-spin orange bigger-250"></i>
                                                 	 </div>
													
                                                     <div id="elenco_valutazioni_<?php echo $idst."_".$i ?>"  class="elenco_valutazioni" id_dom_profili_utenti_blocco_valutazioni="<?php echo $value["id_dom_profili_utenti_blocco_valutazioni"] ?>" style="display:none">
														<table class="tb1_<?php echo $idst."_".$i?> table table-striped-main table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th align="center" style="width: 15%">*</th>
																	<th style="width: 25%">Stato</th>
																	<th style="width: 20%">Data Assegnazione</th>
																	<th style="width: 20%">Data fine</th>
																	<th style="width: 20%">Valutatore</th>
																
																	<th style="width: 10%">Gestione</th>
																</tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            ?>
																<!-- {{#Records}} -->
																<tr>
																<td>{{id_dom_profili_utenti_valutazioni}}</td>
																<td>
																		{{stato}}
																</td>
																<td>
																	{{data_inizio}}
																</td>
																<td>
																	{{data_fine}}
																</td>
																<td id="{{id_valutatore}}">
																		<b>{{valutatore}}</b>
																</td>
																<td>
																<center>
																	<a href=# autovalutata="{{autovalutata}}" fk_id_dom_profili_x_utenti="<?php echo $array_results[$idst]['id_profili_utenti'] ?>" id_dom_profili_utenti_valutazioni="{{id_dom_profili_utenti_valutazioni}}" idst='<?php echo $idst ?>' title='Cancella valutazione'><i class='ace-icon fa fa-trash-o bigger-140 red'></i></a>
																</center>
																</td>
																</tr>
																<!-- {{/Records}} -->
                                                            <?php

                                                            ?>
                                                            </tbody>
                                                         </table>
														 </div> 
														 <hr/>
														<?php 
															$i++;
															endforeach;
														?>
													
                                                    </div><!-- /.col -->
												</div>
											</div>
                                            </td>
                                            <td>
                                     		   <a class="accordion-toggle dark tooltip-warning inizializza" data-rel='tooltip' data-placement='bottom' title='Visualizza valutazioni' data-toggle="collapse" data-parent="#accordion" idst='<?php echo $idst ?>' href="#collapse<?php echo $idst ?>"><i  class="ace-icon fa fa-search bigger-120 orange"></i></a>&nbsp;&nbsp;
                                     		   <a class="dark tooltip-success aggiungi-blocco" data-rel='tooltip' data-placement='bottom' title='Aggiungi gruppo di valutazioni' href="#" fk_id_dom_profili_utenti="<?php echo $array_results[$idst]['id_profili_utenti'] ?>" idst='<?php echo $idst ?>' ><i  class="ace-icon fa fa-plus bigger-120 green"></i></a>&nbsp;&nbsp;
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
							data: '',
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
				var fk_id_dom_sottoprocessi="";
				var fk_id_dom_fasi=[];
				table1.$('.seleziona:checked').each(function(){
					fk_id_dom_fasi.push($(this).attr('id'));
				});

				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_sottoprocessi_x_fasi',
					type: 'GET',
					data: {
						fk_id_dom_fasi: fk_id_dom_fasi,
						fk_id_dom_sottoprocessi: ''
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

$(document).on('click', '.aggiungi-blocco', function(){
	var fk_id_dom_profili_x_utenti = $(this).attr("fk_id_dom_profili_x_utenti");
	var date_today = new Date().toJSON().slice(0,10);
	
	$.ajax({
		url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_utenti_blocco_valutazioni',
		type: 'GET',
		data: {
			id_dom_profili_utenti_blocco_valutazioni: 0,
			fk_id_dom_profili_x_utenti: fk_id_dom_profili_x_utenti,
			data: date_today,
			autovalutata:0,
			valutazione_finale:0
		},
		success:function(msg){
			location.reload();
		},
		error: function(response) {},
		async: false
	});
	
});	


$(document).on('click', '.add-valutation', function(){
		var id =0;
		var idst = $(this).attr('idst');
		var fk_id_dom_profili_utenti = $(this).attr('fk_id_dom_profili_utenti');
		var fk_id_dom_profili_utenti_blocco_valutazioni = $(this).attr('fk_id_dom_profili_utenti_blocco_valutazioni');
	
				
			var cod_fase = ""+"_";
			var fase = "";
			if(!$('#scelta_valutatore').length || old_idst!=idst){
			var form;
			var recordset_attivita;
			old_idst=idst;

			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=form_seleziona_valutatore',
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
				url: '../build/p_lib/ajax_check.php?op=get_recordset_valutatori',
				type: 'POST',
				data: {
					idst : idst,
					fk_id_dom_profili_utenti : fk_id_dom_profili_utenti
				},
				success:function(msg){
					recordset_valutatori=JSON.parse(msg);
				},
				error: function(response) {},
				async: false
			});
			$('#scelta_valutatore').modal('show');
			render_table_attivita(recordset_valutatori);
			table2.page(0).draw(false);
		}
		else {
			$('#scelta_valutatore').modal('show');
		}
		
		$('#conferma_scelta_valutatore').attr('idst',idst);
		$('#conferma_scelta_valutatore').attr('fk_id_dom_profili_utenti',fk_id_dom_profili_utenti);
		$('#conferma_scelta_valutatore').attr('fk_id_dom_profili_utenti_blocco_valutazioni',fk_id_dom_profili_utenti_blocco_valutazioni);
				
		
		return;
			
});	
	
$(document).on('click', '.richiedi_autovalutazione', function(){
	
	
	var idst = $(this).attr("idst");
	var fk_id_dom_profili_x_utenti= $(this).attr("fk_id_dom_profili_utenti");
	var id_dom_profili_utenti_valutazioni= $(this).attr("id_dom_profili_utenti_valutazioni");
	var id_autovalutatore=$(this).parent().prev().attr('id');
	
	var id_autovalutazione=$(this).attr("id_autovalutata");
	var id_dom_profili_utenti_blocco_valutazioni=$(this).attr("id_dom_profili_utenti_blocco_valutazioni");
	
	
	
	
	if($(this).prop("checked")==false){
		
		if(!confirm("Deselezionando eliminerai la relativa valutazione sei sicuro"))
			return false;
		
		$.ajax({
			url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_utenti_valutazioni',
			type: 'GET',
			data: {
				id_dom_profili_utenti_valutazioni: id_autovalutazione,
				deleted:1
			},
			success:function(msg){
				
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_utenti_blocco_valutazioni',
					type: 'GET',
					data: {
						id_dom_profili_utenti_blocco_valutazioni: id_dom_profili_utenti_blocco_valutazioni,
						autovalutata:0
					},
					success:function(m){
						
					},
					error: function(response) {},
					async: false
				});
					
			
			},
			error: function(response) {},
			async: false
		});
		$(this).attr("id_autovalutata",0);
		
	} else {
		// Spuntando il checked
		
		
		$.ajax({
			url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_utenti_valutazioni',
			type: 'GET',
			data: {
				id_dom_profili_utenti_valutazioni: 0,
				fk_id_dom_profili_x_utenti:fk_id_dom_profili_x_utenti,
				fk_id_dom_profili_utenti_blocco_valutazioni:0,
				data:new Date().toISOString().slice(0,10),
				fk_idst_valutatore:idst
			},
			success:function(msg){
				
				id_autovalutata = jQuery.parseJSON(msg);
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_utenti_blocco_valutazioni',
					type: 'GET',
					data: {
						id_dom_profili_utenti_blocco_valutazioni: id_dom_profili_utenti_blocco_valutazioni,
						autovalutata:id_autovalutata
					},
					success:function(m){
						
					},
					error: function(response) {},
					async: false
				});
					
			
			},
			error: function(response) {},
			async: false
		});
		
		
		$(this).attr("id_autovalutata",id_autovalutata);
		
	}
				
});

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
						{data: "idst"},
						{data: "nome"},
						{data: "cognome"},
						{data: "matricola"}
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
				$('#scelta_valutatore_selectall').prop('checked', flag);
			});

		
			$(document).on('click', '#annulla_scelta_valutatore', function(){
				$('#scelta_valutatore').modal('hide');
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
											   fk_id_dom_sottoprocessi:''
											   },
										success:function(msg){
											if(id>0){
												$('#fase'+id).html('<b>'+fase+'</b>');
												$('#cod_fase'+id).html(cod_fase);
												refresh_valutazioni(msg);
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
											refresh_valutazioni(id_fase);
											
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
	var old_idst="";
   $(document).on('click', '.add_sub_element', function(){

		//	var id = $(this).attr("id_att");
			var id =0;
			var idst = $(this).attr('idst');
			var fk_id_dom_profili_utenti = $(this).attr('fk_id_dom_profili_utenti');
			var fk_id_dom_profili_utenti_blocco_valutazioni = $(this).attr('fk_id_dom_profili_utenti_blocco_valutazioni');
			
				
				var cod_fase = ""+"_";
				var fase = "";
				if(!$('#scelta_valutatore').length || old_idst!=idst){
					var form;
					var recordset_attivita;
					old_idst=idst;

					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=form_seleziona_valutatore',
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
						url: '../build/p_lib/ajax_check.php?op=get_recordset_valutatori',
						type: 'POST',
						data: {
							idst : idst,
							fk_id_dom_profili_utenti : fk_id_dom_profili_utenti
						},
						success:function(msg){
							recordset_valutatori=JSON.parse(msg);
						},
						error: function(response) {},
						async: false
					});
					$('#scelta_valutatore').modal('show');
					render_table_attivita(recordset_valutatori);
					table2.page(0).draw(false);
				}
				else {
					$('#scelta_valutatore').modal('show');
				}
				
				$('#conferma_scelta_valutatore').attr('idst',idst);
				$('#conferma_scelta_valutatore').attr('fk_id_dom_profili_utenti',fk_id_dom_profili_utenti);
				
				return;
			


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
		var idst = $(this).attr('idst');
		var offset = $('.offset-table_'+idst);
		var limit = $('.limit-table_'+idst);
		if(parseFloat(offset.val())<parseFloat(limit.val()))
			return false;
		offset.val(0);
		refresh_valutazioni(idst);

	});

	$(document).on('click', '.pager-previous', function(){
		var idst = $(this).attr('idst');
		var offset = $('.offset-table_'+idst);
		var limit = $('.limit-table_'+idst);
		var total =   $('.total-table_'+idst);
		if(parseFloat(offset.val())<parseFloat(limit.val())) return false;
		offset.val(parseFloat(offset.val()) - parseFloat(limit.val()));
		refresh_valutazioni(idst);
	});

	$(document).on('click', '.pager-reload-current', function(){
		var idst = $(this).attr('idst');
		//var table =  $(this).closest('table');
		refresh_valutazioni(idst);
	});

	$(document).on('click', '.pager-next', function(){
		var idst = $(this).attr('idst');
		var offset = $('.offset-table_'+idst);
		var limit = $('.limit-table_'+idst);
		var total =   $('.total-table_'+idst);
			//var table =  $(this).closest('table');

		if((parseFloat(offset.val())+parseFloat(limit.val()))>=parseFloat(total.val()))
			return false;
		offset.val(parseFloat(limit.val())+parseFloat(offset.val()));
		refresh_valutazioni(idst);
	});


	$(document).on('click', '.text-search', function(){
		var idst = $(this).attr('idst');
		$('.offset-table_'+idst).val(0);
		refresh_valutazioni(idst);

	});

	$(document).on('click', '.inizializza', function(){
		idst = $(this).attr('idst');
		refresh_valutazioni(idst);
	});


	$(document).on('click', '#conferma_scelta_valutatore', function(){
		
		idst = $(this).attr('idst');
		fk_id_dom_profili_utenti = $(this).attr('fk_id_dom_profili_utenti');
		fk_id_dom_profili_utenti_blocco_valutazioni = $(this).attr('fk_id_dom_profili_utenti_blocco_valutazioni');
		alert(fk_id_dom_profili_utenti_blocco_valutazioni);
		var id_selezionate =$('#scelta_valutatore .seleziona:checked').map(function () {
			return $(this).attr("id");
		}).get();
		
		$.each(id_selezionate,function(i, item) {
		
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_utenti_valutazioni',
				type: 'GET',
				data: {
					id_dom_profili_utenti_valutazioni: 0,
					fk_idst_valutatore: id_selezionate[i],
					fk_id_dom_profili_x_utenti:fk_id_dom_profili_utenti,
					fk_id_dom_profili_utenti_blocco_valutazioni:fk_id_dom_profili_utenti_blocco_valutazioni,
					data:new Date().toISOString().slice(0,10)
				},
				success:function(msg){
					refresh_valutazioni(idst);
					$('#scelta_valutatore').modal('hide');
				//	location.reload();
				},
				error: function(response) {},
				async: false
			});
		
		});
	});
	$(document).on('click', '.annulla_scelta_valutatore', function(){
		$('#scelta_valutatore').modal('hide');
	});
	
	/*
	$(document).on('click', '.annulla_scelta_valutatore', function(){
		$.ajax({
				url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_profili_utenti_blocco_valutazioni',
				type: 'GET',
				data: {
					
					id_dom_profili_utenti_blocco_valutazioni: 0,
					fk_id_dom_profili_x_utenti: ,
					data: ,
					autovalutata:fk_id_dom_profili_utenti,
					valutazione_finale:new Date().toISOString().slice(0,10)
				},
				success:function(msg){
					refresh_valutazioni(idst);
					$('#scelta_valutatore').modal('hide');
				//	location.reload();
				},
				error: function(response) {},
				async: false
			});
		
	

	});
*/

	
	function refresh_valutazioni(idst){
		var i=0;
		var tp = $('.elenco_valutazioni');
		
		$.each(tp, function(index,item) {
				
			var offset = $('.offset-table_'+idst+"_"+i);
			var limit = $('.limit-table_'+idst+"_"+i);
			var search = $('.search-table_'+idst+"_"+i);
			var total = $('.total-table_'+idst+"_"+i);
			var table = $('#tb1_'+idst+"_"+i);
				
			var ap = $('#elenco_valutazioni_dest_'+idst+"_"+i);
			var tpl = $('#elenco_valutazioni_'+idst+"_"+i).html();
			var id_dom_profili_utenti_blocco_valutazioni = $(item).attr("id_dom_profili_utenti_blocco_valutazioni");
			
			var _data = {
				op:'get_recordset_dettaglio_valutazione_2',
				offset: offset.val(),
				limit: limit.val(),
				search: search.val(),
				total: total.val(),
				table: table.attr('id'),
				idst: idst,
				fk_id_dom_profili_utenti_blocco_valutazioni:id_dom_profili_utenti_blocco_valutazioni
			};
			
			
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=get_recordset_dettaglio_valutazione_2',
				type: 'POST',
				data: _data,
				success:function(value){
					results = JSON.parse(value);
					mydata2 = {
						Records: results['dati']
					};
					totale = results['totale'];
					total.val(totale);
					var output = Mustache.render(tpl, mydata2);
					ap.html(output);
					new_offset = offset+limit;
					$(this).closest('div').find('.offset-table_'+idst+"_"+i).val(new_offset);
					$('#pager-count-from'+idst+"_"+i).text((parseFloat(totale)==0)? 0: parseFloat(offset.val())+1 );
					$('#pager-count-to'+idst+"_"+i).text(parseFloat(offset.val())+parseFloat(results['dati'].length));
					$('#pager-count-total'+idst+"_"+i).html('Totale: '+parseFloat(totale));
				},
				error: function(response) {},
				async: false
			});

			if(parseFloat(offset.val())==0){
				$('.pager-first.idf'+idst+"_"+i).attr("disabled", true);
				$('.pager-previous.idf'+idst+"_"+i).attr("disabled", true);
			}
			else{
				$('.pager-first.idf'+idst+"_"+i).removeAttr("disabled");
				$('.pager-previous.idf'+idst+"_"+i).removeAttr("disabled");
			}

			if((parseFloat(offset.val())+parseFloat(limit.val()))>=parseFloat(total.val())){
				$('.pager-next.idf'+idst+"_"+i).attr("disabled", true);
			}
			else{
				$('.pager-next.idf'+idst+"_"+i).removeAttr("disabled");
			}
			i++;	
		});

	}

	url = window.location.href;
    name = 'idq';
	name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (results){
		idq = decodeURIComponent(results[2].replace(/\+/g, " "));
		refresh_valutazioni(idq);
	}

	//console.log(idq);

		if ($('#desktopTest').is(':hidden')) {
			$(".mystato").css({'zoom':'70%'})

		} else {

//    		$(".mystato").text("tutte");
		}

	</script>
