<?php
if(isset($js_grid)) echo $js_grid;
?>
<script type="text/javascript">

function test(com,grid)
{
    if (com=='Select All')
    {
		$('.bDiv tbody tr',grid).addClass('trSelected');
    }
    
    if (com=='DeSelect All')
    {
		$('.bDiv tbody tr',grid).removeClass('trSelected');
    }
    
    if (com=='Delete')
        {
           if($('.trSelected',grid).length>0){
			   if(confirm('Delete ' + $('.trSelected',grid).length + ' items?')){
		            var items = $('.trSelected',grid);
		            var itemlist ='';
		        	for(i=0;i<items.length;i++){
						itemlist+= items[i].id.substr(3)+",";
					}
					$.ajax({
					   type: "POST",
					   url: "<?php echo site_url("/ajax/deletec");?>",
					   data: "items="+itemlist,
					   success: function(data){
					   	$('#flex1').flexReload();
					  	alert(data);
					   }
					});
				}
			} else {
				return false;
			} 
        }          
} 
</script>
		<table id="flex1" style="display:none"></table>

