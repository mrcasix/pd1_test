   							<?php
								
								if(isset($_POST["tabella"])){
									if($_POST["tabella"]=="criteri-valutazione"){
										if(isset($_POST["elimina"])) {
											$pk = array();
											$pk["id_dom_criteri_valutazione"] = $_POST["id"];
											$sql = del_tb_db('dom_criteri_valutazione',$pk);
										}
										else if(isset($_POST["id"]) && $_POST["id"]!=0){
											$fields = array();
											$pk = array();
											$fields["nome_criterio"] = $_POST["nome"];
											$pk["id_dom_criteri_valutazione"] = $_POST["id"];
											$id = update_tb_db($fields,'dom_criteri_valutazione',$pk);
										}
										else {
											$fields = array();
											$pk = array();
											$fields["nome_criterio"] = $_POST["nome"];
											$id = save_tb_db($fields,'dom_criteri_valutazione',$pk);
										}
									}
									else if($_POST["tabella"]=="descrizione-livelli"){
										if(isset($_POST["elimina"])) {
											$pk = array();
											$pk["id_dom_descrizione_livelli"] = $_POST["id"];
											$sql = del_tb_db('dom_descrizione_livelli',$pk);
										}
										else if(isset($_POST["id"]) && $_POST["id"]!=0){
											$fields = array();
											$pk = array();
											$fields["nome_criterio"] = $_POST["nome"];
											$pk["id_dom_criteri_valutazione"] = $_POST["id"];
											$id = update_tb_db($fields,'dom_criteri_valutazione',$pk);
										}
										else {
											$fields = array();
											$pk = array();
											$fields["nome_criterio"] = $_POST["nome"];
											$id = save_tb_db($fields,'dom_criteri_valutazione',$pk);
										}
									}
								}	
									
								$elenco_criteri_valutazione = elenco_criteri_valutazione();	
								$elenco_descrizione_livelli = elenco_descrizione_livelli();	
								
							?>
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
                                
								<!-- #section:custom/extra.hr -->
                                <div class="row">
									<div class="col-sm-12">
                                    
                                    	<div class="widget-body">
											<div class="widget-main no-padding">
                                                <div class="center">
                                                   
                                                   
                                                    
                                                    <div class="btn-group" role="group">
													   <button type="button" id="mat_did" class="btn btn-info btn-large dropdown dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
															GESTIONE CAMPI &nbsp;&nbsp; <i class="ace-icon fa fa-caret-right white"></i>
														</button>
													   <ul style="width: 100%;" class="dropdown-menu" role="menu" aria-labelledby="campi_discreti">
															<li role="presentation">
																<a role="menuitem" tabindex="-1" href="javascript:void(0);" class="">
																	<span class="configurazioni-base">Configurazione di base</span>
																</a>
															</li>
															<li role="presentation">
																<a role="menuitem" tabindex="-1" href="javascript:void(0);" class="">
																	<span class="parametri-posta">Paramentri di posta</span>
																</a>
															</li>
															<li role="presentation">
																<a role="menuitem" tabindex="-1" href="javascript:void(0);" class="mostra_criteri_valutazione">
																	<span class="criteri-valutazione">Criteri di Valutazione</span>
																</a>
															</li>
															<li role="presentation">
																<a role="menuitem" tabindex="-1" href="javascript:void(0);" class="">
																	<span class="anagrafiche-utenti">Anagrafiche Utenti</span>
																</a>
															</li>
															
														</ul>
                                                   
													</div>
                                                  
                                              
                                                </div>
                                    		</div>
                                        </div>  
                               			<div class="hr hr32 hr-dotted"></div>
                                		<div class="widget-body">
                                            
											<div class="widget-main no-padding elenco_criteri_valutazione" 
												 <?php if(!isset($_POST["tabella"]) || $_POST["tabella"]!="criteri-valutazione"): ?>
                                                    style="display: none;"
                                                 <?php endif; ?>
                                             >
                                                 <table id="criteri-valutazione"
                                                 class="table table-striped table-bordered table-hover no-margin-bottom no-border-top table-campi-discreti">
                                                 	<thead>
													<tr><th colspan="2"><center>Definisci i criteri di valutazione<b></b></center></th></tr>
													<tr><th>Criteri di valutazione</th><th></th></tr></thead>
                                            	<?php if(isset($elenco_criteri_valutazione))
													foreach($elenco_criteri_valutazione as $key => $value): ?>
                                                	<tr>
                                                        <td class="link modifica-campo-discreto">
                                                        	<span class="nome"><?php echo $value ?></span>
                                                        </td>
                                                        <td style="width: 10%" class="center">
															 <button id="<?php echo $key ?>" class="definisci-descrizione-livelli btn btn-sm btn-info">
                                                                <i class='ace-icon fa fa-bar-chart-o bigger-120'></i>
                                                            </button>
														
                                                            <button id="<?php echo $key ?>" class="elimina-campo-discreto btn btn-sm btn-danger">
                                                                <i class='ace-icon fa fa-trash-o bigger-120'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
												<?php endforeach; ?>
                                                </table>
											</div>
                                        </div>
										
										
										
										<div class="widget-main no-padding elenco_descrizione_livelli" 
												 <?php if(!isset($_POST["tabella"]) || $_POST["tabella"]!="descrizione-livelli"): ?>
                                                    style="display: none;"
                                                 <?php endif; ?>
                                             >
												<table id="descrizione-livelli"
                                                 class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
                                                 	<thead><tr>
															<th colspan="8"  style=""><center><h3 id="nome_criterio_descrizione">Lorem ipsum</h3></center></th>
															  
															</tr>
															
															<tr>
															<th style="width: 5%">#</th>
															   <th>Livello</th>
																<th>Definizione</th>
															   <th>Descrizione</th>															   
															   <th>Conoscenze</th> 
															   <th>Abilita'</th> 
															   <th>Competenze</th> 
															   <th style="width: 10%"></th> 
															   <th style="width: 0%"></th> 
															</tr>
													</thead>
												
                                            	</table>
											</div>
											
                                        </div>
                                    </div>
								</div><!-- /.widget-body -->
							</div><!-- /.widget-box -->
								
		<script src="../assets/js/jquery.dataTables.min.js"></script>
		<script src="../assets/js/jquery.dataTables.bootstrap.js"></script> 
		<script src="../build/p_lib/mostra_risposte.js"></script>
		<script src="../build/p_lib/form_categorie.js"></script>  
		<link rel="stylesheet" href="../assets/css/jquery.gritter.css" />
		<link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
		<script type="text/javascript">
		
		jQuery(function($){
			$(".sensibile").hide();
		});
		
		function render_table_descrizione(record_set){
			jQuery(function($){
				
				if(typeof table2 !== 'undefined') {
					table2.destroy();
				}
				if(record_set[0] != undefined)
				$("#nome_criterio_descrizione").html(record_set[0].criterio);
				
				if(record_set[0].vuoto)
					record_set={};
				
				table2 = $('#descrizione-livelli').DataTable({
					bAutoWidth: false,
					data: record_set,
					columns: [
						{data: "id_dom_descrizione_livelli", bSortable:false},
						{data: "livello"},
						{data: "definizione"},
						{data: "descrizione"},
						{data: "conoscenze"},
						{data: "abilita"},
						{data: "competenze"},
						{data: "gestione"},
						{data: "criterio",visible:false}
					],
					"bDestroy": true,
					aLengthMenu: [
						[10, 20, 50, 100, -1],
						[10, 20, 50, 100, "Mostra tutti"]
					],
					"oLanguage": {
						"sLengthMenu": "<button id-dom-criteri-valutazione='"+fk_id_dom_criteri_valutazione+"' class='form-control btn btn-success aggiungi-descrizione-livelli'>+ Aggiungi </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+"Visualizza _MENU_ elementi per pagina ",
						"sZeroRecords": "Nessun elemento trovato",
						"sInfo": "_START_ - _END_ di _TOTAL_ richieste",
						"sInfoEmpty": "0 - 0 di 0 richieste",
						"sInfoFiltered": "(risultanti da un totale di _MAX_ richieste)",
						"sSearch": "Ricerca",
						oPaginate:{sFirst:"Prima",sLast:"Ultima",sNext:"Avanti",sPrevious:"Indietro"}
					}
				});
			});

		}


	
	
		$(document).on('click', '.mostra_criteri_valutazione', function(){
			$(".elenco_criteri_valutazione").show();
			$(".elenco_descrizione_livelli").hide();
		
		});
		var fk_id_dom_criteri_valutazione = "";
		$(document).on('click', '.definisci-descrizione-livelli', function(){
			var id = $(this).attr("id");
			var record_set;	
			fk_id_dom_criteri_valutazione=id;			
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=get_descrizione_livelli',
				type: 'POST',
				data: {
					fk_id_dom_criteri_valutazione: id,
				},
				success:function(msg){
					record_set = JSON.parse(msg);
					render_table_descrizione(record_set);
					
				},
				error: function(response) {},
				async: false
			});	
			
			$(".elenco_descrizione_livelli").show();
			$(".elenco_criteri_valutazione").hide();
		
		});
		
		
		$(".table-campi-discreti").DataTable({
			bAutoWidth: false,
			"bSort": false,
			aLengthMenu: [
				[10, 20, 50, 100, -1],
				[10, 20, 50, 100, "Mostra tutti"]
			],
			"oLanguage": {
				"sLengthMenu": "Visualizza _MENU_ elementi per pagina ",
				"sZeroRecords": "Nessun elemento trovato",
				"sInfo": "<div>"+
							  	"<input type='text' class='form-control'></input>"+
								"<button type='button' class='aggiungi-campo-discreto btn btn-sm btn-success'>"+
									"<i class='ace-icon fa fa-plus bigger-120'></i>"+
								"</button>"+
						 "</div>",
				"sInfoEmpty": "<div>"+
							  	"<input type='text' class='form-control'></input>"+
								"<button type='button' class='aggiungi-campo-discreto btn btn-sm btn-success'>"+
									"<i class='ace-icon fa fa-plus bigger-120'></i>"+
								"</button>"+
							  "</div>",
				"sInfoFiltered": "(risultanti da un totale di _MAX_ richieste)",
				"sSearch": "Ricerca",
				oPaginate:{sFirst:"Prima",sLast:"Ultima",sNext:"Avanti",sPrevious:"Indietro"}
			}
		});
		
		
		$(document).on('click', '.modifica-campo-discreto', function(){
			var campo;
			var tipo;
			
			if($(this).find('.nome').length != 0){
				campo = $(this).find('.nome').text();
				tipo = "nome";
			} else if($(this).find('.immagine').length != 0){
				campo = $(this).find('.immagine').text();
				tipo = "immagine";
			}
			console.log($(this).find('.immagine'));
			var newElement = $("<input _field='"+tipo+"' type='text' value='"+campo+"' />"+
							   "<div class='btn-group'>"+
							   		"<button id='"+$(this).attr('id')+"' class='conferma-modifica-campo-discreto btn btn-sm btn-success'>"+
							   			"<i class='ace-icon fa fa-check bigger-120'></i>"+
							   		"</button>"+
							   "</div>"+
							   "<div class='btn-group'>"+
								   "<button id='"+$(this).attr('id')+"' class='annulla-modifica-campo-discreto btn btn-sm btn-danger' "+
								   	 "_prev_value='"+campo+"'>"+
										"<i class='ace-icon fa fa-times bigger-120'></i>"+
								   "</button>"+
							   "</div>");
			$(this).closest('table').find('.modifica-campo-discreto').addClass('_modifica-campo-discreto');
			$(this).closest('table').find('._modifica-campo-discreto').removeClass('modifica-campo-discreto');
			$(this).closest('table').find('._modifica-campo-discreto').removeClass('link');

			if($(this).find('.nome').length != 0)
				$(this).find('.nome').replaceWith(newElement);
			else if($(this).find('.immagine').length != 0)
				$(this).find('.immagine').replaceWith(newElement);
		});
		
		$(document).on('click', '.conferma-modifica-campo-discreto', function(){
			
			
			var form = '<form id="form_campo_discreto" action="index.php?page=tools" method="post">' +
							'<input type="hidden" name="id" value="'+$(this).closest('tr').find('.elimina-campo-discreto').attr('id')+'" />' +
  					   		'<input type="hidden" name="'+$(this).closest('td').find('input').attr("_field")+
								'" value="'+$(this).closest('td').find('input').val()+'" />' +
							'<input type="hidden" name="'+$(this).closest('td').find('input').attr("_field")+
								'" value="'+$(this).closest('td').find('input').val()+'" />' +
							'<input type="hidden" name="tabella" value="'+$(this).closest('table').attr('id')+'" />' +
  					   '</form>';
			
          //alert(form);return; 			
		     			   
					   
			$('body').append($(form));
			$('#form_campo_discreto').submit();
			
			
		});
		
		$(document).on('click', '.annulla-modifica-campo-discreto', function(){
			var newElement = $("<span class='nome'>"+$(this).attr("_prev_value")+"</span>");
			$(this).closest('td').find('input').remove();
			$(this).closest('table').find('._modifica-campo-discreto').addClass('modifica-campo-discreto');
			$(this).closest('table').find('._modifica-campo-discreto').addClass('link');
			$(this).closest('table').find('.modifica-campo-discreto').removeClass('_modifica-campo-discreto');
			$(this).closest('td').append(newElement);
			$(this).closest('td').find('.btn-group').remove();
			
		});
		
	
		$(document).on('click', '.elimina-campo-discreto', function(){
			var mod_conf = $('<div id="confirm_massive_elim" class="modal fade remove-on-hide" tabindex="-1">'+
				'<div class="modal-dialog">'+
					'<div class="modal-content">'+
						'<div class="modal-header no-padding">'+
							'<div class="table-header">CONFERMA ELIMINAZIONE</div>'+
						'</div>'+
						'<div class="page-header" align="center">'+
							'<h1>Sei sicuro di voler eliminare l\'elemento selezionato?</h1>'+
						'</div>'+
						'<div class="modal-footer no-margin-top" align="center">'+
							'<button id="'+$(this).attr('id')+'" class="conferma-eliminazione-campo-discreto btn btn-sm btn-success" '+
							'_tabella="'+$(this).closest('table').attr('id')+'">CONFERMA</button>'+
							'<button value="NO" class="btn btn-sm btn-danger" data-dismiss="modal">ANNULLA</button>'+							
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>');
		
			$('html').append(mod_conf);
			$('#confirm_massive_elim').modal('show');
		});
		
		$(document).on('click', '.conferma-eliminazione-campo-discreto', function(){
			var form = '<form id="form_campo_discreto" action="index.php?page=tools" method="post">' +
							'<input type="hidden" name="id" value="'+$(this).attr('id')+'" />' +
							'<input type="hidden" name="elimina" value="1" />' +
							'<input type="hidden" name="tabella" value="'+$(this).attr('_tabella')+'" />' +
  					   '</form>';
			$('body').append($(form));
			$('#form_campo_discreto').submit();
		});
		
		$(document).on('click', '.aggiungi-campo-discreto', function(){
			var form = '<form id="form_campo_discreto" action="index.php?page=tools" method="post">' +
							'<input type="hidden" name="id" value="0" />' +
  					   		'<input type="hidden" name="nome" value="'+$(this).prev().val()+'" />' +
							'<input type="hidden" name="tabella" value="'+$(this).closest('.row').prev().attr('id')+'" />' +
  					   '</form>';
			$('body').append($(form));
			$('#form_campo_discreto').submit();
		});
	/*
		$(document).on('click', '.aggiungi-descrizione-livelli', function(){
				//alert('Aggiungi');
				
				
				
		});
		*/
		
		$(document).on('click', '.aggiungi-descrizione-livelli', function(){
				//alert('Modifica');
		
			if(!$('#modal_descrizione_livelli').length){
				var form;
				var recordset_dati;
				
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=form_modifica_descrizione_livelli',
					type: 'POST',
					data: {
				   },
					success:function(msg){
						form = msg;
						$('body').append(form);
						$('#modal_descrizione_livelli').append("<input type=\"hidden\" value=\"0\" id=\"id_descrizione_livelli\"/>");
						$('#modal_descrizione_livelli').append("<input type=\"hidden\" value=\"0\" id=\"id_criteri_valutazione\"/>");
				},
					error: function(response) {},
					async: false
				});

				
			}
			else {
				$('#modal_descrizione_livelli').modal('show');
			}
			
			$('#id_descrizione_livelli').val('0');
			$('#id_criteri_valutazione').val(fk_id_dom_criteri_valutazione);
			$('#id-input-file-livello').val('');
			$('#id-input-file-abilita').val('');
			$('#id-input-file-conoscenze').val('');
			$('#id-input-file-definizione').val('');
			$('#id-input-file-descrizione').val('');
			$('#id-input-file-competenze').val('');
			$('#modal_descrizione_livelli').modal('show');
				
				
		
		});
		
		var old_id="";
		$(document).on('click', '.modifica-descrizione-livelli', function(){
				//alert('Modifica');
			var id = $(this).attr('id');	
			var competenze =$(this).closest('td').prev('td').text();
			var abilita =$(this).closest('td').prev('td').prev('td').text();
			var conoscenze =$(this).closest('td').prev('td').prev('td').prev('td').text();
			var definizione =$(this).closest('td').prev('td').prev('td').prev('td').prev('td').text();
			var descrizione =$(this).closest('td').prev('td').prev('td').prev('td').prev('td').prev('td').text();
			var livello =$(this).closest('td').prev('td').prev('td').prev('td').prev('td').prev('td').prev('td').text();
			
			if(!$('#modal_descrizione_livelli').length || old_id!=id){
				var form;
				var recordset_dati;
				old_id=id;

				$.ajax({
					url: '../build/p_lib/ajax_check.php?op=form_modifica_descrizione_livelli',
					type: 'POST',
					data: {
				   },
					success:function(msg){
						form = msg;
						$('body').append(form);
						$('#modal_descrizione_livelli').append("<input type=\"hidden\" value=\"0\" id=\"id_descrizione_livelli\"/>");
						$('#modal_descrizione_livelli').append("<input type=\"hidden\" value=\"0\" id=\"id_criteri_valutazione\"/>");
			
				},
					error: function(response) {},
					async: false
				});

				
			}
			else {
				$('#modal_descrizione_livelli').modal('show');
			}
			
			$('#id_descrizione_livelli').val(id);
			$('#id_criteri_valutazione').val(fk_id_dom_criteri_valutazione);
			$('#id-input-file-livello').val(livello);
			$('#id-input-file-abilita').val(abilita);
			$('#id-input-file-conoscenze').val(conoscenze);
			$('#id-input-file-definizione').val(definizione);
			$('#id-input-file-descrizione').val(descrizione);
			$('#id-input-file-competenze').val(competenze);
			$('#modal_descrizione_livelli').modal('show');
				
				
		
		});
		
		
		$(document).on('click', '#salva-descrizione-livelli', function(){
			
		
			
			var id = $('#id_descrizione_livelli').val();	
			
			var competenze =$('#id-input-file-competenze').val();
			var abilita =$('#id-input-file-abilita').val();
			var conoscenze =$('#id-input-file-conoscenze').val();
			var definizione =$('#id-input-file-definizione').val();
			var descrizione =$('#id-input-file-descrizione').val();
			var livello =$('#id-input-file-livello').val();
			var valutazione=1;
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=get_max_valore_numerico',
				type: 'POST',
				data: {
					id_dom_descrizione_livelli: id,
					livello: livello,
					definizione: definizione,
					fk_id_dom_criteri_valutazione: fk_id_dom_criteri_valutazione,
					descrizione: descrizione,
					conoscenze: conoscenze,
					abilita: abilita,
					competenze: competenze
				},
				success:function(msg){
					
					valutazione = parseInt(JSON.parse(msg));
				},
				error: function(response) {},
				async: false
			});
			valutazione++;
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=save_tb&tb=dom_descrizione_livelli',
				type: 'POST',
				data: {
					id_dom_descrizione_livelli: id,
					livello: livello,
					definizione: definizione,
					fk_id_dom_criteri_valutazione: fk_id_dom_criteri_valutazione,
					descrizione: descrizione,
					conoscenze: conoscenze,
					valore_numerico: valutazione,
					abilita: abilita,
					competenze: competenze
				},
				success:function(msg){
					
					$('.definisci-descrizione-livelli[id="'+fk_id_dom_criteri_valutazione+'"]').click();
					
					//location.reload();
				},
				error: function(response) {},
				async: false
			});
			
			
			$('#modal_descrizione_livelli').modal('hide');
		});
		
		$(document).on('click', '.elimina-descrizione-livelli', function(){
			$('#confirm_massive_elim').remove();
			var mod_conf = $('<div id="confirm_massive_elim" class="modal fade remove-on-hide" tabindex="-1">'+
				'<div class="modal-dialog">'+
					'<div class="modal-content">'+
						'<div class="modal-header no-padding">'+
							'<div class="table-header">CONFERMA ELIMINAZIONE</div>'+
						'</div>'+
						'<div class="page-header" align="center">'+
							'<h1>Sei sicuro di voler eliminare l\'elemento selezionato?</h1>'+
						'</div>'+
						'<div class="modal-footer no-margin-top" align="center">'+
							'<button id="'+$(this).attr('id')+'" class="conferma-eliminazione-descrizione-livelli btn btn-sm btn-success" '+
							'_tabella="'+$(this).closest('table').attr('id')+'">CONFERMA</button>'+
							'<button value="NO" class="btn btn-sm btn-danger" data-dismiss="modal">ANNULLA</button>'+							
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>');
		
			$('html').append(mod_conf);
			$('#confirm_massive_elim').modal('show');
		});
		
		
		$(document).on('click', '.conferma-eliminazione-descrizione-livelli', function(){
			
			
			var n_id = $(this).attr('id');	
			
			console.log(n_id);
			
			$.ajax({
				url: '../build/p_lib/ajax_check.php?op=update_tb&tb=dom_descrizione_livelli',
				type: 'POST',
				data: {
					id_dom_descrizione_livelli: n_id,
					deleted: 1,
				},
				success:function(msg){
					
					$('.definisci-descrizione-livelli[id="'+fk_id_dom_criteri_valutazione+'"]').click();
					
					//location.reload();
				},
				error: function(response) {},
				async: false
			});
			
			
			$('#confirm_massive_elim').modal('hide');
			
			
		});
		
		/*
		$(document).on('click', '.annulla-modifica-campo-discreto', function(){
			var newElement = $("<span class='nome'>"+$(this).attr("_prev_value")+"</span>");
			$(this).closest('td').find('input').remove();
			$(this).closest('table').find('._modifica-campo-discreto').addClass('modifica-campo-discreto');
			$(this).closest('table').find('._modifica-campo-discreto').addClass('link');
			$(this).closest('table').find('.modifica-campo-discreto').removeClass('_modifica-campo-discreto');
			$(this).closest('td').append(newElement);
			$(this).closest('td').find('.btn-group').remove();
			
		});
		
	*/
	
	
	</script>