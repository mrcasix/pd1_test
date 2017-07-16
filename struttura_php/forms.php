<?php
require_once("../build/p_lib/dbconnect.php");
require_once("../build/p_lib/query.php");
require_once("../build/p_lib/query_scorm.php");
require_once("../build/p_lib/functions.php");
session_start();
require_once("../build/p_lib/conf.php");

$op = $_GET["op"];
$function = dispatch($op);	

function add_fornitore() {
	$form_js = generaform(array(
                          array("edit", ""),
						  array("nome","Fornitore",1,"text",10,"",740),
                          ));
	echo $form_js;
}
//////////////// form test per edizioni
function new_test(){
	$id_test = $_POST["id_test_b"];
	$id_edizione = $_POST["id_ed_b"];
	$punteggio_tipo_list = array(array("Percentuale", 0), array("Punti", 1));
	$marcatori = array(array("No", 0), array("Sì", 1));
	$soglia = $GLOBALS["soglia_test"];
	$nomi_test = array(array("Apprendimento", "Apprendimento"), array("Efficacia", "Efficacia"));
	
	if($id_test>0):
		//$campi = array("titolo","punteggio_min","punteggio_tipo","numero_tentativi","marcatore");
		$info_test = get_test_edition($id_edizione, $id_test);
		$lista = merge_sub($punteggio_tipo_list);
		$tipo_sel = $lista[$info_test[0][3]];
		$lista_m = merge_sub($marcatori);
		$marcatore_sel = $lista_m[$info_test[0][5]];
	else:
		$info_test = array(array("","",0,0,0,0));
		$tipo_sel = "Percentuale";
		$marcatore_sel = "No";
	endif;
	$form_js = generaform(array(
                          array("edit", "javascript:salva_form();"),
						  array("id_test","idtest",1,"hidden",10,$id_test,740),
						  //array("titolo","Titolo",1,"list",10,array($info_test[0][1], $nomi_test),740,"onchange=calcola_soglia()",""),
						  array("titolo","Titolo",1,"text",10,$info_test[0][1],740,"",""),
						  //array("punteggio_min","Soglia superamento",1,"number",4,$info_test[0][2],740),
						  array("punteggio_min","Soglia superamento (%)<br />(Imposta a 0 per gradimenti e sondaggi)",1,"text",2,$soglia,740,"",""),
						  array("punteggio_tipo","",1,"hidden",4,0,500),
						  array("marcatore","Marcatore di fine corso",1,"list",4,array($marcatore_sel, $marcatori),500),
						  //array("punteggio_tipo","Calcola in",1,"list",4,array($tipo_sel, $punteggio_tipo_list),500),
						  array("numero_tentativi","Numero di tentativi (0=Infiniti)",0,"number",4,$info_test[0][4],740),
						  array("numero_tentativi","",1,"hidden",4,0,740),
                          ));
		
	$form_js .= "</form>";
	echo $form_js;
}

function new_question(){
	$id_domanda = $_POST["id_question"];
	$id_test = $_POST["id_test"];
	if($id_domanda>0):
		$info_domanda = get_test_edition_question($id_test, $id_domanda);
		$domanda = $info_domanda[0][1];
	else:
		$domanda = "";
	endif;
	
	$form_js = generaform(array(
                          array("edit", ""),
						  array("domanda","Domanda",1,"text",10,$domanda,740)
                          ));
	echo $form_js;
}

function new_answer(){
	$id_answer = $_POST["id_answer"];
	$id_question = $_POST["id_question"];
	$corretta_menu = array(array("No", 0), array("Sì", 1));
	$lista = merge_sub($corretta_menu);

	if($id_answer>0):
		$info_risposta = get_test_edition_question_answer($id_question, $id_answer);
		$corretta = $lista[$info_risposta[0][2]];
		$risposta = $info_risposta[0][1];
	else:
		$corretta = "No";
		$risposta = "";
	endif;
	
	$form_js = generaform(array(
                          array("edit", ""),
						  array("risposta","Risposta",1,"text",10,$risposta,740),
						  array("corretta","Corretta",1,"list",4,array($corretta, $corretta_menu),500),						  
                          ));

	echo $form_js;
}

//////////////// form test per edizioni
function new_test_course(){
	$id_test = $_POST["id_test"];
	$id_course = $_POST["id_course"];
	$punteggio_tipo_list = array(array("Percentuale", 0), array("Punti", 1));
	$marcatori = array(array("No", 0), array("Sì", 1));
	$nomi_test = array(array("Apprendimento", "Apprendimento"), array("Efficacia", "Efficacia"));
	$soglia = $GLOBALS["soglia_test"];
	if($id_test>0):
		//$campi = array("titolo","punteggio_min","punteggio_tipo","numero_tentativi","marcatore");
		$info_test = get_test_course($id_course, $id_test);
		$lista = merge_sub($punteggio_tipo_list);
		$tipo_sel = $lista[$info_test[0][3]];
		$lista_m = merge_sub($marcatori);
		$marcatore_sel = $lista_m[$info_test[0][5]];
	else:
		$info_test = array(array("","",0,0,0,0));
		$tipo_sel = "Percentuale";
		$marcatore_sel = "No";
	endif;
	$form_js = generaform(array(
                          array("edit", "javascript:salva_form();"),
						  array("id_test","idtest",1,"hidden",10,$id_test,740),
						  //array("titolo","Titolo",1,"list",10,array($info_test[0][1], $nomi_test),740,"onchange=calcola_soglia()",""),
						  array("titolo","Titolo",1,"text",10,$info_test[0][1],740,"",""),
						  //array("punteggio_min","Soglia superamento",1,"number",4,$info_test[0][2],740),
						  array("punteggio_min","Soglia superamento (%)<br />(Imposta a 0 per gradimenti e sondaggi)",1,"text",2,$soglia,740,"",""),
						  array("punteggio_tipo","",1,"hidden",4,0,500),
						  array("marcatore","Marcatore di fine corso",1,"list",4,array($marcatore_sel, $marcatori),500),
						  //array("punteggio_tipo","Calcola in",1,"list",4,array($tipo_sel, $punteggio_tipo_list),500),
						  //array("numero_tentativi","Numero di tentativi (0=Infiniti)",1,"number",4,$info_test[0][4],740),
						  array("numero_tentativi","",1,"hidden",4,0,740),
                          ));
	
	$form_js .= "</form>";
	echo $form_js;
}

function new_question_course(){
	$id_domanda = $_POST["id_question"];
	$id_test = $_POST["id_test"];
	if($id_domanda>0):
		$info_domanda = get_test_course_question($id_test, $id_domanda);
		$domanda = $info_domanda[0][1];
	else:
		$domanda = "";
	endif;
	
	$form_js = generaform(array(
                          array("edit", ""),
						  array("domanda","Domanda",1,"text",10,$domanda,740)
                          ));
	echo $form_js;
}

function new_answer_course(){
	$id_answer = $_POST["id_answer"];
	$id_question = $_POST["id_question"];
	$corretta_menu = array(array("No", 0), array("Sì", 1));
	$lista = merge_sub($corretta_menu);

	if($id_answer>0):
		$info_risposta = get_test_course_question_answer($id_question, $id_answer);
		$corretta = $lista[$info_risposta[0][2]];
		$risposta = $info_risposta[0][1];
	else:
		$corretta = "No";
		$risposta = "";
	endif;
	
	$form_js = generaform(array(
                          array("edit", ""),
						  array("risposta","Risposta",1,"text",10,$risposta,740),
						  array("corretta","Corretta",1,"list",4,array($corretta, $corretta_menu),500),						  
                          ));

	echo $form_js;
}
//////////////////////////////////////////////////////////////////////
function test_list(){
	$id_corso = $_POST["id_corso"];
	$elenco_test = elenco_test_course($id_corso);
	$form_js = generaform(array(
                          array("edit", ""),
						  array("id_test","Lista dei Test",1,"list",8,array("Seleziona", $elenco_test),500),						  
                          ));
						  
	echo $form_js;
}

function test_standard_list(){
	$elenco_test = elenco_test_standard();
	$form_js = generaform(array(
                          array("edit", ""),
						  array("id_test","",1,"list",8,array("Seleziona", $elenco_test),500),						  
                          ));
						  
	echo $form_js;
}


function merge_sub($arr){
	$arr_merged = array();
	for($a=0; $a<count($arr); $a++):
		$arr_merged[$arr[$a][1]] = $arr[$a][0];
	endfor;
	return $arr_merged;
}

function certificate(){
	require('../build/p_lib/pdf/html2pdf.class.php');

	
	/*
	$info_cert = get_info_certificate($_POST);
	$cert = '';
	$cert .= '<div id="certificate" style="background-image:  url(../assets/img_p/attestato_consulman.jpg); background-color: #fff !important; background-color: #ccc !important; width:595px; height:842px;">';
	$cert .= 'ciao ciao ciao';
	$cert .= '</div>';
	*/
	
	ob_start();
    include('../build/p_lib/pdf/examples/exemple04.php');
    $content = ob_get_clean();

    // convert to PDF
    require_once('../build/p_lib/pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('exemple04.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}


function scorm_list(){
	$id_corso = $_REQUEST["id_corso"];
	$id_edizione = $_REQUEST["id_edizione"];
	$elenco_scorm = elenco_scorm_tendina($id_corso, $id_edizione);
	$form_js = generaform(array(
                          array("edit", ""),
						  array("id_package","Lista degli scorm",1,"list",8,array("Seleziona", $elenco_scorm),500),						  
                          ));
						  
	echo $form_js;
}

function prerequisites_list(){
	$id_object = $_REQUEST["id_object"];
	$id_course = $_REQUEST["id_corso"];
	$id_edizione = $_REQUEST["id_edizione"];
	$type = $_REQUEST["type"];

	$scorm_packages = get_scorm_packages($id_edizione);
	$tmp_course_attachments = get_course_files($id_course);
	$edition_attachments = get_editions_files($id_edizione);
	$course_attachments = array_merge($tmp_course_attachments, $edition_attachments);	
	$elenco_test = get_test_edition($id_edizione, 0);
	$prerequisiti_oggetto = prereq_list($id_edizione, $id_object, $type);
	$tables = '<div class="row">
                   	<div class="col-xs-12 col-sm-12 widget-container-col">
                            <div class="widget-box widget-color-blue">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter">
                                        <i class="ace-icon fa fa-desktop"></i>Video lezioni
                  					</h5>
                            	</div>
								<div class="widget-body">
									<div class="widget-main no-padding">
										<table class="table table-striped table-bordered">
											<tr>
												<td width="90%"><b>Scorm</b></td>
												<td><b>*</b></td>
											</tr>';
										for($s=0; $s<count($scorm_packages); $s++):
											$id_scorm_packages = $scorm_packages[$s][0];
											$scorm_title =  $scorm_packages[$s][1];
											$prereq_scorm = $prerequisiti_oggetto["scorm"];
											if(in_array($id_scorm_packages, $prereq_scorm)) $status = "checked";
											else $status = "";
	$tables .= '
											<tr>
												<td width="90%">'.$scorm_title.'</td>
												<td width="10%">';
												if($id_scorm_packages!=$id_object):
	$tables .= '									<input type="checkbox" '.$status.' class="ace save_pre" name="prereq" id_object='.$id_object.' type_object="'.$type.'" id_object_p="'.$id_scorm_packages.'" type_object_p="scorm"><span class="lbl"></span>';
												endif;
	$tables .= '								</td>
											</tr>';
											
											 
										endfor;
										
	$tables .= '						</table>
									</div>
								</div>
							</div>
					</div>
				</div>
				<div class="col-sm-1 col-xs-12"><span></span></div> ';
				
	$tables .= '<div class="row">
                   	<div class="col-xs-12 col-sm-12 widget-container-col">
                            <div class="widget-box widget-color-pink">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter">
                                        <i class="ace-icon fa fa-pencil-square-o"></i>
                                        Test
                                    </h5>
	                            </div>
                            	<div class="widget-body">
									<div class="widget-main no-padding">
										<table class="table table-striped table-bordered">
											<tr>
												<td width="90%"><b>Test</b></td>
												<td><b>*</b></td>
											</tr>';
										for($s=0; $s<count($elenco_test); $s++):
											$id_test = $elenco_test[$s][0];
											$test_title =  $elenco_test[$s][1];
											$prereq_test = $prerequisiti_oggetto["test"];
											if(in_array($id_test, $prereq_test)) $status = "checked";
											else $status = "";
	$tables .= '
											<tr>
												<td width="90%">'.$test_title.'</td>
												<td width="10%">';
												if($id_test!=$id_object):
	$tables .= '									<input type="checkbox" '.$status.' class="ace save_pre" name="prereq" id_object='.$id_object.' type_object="'.$type.'"  id_object_p="'.$id_test.'" type_object_p="test"><span class="lbl"></span>';
												endif;
	$tables .= '								</td>
											</tr>';
											
											 
										endfor;											
	$tables .= '						</table>
									</div>
								</div>
							</div>
					</div>
				</div>
				<div class="col-sm-1 col-xs-12"><span></span></div> ';	
							
	$tables .= '<div class="row">
                   	<div class="col-xs-12 col-sm-12 widget-container-col">
                            <div class="widget-box widget-color-green2">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter">
                                        <i class="ace-icon fa fa-paperclip"></i>
                                        Allegati
                                    </h5>
	                            </div>
								<div class="widget-body">
									<div class="widget-main no-padding">
										<table class="table table-striped table-bordered">
											<tr>
												<td width="90%"><b>Allegato</b></td>
												<td><b>*</b></td>
											</tr>';
										for($s=0; $s<count($course_attachments); $s++):
											$id_attach = $course_attachments[$s][0];
											$nome_file_full = $course_attachments[$s][1];
											$source_attach =  $course_attachments[$s][2];
											$pos = strrpos($nome_file_full, "@");
											$attach_title = substr($nome_file_full, $pos+1, 1000);
											$prereq_attach = $prerequisiti_oggetto["attach_".$source_attach];
											if(in_array($id_attach, $prereq_attach)) $status = "checked";
											else $status = "";
	$tables .= '
											<tr>
												<td width="90%">'.$attach_title.'</td>
												<td width="10%">';
												if($id_attach!=$id_object):
	$tables .= '									<input type="checkbox" '.$status.' class="ace save_pre" name="prereq" id_object='.$id_object.' type_object="'.$type.'" id_object_p="'.$id_attach.'" type_object_p="attach_'.$source_attach.'"><span class="lbl"></span>';
												endif;
	$tables .= '								</td>
											</tr>';
											 
										endfor;													
	$tables .= '						</table>
									</div>
								</div>
							</div>
					</div>
				</div>';
	
						  
	echo $tables;
}


function dispatch($op){
	switch ($op){
	  case 'new_test':
		  new_test();
	  break;
	  case 'new_question':
		  new_question();
	  break;	  
	  case 'new_answer':
		  new_answer();
	  break;
	  //// form per test corso
	  case 'new_test_course':
		  new_test_course();
	  break;
	  case 'new_question_course':
		  new_question_course();
	  break;	  
	  case 'new_answer_course':
		  new_answer_course();
	  break;
	  case 'new_answer_course':
		  new_answer_course();
	  break;	  
	  case 'add_fornitore':
		  add_fornitore();
	  break;	  

	  /////////////////////////
	  case 'test_list':
		  test_list();
	  break;
	  case 'test_standard_list':
		  test_standard_list();
	  break;
	  case 'certificate':
		  certificate();
	  break;
	  case 'scorm_list':
		  scorm_list();
	  break;
	  case 'prerequisites_list':
		  prerequisites_list();
	  break;
	    	  
  
	}
}
?>