<?php 
//$this->lang->load('common', 'spanish');
echo doctype(); 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php 
		//$this->load->view('meta');
		echo $meta;
		if(isset($scripts)) echo $scripts;
	?>

    <script type="text/javascript">

        $(document).ready(function() {

            $("#Detailbtn").click(function() {  $(parent.window.document.location).attr("href","<?php echo site_url('lessons/detail/'.$event->id);?>"); });
            $("#Closebtn").click(function() { CloseModelWindow(); });


        });
    </script> 
    
</head>

<body class="twoColHybLtHdr">
   <noscript>This site just doesn't work, period, without JavaScript</noscript>




     
    <style type="text/css">     
    .calpick     {        
        width:16px;   
        height:16px;     
        border:none;        
        cursor:pointer;        
        background:url("<?php echo base_url(); ?>images/calendar/icons/cal.gif") no-repeat center 2px;        
        margin-left:-22px;    
    }      
    </style>
   
    <div>      
       <div class="toolBotton">           
       
        <?php if(isset($event)){ ?>
        <a id="Detailbtn" class="imgbtn" href="javascript:void(0);">                    
          <span class="Detail" title="Ver detalle">Ver detalle
          </span>                
        </a>             
        <?php } ?>            
        
        <a id="Closebtn" class="imgbtn" href="javascript:void(0);">                
          <span class="Close" title="Close the window" >Close
          </span></a>            
        </a>        
      </div>                 
      <div style="clear: both">         
      </div>        
      <div class="infocontainer">            
        <form action="<?php echo site_url("lessons/datafeed"); ?>/adddetails<?php echo isset($event)?"/".$event->id:""; ?>" class="fform" id="fmEdit" method="post">                 
          <label>                    
            <b>*Curso: <?php echo isset($event)?$event->description:"" ?></b>
          </label>                 
          <label>                    
            *Nivel: <b><?php echo isset($event)? $event->level_desc:"No especificado"; ?></b>
          </label>                 
          <label>                    
            *Duraci&oacute;n: <?php echo isset($event)?'de <b>'.date($this->config->item('reserve_date_filter_format'),strtotime($event->start_date)).'</b>':""; ?> <?php echo isset($event)?' hasta <b>'.date($this->config->item('reserve_date_filter_format'),strtotime($event->end_date)).'</b>':""; ?>
          </label>                 
          <label>                    
            *Horario <?php echo isset($event)?'de <b>'.date($this->config->item('reserve_hour_filter_format'),strtotime($event->start_time)).'</b>':""; ?> <?php echo isset($event)?' a <b>'.date($this->config->item('reserve_hour_filter_format'),strtotime($event->end_time)).'</b>':""; ?>
          </label>                 
          <label>                    
            *Profesor: <b><?php echo isset($event)? $event->first_name.' '.$event->last_name:"No asignado"; ?></b>
          </label>                 
          <label>                    
            *Pista: <b><?php echo isset($event)? $event->court_desc:"No asignada"; ?></b>
          </label>                 
          <label>                    
            *Restricci&oacute;n sexo: <b><?php echo isset($event)? $event->gender_desc:"No especificado"; ?></b>
          </label>                 
          <label>                    
            <?php echo isset($event)? (($event->current_vacancies>0)?'*'.$event->current_vacancies.' plazas disponibles de '.$event->max_vacancies:"<span style=\"color:red;\">Todas las plazas est&aacute;n ocupadas</span>"):"Desconocidas"; ?>
          </label>             
          <input id="timezone" name="timezone" type="hidden" value="" />           
        </form>         
      </div>         
    </div>


    </body>
    

</html>
