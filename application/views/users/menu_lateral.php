<div class="arbol">
	<ul id="files">
	<li><a href="documents">Listados</a>
		<ul>
			<!--
			<li><a href="documents/Christines_Files/">2010</a>
				<ul>
					<li><a href="#">enero</a></li>
				</ul>
			</li>
			-->
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

</ul>
</div>