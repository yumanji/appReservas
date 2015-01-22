<?php 
 //print("<pre>");print_r($info); print("</pre>");
?>
<?php
//$this->lang->load('lessons');

$disabled = '';
if($info['started'] == '1') $disabled = 'disabled';
?>
  <form class="form_user" name="formRanking" id="formRanking" method="post" action="<?php echo current_url(); ?>">
  	<input name="id" id="id" type="hidden" value="<?php echo $info['id'];?>"/>
  	<input name="action" id="action" type="hidden" value=""/>
    <table width="100%" border="0" cellspacing="10" class="nota">
      <tr>
        <td width="470" valign="top">
        	<label><span>Nombre*</span>
              <input name="description" type="text" id="description" value="<?php echo $info['description']; ?>" size="25" />
          </label>
            <label><span>Fecha inicio*</span>
            <input type="text" name="start_date" id="start_date" <?php echo $disabled; ?> value="<?php echo $info['inicio']; ?>" size="10" />
            </label>
            <label><span>Fecha fin*</span>
            <input type="text" name="end_date" id="end_date" <?php echo $disabled; ?> value="<?php echo $info['final']; ?>" size="10" />
            </label>
            <label><span>N&ordm; grupos*</span>
						<input type="text" name="groups" id="groups" <?php echo $disabled; ?> size="2" value="<?php echo $info['groups']; ?>" alt="integer" />
          	</label>
            <label><span>Equipos / Grupo*</span>
						<input type="text" name="teams" id="teams" <?php echo $disabled; ?> size="2" value="<?php echo $info['teams']; ?>" alt="integer" />
          	</label>
            <label><span>Usuarios / Equipo*</span>
						<input type="text" name="team_mates" id="team_mates" <?php echo $disabled; ?> size="2" value="<?php echo $info['team_mates']; ?>" alt="integer" />
          	</label>

				</td>
        <td width="476" >
            <label><span>Deporte</span>
							<select name="sport" id="sport" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($deportes	 as $code => $deporte)
									{
										if($info['sport']==$code) echo '<option value="'.$code.'" selected>'.$deporte.'</option>';
										else echo '<option value="'.$code.'">'.$deporte.'</option>';
									}
								?>
							</select>
            </label>
						<label><span>G&eacute;nero</span>
							<select name="gender" id="gender" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($generos	 as $code => $genero)
									{
										if($info['gender']==$code) echo '<option value="'.$code.'" selected>'.$genero.'</option>';
										else echo '<option value="'.$code.'">'.$genero.'</option>';
									}
								?>
							</select>
						</label>
						<label><span>Tipo promocion*</span>
							<select name="promotion_type" id="promotion_type" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($promociones	 as $code => $promocion)
									{
										if($info['promotion_type']==$code) echo '<option value="'.$code.'" selected>'.$promocion.'</option>';
										else echo '<option value="'.$code.'">'.$promocion.'</option>';
									}
								?>
							</select>
						</label>
						<label><span>Tarifa*</span>
							<select name="price" id="price" <?php echo $disabled; ?> >
								<?php 
								//pinto combo de niveles
									$seleccionar = "";
									foreach($tarifas	 as $code => $tarifa)
									{
										if($info['price']==$code) echo '<option value="'.$code.'" selected>'.$tarifa.'</option>';
										else echo '<option value="'.$code.'">'.$tarifa.'</option>';
									}
								?>
							</select>
						</label>
        		<label> <span>Cuota alta</span>
              <input type="text" name="signin" id="signin" value="<?php echo $info['signin']; ?>" size="10"  alt="dinero" />
          	</label>
            <label><span>Activo</span>
            <input type="checkbox" name="active" id="active" value="1" <?php if($info['active']) echo 'checked'; ?>/>
            </label>

				</td>
      </tr>
      <tr>
      	<td colspan="2">
      		aa
      	</td>
      </tr>
    </table>
    
    <br clear="all" />
    
