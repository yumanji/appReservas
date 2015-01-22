	<script type="text/javascript">
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$("#dialog").dialog("destroy");
		
		var email = $("#login_email"),
			code = $("#login_code"),
			passw = $("#login_passw"),
			allFields = $([]).add(email).add(code).add(passw),
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
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				'Loguearse': function() {
					var bValid = true;
					allFields.removeClass('ui-state-error');

					if($("#login_code").val() == "") bValid = bValid && checkLength(email,"email",6,80);
					if($("#login_email").val() == "") bValid = bValid && checkLength(email,"code",1,10);
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
				'Cancelar': function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		});
		
		
		
		$('#login-user')
			.button()
			.click(function() {
				$('#dialog-form').dialog('open');
			});

	});
	</script>



<div class="demo">

<div id="dialog-form" title="Loguearse">
	<p class="validateTips">All form fields are required.</p>

<?php 
	$attributes = array('class' => 'frmAcceso', 'id' => 'frmAcceso');
	echo form_open('welcome/login', $attributes); 
?>
	<fieldset>
		<label for="email">Emaillll:</label>
		<input type="text" name="login_email" id="login_email" value="" class="text ui-widget-content ui-corner-all" />
		<br>o<br>
		<label for="login_code">C&oacute;digo:</label>
		<input type="text" name="login_code" id="login_code" value="" class="text ui-widget-content ui-corner-all" />
		<br>
		<label for="password">Password:</label>
		<input type="password" name="login_passw" id="login_passw" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
<?php echo form_close(''); ?>
</div>



<button id="login-user">Login</button>