Bienvenido a la aplicaci&oacute;n de reservas de <?php echo $club_name; ?>.

Te has registrado en la aplicación con este email y con la contraseña <b><?php print $clear_password; ?></b>. Se te ha asignado el codigo de usuario <b><?php echo $user_id; ?></b>.<br>&nbsp;<br>
En cualquier caso, antes de poder acceder a la aplicación debemos validar tu cuenta, para lo cual debes hacer click en el siguiente enlace: 
<a href="<?php print site_url('welcome/activate/'.$activation); ?>">Activaci&oacute;n</a>.<br><FONT SIZE="-2">Si el enlace anterior no funcionara, por favor, copie y pegue la siguiente direcci&oacute;n en la barra de direcciones del navegador: <?php echo site_url('welcome/activate/'.$activation); ?></FONT><br>&nbsp;<br>
Reiteramos nuestra bienvenida y esperamos que el uso de esta aplicaci&oacute;n te sea satisfactorio.

<br>&nbsp;<br>
Un saludo