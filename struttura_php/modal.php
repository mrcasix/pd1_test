<?php
function mount_modal($dettagli_corso, $intestazioni, $titolo, $id, $extra){
	$m_tab = '<div id="modal'.$id.'" class="modal fade" tabindex="-1">
				<div class="modal-dialog" style="width:80% !important;">
					<div id="stampa_registro" class="modal-content">
						<div class="modal-header no-padding">
							<div class="table-header">
								<button type="button" class="close hidden-print" data-dismiss="modal" aria-hidden="true">
									<span class="white">&times;</span>
								</button>'
								.$titolo.'
							</div>
						</div>';
	
	$m_tab .=			'<div id="body'.$id.'" class="modal-body no-padding">';
	$m_tab .= $extra;
	$m_tab .=			'	<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
								<thead>
									<tr>';
									for($m=0; $m<count($intestazioni); $m++):
										if($intestazioni[$m] != ""):
											$int = $intestazioni[$m];
	$m_tab .=								'<td>'.$int.'</td>';
										endif;
									endfor;
	$m_tab .= 					   '</tr>
								</thead>
								<tbody>';
									for($c=0; $c<count($dettagli_corso); $c++):
	$m_tab .= 						'<tr>';
										for($m=0; $m<count($intestazioni); $m++):
											if($intestazioni[$m] != ""):
												$val = $dettagli_corso[$c][$m];
	$m_tab .=									'<td>'.$val.'</td>';
											endif;
										endfor;
	$m_tab .= 						'</tr>';										
									endfor;
	$m_tab .= 			   '	</tbody>
							</table>
						</div>
						<div class="modal-footer no-margin-top hidden-print">
							<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
								<i class="ace-icon fa fa-times"></i>
								Chiudi
							</button>
					   </div>
					</div>
				</div>
			</div>';
			
	return $m_tab;
}

function modal_confirm($id, $msg, $bt1, $bt2){
	$m_tab = '<div id="confirm'.$id.'" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header no-padding">
							<div class="table-header">ATTENZIONE!</div>
						</div>';
	$m_tab .= 			'<div class="page-header" align="center">
							<h1>'.$msg.'</h1>
						</div>';
	$m_tab .=			'<div class="modal-footer no-margin-top" align="center">';
							if(isset($bt1[3])):
	$m_tab .=					'<button  onClick="javascript='.$bt1[2].'" class="btn btn-sm btn-'.$bt1[1].'" >';
							else:
	$m_tab .=					'<button  onClick="parent.location=\''.$bt1[2].'\'" class="btn btn-sm btn-'.$bt1[1].'" >';
							endif;
	$m_tab .=				$bt1[0];
	$m_tab .=			'	</button>
							<button class="btn btn-sm btn-'.$bt2[1].'" data-dismiss="modal">
								'.$bt2[0].'
							</button>							
					     </div>
					</div>
				</div>
			</div>';
			
	return $m_tab;
}


function modal_tab_confirm($id, $id_table, $titolo, $intestazioni, $bt1, $bt2){
		$box = '';
		$box = '<div id="'.$id.'" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
					<div class="modal-dialog" style="width:95% !important;">
						<div class="modal-content">
							<div class="modal-header no-padding">
								<div class="table-header">
									'.$titolo.'
								</div>
							</div>
							<div class="modal-body no-padding">
								<table id="'.$id_table.'" class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
									<thead>
										<tr>
											 <th class="center">
											 	<label class="position-relative">
											 		<input type="checkbox" id="'.$id.'_selectall" class="ace" />
													<span class="lbl"></span>
												</label>
											 </th>';
										for($m=0; $m<count($intestazioni); $m++):
		$box .=								'<th>'.$intestazioni[$m][0].'</th>';
										endfor;
		$box .= 						'</tr>
									</thead>								
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											 <th style="width:5% !important;"><input type="checkbox" id="'.$id.'_selectall" /></th>';
									for($m=0; $m<count($intestazioni); $m++):
		$box .=								'<th style="width:'.$intestazioni[$m][2].'% !important;">'.$intestazioni[$m][1].'</th>';
									endfor;
		$box .=						   '</tr>
									</tfoot>	
								</table>
							</div>
							<div class="modal-footer no-margin-top" align="center">
								<button  id="'.$bt1[0].'" onClick="parent.location=\''.$bt1[3].'\'" class="btn btn-sm btn-'.$bt1[2].'" >
									'.$bt1[1].'
								</button>
								<button id="'.$bt2[0].'" onClick="parent.location=\''.$bt2[3].'\'" class="btn btn-sm btn-'.$bt2[2].'" >
									'.$bt2[1].'
								</button>							
							 </div>
						</div>
					</div>
				</div>';
			
	return $box;
}

function modal_input_data($id, $titolo, $confirm_btn, $labels,$inputs){
	
	$modal = '<div id="modal_'.$id.'" class="modal fade" tabindex="-1">
			  <div class="modal-dialog">
			  <div class="modal-content">
			  <div class="modal-header no-padding">
			  <div class="table-header">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			  <span class="white">&times;</span>
			  </button>'.$titolo.'</div></div>';
	$modal .='<div class="modal-body no-padding" align="rigth">';
	$modal .='<table id="search-table" class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
			  <thead>';
	for($m=0; $m<count($labels); $m++):
			$modal .='<tr><td>';
			$modal .='<div class="form-group">
			          <label class="col-lg-6 col-md-6 control-label no-padding-right" >'.$labels[$m].'</label>
                      <div class="col-sm-4">'.$inputs[$m].'</div></div>';
			$modal .='</td></tr>';
	endfor;
	$modal .='</thead><tbody>
			  </tbody><tfoot>
			  </tfoot></tbody></table>';
	$modal .='<div class="modal-footer no-margin-top" align="center">
			  <button  id="'.$confirm_btn[0].'" class="btn btn-sm btn-success" >
						'.$confirm_btn[1].'
			  </button>
			  <button class="btn btn-sm btn-danger" data-dismiss="modal">
						ANNULLA
			  </button>								
			  </div>
			  </div>
		      </div>
	          </div></div>';
	
	return $modal;
}





?>

