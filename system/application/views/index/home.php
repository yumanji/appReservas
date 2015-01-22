   <noscript>This site just doesn't work, period, without JavaScript</noscript>


		 <!-- 					<center><a href="<?php echo site_url('reservas'); ?>"><img border="0" src="<?php echo base_url(); ?>images/web_mod_1024_03.gif"  alt=""></a></center>

  IF LOGGED IN -->

          <!-- Content here -->

   <!-- IF LOGGED OUT -->
   <?php
   
   $data = array(
    'name' => 'button1',
    'id' => 'create-user',
    'value' => 'true',
    'type' => 'button'
		);
   $data2 = array(
    'name' => 'button2',
    'id' => 'login-user',
    'value' => 'true',
    'type' => 'button'
		);

		# link general de la página para ir a reservas
		$home_link = ''; $home_link_end = '';
		if($this->config->item('anonymous_enabled')) { $home_link = '<a href="'.site_url('reservas').'">'; $home_link_end = '</a>'; }

   ?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
    	<?php echo $home_link; ?><img border="0" src="<?php echo base_url(); ?>images/web_mod_1024_03.gif"  alt=""><?php echo $home_link_end; ?>
    </td>
    <td>
    	<ul class="index_button_bar">
				<?php if($this->config->item('anonymous_enabled')) { ?><li class="index_button" ><table border="0"><tr><td><span class="index_bigbutton">Quiero reservar YA!</span><br>No quiero registrarme pero quiero reservar una pista.</td><td><a href="<?php echo site_url('reservas');?>"><?php echo img( array('src'=>'images/anonymous_user.png', 'border'=>'0', 'alt' => 'Acceso anonimo al sistema'));?></a></td></tr></table></li><?php } ?>
				<?php if($this->config->item('public_register_user')) { ?><li class="index_button" ><table border="0"><tr><td><span class="index_bigbutton">Quiero registrarme!</span><br>Y disfrutar de todas las ventajas que ofrece el servicio.</td><td><?php echo form_button($data);?></td></tr></table></li><?php } ?>
				<?php if($this->config->item('public_login_user')) { ?><li class="index_button" ><table border="0"><tr><td><span class="index_bigbutton">Ya soy usuario!</span><br>Y quiero acceder para disfrutar de mis condiciones especiales.</td><td><?php echo form_button($data2);?></td></tr></table><a href="#" id="remember_button">Olvid&eacute; mi contrase&ntilde;a.</a></li><?php } ?>
    	</ul>
    </td>
  </tr>
</table>


<?php if($this->config->item('public_register_user')) { ?>
<script type="text/javascript">
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$("#dialog").dialog("destroy");
		
		var name = $("#name"), firstname = $("#first_name"), lastname = $("#last_name"),
			email = $("#email"),
			email2 = $("#email2"),
			passw = $("#passw"),
			passw2 = $("#passw2"),
			phone = $("#phone"),
			allFields = $([]).add(firstname).add(lastname).add(email).add(email2).add(passw).add(passw2).add(phone),
			tips = $(".validateTips");

		function updateTips(t) {
			tips
				.text(t)
				.addClass('ui-state-highlight');
			setTimeout(function() {
				tips.removeClass('ui-state-highlight', 1500);
			}, 500);
		}

		function checkLength(o,n,min,max) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass('ui-state-error');
				updateTips("La longitud de " + n + " debe estar entre "+min+" y "+max+".");
				return false;
			} else {
				return true;
			}

		}

		function checkRegexp(o,regexp,n) {

			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass('ui-state-error');
				updateTips(n);
				return false;
			} else {
				return true;
			}

		}
		
		$("#register-form").dialog({
			autoOpen: false,
			height: 420,
			width: 350,
			modal: true,
			buttons: {
				'<?php echo $this->lang->line('signin'); ?>': function() {
					var bValid = true;
					allFields.removeClass('ui-state-error');

					//bValid = bValid && checkLength(name,"Apodo",3,40);
					bValid = bValid && checkLength(firstname,"Nombre",3,40);
					bValid = bValid && checkLength(lastname,"Apellido",3,60);
					bValid = bValid && checkLength(phone,"Telefono",9,15);
					bValid = bValid && checkLength(email,"Email",6,80);
					bValid = bValid && checkLength(passw,"Password",5,16);
					bValid = bValid && checkLength(email2,"Email 2",6,80);
					bValid = bValid && checkLength(passw2,"Password 2",5,16);

					bValid = bValid && checkRegexp(firstname,/^[a-z]([0-9a-z_\s])+$/i,"El nombre debe ser una secuencia de a-z, 0-9, empezando con una letra.");
					bValid = bValid && checkRegexp(lastname,/^[a-z]([0-9a-z_\s])+$/i,"El apellido debe ser una secuencia de  a-z, 0-9, empezando con una letra.");
					bValid = bValid && checkRegexp(phone,/^([0-9])+$/i,"El telefono debe ser num&eacute;rico.");
					//bValid = bValid && checkRegexp(name,/^[a-z]([0-9a-z_])+$/i,"Lastname may consist of a-z, 0-9, underscores, begin with a letter.");
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp(email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"eg. ui@jquery.com");
					bValid = bValid && checkRegexp(passw,/^([0-9a-zA-Z])+$/,"El password solo puede contener : a-z 0-9");
					bValid = bValid && checkRegexp(email2,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"eg. ui@jquery.com");
					bValid = bValid && checkRegexp(passw2,/^([0-9a-zA-Z])+$/,"El password solo puede contener : a-z 0-9");
					if(email.val() != email2.val()) {
						bValid = false;
						alert('Ambos campos de email deben coincidir');
					}
					if(passw.val() != passw2.val()) {
						bValid = false;
						alert('Ambos campos de password deben coincidir');
					}
					if (bValid) {
						/*
						$('#users tbody').append('<tr>' +
							'<td>' + name.val() + '</td>' + 
							'<td>' + email.val() + '</td>' + 
							'<td>' + passw.val() + '</td>' +
							'</tr>'); */
							
							
						document.getElementById('frmRegister').submit();
						$(this).dialog('close');
					}
				},
				'<?php echo $this->lang->line('cancel'); ?>': function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		});
		
		
		
		$('#create-user')
			.button()
			.click(function() {
				$('#register-form').dialog('open');
			});

	});
	</script>

<div id="register-form" title="<?php echo $this->lang->line('register_form'); ?>">
	<p class="validateTips"><?php echo $this->lang->line('all_fields_mandatory'); ?></p>

<?php 
	$attributes = array('class' => 'ui-widget frmRegister', 'id' => 'frmRegister');
	echo form_open('welcome/register', $attributes); 
?>
	<fieldset>
		<ol>
			<li>
				<label for="first_name"><?php echo $this->lang->line('first_name'); ?></label>
				<input type="text" name="first_name" id="first_name" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="last_name"><?php echo $this->lang->line('last_name'); ?></label>
				<input type="text" name="last_name" id="last_name" class="text ui-widget-content ui-corner-all" />
			</li>
			<!--
			<li>
				<label for="name"><?php echo $this->lang->line('username'); ?></label>
				<input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all" />
			</li>
			-->
			<li>
				<label for="phone">Telefono</label>
				<input type="text" name="phone" id="phone" value="" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="email">Email</label>
				<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="email">Repetir Email</label>
				<input type="text" name="email2" id="email2" value="" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="password">Password</label>
				<input type="password" name="passw" id="passw" value="" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="password">Repetir Password</label>
				<input type="password" name="passw2" id="passw2" value="" class="text ui-widget-content ui-corner-all" />
			</li>
		</ol>
<?php if($this->config->item('users_register_legal_advice')) { ?>
	Si continua con el registro est&aacute; afirmando haber le&iacute;do y aceptado las <a target="_blank" href="<?php echo site_url('estatico/legal'); ?>">condiciones legales</a> y de <a target="_blank" href="<?php echo site_url('estatico/privacidad'); ?>">privacidad</a>.
<?php } ?>
	</fieldset>
<?php echo form_close(''); ?>
</div>

<?php } ?>




<?php if($this->config->item('public_login_user')) { ?>

	<script type="text/javascript">
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$("#dialog").dialog("destroy");
		
		var email = $("#login_email"), email2 = $("#email_remember"),
			code = $("#login_code"),
			passw = $("#login_passw"),
			allFields = $([]).add(email).add(email2).add(passw),
			tips = $(".validateTips");

		function updateTips(t) {
			tips
				.text(t)
				.addClass('ui-state-highlight');
			setTimeout(function() {
				tips.removeClass('ui-state-highlight', 1500);
			}, 500);
		}

		function checkLength(o,n,min,max) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass('ui-state-error');
				updateTips("Length of " + n + " must be between "+min+" and "+max+".");
				return false;
			} else {
				return true;
			}

		}

		function checkRegexp(o,regexp,n) {

			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass('ui-state-error');
				updateTips(n);
				return false;
			} else {
				return true;
			}

		}
		
		$("#dialog-form").dialog({
			autoOpen: false,
			height: 290,
			width: 330,
			modal: true,
			buttons: {
				'<?php echo $this->lang->line('access'); ?>': function() {
					var bValid = true;
					allFields.removeClass('ui-state-error');

					if($("#login_code").val() == "") bValid = bValid && checkLength(email,"email",6,80);
					if($("#login_email").val() == "") bValid = bValid && checkLength(code,"code",1,10);
					bValid = bValid && checkLength(passw,"passw",5,16);

					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					if($("#login_code").val() == "") bValid = bValid && checkRegexp(email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"eg. ui@jquery.com");
					bValid = bValid && checkRegexp(passw,/^([0-9a-zA-Z])+$/,"Password field only allow : a-z 0-9");
					
					if (bValid) {
						/*
						$('#users tbody').append('<tr>' +
							'<td>' + name.val() + '</td>' + 
							'<td>' + email.val() + '</td>' + 
							'<td>' + passw.val() + '</td>' +
							'</tr>'); */
							
							
						document.getElementById('frmAcceso').submit();
						$(this).dialog('close');
					}
				},
				'<?php echo $this->lang->line('cancel'); ?>': function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		});
		
		
		
		$("#remember-form").dialog({
			autoOpen: false,
			height: 290,
			width: 330,
			modal: true,
			buttons: {
				'Recuperar': function() {
					var bValid = true;
					allFields.removeClass('ui-state-error');

					bValid = bValid && checkLength(email2,"email",6,80);

					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp(email2,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"eg. ui@jquery.com");
					
					if (bValid) {
						/*
						$('#users tbody').append('<tr>' +
							'<td>' + name.val() + '</td>' + 
							'<td>' + email.val() + '</td>' + 
							'<td>' + passw.val() + '</td>' +
							'</tr>'); */
							
							
						$('#frmRemember').submit();
						$(this).dialog('close');
					}
				},
				'<?php echo $this->lang->line('cancel'); ?>': function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		});
		
		$('#remember_button')
			.button()
			.click(function() {
				$('#remember-form').dialog('open');
			});
			
			$('#login-user')
			.button()
			.click(function() {
				$('#dialog-form').dialog('open');
			});
					


	});
	</script>
<div id="dialog-form" title="<?php echo $this->lang->line('login_form'); ?>">
	<p class="validateTips"><?php echo $this->lang->line('two_fields_mandatory'); ?></p>

<?php 
	$attributes = array('class' => 'frmAcceso', 'id' => 'frmAcceso');
	echo form_open('welcome/login', $attributes); 
?>
	<fieldset>
		<ol>
			<li>
				<label for="email">Email:</label>
				<input type="text" name="login_email" id="login_email" size="15" value="" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="login_code">C&oacute;digo:</label>
				<input type="text" name="login_code" id="login_code" size="15"  value="" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="password">Password:</label>
				<input type="password" name="login_passw" id="login_passw" size="15" value="" class="text ui-widget-content ui-corner-all" />
			</li>
		</ol>
	</fieldset>
<?php echo form_close(''); ?>
</div>


<div id="remember-form" title="Recordatorio de password">
	<p class="validateTips"></p>

<?php 
	$attributes = array('class' => 'frmAcceso', 'id' => 'frmRemember');
	echo form_open('welcome/remember', $attributes); 
?>
	<fieldset>
		<ol>
			<li>
				<label for="codigo">C&oacute;digo:</label>
				<input type="text" name="codigo_remember" id="codigo_remember" size="15" value="" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="email">Email:</label>
				<input type="text" name="email_remember" id="email_remember" size="15" value="" class="text ui-widget-content ui-corner-all" />
			</li>
		</ol>
	</fieldset>
<?php echo form_close(''); ?>
</div>


<?php  } ?>