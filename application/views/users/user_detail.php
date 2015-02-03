<?php

//  print("<pre>");print_r($array_user);print("</pre>");
?>
  <form class="form_user" name="formUser" id="formUser" method="post">
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="93">
        	<?php 
        		if(trim($array_user['avatar'])!='' && file_exists($this->config->item('root_path').'images/users/'.$array_user['avatar'])) echo img( array('src'=>'images/users/'.$array_user['avatar'], 'border'=>'0',  'width'=>'90',  'alt' => 'Avatar',  'align'=>'absmiddle')); 
        		else echo img( array('src'=>'images/avatar.jpg', 'border'=>'0',  'width'=>'90',  'height'=>'110"', 'alt' => 'foto',  'align'=>'absmiddle'));
        		//echo img( array('src'=>'images/users/'.$array_user['avatar'], 'border'=>'0',  'width'=>'90', 'height'=>'110"', 'alt' => 'foto',  'align'=>'absmiddle')); 
        	?>
            <div><a id="cambiar_foto" style="cursor: pointer; text-decoration: underline;">cambiar imagen</a></div>
            <br>
            <?php
				if(isset($carnet_enabled) && $carnet_enabled) echo '<div><a id="ver_carnet" style="cursor: pointer; text-decoration: underline;">'.img( array('src'=>'images/vcard.png', 'border'=>'0',  'width'=>'16',  'height'=>'16"', 'alt' => 'carnet', 'title' => 'Ver carnet',  'align'=>'absmiddle')).'</a> <a id="ver_carnet2" style="cursor: pointer; text-decoration: underline;">Carnet</a>&nbsp;&nbsp;&nbsp;&nbsp;<a id="ver_carnet_recibo" style="cursor: pointer; text-decoration: underline;">'.img( array('src'=>'images/receipts.png', 'border'=>'0',  'width'=>'16',  'height'=>'16"', 'alt' => 'Ver recibo de carnet', 'title' => 'Ver recibo de carnet',  'align'=>'absmiddle')).'</a></div><br>';
            ?>
			<label>Activo
            <input type="checkbox" name="user_active" id="user_active" value="1" <?php if ($array_user['user_active'] == '1') echo 'checked'?>  />
            </label>
            <label style="font-size: 90%;">Saldo: <?php echo number_format($array_user['prepaid_cash'], 2).'&euro;';?>
            </label>
            </td>
        <td width="405" valign="top">
        	<input type="hidden" id="paymentway" name="paymentway" value="">
        	<input type="hidden" id="id_user" name="id_user" value="<?php echo $array_user['user_id'];?>">
        	<input type="hidden" id="payable_quota" name="payable_quota" value="">
        	<input type="hidden" id="payd_date" name="payd_date" value="">
        	<input type="hidden" id="returnUrl" name="returnUrl" value="<?php echo current_url();?>">
        	<label><span>Id</span>
              <input name="id" type="text" id="id" size="2" disabled value="<?php echo $code_user;?>" size="25" />
          </label>
        	<label><span>Nombre</span>
              <input name="first_name" type="text" id="first_name" value="<?php echo $array_user['user_name'];?>" size="25" />
          </label>
            <label><span>Apellidos</span>
            <input type="text" name="last_name" id="last_name" value="<?php echo $array_user['user_lastname'];?>" size="25" />
            </label>
            <label><span>Email </span>
            <input type="text" name="email" id="email" value="<?php echo $array_user['user_email'];?>" size="25" />
          </label>
            <label><span>NIF</span>
            <input type="text" name="nif" id="nif" value="<?php echo $array_user['nif'];?>" />
            </label>
						<label><span>Nivel de usuario</span>
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
						</select></label>
						<?php if($tarifa_enabled) { ?>
							<label><span>Tarifa</span>
							<select name="code_price" id="code_price">
								<option value="0">--Seleccionar--</option>
							<?php 
							//pinto combo de niveles
								$seleccionar = "";
								foreach($array_quotas	 as $value)
								{
									if ($array_user['code_price'] == $value['id']) $seleccionar = "selected";
									else $seleccionar = ""; 
									echo '<option '.$seleccionar.' value="'.$value['id'].'">'.$value['description'].'</option>';
								}
							?>
							</select></label>
						<?php } ?>
            <label><span>Password</span>
            	<input name="Password" type="password" id="Password" value="sfsfsfsf"  size="6" disabled/>
            	<?php if(isset($change_pwd_enabled) && $change_pwd_enabled) echo '<input type="button" id="password_change" class="boton" value="Cambiar"/>'; ?>
            </label>
            <label><span>Num. cuenta</span>
            <input name="bank" style="width: 34px"  type="text" id="bank" value="<?php echo $array_user['bank'];?>" maxlength="4" size="2" />
            <input name="bank_office" style="width: 34px"  type="text" id="bank_office" value="<?php echo $array_user['bank_office'];?>" size="2" />
            <input name="bank_dc" style="width: 18px"  type="text" id="bank_dc" value="<?php echo $array_user['bank_dc'];?>" size="1" />
            <input name="bank_account" style="width: 75px"  type="text" id="bank_account" value="<?php echo $array_user['bank_account'];?>" size="6" />
          </label>
		  <label><span>Cuenta IBAN</span>
            	<input name="bank_iban" disabled type="text" id="bank_iban" size="24" value="<?php echo $array_user['bank_iban'];?>"/>
            </label>
            <label><span>Titular cuenta</span>
            	<input name="bank_titular"  type="text" id="bank_titular" value="<?php echo $array_user['bank_titular'];?>"/>
            </label>
          </td>
        <td width="476" >
        	
        	<?php
        		if($numero_socio_visible) {
        			if($numero_socio_automatico) $disabled = ' disabled ';
        			else $disabled = '';
        			?>
        		<label> <span>Numero abonado </span>
              <input type="text" name="code" id="code" value="<?php echo $array_user['numero_socio'];?>" <?php echo $disabled;?>size="30" />
          </label>        			
        			<?php
        		}
        	?>
        		<label> <span>Direcci&oacute;n </span>
              <input type="text" name="address" id="address" value="<?php echo $array_user['address'];?>" size="30" />
          </label>
            <label><span>CP </span>
            <input type="text" name="cp" id="cp" value="<?php echo $array_user['cp'];?>"  size="5" />
            </label>
            <label><span>Localidad </span>
            <input type="text" name="population" id="population" value="<?php echo $array_user['population'];?>" size="30"  />
            </label>
            <label><span>Provincia </span>
      				<select name="code_province" id="code_province">
							<?php 
							//pinto combo de niveles
								$seleccionar = "";
								foreach($array_province as $value)
								{
									if (isset($array_user['code_province']) && isset($array_user['code_province']) && $array_user['code_province'] == $value['id']) $seleccionar = "selected";
									else $seleccionar = ""; 
									echo '<option '.$seleccionar.' value="'.$value['id'].'">'.$value['description'].'</option>';
								}
							?>
							</select>
            </label>
            <label><span>Tel&eacute;fono</span>
            <input type="text" name="user_phone" id="user_phone" value="<?php echo $array_user['user_phone'];?>" size="10" />&nbsp;&nbsp;<input type="text" name="mobile_phone" id="mobile_phone" value="<?php echo $array_user['mobile_phone'];?>" size="10" />
            </label>
            <label><span>G&eacute;nero</span>
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
            </label>
            <!--
            <label><span>Tel&eacute;fono 2</span>
            <input type="text" name="mobile_phone" id="mobile_phone" value="<?php echo $array_user['mobile_phone'];?>" size="10" />
            </label>
            -->
           <label><span>Fecha Nacimiento</span>
            <input type="text" name="birth_date" id="birth_date"  value="<?php echo date($this->config->item('reserve_date_filter_format'), strtotime($array_user['birth_date'])); ?>" />
          <?php //echo img( array('src'=>'images/calendar.jpg', 'border'=>'0',  'width'=>'24',  'height'=>'24"', 'alt' => 'calendario',  'align'=>'absmiddle')); ?>
          </label>
            <label><span>Nivel de juego</span>
						<input type="text" name="player_level"  id="player_level" disabled size="2" value="<?php echo $array_user['player_level'];?>"  />
						<div id="slider" style="margin-left:5px; margin-top:5px; margin-right:10px; width:90px; float:left;"></div>
          </label>
				</td>
      </tr>
    </table>
    <br clear="all" />
    <table border="0" width="100%">
      <tr>
        <!--
        <td><fieldset>
          <legend>Seleccion por opción</legend>
          <div>
            <input name="" type="radio" value="" />
            Reserva bono mensual </div>
          <div>
            <input name="" type="radio" value="" />
            Reserva bono anual</div>
        </fieldset></td>
        -->
        <td><fieldset>
          <legend>Privacidad</legend>
          <div>
            <input name="allow_phone_notification" type="checkbox" value="1" <?php if ($array_user['allow_phone_notification'] == '1') echo 'checked'?>/>
            Recibir noticias SMS </div>
          <div>
            <input name="allow_mail_notification" type="checkbox" value="1" <?php if ($array_user['allow_mail_notification'] == '1') echo 'checked'?>/>
            Recibir noticias Email</div>
        </fieldset></td>
        <td><fieldset>
          <legend>Dias disponibles para Retos</legend>
          <div>
            <input name="lunes" type="checkbox" value="1"  <?php if ($array_user['reto_lunes'] == '1') echo 'checked'?>/>
            Lunes
            <input name="martes" type="checkbox" value="1"  <?php if ($array_user['reto_martes'] == '1') echo 'checked'?>/>
            Martes
            <input name="miercoles" type="checkbox" value="1"  <?php if ($array_user['reto_miercoles'] == '1') echo 'checked'?>/>
            Mi&eacute;rcoles
            <input name="jueves" type="checkbox" value="1"  <?php if ($array_user['reto_jueves'] == '1') echo 'checked'?>/>
            Jueves
            <input name="viernes" type="checkbox" value="1"  <?php if ($array_user['reto_viernes'] == '1') echo 'checked'?>/>
            Viernes
            <input name="sabado" type="checkbox" value="1"  <?php if ($array_user['reto_sabado'] == '1') echo 'checked'?>/>
            S&aacute;bado
            <input name="domingo" type="checkbox" value="1"  <?php if ($array_user['reto_domingo'] == '1') echo 'checked'?>/>
            Domingo </div>
          <div>
            <input name="manana" type="checkbox" value="1"  <?php if ($array_user['reto_manana'] == '1') echo 'checked'?>/>
            Ma&ntilde;anas
            <input name="tardes" type="checkbox" value="1" <?php if ($array_user['reto_tarde'] == '1') echo 'checked'?> />
            Tardes
            <input name="finde" type="checkbox" value="1"  <?php if ($array_user['reto_finde'] == '1') echo 'checked'?>/>
            Fines de semana
            <input name="avisar_retos" type="checkbox" value="1"  <?php if ($array_user['reto_notifica'] == '1') echo 'checked'?>/>
            Quiero recibir avisos</div>
        </fieldset></td>
        
        <?php if($tarifa_payable) { ?>
        <td><fieldset>
          <legend>Cuota de socio</legend>
          <div>Cuota: <?php echo number_format($quota,2).'&euro;'; ?></div>
          <div><?php if($array_user['last_payd_date']!="") echo 'Pagado hasta '.date($this->config->item('reserve_date_filter_format') , strtotime($array_user['last_payd_date'])); else echo 'Sin pagos realizados';?></div>
        </fieldset></td>
      <?php } ?>     
	  
      </tr>
        <?php if(1==1) { ?>
        <tr><td colspan="3"><fieldset>
          <legend>Notas</legend>
          <div><textarea id="notas" name="notas" rows=4 style="border: 1px solid #ccc; background-color: #fff; width: 98%; padding: 0px; margin: 0px;"><?php echo $array_user['notas']; ?></textarea></div>
        </fieldset></td></tr>
      <?php } ?>
    </table>
  </form>
    <!--Fin Formulario usuario -->
    <br clear="all" />
      <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Guardar"/>
      <input type="button" id="cancelar_button" class="boton" value="Volver"/>
      <?php if($tarifa_payable) { ?><input type="button" id="pagar_button" class="boton" value="Pagar cuota"/><?php } ?>
    </div>
    &nbsp;
    <br clear="all" />

	<script type="text/javascript">
			$(function() {
				$("#slider").slider({
					value:<?php echo $array_user['player_level'];?>,
					min: 1,
					max: 6,
					step: 0.1,
					slide: function(event, ui) {
						$("#player_level").val( ui.value);
					}
				});
				$("#player_level").val( $("#slider").slider("value"));
			});

			// Validaciones
			///
			if(!valida_nif_cif_nie('nif') && $('#nif').val()!='')  $('#nif').attr('class', 'error'); 
			
			$('#nif').change(function() {
			  //alert($('#email').val());
			  if(!valida_nif_cif_nie('nif') && $('#nif').val()!='') $('#nif').attr('class', 'error');
			  else $('#nif').removeClass('error');
			});
			$('#user_phone').change(function() {
			  //alert($('#user_phone').val().length);
			  if($('#user_phone').val().length < 9) $('#user_phone').attr('class', 'error');
			  else $('#user_phone').removeClass('error');
			});
			$('#mobile_phone').change(function() {
			  //alert($('#phone').val().length);
			  if($('#mobile_phone').val().length < 9) $('#mobile_phone').attr('class', 'error');
			  else $('#mobile_phone').removeClass('error');
			});			

			var dates = $( "#birth_date" ).datepicker({
				showOn: 'button',
				buttonImage: '<?php echo base_url(); ?>/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 2,
				dateFormat: 'dd-mm-yy',
				dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
				monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				firstDay: 1,
				
				onSelect: function( selectedDate ) {
					var option = this.id == "start_date" ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" );
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
				}
			}
				
				);

						
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

		<script type="text/javascript">
				$('#cambiar_foto')
					.click(function() {
						$('#upload_dialog').dialog('open');
					});
			<?php
				# Visualización del carnet de socio en una ventana aparte
				if(isset($carnet_enabled) && $carnet_enabled) echo "
					$('#ver_carnet,#ver_carnet2')
						.click(function(event) {
							event.preventDefault();
							//window.open('".site_url('users/carnet/'.$array_user['user_id'])."', 'carnetWindow', 'width=510,height=330,scrollbars=yes');
							$(document.location).attr('href', '".site_url('users/carnet/'.$array_user['user_id'])."');
						});
					
					$('#ver_carnet_recibo')
						.click(function(event) {
							event.preventDefault();
							//window.open('".site_url('users/carnet/'.$array_user['user_id'])."', 'carnetWindow', 'width=510,height=330,scrollbars=yes');
							$(document.location).attr('href', '".site_url('users/carnet_recibo/'.$array_user['user_id'])."');
						});
					";			
			?>
				$('#guardar_button')
					.click(function() {
						if(valida_nif_cif_nie('nif') || $('#nif').val()=='') {
							document.getElementById('player_level').disabled=false;
							$('#activar_dialog').dialog('open');
						} else alert('Debe corregir el valor del NIF o dejarlo vacio');
					});
				<?php if($tarifa_payable) { ?>
				$('#pagar_button')
					.click(function() {
						$('#pagar_dialog').dialog('open');
					});
				<?php } ?>
				$('#cancelar_button')
				.click(function() {
					$(document.location).attr("href", "<?php echo $returnUrl; ?>");
				});
				$('#password_change')
				.click(function() {
						$('#password_dialog').dialog('open');
				});
				
				
				$('#group_id')
				.change(function() {
						$('#formUser').submit();
				});
			// increase the default animation speed to exaggerate the effect
			//$.fx.speeds._default = 1000;
			$(function() 
			{
				//Definición de máscaras del formulario
				$.mask.masks.dinero = {mask : '99.999', type : 'reverse', defaultValue: '000'};
				$.mask.masks.dni = {mask : 'a-99999999', type : 'reverse'};
				$('input:text').setMask();

				// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
				$("#dialog").dialog("destroy");
				
				var first_name = $("#first_name"), last_name = $("#last_name"),
				email = $("#email"),
				password_user = $("#password_user"),
				user_phone = $("#user_phone"),
				group_id = $("#group_id"), old_password = $("#old_password"), new_password = $("#new_password"),
				re_password = $("#re_password"),
				allFields = $([]).add(first_name).add(last_name).add(password_user).add(user_phone).add(old_password).add(new_password).add(re_password),
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
							<?php if($tarifa_enabled) { ?>
								if($("#code_price").val() == '0') { bValid = false; alert('Debe seleccionar una tarifa a aplicar'); }
							<?php } ?>					
							if (bValid) {
								document.forms["formUser"].action='<?php echo site_url('users/edit_user');?>';
								document.forms["formUser"].submit(); 
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

				
				$('#password_dialog').dialog({
					autoOpen: false,
					show: 'blind',
					modal: true,
					buttons: {
						'Modificar': function() {
							//chequeo de usuarios
							var bValid = true;
							//bValid = bValid && checkLength(name,"Apodo",3,40);
							//bValid = bValid && checkLength(old_password,"Password",5,16);
							bValid = bValid && checkLength(new_password,"Password",5,16);
							bValid = bValid && checkLength(re_password,"Password",5,16);
							//bValid = bValid && checkLength(email,"Email",6,80);
							if($("#new_password").val() != $("#re_password").val()) {
								bValid = bValid && false;
								updateTips("Las dos copias del nuevo password deben ser iguales.");
							}
							
							if (bValid) {
								$('#frmPassword').submit();
							}
						},
						'Cancelar': function() {
							$(this).dialog('close');
						}
					}
				});
								
				<?php if($tarifa_payable) { ?>
				$('#pagar_dialog').dialog({
					autoOpen: false,
					width: 375,
					show: 'blind',
					modal: true,
					buttons: {
						'Tarjeta': function() {
								$("#paymentway").val('2');
								document.forms["formUser"].action='<?php echo site_url('users/pay_quota/'.$array_user['user_id']);?>';
								$('#payable_quota').val($('#payable_quota_tmp').val());
								$('#payd_date').val($('#payd_date_tmp').val());
								document.forms["formUser"].submit(); 

						},
						'Efectivo': function() {
								$("#paymentway").val('1');
								document.forms["formUser"].action='<?php echo site_url('users/pay_quota/'.$array_user['user_id']);?>';
								$('#payable_quota').val($('#payable_quota_tmp').val());
								$('#payd_date').val($('#payd_date_tmp').val());
								document.forms["formUser"].submit(); 

						},
						'Banco': function() {
								$("#paymentway").val('4');
								document.forms["formUser"].action='<?php echo site_url('users/pay_quota/'.$array_user['user_id']);?>';
								$('#payable_quota').val($('#payable_quota_tmp').val());
								$('#payd_date').val($('#payd_date_tmp').val());
								document.forms["formUser"].submit(); 

						},
						'Cancelar': function() {
							$(this).dialog('close');
						}
					}
				});
			<?php } ?>
			
			
				$('#upload_dialog').dialog({
					autoOpen: false,
					show: 'blind',
					modal: true,
					buttons: {
						'Subir': function() {
								document.forms["frmUpload"].submit(); 

						},
						'Cancelar': function() {
							$(this).dialog('close');
						}
					}
				});			
			
			});
			
		</script>
		
		<div id="activar_dialog" title="Modificar usuario">
			<p>Est&aacute; seguro de querer cambiar los datos del usuario?</p>
		</div>
		<?php if($tarifa_payable) { ?>
		<div id="pagar_dialog" title="Pagar cuota">
			<p>De esta forma pagar&aacute; la cuota de socio hasta el pr&oacute;ximo <?php echo $next_payment_date; ?>. 
			<br>Cuota a cobrar <input name="payable_quota_tmp" type="text" id="payable_quota_tmp" value="<?php echo number_format($quota,2).'&euro;'; ?>" size="7" alt="dinero"/>
			<br>Pagado hasta: <input name="payd_date_tmp" type="text" id="payd_date_tmp" value="<?php echo $next_payment_date; ?>"  /></p>
		</div>
		<?php } ?>
		
		<!-- Capa para subir la foto -->
		<div id="upload_dialog" title="Subir fotografia">
			<p>Subida de fotograf&iacute;a de usuario<br/>
				<?php 
					echo form_open_multipart('users/upload_photo/'.$array_user['user_id'], array('name' => 'frmUpload', 'id' => 'frmUpload'));
					echo form_upload(array('name' => 'archivo', 'id' => 'archivo', 'style' => 'width:200px'));
					echo form_close();
				?>
			</p>
		</div>
		<?php if(isset($change_pwd_enabled) && $change_pwd_enabled) { ?>
		<div id="password_dialog" title="Modificar contrase&ntilde;a">
			<p class="validateTips">Rellene todos los campos.</p>
			<?php 
				$attributes = array('class' => 'frmPassword', 'id' => 'frmPassword');
				echo form_open('users/reset_password', $attributes); 
			?>
				<input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo site_url('users/profile');?>"/>
				<input type="hidden" name="id_user" id="id_user" value="<?php echo $array_user['user_id'];?>"/>
				<fieldset>
					<label for="new_password">Contrase&ntilde;a nueva:</label>
					<input type="password" name="new_password" id="new_password" value="" class="text ui-widget-content ui-corner-all" />
					<br>
					<label for="re_password">Repetir contrase&ntilde;a:</label>
					<input type="password" name="re_password" id="re_password" value="" class="text ui-widget-content ui-corner-all" />
				</fieldset>
			<?php echo form_close(''); ?>
		</div>
	<?php } ?>


	<script type="text/javascript">
		$(function() {

			$( "#payd_date_tmp" ).datepicker({
				showOn: 'button',
				buttonImage: '<?php echo base_url(); ?>/images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 2,
				dateFormat: 'dd-mm-yy',
				dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
				monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				firstDay: 1,
				
				onSelect: function( selectedDate ) {
					var option = this.id == "start_date" ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" );
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
				}
			});
		});
</script>