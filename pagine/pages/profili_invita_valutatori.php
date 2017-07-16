<?php
	
	include_once("../build/p_lib/query.php");

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])==0) echo "<script>window.location.href = 'index.php?page=404';</script>";

	$id_profilo = base64_decode($_REQUEST['id']);
	$idst = base64_decode($_REQUEST['idst']);
	$fk_id_profili_utenti = base64_decode($_REQUEST['idpf']);
	$valutato = name_surname($idst);
	$info_profilo = get_table('dom_profili_ruolo', 'profilo', 'id_dom_profili_ruolo='.$id_profilo);
	$nome_profilo = $info_profilo[0]['profilo'];
	
	$anagrafica = get_table('anagrafica', 'idst, matricola, cognome, nome', 'idst<>'.$idst);

	$eterovalutazioni_enabled = eterovalutazioni_enabled($id_profilo, $idst);
	$elenco_valutatori = array();
	for($r=0; $r<count($eterovalutazioni_enabled); $r++):
		$elenco_valutatori[] = $eterovalutazioni_enabled[$idst][$r]['fk_idst_valutatore'];
	endfor;
	

	$data = "[\n";
		for($x=0; $x<count($anagrafica); $x++):
			$idst_anagrafica = $anagrafica[$x]['idst'];
			
			$checked2 = 0;
			$id_valutazione = 0;
			$fk_idst_valutatore = 0;
			$ultima_eterovalutazione = '--';

			if(in_array($idst_anagrafica, $elenco_valutatori)):
				// adesso metto lo 0 come indice dell'array poi chissà se sono più di una
				if($eterovalutazioni_enabled[$idst][0]['deleted']==0) $checked2 = 'checked';
				if(substr($eterovalutazioni_enabled[$idst][0]['data'], 0, 4) != '0000'):
					$ultima_eterovalutazione = date('d-m-Y', strtotime($eterovalutazioni_enabled[$x]['data']));
					//$checked2 = ''; RIABILITA QUANDO FAREMO MULTIVALUTAZIONI
				endif;
				//$fk_id_profili_utenti = $eterovalutazioni_enabled[$x]['id_profili_utenti'];
				$id_valutazione = $eterovalutazioni_enabled[$idst][0]['id_valutazione'];
				$fk_idst_valutatore = $eterovalutazioni_enabled[$idst][0]['fk_idst_valutatore'];
			endif;

			$data .= "{";
			$data .= ' "button": "<input type=\"checkbox\" '.$checked2.' id_valutazione=\"'.$id_valutazione.'\" fk_id_profili_utenti=\"'.$fk_id_profili_utenti.'\" name=\"people\" class=\"ace save_people_valutazione\" value=\"'.$anagrafica[$x]['idst'].'\" /><span class=\"lbl\"></span>",';
			$data .= ' "eterovalutazione": "'.$ultima_eterovalutazione.'",';
			$data .= ' "matricola": "'.$anagrafica[$x]['matricola'].'",';
			$data .= ' "nome": "'.$anagrafica[$x]['cognome'].' '.$anagrafica[$x]['nome'].'"';
			//$data .= ' "desc_sp": "'.str_replace("\n", "", $sottoprocessi[$x]['desc_sp']).'"';
			$data .= "},\n";
		endfor;
	$data .= "],\n";	
	
	
	 
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
	                <h3 class="widget-title lighter">
						<small>Invita una o più persone a valutare</small> <b><?php echo $valutato; ?></b> 
                        <small>per il profilo di ruolo</small> <b><?php echo $nome_profilo; ?></b>
                    </h3>
                    <div class="widget-toolbar">
                        <div class="btn-group">
                        	<a href="index.php?page=ask-valutations&id=<?php echo base64_encode($id_profilo) ?>" role="button" class="tooltip-info back" data-placement="bottom" title="Indietro">
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
                        <th>*</th>
                        <th>Data Eterovalutazione</th>
                        <th>Matricola</th>
                        <th>Nome</th>
                     </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width:5% !important;"></th>
                        <th style="width:10% !important;"></th>
                        <th style="width:15% !important;"></th>
                        <th>input</th>
                     </tr>
                </tfoot>
              <tbody>
              </tbody>
           </table>
		</div><!-- /.widget-main -->
	</div><!-- /.widget-body -->
     <!-- -->                        
	<!-- CSS adjustments for browsers with JavaScript disabled -->
    <link rel="stylesheet" href="../assets/css/ace.min.css" id="main-ace-style" />
   	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/jquery.dataTables.bootstrap.js"></script>
    <script src="../assets/js/bootbox.min.js"></script>
	<script type="text/javascript">
		
	jQuery(function($) {
			//$('#data').mask('99-99-9999');
				// DataTable
			var table1 = $('#table-1').DataTable({
								bAutoWidth: false,
								data: <?php echo $data ?>
								columns: [
									{data: "button"},
									{data: "eterovalutazione"},																		
									{data: "matricola"},
									{data: "nome"}
								],
								columnDefs: [ { orderable: false, targets: [0,1,2,3] }],
								order: [[ 3, "asc" ]],
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
			
			
			$(document).on('click', '.save_people_valutazione', function(){
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
			////////////////////////////////////////////////////
			
		
		
		
		if ($('#desktopTest').is(':hidden')) {
			$(".mystato").css({'zoom':'70%'})
    		
		} else {
			
//    		$(".mystato").text("tutte");
		}
	
	</script>
 
