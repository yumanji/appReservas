<?php 
 //print("<pre>");print_r($info); print("</pre>");

//$this->lang->load('lessons');

?>
  <form class="form_user" name="formRanking" id="formRanking" method="post" action="<?php echo current_url(); ?>">
  	<input name="id" id="id" type="hidden" value="<?php echo $info['id'];?>"/>
  	<input name="action" id="action" type="hidden" value=""/>
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td>
        	<label><span>Nombre*</span>
              <input name="description" type="text" id="description" value="<?php echo $info['description']; ?>" size="25" />
          </label>
          <label><span>Fecha inicio*</span>
          	<input type="text" name="start_date" id="start_date" value="<?php echo $info['inicio']; ?>" size="10" />
          </label>
				</td>
      </tr>
    </table>
    
    <br clear="all" />
    
