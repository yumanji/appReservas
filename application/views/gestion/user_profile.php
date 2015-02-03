	<script>
	$(function() {
		$( "#tabs" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						'<div class="ui-widget">  <div class="ui-state-highlight ui-corner-all" > <p><?php echo img( array('src'=>'images/warning_48.png', 'border'=>'0', 'alt' => 'Warning', 'align'=>'absmiddle')); ?>La p&aacute;gina que intenta consultar se encuentra a&uacute;n en desarrollo. ' +
						'</p>  </div> </div>' );
				}
			}
		});
	});
	</script>

<div id="tabs" style="float: left; width: 770px">
	<ul>
		<li><a href="../../reservas_gest/list_all_by_user/<?php echo $array_user['user_id'];?>">Resumen</a></li>
		<li><a href="#tabs-1">Detalle</a></li>
		<li><a href="../../reservas_gest/list_all_by_user/<?php echo $array_user['user_id'];?>">Reservas</a></li>
		<li><a href="../../facturacion/list_all_by_user/<?php echo $array_user['user_id'];?>">Pagos</a></li>
		<li><a href="../aaaaa/lessons/<?php echo $array_user['user_id'];?>">Cursos</a></li>
	</ul>
	<div id="tabs-1">
		<div id="rowDetail" style="float: left;">
			<p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
			<?php 
				//$this->load->view('meta');
				//print("<pre>");print_r($this->session);echo "AA:".$this->session->userdata('user_id');
			?>
			<input type="hidden" id="id_user" name="id_user" value="<?php echo $array_user['user_id'];?>">
			<fieldset>
				<legend>Datos de usuario</legend>
				<label for="code">Codigo</label>
				<input type="text" name="code" id="code" disabled value="<?php echo $code_user;?>"  />
				<br>
				<label for="first_name">Nombre(*)</label>
				<input type="text" name="first_name" id="first_name" value="<?php echo $array_user['user_name'];?>"  />
				<br>
				<label for="last_name">Apellido(*)</label>
				<input type="text" name="last_name" id="last_name" value="<?php echo $array_user['user_lastname'];?>"  />
				<br>
				<!--  <label for="group_description">Nivel</label>
				<input type="text" name="group_description" id="group_description" value="<?php echo $array_user['group_description'];?>"  />
				-->
				<label for="group_id">Tipo</label>
				<select name="group_id" id="group_id">
				<?php 
				//pinto combo de niveles
					$seleccionar = "";
					foreach($array_groups	 as $value)
					{
						if ($array_user['group_id'] == $value['id']) $seleccionar = "selected";
						else $seleccionar = ""; 
						echo '<option '.$seleccionar.' value="'.$value['id'].'">'.$value['description'].'</option>';
					}
				?>
				</select>
				<br>
				<label for="email">Email(*)</label>
				<input type="text" name="email" id="email" value="<?php echo $array_user['user_email'];?>"  />
				<br>
				<label for="user_active">Activo(*)</label>
				<input type="checkbox" name="user_active" id="user_active" <?php if ($array_user['user_active'] == '1') echo 'checked'?>  />
				<br>
				<label for="user_phone">Tel&eacute;fono(*)</label>
				<input type="text" name="user_phone" id="user_phone" value="<?php echo $array_user['user_phone'];?>"  />
				<br>
				<label for="player_level">Nivel de juego</label>
				<input type="text" name="player_level"  id="player_level" disabled size="2" value="<?php echo $array_user['player_level'];?>"  />
				<div id="slider" style="margin-left:5px; margin-top:5px; margin-right:10px; width:90px; float:left;"></div>
				<br>
			</fieldset>
			
			<fieldset>
				<legend>Datos personales</legend>
				<label for="mobile_phone">Tfno M&oacute;vil</label>
				<input type="text" name="mobile_phone" id="mobile_phone" value="<?php echo $array_user['mobile_phone'];?>"  />
				<br>
				<label for="address">Direcci&oacute;n</label>
				<input type="text" name="address" id="address" value="<?php echo $array_user['address'];?>"  />
				<br>
				<label for="cp">C&oacute;digo Postal</label>
				<input type="text" name="cp" id="cp" value="<?php echo $array_user['cp'];?>"  />
				<br>
				<label for="population">Poblaci&oacute;n</label>
				<input type="text" name="population" id="population" value="<?php echo $array_user['population'];?>"  />
				<br>
				<!--  <label for="group_description">Nivel</label>
				<input type="text" name="group_description" id="group_description" value="<?php echo $array_user['group_description'];?>"  />
				-->
				<label for="code_province">Provincia</label>
				<select name="code_province" id="code_province">
				<?php 
				//pinto combo de niveles
					$seleccionar = "";
					foreach($array_province as $value)
					{
						if ($array_user['code_province'] == $value['id']) $seleccionar = "selected";
						else $seleccionar = ""; 
						echo '<option '.$seleccionar.' value="'.$value['id'].'">'.$value['description'].'</option>';
					}
				?>
				</select>
				<br>
				<!-- CAMBIAR MÁS ADELANTE -->
				<label for="country">Pa&iacute;s</label>
				<input type="text" name="country" id="country" value="ESPA&Ntilde;A"  />
				<input type="hidden" name="code_country" id="code_country" value="196"  />
				<!-- FIN CAMBIAR MAS ADELANTE-->
				<br>
				<label for="gender">Sexo</label>		
				<select name="gender" id="gender">
					<option value="0">--Seleccionar--</option>
					<?php
						$check_female = "";
						if ($array_user['gender'] == '2') $check_female = "selected";
						$check_male = "";
						if ($array_user['gender'] == '1') $check_male = "selected";
					?>
					<option value="1" <?php echo $check_male?>>Hombre</option>
					<option value="2" <?php echo $check_female?>>Mujer</option>
				</select>
				<br>
				<label for="nif">N.I.F.</label>
				<input type="text" name="nif" id="nif" value="<?php echo $array_user['nif'];?>"  />
				<!--
				<br>
				<label for="birth_date">Fecha de Nac.</label>
				<input type="text" name="birth_date" id="birth_date" value="<?php echo $array_user['birth_date'];?>"  />
				-->
			</fieldset>
		<!--
			<fieldset>
				<legend>Datos bancarios</legend>
				<label for="bank">Bank</label>
				<input type="text" name="bank" id="bank" value="<?php echo $array_user['bank'];?>"  />
				<br>
				<label for="bank_office">Bank_office</label>
				<input type="text" name="bank_office" id="bank_office" value="<?php echo $array_user['bank_office'];?>"  />
				<br>
				<label for="bank_dc">Bank_dc</label>
				<input type="text" name="bank_dc" id="bank_dc" value="<?php echo $array_user['bank_dc'];?>"  />
				<br>
				<label for="bank_account">Bank_account</label>
				<input type="text" name="bank_account" id="bank_account" value="<?php echo $array_user['bank_account'];?>"  />
				<br>
				<label for="bank_titular">bank_titular</label>
				<input type="text" name="bank_titular" id="bank_titular" value="<?php echo $array_user['bank_titular'];?>"  />
				<br>
			</fieldset>
			-->
			<br>
			<input type="button" id="guardar_button" value="Guardar">
			<input type="button" id="cancelar_button" value="Volver">
		
		<script type="text/javascript">
				$('#guardar_button')
					.button()
					.click(function() {
						document.getElementById('player_level').disabled=false;
						$('#activar_dialog').dialog('open');
					});
				
				$('#cancelar_button')
				.button()
				.click(function() {
					$(document.location).attr("href", "<?php echo site_url(''); ?>");
				});
		</script>
		<script type="text/javascript">
			// increase the default animation speed to exaggerate the effect
			//$.fx.speeds._default = 1000;
			$(function() 
			{
				// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
				$("#dialog").dialog("destroy");
				
				var first_name = $("#first_name"), last_name = $("#last_name"),
				email = $("#email"),
				password_user = $("#password_user"),
				user_phone = $("#user_phone"),
				group_id = $("#group_id"),
				allFields = $([]).add(first_name).add(last_name).add(password_user).add(user_phone),
				tips = $(".validateTips");
		
				function updateTips(t) {
					tips
						.text(t)
						.addClass('ui-state-highlight');
					setTimeout(function() {
						tips.removeClass('ui-state-highlight', 1500);
					}, 500);
				}
		
				function checkLength(o,n,min,max) 
				{
					if ( o.val().length > max || o.val().length < min ) {
						o.addClass('ui-state-error');
						updateTips("La longitud de " + n + " debe estar entre "+min+" y "+max+".");
						return false;
					} else {
						return true;
					}
		
				}
				
				function checkRegexp(o,regexp,n) 
				{
					if ( !( regexp.test( o.val() ) ) ) {
						o.addClass('ui-state-error');
						updateTips(n);
						return false;
					} else {
						return true;
					}
		
				}
		
				$('#activar_dialog').dialog({
					autoOpen: false,
					show: 'blind',
					modal: true,
					buttons: {
						'Guardar': function() {
							//chequeo de usuarios
							var bValid = true;
							//bValid = bValid && checkLength(name,"Apodo",3,40);
							bValid = bValid && checkLength(first_name,"Nombre",3,40);
							bValid = bValid && checkLength(last_name,"Apellido",3,60);
							bValid = bValid && checkLength(user_phone,"Telefono",9,15);
							//bValid = bValid && checkLength(email,"Email",6,80);
		
							bValid = bValid && checkRegexp(user_phone,/^([0-9])+$/i,"El telefono debe ser numerico.");
							bValid = bValid && checkRegexp(group_id,/^([0-9])+$/i,"Seleccione un nivel.");
							//bValid = bValid && checkRegexp(name,/^[a-z]([0-9a-z_])+$/i,"Lastname may consist of a-z, 0-9, underscores, begin with a letter.");
							// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
							if($("#email").val() != '') bValid = bValid && checkRegexp(email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"Formato mail incorrecto, ejemplo:  prueba@prueba.com");
												
							if (bValid) {
								document.forms["formDetail"].action='<?php echo site_url('users/edit_user');?>';
								document.forms["formDetail"].submit(); 
							}	
							else
							{
								$(this).dialog('close');
							}
						},
						'Cancelar': function() {
							document.getElementById('player_level').disabled=true;
							$(this).dialog('close');
						}
					}
				});
			});
			
		</script>
		
		<div id="activar_dialog" title="Modificar usuario">
			<p>Est&aacute; seguro de querer cambiar los datos del usuario?</p>
		</div>

		</div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<!--  <style type="text/css">
				#demo-frame > div.demo { padding: 10px !important; };
			</style>-->

		
			<script type="text/javascript">
			$(function() {
				$("#slider").slider({
					value:<?php echo $array_user['player_level'];?>,
					min: 1,
					max: 6,
					step: 0.5,
					slide: function(event, ui) {
						$("#player_level").val( ui.value);
					}
				});
				$("#player_level").val( $("#slider").slider("value"));
			});
			</script>
		
			<script type="text/javascript">
				//Construyo un array
				var array_level = new Array();
				array_level[0] = 'No seleccionado';
				<?php 
					//relleno el array de niveles
					if(isset($array_levels) && is_array($array_levels)) {	
						foreach($array_levels as $value)
						{
							echo 'array_level['.$value['id'].']= \''.$value['name'].'\';';
						}
					}
				?>
			</script>
	</div>
</div>
