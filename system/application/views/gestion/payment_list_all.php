<script type="text/javascript">
	var identificador = '';
	function rowClick(celDiv, id){
	    
		$(celDiv).click(
	  function() { 
			$('#modificar_dialog').dialog('open');

				  }
	    )
	}
		
	function changeStatus(celDiv, id){
		$(celDiv).click(
	  function() { 
			$('#activar_dialog').dialog('open');
		  identificador = id;  
				  }
	    )
	}		
		
	function changePassword(celDiv, id){
		$(celDiv).click(
	  function() { 
			$('#reset_password_dialog').dialog('open');
		  identificador = id;  
				  }
	    )
	}		
	function prepaid(celDiv, id){
	    
		$(celDiv).click(
	  function() { 
		  identificador = id;
		  location.href = '<?php echo site_url('users/add_prepaid');?>/' + id;
				  }
	    )
	}		
	function detail(celDiv, id){
		$(celDiv).click(
	  function() { 
		  identificador = id; 
		  goto_detail(identificador);
				  }
	    )
	}
	function goto_detail(id)
	{
		location.href = '<?php echo site_url('users/detail');?>/' + id;
	}
	function reserved(celDiv, id){
	    
		$(celDiv).click(
	  function() { 
			$('#modificar_dialog').dialog('open');
		  identificador = id;  
				  }
	    )
	}
</script>

	<script type="text/javascript">
	// increase the default animation speed to exaggerate the effect
	//$.fx.speeds._default = 1000;
	$(function() 
	{
		$('#activar_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Cambiar': function() {
					location.href='<?php echo site_url('users/change_status');?>/'+identificador;
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});
		
		$('#reset_password_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Cambiar': function() {
					location.href='<?php echo site_url('users/reset_password');?>/'+identificador+'/'+$("#new_password").val();
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});

		$('#prepago_dialog').dialog({
			autoOpen: false,
			show: 'blind',
			modal: true,
			buttons: {
				'Cargar': function() {
					alert($('#amount').val());
					$('#prepago_form').submit();
				},
				'Cancelar': function() {
					$(this).dialog('close');
				}
			}
		});
		
		$('#ir_detail').dialog({
			autoOpen: false,
			show: 'blind'
			
		});
		
		$("#radio").buttonset();
		
	});
	</script>
	
	
<div id="prepago_dialog" title="<?php echo $this->lang->line('login_form'); ?>">
	<p class="validateTips"><?php echo $this->lang->line('two_fields_mandatory'); ?></p>

<?php 
	$attributes = array('class' => 'frmAcceso', 'id' => 'prepago_form');
	echo form_open('welcome/login', $attributes); 
?>
	<fieldset>
		<ol>
			<li>
				<label for="email">Email:</label>
				<input type="text" name="login_email" id="login_email" size="15" value="" class="text ui-widget-content ui-corner-all" />
			</li>
			<li>
				<label for="password">Password:</label>
				<input type="password" name="login_passw" id="login_passw" size="15" value="" class="text ui-widget-content ui-corner-all" />
			</li>
		</ol>
	</fieldset>
<?php echo form_close(''); ?>
</div>	
	


<div id="activar_dialog" title="Modificar estado del usuario">
	<p>Est&aacute; seguro de querer cambiar el estado del usuario?</p>
</div>
<div id="reset_password_dialog" title="Cambiar password">
	<p>¿Est&aacute; seguro de querer cambiar el password para este usuario?</p>
		<fieldset>
			<label for="new_password">Nuevo password:</label>
			<input type="text" name="new_password" id="new_password" value=""/>
		</fieldset>
</div>
<?php

if(isset($js_grid)) echo $js_grid;

if(isset($filters) && trim($filters)!="") echo $filters;



?>
<script type="text/javascript">

function buttons(com,grid)
{
  if (com=='Nuevo usuario')
  {
		location.href='<?php echo site_url('users/new_user'); ?>';
  }
}
</script>

<table id="flex1" style="display:none"></table>

