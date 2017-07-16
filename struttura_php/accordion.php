<?php
/* codice da includere nelle pagine
include("includes/accordion.php");
$title_1 = "Tip: Clicca qui per avere qualche suggerimento";
$body_1 = "Contenuto del suggerimento";
$params = array(array($title_1,$body_1, 0)); /// il terzo parametro indica se è aperta o chiusa
crea_accordion($params);
*/
								
function crea_accordion($params){
	$accordion = '<div id="accordion" class="accordion-style1 panel-group no-margin">';
	for($aaa=0; $aaa<count($params); $aaa++):
		if($params[$aaa][2] == 0) $opened = "";
		else $opened = "in";
		$accordion .= '<div class="panel panel-default">
							<div class="panel-heading panel-success">
								<h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$aaa.'">
										<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>'
										.$params[$aaa][0].
									'</a>
								</h4>
							</div>
							<div class="panel-collapse collapse '.$opened.'" id="collapse'.$aaa.'">
								<div class="panel-body">'
									.$params[$aaa][1].
								'</div>
							</div>
						</div>';
	endfor;
	$accordion .= '</div>';
	echo $accordion;
}

?>