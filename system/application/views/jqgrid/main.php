<div style="position:relative; width: 960px; height: 660px;">
<div style="position:absolute; top:0; right:0; width: 780px;">
<table id="grid_name" cellpadding="0" cellspacing="0"></table>
<div id="pager2" class="scroll" style="text-align: center;"></div>
</div></div>

<script type="text/javascript">
$(document).ready(function(){
	jQuery("#grid_name").jqGrid({
	   	url:'<?php
		      Echo site_url( "informes/browse" );
		      //echo site_url( "reservas_gest/reserve_list_all" );
		      ?>',
		datatype: "json",
		mtype : "post",
		colNames:[<?php echo $colnames; ?>],
   	colModel:[<?php echo $colmodel; ?>],
   	rownumbers: true,
   	rowNum:20,
   	rowList:[10,20,30],
   	pager: jQuery('#pager2'),
   	sortname: 'date',
   	autowidth: true,
   	height: "480px",
    viewrecords: true,
    loadComplete: function(){
					$("#grid_name").setLabel("id","",{'text-align':'center'});
					$("#grid_name").setLabel('role_name',"",{'text-align':'center'});
					var ids = jQuery("#grid_name").getDataIDs();
						for(var i=0;i<ids.length;i++){

							var cl = ids[i];

							be = '<span class="one_line">'
							+'<a href="javascript:;" onclick="del_message(\''+ids[i]+'\');"><span class="ui-icon ui-icon-closethick"></span></a>'
							+'</span>';
							jQuery("#grid_name").setRowData(ids[i],{act:be});
						}
				    },

    sortorder: "desc",

    jsonReader: { repeatitems : false, id: "0" }, caption:"Caption Text"
	}).navGrid('#pager2',{edit:false,add:false,del:false});
});

function del_message(message_id)
{
	if(confirm("Confirm Message")){

		$.ajax({
			url : site_url+"/message/inbox/del_message",
			type : "post",
			dataType : "json",
			data : "message_id="+message_id+"",
			success : function(e){
				$("#msg").html(e.msg)
				alert($("#msg").html());
				//$.jGrowl(e.code+"<br>"+e.message);
				jQuery("#grid_name").trigger("reloadGrid");
			}
		});
	}
}

</script>
