<script type="text/javascript">
    $(document).ready(function() {     
       var view="month";          
       
        var DATA_FEED_URL = "<?php echo site_url("lessons/datafeed_detail/".$lesson); ?>";
        var op = {
            view: view,
            theme:3,
            showday: new Date(),
            EditCmdhandler:Edit,
            DeleteCmdhandler:Delete,
            ViewCmdhandler:View,    
            onWeekOrMonthToDay:wtd,
            onBeforeRequestData: cal_beforerequest,
            onAfterRequestData: cal_afterrequest,
            onRequestDataError: cal_onerror, 
            autoload:true,
            enableDrag:false,
            url: DATA_FEED_URL + "/list",  
            quickAddUrl: "", 
            quickUpdateUrl: "",
            quickDeleteUrl: ""        
        };
        var $dv = $("#calhead");
        var _MH = document.documentElement.clientHeight-190;
        var _MW = $("#general").width();
        //alert(_MW);
        $("#general").width(_MW);
        var dvH = $dv.height() + 2;
        //alert('mh:'+_MH+' y dv:'+dvH);
        op.height = _MH - dvH;
        op.eventItems =[];

        var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
        if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
        }
        $("#caltoolbar").noSelect();
        
        $("#hdtxtshow").datepicker({ picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
        onReturn:function(r){                          
                        var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
                        if (p && p.datestrshow) {
                            $("#txtdatetimeshow").text(p.datestrshow);
                        }
                 } 
        });
        function cal_beforerequest(type)
        {
            var t="Loading data...";
            switch(type)
            {
                case 1:
                    t="Loading data...";
                    break;
                case 2:                      
                case 3:  
                case 4:    
                    t="The request is being processed ...";                                   
                    break;
            }
            $("#errorpannel").hide();
            $("#loadingpannel").html(t).show();    
        }
        function cal_afterrequest(type)
        {
            switch(type)
            {
                case 1:
                    $("#loadingpannel").hide();
                    break;
                case 2:
                case 3:
                case 4:
                    $("#loadingpannel").html("Success!");
                    window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
                break;
            }              
           
        }
        function cal_onerror(type,data)
        {
            $("#errorpannel").show();
        }
        function Edit(data)
        {
           var eurl="<?php echo site_url("lessons/edit"); ?>/{0}";   
           /*var eurl="<?php echo site_url("lessons/edit"); ?>/{0}/{2}/{3}/{4}/{1}";   */
           /*var eurl="<?php echo site_url("lessons/edit"); ?>/{0}&start={2}&end={3}&isallday={4}&title={1}";  */ 
            if(data)
            {
                var url = StrFormat(eurl,data);
                OpenModelWindow(url,{ width: 450, height: 220, caption:"Datos del curso",onclose:function(){
                   $("#gridcontainer").reload();
                }});
            }
        }    
        function View(data)
        {
            var str = "";
            $.each(data, function(i, item){
                str += "[" + i + "]: " + item + "\n";
            });
            alert(str);               
        }    
        function Delete(data,callback)
        {           
            
            $.alerts.okButton="Ok";  
            $.alerts.cancelButton="Cancel";  
            hiConfirm("Are You Sure to Delete this Event", 'Confirm',function(r){ r && callback(0);});           
        }
        function wtd(p)
        {
           if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }
            $("#caltoolbar div.fcurrent").each(function() {
                $(this).removeClass("fcurrent");
            })
            $("#showdaybtn").addClass("fcurrent");
        }
        //to show day view
        $("#showdaybtn").click(function(e) {
            //document.location.href="#day";
            $("#caltoolbar div.fcurrent").each(function() {
                $(this).removeClass("fcurrent");
            })
            $(this).addClass("fcurrent");
            var p = $("#gridcontainer").swtichView("day").BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }
        });
        //to show week view
        $("#showweekbtn").click(function(e) {
            //document.location.href="#week";
            $("#caltoolbar div.fcurrent").each(function() {
                $(this).removeClass("fcurrent");
            })
            $(this).addClass("fcurrent");
            var p = $("#gridcontainer").swtichView("week").BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }

        });
        //to show month view
        $("#showmonthbtn").click(function(e) {
            //document.location.href="#month";
            $("#caltoolbar div.fcurrent").each(function() {
                $(this).removeClass("fcurrent");
            })
            $(this).addClass("fcurrent");
            var p = $("#gridcontainer").swtichView("month").BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }
        });
        
        $("#showreflashbtn").click(function(e){
            $("#gridcontainer").reload();
        });
        
        //Add a new event
        $("#faddbtn").click(function(e) {
            var url ="<?php echo site_url("lessons/edit"); ?>";
            OpenModelWindow(url,{ width: 500, height: 400, caption: "Crear nuevo curso"});
        });
        //go to today
        $("#showtodaybtn").click(function(e) {
            var p = $("#gridcontainer").gotoDate().BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }


        });
        //previous date range
        $("#sfprevbtn").click(function(e) {
            var p = $("#gridcontainer").previousRange().BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }

        });
        //next date range
        $("#sfnextbtn").click(function(e) {
            var p = $("#gridcontainer").nextRange().BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }
        });
        
    });
</script>
<div id="general">
  <div id="calhead" style="padding-left:1px;padding-right:1px;">          
    <div class="cHead"><div class="ftitle">Visor de cursos activos</div>
    <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Cargando datos...</div>
     <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">Lo sentimos. No hemos podido cargar los datos. Pruebe de nuevo más tarde.</div>
    </div>          
    
    <div id="caltoolbar" class="ctoolbar">
      <div id="faddbtn" class="fbutton">
        <div><span title='Click para crear un nuevo curso' class="addcal">

        Nuevo curso                
        </span></div>
    </div>
    <div class="btnseparator"></div>
     <div id="showtodaybtn" class="fbutton">
        <div><span title='Click para ir a la fecha actual' class="showtoday">
        Hoy</span></div>
    </div>
      <div class="btnseparator"></div>

    <div id="showdaybtn" class="fbutton">
        <div><span title='D&iacute;a' class="showdayview">D&iacute;a</span></div>
    </div>
      <div  id="showweekbtn" class="fbutton fcurrent">
        <div><span title='Semana' class="showweekview">Semana</span></div>
    </div>
      <div  id="showmonthbtn" class="fbutton">
        <div><span title='Mes' class="showmonthview">Mes</span></div>

    </div>
    <div class="btnseparator"></div>
      <div  id="showreflashbtn" class="fbutton">
        <div><span title='Actualizar vista' class="showdayflash">Actualizar</span></div>
        </div>
     <div class="btnseparator"></div>
    <div id="sfprevbtn" title="Prev"  class="fbutton">
      <span class="fprev"></span>

    </div>
    <div id="sfnextbtn" title="Next" class="fbutton">
        <span class="fnext"></span>
    </div>
    <div class="fshowdatep fbutton">
            <div>
                <input type="hidden" name="txtshow" id="hdtxtshow" />
                <span id="txtdatetimeshow">Cargando</span>

            </div>
    </div>
    
    <div class="clear"></div>
    </div>
  </div>
  <div style="padding-left:1px;">
    <div class="t1 chromeColor">
        &nbsp;</div>
    <div class="t2 chromeColor">
        &nbsp;</div>
    <div id="dvCalMain" class="calmain printborder">
        <div id="gridcontainer" style="overflow-y: visible;">
        </div>
    </div>
    <div class="t2 chromeColor">

        &nbsp;</div>
    <div class="t1 chromeColor">
        &nbsp;
    </div>   
    </div>
 
</div>