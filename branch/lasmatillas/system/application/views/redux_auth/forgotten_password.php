
		<h1>Solicitud de recuperacion de password</h1>
		<p>T&uacute; (<?php echo $identity; ?>) has solicitado un nuevo password para acceder a la aplicacion.</p>
		<p>Por favor, haz clic en el siguiente link e introduce el siguiente codigo de autenticacion cuando se te solicite.</p>
		<p>Codigo: <b><?php echo $forgotten_password_code; ?></b></p>
		<p><?php echo anchor('welcome/forgotten_password/', 'Haz clic aqui para obtener tu nuevo Password'); ?></p>
