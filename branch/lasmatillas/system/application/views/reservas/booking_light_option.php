<style type="text/css">
<!--
.ui-button { background: #DAE6F3 url(<?php echo base_url().'/images/luz_no.gif';?>) 50% 50% no-repeat; display: inline-block; position: relative; padding: 0; margin-right: .1em; text-decoration: none !important; cursor: pointer; text-align: center; zoom: 1; overflow: visible; }
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { border: 1px solid #DAE6F3; font-weight: bold; color: #2d588b; }
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus { border: 1px solid #2d588b; font-weight: bold; color: #ffffff; }
-->
</style>	
<input type="checkbox" name="allow_light" id="allow_light"><label for="allow_light" id="light_button" style="width: 25px; background-repeat: no-repeat; ">&nbsp;</label><span id="light_prev"> Solicitar luz </span><span id="light">(<?php echo number_format($precio, 2); ?>&euro;)</span>

<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#allow_light" ).button();
	$("#allow_light").click( function() {
			//alert($('#light_button').css('background-image'));
			//window.alert( $('#allow_light').attr('checked')  );
			if($('#allow_light').attr('checked') == true) {
				$('#light_button').css('background-image','url(<?php echo base_url(); ?>images/luz.gif)');
				document.getElementById('light_prev').innerHTML=' Luz seleccionada ';
			}
			if($('#allow_light').attr('checked') == false) {
				$('#light_button').css('background-image','url(<?php echo base_url(); ?>images/luz_no.gif)');
				document.getElementById('light_prev').innerHTML=' Solicitar luz ';
			}
			//$('#light_button').css('background-image','url(../images/luz.gif)');
	})	
});
</script>