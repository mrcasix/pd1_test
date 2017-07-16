<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="alert alert-block alert-info"><b>Attendere, operazione in corso...</b></div>
			<div class="hr hr8 hr-dotted"></div>  
			<div class="row">
                <!-- inizio pagina -->
                <div class="col-sm-12">
                 	<div class="progress progress-striped active">
                    	
						<?php for($a=0; $a<101; $a++):
							echo '<div style="width:'.$a.'%" class="progress-bar progress-bar-success"></div>';
							sleep(0.5);

						endfor;
						?>
                    </div>
                <!-- fine pagina -->
				</div>
             </div><!-- /.row -->

		<div class="hr hr32 hr-dotted"></div>
		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->