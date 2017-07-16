<div id = 'barra_laterale'>
	<ul>
		<a href="<?php echo __LINK_SITO__ ?>" alt="Homepage"><li>HOMEPAGE</li></a>
		<?php if(!isset($_SESSION['logged'])){?>
			<a href="<?php echo __LINK_SITO__ ?>login.php"><li>LOGIN</a></li>
			<a href="<?php echo __LINK_SITO__ ?>registrati.php"><li>REGISTRATI</a></li>
		<?php } else {?>
			<a href="<?php echo __LINK_SITO__ ?>logout.php"><li>LOGOUT</li></a>
		<?php  } ?>
	</ul>
</div>