<div class="arbol">
	<ul id="files">
	<li id="mnu_reserva"><a href="documents">Reservas</a>
		<ul>
		  <li><a href="<?php echo site_url('reservas_gest/list_all'); ?>">Global</a></li>
		  <li><a href="<?php echo site_url('reservas_gest/list_all/future'); ?>">Futuras</a></li>
		  <li><a href="<?php echo site_url('reservas_gest/list_all/today'); ?>">Hoy</a></li>
		  <li><a href="<?php echo site_url('reservas_gest/list_all/week'); ?>">Semana actual</a></li>
		  <li><a href="<?php echo site_url('reservas_gest/list_all/last_week'); ?>">Semana pasada</a></li>
		  <li><a href="<?php echo site_url('reservas_gest/list_all/month'); ?>">Mes actual</a></li>
		  <li><a href="<?php echo site_url('reservas_gest/list_all/last_month'); ?>">Mes pasado</a></li>
			<li><a href="">Por pago</a>
                <ul>
                  <li><a href="<?php echo site_url('reservas_gest/list_all/unpaid'); ?>">No pagadas</a></li>
                  <li><a href="<?php echo site_url('reservas_gest/list_all/no_cost'); ?>">Sin coste</a></li>
                </ul>
		  </li>
		  <li><a href="<?php echo site_url('reservas_gest/list_all/canceled'); ?>">Canceladas</a></li>
		</ul>
	</li>
	<li id="mnu_fact"><a href="documents">Facturaci&oacute;n</a>
		<ul>
		  <li><a href="<?php echo site_url('facturacion/list_all'); ?>">Global</a></li>
		  <li><a href="<?php echo site_url('facturacion/list_all/today'); ?>">Hoy</a></li>
		  <li><a href="<?php echo site_url('facturacion/list_all/week'); ?>">Semana actual</a></li>
		  <li><a href="<?php echo site_url('facturacion/list_all/last_week'); ?>">Semana pasada</a></li>
		  <li><a href="<?php echo site_url('facturacion/list_all/month'); ?>">Mes actual</a></li>
		  <li><a href="<?php echo site_url('facturacion/list_all/last_month'); ?>">Mes pasado</a></li>
		  <li><a href="<?php echo site_url('facturacion/list_all_canceled'); ?>">Cancelados</a></li>
		  <li><a href="<?php echo site_url('facturacion/list_all/remesa'); ?>">Remesas enviadas</a></li>
		  <li><a href="<?php echo site_url('facturacion/list_all/remesa_pend'); ?>">Remesas pendientes</a></li>
		</ul>
	</li>
	<li id="mnu_user"><a href="documents">Usuarios</a>
		<ul>
		  <li><a href="<?php echo site_url('users/index'); ?>">Global</a></li>
		  <li><a href="<?php echo site_url('users/index/active'); ?>">Activos</a></li>
		  <li><a href="<?php echo site_url('users/index/inactive'); ?>">Inactivos</a></li>
			<li><a href="">Cuotas</a>
                <ul>
                  <li><a href="<?php echo site_url('users/index/quotas'); ?>">Con cuotas</a></li>
                  <li><a href="<?php echo site_url('users/index/next_quotas'); ?>">Vencimiento</a></li>
                  <li><a href="<?php echo site_url('users/index/old_quotas'); ?>">Impagados</a></li>
                </ul>
		  </li>
			<li><a href="">Por nivel</a>
                <ul>
                  <li><a href="<?php echo site_url('users/index/users'); ?>">Usuarios</a></li>
                  <li><a href="<?php echo site_url('users/index/members'); ?>">Socios</a></li>
                  <li><a href="<?php echo site_url('users/index/teacher'); ?>">Profesores</a></li>
                </ul>
		  </li>
		</ul>
	</li>

	<li><a href="photos">Categoria</a>
		<ul>
			<li><a href="#">Enlace</a></li>
			<li><a href="#">Enlace</a></li>
		</ul>
	</li>
</ul>
</div>