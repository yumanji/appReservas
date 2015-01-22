<script>
	$(function() {
		$( "#number_players_" ).slider({ 
			min: <?php echo $record_players_range[0]; ?> , 
			max: <?php echo $record_players_range[count($record_players_range) - 1]; ?>,
			slide: function(event, ui) {
				$("#number_players").val( ui.value);
				
			} ,
			change: function(event, ui) {
				//alert(ui.value);
				if(ui.value > 1) $("#multiuser_detail2").css('display', 'block');
				else $("#multiuser_detail2").css('display', 'none');
					
				for(i=1; i<=ui.value-1; i++ ) {
					//alert("#multiuser_detail_"+i + " - "+ $("#multiuser_detail_"+i).css('visibility'));
					$("#multiuser_detail_"+i).css('display', 'block');
				}
				for(i=ui.value; i<=3; i++ ) {
					$("#multiuser_detail_"+i).css('display', 'none');
				}
				
			} 
		});
	});
</script>

Jugadores: <input type="text" name="number_players"  id="number_players" disabled size="2" value="1"  />
<div id="number_players_" style="margin-left:5px; margin-top:5px; margin-right:10px; width:90px; float:left;"></div>