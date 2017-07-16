<?php

function dispatch($page){
		switch ($page):
		  case 'home':
		  	  $_SESSION["active"] = 0;
			  $loadpage = "pages/home.php";
		  break;
		  		  
		  case 'processi':
			  $_SESSION["active"] = 1; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/processi.php";
		  break;

		  case 'sottoprocessi':
			
				$_SESSION["active"] = 1; 
			
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/sottoprocessi.php";
		  break; 
		  
		 
		  
	  
		  case 'f_tree':
			  if(isset($_REQUEST["id"])) {
				$_SESSION["active"] = 1; 
				$loadpage = "pages/fasi_albero.php";
			  }
			  else {
				$_SESSION["active"] = 13;
				$loadpage = "pages/fasi.php";
			  }
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  
		  break;	

		 case 'attivita':
			
				$_SESSION["active"] = 14; 
			
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/attivita.php";
		  break;

		  

		  case 'competence':
			  $_SESSION["active"] = 2; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/competenze_tipo.php";
		  break;

		  case 'competence-tree':
			  $_SESSION["active"] = 2; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/competenze_albero.php";
		  break;	
		  
		  case 'view-profile':
			  $_SESSION["active"] = 3; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/profilo_scheda.php";
		  break;			  	  

		  case 'build-profile':
			  $_SESSION["active"] = 3; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/profili_costruisci.php";
		  break;			  	  

		  case 'people-profile':
			  $_SESSION["active"] = 3; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/profili_persone.php";
		  break;
		    case 'people-profile-detail':
			  $_SESSION["active"] = 3; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/profili_persone_dettaglio.php";
		  break;
		  
		  case 'ask-valutations':
			  $_SESSION["active"] = 3; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/profili_richiedi_val.php";
		  break;
		  
		  case 'valutatori':
			  $_SESSION["active"] = 3; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/profili_invita_valutatori.php";
		  break;
		  
		  case 'valuta':
			  $_SESSION["active"] = 3; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/profili_valuta.php";
		  break;			  	  	
		  
		  case 'role-profile':
			  $_SESSION["active"] = 3; 
			  //if ($_SESSION["is_admin"][0][0] == 0) $loadpage = "pages/home.php";
			  //else
			  $loadpage = "pages/profili.php";
		  break;
		  
		  case 'gest-attivita-profilo':
			  $_SESSION["active"] = 3; 
			  $loadpage = "pages/gest_attivita_profilo.php";
		  break;
	    			
		  case 'repo':
  			  $_SESSION["active"] = 7;
			  $loadpage = "pages/report.php";
		  break;
		  
		  case 'valutation':
  			  $_SESSION["active"] = 11;
			  $loadpage = "pages/valutation.php";
		  break;
		  
		  /**/
		  case 'people':
  			  $_SESSION["active"] = 9;
			  //if($_SESSION["is_admin"][6][0]==0) $loadpage = "../public/pages/home.php";
			  $loadpage = "pages/anagr.php";
		  break;  
		  
		  case 'tools':
  			  $_SESSION["active"] = 10;
			
			  //if($_SESSION["is_admin"][6][0]==0) $loadpage = "../public/pages/home.php";
			  $loadpage = "../public/pages/tools.php";
		  break;

		  case 'edit_people':
  			  $_SESSION["active"] = 9;
			  //if($_SESSION["is_admin"][6][0]==0) $loadpage = "../public/pages/home.php";
			  $loadpage = "pages/edit_p.php";
		  break;

		  case '404':
  			  $_SESSION["active"] = 12;
			  $loadpage = "pages/404.php";
		  break;	
		  
		  default:
  			  $_SESSION["active"] = 12;
			  $loadpage = "pages/404.php";
		  break;			  	

		endswitch;
		
		return $loadpage;
}

?>