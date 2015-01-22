	<script type="text/javascript">
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$("#dialog").dialog("destroy");
		
		var name = $("#name"), firstname = $("#first_name"), lastname = $("#last_name"),
			email = $("#email"),
			passw = $("#passw"),
			allFields = $([]).add(firstname).add(lastname).add(email).add(passw),
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
		
		$("#register-form").dialog({
			autoOpen: false,
			height: 390,
			width: 350,
			modal: true,
			buttons: {
				'Create an account': function() {
					var bValid = true;
					allFields.removeClass('ui-state-error');

					bValid = bValid && checkLength(name,"Username",3,40);
					bValid = bValid && checkLength(firstname,"Firstname",3,40);
					bValid = bValid && checkLength(lastname,"Lastname",3,60);
					bValid = bValid && checkLength(email,"email",6,80);
					bValid = bValid && checkLength(passw,"passw",5,16);

					bValid = bValid && checkRegexp(firstname,/^[a-z]([0-9a-z_])+$/i,"Firstname may consist of a-z, 0-9, underscores, begin with a letter.");
					bValid = bValid && checkRegexp(lastname,/^[a-z]([0-9a-z_])+$/i,"Lastname may consist of a-z, 0-9, underscores, begin with a letter.");
					bValid = bValid && checkRegexp(name,/^[a-z]([0-9a-z_])+$/i,"Lastname may consist of a-z, 0-9, underscores, begin with a letter.");
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp(email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"eg. ui@jquery.com");
					bValid = bValid && checkRegexp(passw,/^([0-9a-zA-Z])+$/,"Password field only allow : a-z 0-9");
					
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
				Cancel: function() {
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



<div class="demo">

<div id="register-form" title="Create new user">
	<p class="validateTips">All form fields are required.</p>

<?php 
	$attributes = array('class' => 'ui-widget frmRegister', 'id' => 'frmRegister');
	echo form_open('welcome/register', $attributes); 
?>
	<fieldset>
		<label for="first_name">First Name</label>
		<input type="text" name="first_name" id="first_name" class="text ui-widget-content ui-corner-all" />
		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" id="last_name" class="text ui-widget-content ui-corner-all" />
		<label for="name">Name</label>
		<input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all" />
		<label for="email">Email</label>
		<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
		<label for="password">Password</label>
		<input type="password" name="passw" id="passw" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
<?php echo form_close(''); ?>
</div>

<button id="create-user">Registrate</button>