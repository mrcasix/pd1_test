					
                            
							<?php
								
								
								
							?>

							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
                                  

								<div class="alert alert-block alert-success">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
									Ciao <?php echo $nome; ?>,<br />
									<strong class="green">
										Benvenuto in <?php echo $GLOBALS["nometool"]?>
									</strong>,
									<?php echo $GLOBALS["desc_home"] ?><br /><br />
                                    <strong><a href="">Clicca qui per scaricare il MANUALE UTENTE della piattaforma</a></strong>
								</div>
								<!-- #section:custom/extra.hr -->
                                <div class="row">
									<div class="col-sm-12">
										<div class="widget-box transparent">
											<div class="widget-header widget-header-flat">
												<h2 class="widget-title lighter">
													<!--<i class="ace-icon fa fa-graduation-cap orange"></i>-->
													Le tue autovalutazioni
												</h2>
											</div>
											<div class="widget-body">
												<div class="widget-main no-padding">
													<table class="table table-striped table-bordered">
														<thead class="thin-border-bottom">
															<tr>
																<th width="50%">Profilo di ruolo</th>
																<th width="15%">Data Assegnazione</th>
																<th width="15%">Data Completamento</th>
                                                                <th width="20%">Stato</th>
                                                            
														</thead>
														<tbody>
                                                        	<?php 
																$tb = '';
																$mie_autovalutazioni = autovalutazioni_enabled(0, $_SESSION['sito']);
																//$ids_profili = array_keys($mie_autovalutazioni);
																
																
																
																
																foreach($mie_autovalutazioni as $id_profilo=>$info_valutazioni):
																	$info_profilo = get_table('dom_profili_ruolo', 'profilo', 'id_dom_profili_ruolo='.$id_profilo);
																	
																	foreach($info_valutazioni[$_SESSION['sito']] as $index=>$info_valutazioni_ext):
																	
																	if($info_valutazioni_ext['deleted']==0):
																		$id_valutazione = $info_valutazioni_ext['id_valutazione'];
																		$risultato_valutazione = get_table('dom_profili_utenti_valutazioni dv,dom_profili_x_utenti du', 'dv.data,dv.completata,dv.data_fine,IFNULL((SELECT "Fatta" from dom_profili_utenti_valutazioni where id_dom_profili_utenti_valutazioni IN (select fk_id_dom_profili_utenti_valutazioni from dom_profili_utenti_valutazioni_valori)  and dom_profili_utenti_valutazioni.id_dom_profili_utenti_valutazioni=dv.id_dom_profili_utenti_valutazioni),"Non Fatta") as stato', 'du.deleted=0 and dv.deleted=0 and dv.fk_id_dom_profili_x_utenti=du.id_dom_profili_x_utenti and dv.id_dom_profili_utenti_valutazioni='.$id_valutazione); // tab, order eg array(array("data_validita", "desc")), where 
																		$data_assegnazione = date('d-m-Y', strtotime($risultato_valutazione[0]["data"]));
																		if($risultato_valutazione[0]["completata"]=="1"):
																			$data_fine = date('d-m-Y', strtotime($risultato_valutazione[0]["data_fine"]));
																			$status_label = 'Valutazione completata';
																			$status_color = 'success';
																		elseif($risultato_valutazione[0]["stato"]=="Fatta"):
																			$data_fine = "";
																			$status_label = 'Valutazione in corso';
																			$status_color = 'warning';
																		else:
																		
																			$data_fine = '';
																			$status_label = 'Inizia autovalutazione';
																			$status_color = 'info';
																		endif;
																		$tb .= '<tr>';
																		$tb .= '<td>'.$info_profilo[0]['profilo'].'</td>';
																		$tb .= '<td>'.$data_assegnazione.'</td>';
																		$tb .= '<td>'.$data_fine.'</td>';
																		$tb .= '<td><a href=\'index.php?page=valuta&t=a&idp='.base64_encode($id_profilo).'&idval='.base64_encode($id_valutazione).'\'><span class="label label-'.$status_color.' arrowed-in arrowed-in-right block">'.$status_label.'</span></a></td>';
																		
																		
																		$tb .= '</tr>';
																	endif;
																endforeach;
																endforeach;
																echo $tb;
																
																
																
																
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div><!-- /.widget-box -->
									</div><!-- /.col -->
								</div><!-- /.row -->                                
								<div class="hr hr32 hr-dotted"></div>
                                <div class="row">
									<div class="col-sm-12">
										<div class="widget-box transparent">
											<div class="widget-header widget-header-flat">
												<h2 class="widget-title lighter">
													<!--<i class="ace-icon fa fa-graduation-cap orange"></i>-->
													I tuoi inviti a valutare un collaboratore
												</h2>
											</div>
											<div class="widget-body">
												<div class="widget-main no-padding">
													<table class="table table-striped table-bordered">
														<thead class="thin-border-bottom">
															<tr>
																<th width="30%">Nome</th>
                                                                <th width="40%">Profilo di ruolo</th>
																<th width="10%">Data Assegnazione</th>
																<th width="10%">Data Completamento</th>
                                                                <th width="10%">Stato</th>
																<th width="10%">Gestione</th>
														</thead>
														<tbody>
                                                        	<?php 
																$tb = '';
																$mie_eterovalutazioni = myEterovalutazioni(0, $_SESSION['sito']);
																//$ids_profili = array_keys($mie_autovalutazioni);
																foreach($mie_eterovalutazioni as $id_profilo=>$info_valutazioni):
																	$info_profilo = get_table('dom_profili_ruolo', 'profilo', 'id_dom_profili_ruolo='.$id_profilo);
																	foreach($info_valutazioni as $idst_valutare=>$info_idst_valutare):
																		if($info_idst_valutare[0]['deleted']==0):
																			$info_people = get_table('anagrafica', 'cognome, nome', 'idst='.$idst_valutare);
																			$nome = $info_people[0]['cognome'].' '.$info_people[0]['nome'];
																			$id_valutazione = $info_idst_valutare[0]['id_valutazione'];
																			
																			$risultato_valutazione = get_table('dom_profili_utenti_valutazioni dv,dom_profili_x_utenti du', 'dv.data,dv.completata,dv.data_fine,IFNULL((SELECT "Fatta" from dom_profili_utenti_valutazioni where id_dom_profili_utenti_valutazioni IN (select fk_id_dom_profili_utenti_valutazioni from dom_profili_utenti_valutazioni_valori)  and dom_profili_utenti_valutazioni.id_dom_profili_utenti_valutazioni=dv.id_dom_profili_utenti_valutazioni),"Non Fatta") as stato', 'du.deleted=0 and dv.deleted=0 and dv.fk_id_dom_profili_x_utenti=du.id_dom_profili_x_utenti and dv.id_dom_profili_utenti_valutazioni='.$id_valutazione); // tab, order eg array(array("data_validita", "desc")), where 
																			$data_assegnazione = date('d-m-Y', strtotime($risultato_valutazione[0]["data"]));
																		
																			
																			if($risultato_valutazione[0]["completata"]=="1"):
																				$data_fine = date('d-m-Y H:i:s', strtotime($risultato_valutazione[0]["data_fine"]));
																				$status_label = 'Valutazione completata';
																				$status_color = 'success';
																			elseif($risultato_valutazione[0]["stato"]=="Fatta"):
																				$data_fine = "";
																				$status_label = 'Valutazione in corso';
																				$status_color = 'warning';
																			else:
																				$data_fine = '';
																				$status_label = 'Inizia eterovalutazione';
																				$status_color = 'info';
																			endif;
																			$tb .= '<tr>';
																			$tb .= '<td>'.$nome.'</td>';
																			$tb .= '<td>'.$info_profilo[0]['profilo'].'</td>';
																			$tb .= '<td>'.$data_assegnazione.'</td>';
																			$tb .= '<td>'.$data_fine.'</td>';
																			$tb .= '<td><a href=\'index.php?page=valuta&t=e&idp='.base64_encode($id_profilo).'&idst='.base64_encode($idst_valutare).'&idval='.base64_encode($id_valutazione).'\'><span class="label label-'.$status_color.' arrowed-in arrowed-in-right block">'.$status_label.'</span></a></td>';
																			
																			if($risultato_valutazione[0]["completata"]=="1")
																			$tb .= '<td><a href=\'index.php?page=view-profile&v=s&id='.base64_encode($id_profilo).'&idval='.base64_encode($id_valutazione).'\'>Mostra Grafico</a></td>';
																			else 
																			$tb .= '<td></td>';
																			
																			$tb .= '</tr>';
																		endif;
																	endforeach;
																endforeach;
																echo $tb;
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div><!-- /.widget-box -->
									</div><!-- /.col -->
								</div><!-- /.row -->  
								<!-- /section:custom/extra.hr -->
								
							</div><!-- /.col -->
                            
   