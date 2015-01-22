<?php 
		//$this->load->view('meta');
		$passw_type = "password";
		if($auto_password) {
			$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
			$passw = "";
			for($i=0;$i<6;$i++) {
				$passw .= substr($str,rand(0,62),1);
			}
			$passw_type = "text";
			$password_txt=' value="'.$passw.'" ';
		} else $password_txt=' value="" ';

	?>
  <form class="form_user" name="formUser" method="post">
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="405" valign="top">
        	<label><span>Nombre</span>
              <input name="first_name" type="text" id="first_name" value="" size="30" />
          </label>
            <label><span>Apellidos</span>
            <input type="text" name="last_name" id="last_name" value="" size="30" />
            </label>
            <label><span>Email </span>
            <input type="text" name="email" id="email" value="" size="30" />
          </label>
            <label><span>NIF</span>
            <input type="text" name="nif" id="nif" value=""  alt="dni"/>
            </label>
						<label><span>Nivel de usuario</span>
							<select name="group_id" id="group_id">
								<option value="vacio">--Seleccionar--</option>
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($array_groups	 as $value)
									{
										if (isset($array_user['group_id']) && $array_user['group_id'] == $value['id']) $seleccionar = "selected";
										else $seleccionar = ""; 
										echo '<option '.$seleccionar.' value="'.$value['id'].'">'.$value['description'].'</option>';
									}
								?>
							</select>
						</label>
            <label><span>Password</span>
            <input name="password_user" type="<?php echo $passw_type; ?>" id="password_user" <?php echo $password_txt; ?> />
				</td>
        <td width="476" >
        		<label> <span>Direcci&oacute;n </span>
              <input type="text" name="address" id="address" value="" size="30"/>
          </label>
            <label><span>CP </span>
            <input type="text" name="cp" id="cp" value=""  size="5" />
            </label>
            <label><span>Localidad </span>
            <input type="text" name="population" id="population" value="" size="30"  />
            </label>
            <label><span>Tel&eacute;fono</span>
            <input type="text" name="phone" id="phone" value="" size="10" />&nbsp;/&nbsp;<input type="text" name="mobile_phone" id="mobile_phone" value="" size="10" />
            </label>
            <label><span>G&eacute;nero</span>
							<select name="gender" id="gender">
								<option value="0">--Seleccionar--</option>
								<option value="1">Hombre</option>
								<option value="2">Mujer</option>
							</select>
            </label>
            <!--
            <label><span>Tel&eacute;fono 2</span>
            <input type="text" name="mobile_phone" id="mobile_phone" value="<?php if (isset($array_user['mobile_phone'])) echo $array_user['mobile_phone'];?>" size="10" />
            </label>
            -->
           <label><span>Fecha Nacimiento</span>
            <input type="text" name="birth_date" id="birth_date"  value="" />
          <?php //echo img( array('src'=>'images/calendar.jpg', 'border'=>'0',  'width'=>'24',  'height'=>'24"', 'alt' => 'calendario',  'align'=>'absmiddle')); ?>
          </label>
		  <!--
		  <label><span>Nivel de juego</span>
						<input type="text" name="player_level"  id="player_level" disabled size="2" value=""  />
						<div id="slider" style="margin-left:5px; margin-top:5px; margin-right:10px; width:90px; float:left;"></div>
          </label>
		  -->
				</td>
      </tr>
    </table>
    
    <br clear="all" />
    

    <!--Fin Formulario usuario -->
    <br clear="all" />
      <p class="validateTips">Campos marcados con asterisco(*) son obligatorios.</p>
    <div class="separador">
			<input type="button" id="guardar_button" class="boton" value="Enviar"/>
      <input type="button" id="cancelar_button" class="boton" value="Cancelar"/>
    </div>
    &nbsp;
    <br clear="all" />

	<script type="text/javascript">
			$(function() {
				$("#slider").slider({
					value: 1,
					min: 1,
					max: 6,
					step: 0.1,
					slide: function(event, ui) {
						$("#player_level").val( ui.value);
					}
				});
				$("#player_level").val( $("#slider").slider("value"));
			});

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
	$(function() {
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

			$('#guardar_button')
		.click(function() {
			  if(valida_nif_cif_nie('nif') || $('#nif').val()=='') $('#activar_dialog').dialog('open');
			  else alert('Debe corregir el valor del NIF o dejarlo vacio');
		});
		
		$('#cancelar_button')
		.click(function() {
			$('#cancelar_dialog').dialog('open');
		});
		
		
		// Validaciones
		////////
		$('#nif').change(function() {
		  //alert($('#email').val());
		  if(!valida_nif_cif_nie('nif') && $('#nif').val()!='') $('#nif').attr('class', 'error');
		  else $('#nif').removeClass('error');
		});
		$('#phone').change(function() {
		  //alert($('#phone').val().length);
		  if($('#phone').val().length < 9) $('#phone').attr('class', 'error');
		  else $('#phone').removeClass('error');
		});
		$('#mobile_phone').change(function() {
		  //alert($('#phone').val().length);
		  if($('#mobile_phone').val().length < 9) $('#mobile_phone').attr('class', 'error');
		  else $('#mobile_phone').removeClass('error');
		});
	});
</script>
<script type="text/javascript">
	$(function() 
	{
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$("#dialog").dialog("destroy");
		
		var first_name = $("#first_name"), last_name = $("#last_name"),
		email = $("#email"),
		password_user = $("#password_user"),
		user_phone = $("#phone"),
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
					bValid = bValid && checkLength(password_user,"Password",5,16);

					bValid = bValid && checkRegexp(user_phone,/^([0-9])+$/i,"El telefono debe ser numerico.");
					bValid = bValid && checkRegexp(group_id,/^([0-9])+$/i,"Seleccione un nivel.");
					//bValid = bValid && checkRegexp(name,/^[a-z]([0-9a-z_])+$/i,"Lastname may consist of a-z, 0-9, underscores, begin with a letter.");
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					if($("#email").val() != '') bValid = bValid && checkRegexp(email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"Formato mail incorrecto, ejemplo:  prueba@prueba.com");
					bValid = bValid && checkRegexp(password_user,/^([0-9a-zA-Z])+$/,"El password solo puede contener : a-z 0-9");
					
					if (bValid) {
						$('#player_level').removeAttr('disabled');
						document.forms["formUser"].action='<?php echo site_url('users/create_user');?>';
						document.forms["formUser"].submit();
					}	
					else
					{
						$(this).dialog('close');
					}			 
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});
	});
	
	$(function() 
	{
		$('#cancelar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Salir': function() {
					document.forms["formUser"].action='<?php echo site_url('users');?>';
					document.forms["formUser"].submit(); 
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});
	});
</script>

<div id="activar_dialog" title="Crear Usuario">
	<p>Est&aacute; seguro de querer crear el usuario?</p>
</div>

<div id="cancelar_dialog" title="Salir Crear Usuario">
	<p>Est&aacute; seguro de cancelar la operaci&oacute;n?</p>
</div>