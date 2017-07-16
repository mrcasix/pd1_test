<?php
	
	include_once("../build/p_lib/query.php");

	echo '<div id="desktopTest" class="hidden-xs"></div>'; //usato per determinare il size del browser

	if ($_SESSION["is_owner"]==1000 and end($_SESSION["is_admin"])==0) echo "<script>window.location.href = 'index.php?page=404';</script>";

	$id_profilo = base64_decode($_REQUEST['id']);

	$anagrafica = get_table('anagrafica', '*', '');
	$utenti_in_profilo = get_table('dom_profili_x_utenti', '*', 'fk_id_dom_profili_ruolo='.$id_profilo);

	$idsts = array();
	$array_results = array();
	for($a=0; $a<count($utenti_in_profilo); $a++):
		$array_results[$utenti_in_profilo[$a]['fk_idst']] = array('id_profili_utenti'=>$utenti_in_profilo[$a]['id_dom_profili_x_utenti'], 'deleted'=>$utenti_in_profilo[$a]['deleted']);
	endfor;
	
	$idsts = array_keys($array_results);
	
	$data = "[\n";
		for($x=0; $x<count($anagrafica); $x++):
			$idst = $anagrafica[$x]['idst'];
			
			if(in_array($idst, $idsts)):
				$id_checked = $array_results[$idst]['id_profili_utenti'];
				if($array_results[$idst]['deleted']==0):
					$checked = 'checked';
					$flag = 'Incluso';
				else:
					$checked = '';
					$flag = 'Non incluso';
				endif;
			else:
				$checked = '';
				$id_checked = 0;
				$flag = 'Non incluso';
			endif;
			///

		

			$data .= "{";
			$data .= ' "button": "<input type=\"checkbox\" '.$checked.' name=\"people\" class=\"ace save_people_profilo\" id_checked=\"'.$id_checked.'\" value=\"'.$anagrafica[$x]['idst'].'\" /><span class=\"lbl\"></span>",';
			$data .= ' "flag": "'.$flag.'",';
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
	                <h2 class="widget-title lighter">
						Scegli le persone da associare al profilo di ruolo...
                    </h2>
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
                        <th>*</th>
                        <th>*</th>
                        <th>Matricola</th>
                        <th>Nome</th>
                     </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width:5% !important;"></th>
                        <th style="width:5% !important;"></th>
                        <th style="width:10% !important;"></th>
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
									{data: "flag"},
									{data: "matricola"},
									{data: "nome"}
								],
								columnDefs: [ { visible: false, targets: [1] }],
								order: [[ 1, "asc" ], [ 3, "asc" ]],
								"iDisplayLength": 10,
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
			
			$(document).on('click', '.save_people_profilo', function(){
				id = $(this).attr('value');
				var id_profili_utenti = $(this).attr("id_checked");
								
				if($(this).is(':checked')) {
					//$(this).closest('td').next().text('Incluso');
					if(id_profili_utenti==0){
						var op = 'save_tb';
						var data = {id_dom_profili_x_utenti: 0, fk_idst: id, fk_id_dom_profili_ruolo	: <?php echo $id_profilo ?>, deleted: 0};
					}else{
						var op = 'update_tb';
						var data = {id_dom_profili_x_utenti: id_profili_utenti, deleted: 0};
					}
				}else{
					//$(this).closest('td').next().text('Non incluso');
					var op = 'update_tb';					
					//var id_profili_utenti = $(this).attr("id_checked");
					var data = {id_dom_profili_x_utenti: id_profili_utenti, deleted: 1}
				}
				
				var object = $(this);
				$.ajax({
					url: '../build/p_lib/ajax_check.php?op='+op+'&tb=dom_profili_x_utenti',
					type: 'POST',
					data: data,
					success:function(id_generato){
						//location.reload();
						//location.href='index.php?page=new-cert-attach&id_cert='+id_cert;
						
						object.attr('id_checked', id_generato);
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
 
