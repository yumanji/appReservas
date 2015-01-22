<?php 


echo $meta;
if(isset($scripts)) echo $scripts;


//if(isset($js_grid)) echo $js_grid;



      	if(isset($form)) {
      		$attributes = array('class' => $form, 'id' => $form);
					echo form_open($this->uri->uri_string(), $attributes);
				}


?>

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

<script type="text/javascript">
function buttons(com,grid)
{
  if (com=='Remesa')
  {
		location.href='<?php echo site_url('users/new_user'); ?>';
  }
}
</script>
<?php

if(isset($js_grid)) echo $js_grid;

if(isset($filters) && trim($filters)!="") echo $filters;



?>




<table id="flex1" style="display:none"></table>



<?php
				if(isset($form)) echo form_close();
			?>
