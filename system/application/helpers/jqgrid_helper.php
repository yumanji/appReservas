<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function jqgrid_creator($data)
{
    //return '<script type="text/javascript" src="'.base_url().'system/application/plugins/ckeditor/ckeditor.js"></script>' .
    // '<script type="text/javascript">CKEDITOR.replace("'.$data['id'].'");</script>';
    //
    if(!isset($data['table_id'])) $data['table_id'] = 'grid_name';
    if(!isset($data['pager'])) $data['pager'] = 'pager2';
    if(!isset($data['height'])) $data['height'] = '480px';
    if(!isset($data['viewrecords'])) $data['viewrecords'] = 'true';
    if(!isset($data['title'])) $data['title'] = 'Listado';
    if(!isset($data['autowidth'])) $data['autowidth'] = 'true';
    if(!isset($data['mainwidth'])) $data['mainwidth'] = '780';
    if(!isset($data['mainheight'])) $data['mainheight'] = '480';
    if(!isset($data['row_numbers'])) $data['row_numbers'] = 'true';
    $content = '
								<div style="position:absolute; top:0; right:0; width: '.$data['mainwidth'].'px;">
								<table id="'.$data['table_id'].'" cellpadding="0" cellspacing="0"></table>
								<div id="'.$data['pager'].'" class="scroll" style="text-align: center;"></div>
								</div>
								
								<script type="text/javascript">
								$(document).ready(function(){
									jQuery("#'.$data['table_id'].'").jqGrid({
								   	url:"'.site_url($data['data_url']).'",
										datatype: "json",
										mtype : "post",
										colNames:['.$data['colnames'].'],
								   	colModel:['.$data['colmodel'].'],
								   	rownumbers: '.$data['row_numbers'].',
								   	rowNum:'.$data['default_rows'].',
								   	rowList:['.$data['row_list_options'].'],
								   	pager: jQuery("#'.$data['pager'].'"),
								   	autowidth: '.$data['autowidth'].',
								   	height: "'.$data['mainheight'].'px",
								    viewrecords: '.$data['viewrecords'].',
								    ondblClickRow: function(){
								    	//alert(typeof jqGrid_ondblClickRow);
								    	if(typeof jqGrid_ondblClickRow == \'function\') { 
												jqGrid_ondblClickRow(); 
											}
								    },
								    loadComplete: function(){
													$("#'.$data['table_id'].'").setLabel("id","",{"text-align":"center"});
													$("#'.$data['table_id'].'").setLabel("role_name","",{"text-align":"center"});
													var ids = jQuery("#'.$data['table_id'].'").getDataIDs();
														for(var i=0;i<ids.length;i++){
								
															var cl = ids[i];
								
															be = \'<span class="one_line">\'
															+\'<a href="javascript:;" onclick="del_message(\\\'\'+ids[i]+\'\\\');"><span class="ui-icon ui-icon-closethick"></span></a>\'
															+\'</span>\';
															jQuery("#'.$data['table_id'].'").setRowData(ids[i],{act:be});
														}
												    },
								
								   	sortname: "'.$data['default_orderfield'].'",
								    sortorder: "'.$data['default_orderway'].'",
							
								    jsonReader: { repeatitems : false, id: "0" }, caption:"'.$data['title'].'"
									});

								jQuery("#'.$data['table_id'].'").jqGrid(\'navGrid\',\'#'.$data['pager'].'\',{edit:false,add:false,del:false});

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
												jQuery("#'.$data['table_id'].'").trigger("reloadGrid");
											}
										});
									}
								}
								
								</script>    
    '; 
     
		return $content;
}

function getJqgridFilter($campo, $operador, $valor) {
			$where = $campo;
			switch($operador) {
				case 'cn':
					$where .=' LIKE \'%'.$valor.'%\' '; 
				break;
				case 'nc':
					$where .=' NOT LIKE \'%'.$valor.'%\' '; 
				break;
				case 'eq':
					$where .=' = \''.$valor.'\' '; 
				break;
				case 'ne':
					$where .=' <> \''.$valor.'\' '; 
				break;
				case 'lt':
					$where .=' < \''.$valor.'\' '; 
				break;
				case 'le':
					$where .=' <= \''.$valor.'\' '; 
				break;
				case 'gt':
					$where .=' > \''.$valor.'\' '; 
				break;
				case 'ge':
					$where .=' >= \''.$valor.'\' '; 
				break;
				case 'bw':
					$where .=' LIKE \''.$valor.'%\' '; 
				break;
				case 'bn':
					$where .=' NOT LIKE \''.$valor.'%\' '; 
				break;
				case 'ew':
					$where .=' LIKE \'%'.$valor.'\' '; 
				break;
				case 'en':
					$where .=' NOT LIKE \'%'.$valor.'\' '; 
				break;
			}	
			
			return $where;
}
?>