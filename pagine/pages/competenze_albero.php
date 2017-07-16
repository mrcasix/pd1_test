<?php

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])=="no") echo "<script>window.location.href = 'index.php?page=404';</script>";


	if(isset($_GET["idq"])) $is_to_open = $_GET["idq"];
	else $is_to_open = 0;
	
	$id_tipo = base64_decode($_REQUEST['id']);

	$info_tipo = get_table('dom_competenze_tipologie', '*', 'id_dom_competenze_tipologie='.$id_tipo);
	$nome_tipo = $info_tipo[0]['desc_tipo'];
	$cod_tipo = $info_tipo[0]['cod_tipo'];

	$elenco = get_table('dom_competenze_area', '*', 'deleted=0 and fk_id_dom_competenze_tipologie='.$id_tipo); // tab, order eg array(array("data_validita", "desc")), where
	
	 
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
                <div class="col-sm-12">
                    <div class="widget-header widget-header-flat dark">
                        <small>Tipologia di competenza: </small><br /><b><?php echo $nome_tipo ?></b>&nbsp;<small>(<?php echo $cod_tipo ?>)</small>
                        <div class="widget-toolbar">
                            <a href="index.php?page=competence" role="button" data-rel='tooltip' class="tooltip-success" data-placement="bottom" title="Torna alle tipologie di competenza">
                                &nbsp;<i class="ace-icon fa fa-arrow-left bigger-160 green"></i>&nbsp;
                            </a>
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
                            AREE DI COMPETENZA
                        </h2>
                        <div class="widget-toolbar">
                            <div class="btn-group">
                                <a href="#" role="button" id_area=0 data-rel='tooltip' class="tooltip-info new_element" data-placement="bottom" title="Aggiungi area di competenza">
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
                                        <th align="center" style="width: 15%">Cod. Area</th>
                                        <th align="">Area di competenza</th>
                                        <th style="width: 13%"></th>
                                    </tr>
                                </thead>
	                            <tbody>
                                 <?php
                                 $tab = "";
                                 for($el=0; $el<count($elenco); $el++):
                                    $id_area = $elenco[$el]['id_dom_competenze_area'];
                                    $cod_area = $elenco[$el]['cod_area'];
                                    if($id_area!=$is_to_open) $collapsed_state = "collapsed";
                                    else $collapsed_state = "collapse in";
									
									    $tab = "";
                                        $elenco_competenze = get_table('dom_competenze', '*', 'deleted=0 and fk_id_dom_competenze_area	='.$id_area);
                                 ?>
                                 	
                                    <tr>
                                    	<td>
                                        	 <?php echo '<span id="cod_area'.$id_area.'">'.$elenco[$el]['cod_area'].'</span>'; ?>
                                        </td>
                                        <td>
                                            <div class="widget-title">
                                                    <!--<a class="accordion-toggle dark inizializza" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $id_area ?>">-->
                                                        <!--<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-right" data-icon-show="ace-icon fa fa-angle-down"></i>-->
                                                       <?php echo '<span id="area'.$id_area.'"><b>'.$elenco[$el]['area'].'</b></span>' ?>
                                                    <!--</a>-->
                                            </div>
                                            
                                            <div class="panel-collapse collapse <?php echo $collapsed_state ?>" id="collapse<?php echo $id_area ?>">
                                                <div class="panel-body">
                                                    <div class="row">
                                                    
                                                    <div class="btn-group btn-corner paginazione" id_paginazione='<?php echo $id_area ?>'>
                                                     	 	<button type="button" id_area='<?php echo $id_area ?>' class="btn btn-info btn-sm  pager-first idf<?php echo $id_area ?>"><i class="fa fa-fast-backward"></i></button>
                                                     	 	<button type="button" id_area='<?php echo $id_area ?>' class="btn btn-info btn-sm pager-previous idf<?php echo $id_area ?>"><i class="fa fa-backward"></i></button>
                                                     		<button type="button" id_area='<?php echo $id_area ?>' class="btn btn-info btn-sm" disabled="disabled"><span id="pager-count-from<?php echo $id_area ?>"></span>-<span id="pager-count-to<?php echo $id_area ?>"></span></button>
                                                    		<button type="button" id_area='<?php echo $id_area ?>' class="btn btn-info btn-sm" disabled="disabled"><span id="pager-count-total<?php echo $id_area ?>">Totale: <?php echo count($elenco_competenze) ?></span></button>
                                                    		<button type="button" id_area='<?php echo $id_area ?>' class="btn btn-info btn-sm pager-reload-current"><i class="fa fa-repeat"></i></button>
                                                     		<button type="button" id_area='<?php echo $id_area ?>' class="btn btn-info btn-sm pager-next idf<?php echo $id_area ?>"><i class="fa fa-forward"></i></button>
                                                            <input type="hidden" id_area='<?php echo $id_area ?>' class="limit-table_<?php echo $id_area ?>" value="5" />
                                                            <input type="hidden" id_area='<?php echo $id_area ?>' class="offset-table_<?php echo $id_area ?>" value="0"/>
                                                            <input type="hidden" id_area='<?php echo $id_area ?>' class="total-table_<?php echo $id_area ?>" value="<?php echo count($elenco_competenze) ?>"/>
                                                     		<span class="input-icon" >
                                                                <input type="text"  id_area='<?php echo $id_area ?>' class="search-table_<?php echo $id_area ?> nav-search-input input-sm" placeholder="Search ..." autocomplete="off" />
                                                                <i class="ace-icon fa fa-search nav-search-icon"></i>  
															</span>
														    <a role='button' id_area='<?php echo $id_area ?>' class="btn btn-info btn-sm text-search"><i class="ace-icon fa fa-search"></i></a>
                                                     </div><br /><br />
                                                     <div id="elenco_competenze_dest_<?php echo $id_area ?>">
                                                 		<i class="ace-icon fa fa-spinner fa-spin orange bigger-250"></i>
                                                 	 </div>
                                                     <div id="elenco_competenze_<?php echo $id_area ?>" style="display:none">
                                                     	<table id="tb1_<?php echo $id_area?>" class="table table-striped-main table-bordered secondary">
                                                            <thead>
                                                                <tr>
                                                                    <th align="center" style="width: 15%">Cod. Competenza</th>
                                                                    <th align="">Competenza</th>
                                                                    <th style="width: 12%">*</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            
                                                            
                                                                /*for($t=0; $t<count($elenco_competenze); $t++):
                                                                    $id_competenza = $elenco_competenze[$t]['id_comp'];*/
                                                             ?>
		                                                            <!-- {{#Records}} -->
                                                                    <tr>
                                                                        <td>{{cod_comp}}</td>
                                                                        <td>
																			<a role='button' style='cursor: pointer;' class='tooltip-success view_sub_element' data-rel='tooltip' id_comp="{{id_dom_competenze}}" data-placement='bottom' title='Visualizza descrizione'>
                                                                            {{competenza}}
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                           <a role='button' class='tooltip-success view_sub_element' style='cursor: pointer;' data-rel='tooltip' id_comp="{{id_dom_competenze}}" data-placement='bottom' title='Visualizza descrizione'><i class='ace-icon fa fa-search bigger-120 green'></i></a>&nbsp;&nbsp;
                                                                           <a href=# class='tooltip-info add_sub_element' data-rel='tooltip' id_comp="{{id_dom_competenze}}" idq="<?php echo $id_area ?>" data-placement='bottom' title='Modifica competenza'><i class='ace-icon fa fa-pencil bigger-120'></i></a>&nbsp;&nbsp;
                                                                           <a href=# class='tooltip-error del_comp' data-rel='tooltip' idq="<?php echo $id_area ?>" id_comp="{{id_dom_competenze}}" data-placement='bottom' title='Elimina competenza'><i class='ace-icon fa fa-trash-o red bigger-120'></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <!-- {{/Records}} -->
                                                            <?php
                                                               // endfor;
                                                            ?>   
                                                            </tbody>
                                                         </table>
                                                        </div>
                                                     </div><!-- /.col -->
                                                     
                                                </div>
                                           
                                        </div>
                                            </td>
                                            <td>
                                     		   <a class="accordion-toggle dark tooltip-warning inizializza" data-rel='tooltip' data-placement='bottom' title='Visualizza competenza' data-toggle="collapse" data-parent="#accordion" id_area='<?php echo $id_area ?>' href="#collapse<?php echo $id_area ?>"><i  class="ace-icon fa fa-search bigger-120 orange"></i></a>&nbsp;&nbsp;
                                                <a href="#" class='tooltip-info new_element' data-rel='tooltip' data-placement='bottom' title='Modifica area' id_area='<?php echo $id_area ?>'><i class="ace-icon fa fa-pencil bigger-120"></i></a>&nbsp;&nbsp;
                                                <a href="#" class='tooltip-success add_sub_element' data-rel='tooltip' data-placement='bottom' title='Aggiungi competenza' id_comp='0' idq='<?php echo $id_area ?>'><i class="ace-icon fa fa-plus-square green bigger-120"></i></a>&nbsp;&nbsp;
                                                <a href="#" class='tooltip-error del_area' data-rel='tooltip' data-placement='bottom' title='Elimina area e competenza' id_area='<?php echo $id_area ?>'><i class="ace-icon fa fa-trash-o red bigger-120"></i></a>
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
    
	<script type="text/javascript">
		
	jQuery(function($) {
			
				// DataTable
			
			
			$(document).on('click', '.new_element', function(){
					var id = $(this).attr("id_area");
					if(id>0){
						var area = $('#area'+id).text();
						var cod_area = $('#cod_area'+id).text();
					}else{
						var cod_area = "<?php echo $cod_tipo ?>"+"_";
						var area = "";
					}
					var form;					
					$.ajax({
						url: '../build/p_lib/ajax_check.php?op=form_aree',
						type: 'POST',
						data: {id_area: id,
							   cod_area: cod_area,
							   area: area
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
									cod_area = $("#cod_area").val();
									area =  $("#area").val();
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_competenze_area',
										type: 'POST',
										data: {id_dom_competenze_area: id,
											   cod_area: cod_area,
											   area: area,
											   fk_id_dom_competenze_tipologie: <?php echo $id_tipo ?>
											   },
										success:function(msg){
											if(id>0){
												$('#area'+id).html('<b>'+area+'</b>');
												$('#cod_area'+id).html(cod_area);
												refresh_competenza(msg);
											}else{
												location.href='index.php?page=competence-tree&id=<?php echo base64_encode($id_tipo)?>&idq='+msg;
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
			
			$(document).on('click', '.del_area', function(){
				var id = $(this).attr("id_area");
				var box = bootbox.dialog({
						message: "Sei sicuro di voler eliminare l\'area di competenza?",
						className: "modal70",
						title:   "Attenzione!",
						buttons: {
							"success" : {
								"label" : "Elimina",
								"className" : "btn-sm btn-success",
								"callback": function() {
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_competenze_area',
										type: 'POST',
										data: {id_dom_competenze_area: id, deleted: '1'},
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
			
			$(document).on('click', '.del_comp', function(){
				var id = $(this).attr("id_comp");
				var id_area = $(this).attr("idq");
				var box = bootbox.dialog({
						message: "Sei sicuro di voler eliminare la competenza?",
						className: "modal70",
						title:   "Attenzione!",
						buttons: {
							"success" : {
								"label" : "Elimina",
								"className" : "btn-sm btn-success",
								"callback": function() {
									$.ajax({
										url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_competenze',
										type: 'POST',
										data: {id_dom_competenze: id, deleted: '1'},
										success:function(msg){
											refresh_competenza(id_area);
											//location.href='index.php?page=sp_tree&id=<?php echo base64_encode($id_tipo)?>&idq='+id_area;
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
	
   $(document).on('click', '.add_sub_element', function(){
			var id = $(this).attr("id_comp");
			var id_area = $(this).attr('idq');
			/// form
			var form;					
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=form_competenza',
				type: 'POST',
				data: {id_comp: id, id_area: id_area},
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
							cod_comp = $("#cod_comp").val();
							competenza =  $("#competenza").val();
							descrizione =  $("#descrizione").html();
							conoscenze =  $("#conoscenze").html();
							abilita =  $("#abilita").html();
							$.ajax({
								url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_competenze',
								type: 'POST',
								data: {id_dom_competenze: id,
									   cod_comp: cod_comp,
									   competenza: competenza,
									   fk_id_dom_competenze_area: id_area,
									   descrizione: descrizione,
									   conoscenze: conoscenze,
									   abilita: abilita
									   },
								success:function(msg){
									refresh_competenza(id_area);
									//location.href='index.php?page=sp_tree&id=<?php //echo base64_encode($id_tipo)?>&idq='+id_area;
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
   
   
   $(document).on('click', '.view_sub_element', function(){
			var id = $(this).attr("id_comp");
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=tab_desc_competenza',
				type: 'POST',
				data: {id_comp: id},
				success:function(value){
					var box_attori = bootbox.dialog({
						message:  value,
						className: "modal70",
						title:   "Competenza..."
					});
				},
				error: function(response) {},
				async: false
			});	
	});
	
	
	$(document).on('click', '.pager-first', function(){
		var id_area = $(this).attr('id_area');
		var offset = $('.offset-table_'+id_area);
		var limit = $('.limit-table_'+id_area);
		if(parseFloat(offset.val())<parseFloat(limit.val()))
			return false;
		offset.val(0);
		refresh_competenza(id_area);
			
	});

	$(document).on('click', '.pager-previous', function(){
		var id_area = $(this).attr('id_area');
		var offset = $('.offset-table_'+id_area);
		var limit = $('.limit-table_'+id_area);
		var total =   $('.total-table_'+id_area);
		if(parseFloat(offset.val())<parseFloat(limit.val())) return false;
		offset.val(parseFloat(offset.val()) - parseFloat(limit.val()));
		refresh_competenza(id_area);
	});

	$(document).on('click', '.pager-reload-current', function(){
		var id_area = $(this).attr('id_area');
		//var table =  $(this).closest('table');
		refresh_competenza(id_area);
	});

	$(document).on('click', '.pager-next', function(){
		var id_area = $(this).attr('id_area');
		var offset = $('.offset-table_'+id_area);
		var limit = $('.limit-table_'+id_area);
		var total =   $('.total-table_'+id_area);
			//var table =  $(this).closest('table');

		if((parseFloat(offset.val())+parseFloat(limit.val()))>=parseFloat(total.val()))
			return false;
		offset.val(parseFloat(limit.val())+parseFloat(offset.val()));
		refresh_competenza(id_area);
	});

	
	$(document).on('click', '.text-search', function(){
		var id_area = $(this).attr('id_area');
		$('.offset-table_'+id_area).val(0);
		refresh_competenza(id_area);

	});
	
	$(document).on('click', '.inizializza', function(){
		id_area = $(this).attr('id_area');
		refresh_competenza(id_area);
	});
	
	
	function refresh_competenza(id_area){
		var offset = $('.offset-table_'+id_area);
		var limit = $('.limit-table_'+id_area);
		var search = $('.search-table_'+id_area);
		var total = $('.total-table_'+id_area);
		
		var table = $('#tb1_'+id_area);
		var _data = {
			op:'competenza_pag',
			offset: offset.val(),
			limit: limit.val(),
			search: search.val(),
			total: total.val(),
			table: table.attr('id'),
			id_area: id_area
		};
		var ap = $('#elenco_competenze_dest_'+id_area);
		var tpl = $('#elenco_competenze_'+id_area).html();
		
		$.ajax({
				url: '../build/p_lib/ajax_check.php?op=competenze_pag',
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
					$(this).closest('div').find('.offset-table_'+id_area).val(new_offset);
					
					$('#pager-count-from'+id_area).text(parseFloat(offset.val())+1);
					$('#pager-count-to'+id_area).text(parseFloat(offset.val())+parseFloat(results['dati'].length));
					$('#pager-count-total'+id_area).html('Totale: '+parseFloat(totale));

				},
				error: function(response) {},
				async: false
			});	
			
			if(parseFloat(offset.val())==0){
				$('.pager-first.idf'+id_area).attr("disabled", true);
				$('.pager-previous.idf'+id_area).attr("disabled", true);
			}
			else{
				$('.pager-first.idf'+id_area).removeAttr("disabled");
				$('.pager-previous.idf'+id_area).removeAttr("disabled");
			}
					
			if((parseFloat(offset.val())+parseFloat(limit.val()))>=parseFloat(total.val())){
				$('.pager-next.idf'+id_area).attr("disabled", true);
			}
			else{
				$('.pager-next.idf'+id_area).removeAttr("disabled");
			}
		
	}

	url = window.location.href;
    name = 'idq';
	name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (results){
		idq = decodeURIComponent(results[2].replace(/\+/g, " "));
		refresh_competenza(idq);
	}
	
	console.log(idq);

		if ($('#desktopTest').is(':hidden')) {
			$(".mystato").css({'zoom':'70%'})
    		
		} else {
			
//    		$(".mystato").text("tutte");
		}
	
	</script>
       