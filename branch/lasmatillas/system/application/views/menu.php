<?php
if (isset($menu) && count($menu)>0) {
	echo '<script type="text/javascript">
	$(document).ready(function () {




		$("ul.menu_body3 li:even").addClass("alt");
	
		$("img.menu_head3").click(function () {	
			$("ul.menu_body3").slideToggle("medium");	
		});
		$("ul.menu_body3 li a").mouseover(function () {
			$(this).animate({ fontSize: "12px", paddingLeft: "20px" }, 50 );
		});
		$("ul.menu_body3 li a").mouseout(function () {
			$(this).animate({ fontSize: "12px", paddingLeft: "5px" }, 50 );
		});


		$("ul.menu_body4 li:even").addClass("alt");
	
		$("img.menu_head4").click(function () {	
			$("ul.menu_body4").slideToggle("medium");	
		});
		$("ul.menu_body4 li a").mouseover(function () {
			$(this).animate({ fontSize: "12px", paddingLeft: "20px" }, 50 );
		});
		$("ul.menu_body4 li a").mouseout(function () {
			$(this).animate({ fontSize: "12px", paddingLeft: "5px" }, 50 );
		});


	});
	</script>';
	echo '<div id="sidebar1_1">';
	
	
			//print("<pre>");print_r(get_defined_vars());print("</pre>");
	echo '<ul class="menu" id="menu">';
			$list = array(); $i=1;
			foreach($menu as $option) {
				if(isset($option[2]) && is_array($option[2])) {
					print('<li>'.img( array('src'=>'images/menu2.png', "class"=>"menu_head".$i)).'&nbsp;'.anchor($option[1], $this->lang->line($option[0]), array('class' => 'menulink' ))."\r\n");
					
					print('<ul class="menu_body'.$i.'" id="menu_body'.$i.'">'."\r\n");
					foreach($option[2] as $suboption) {
						if(isset($suboption[3])) print('<li>'.anchor(current_url(), $this->lang->line($suboption[0]), array('onClick' => "javascript: f_open_window_max('".site_url($suboption[1])."', 'recepcion');" ))."\r\n");
						else print('<li>'.anchor($suboption[1], $this->lang->line($suboption[0]))."\r\n");	
					}
					print('</ul>'."\r\n");
					/*print('<ul>'."\r\n");
					foreach($option[2] as $suboption) {
						if(isset($suboption[2]) && is_array($suboption[2])) {
							print('<li>'.anchor($suboption[1], $this->lang->line($suboption[0]), array('class' => 'sub' )).' '."\r\n");
							//print('<li><a href="'.$suboption[1].'"  class="sub">'.$this->lang->line($suboption[0]).'</a>'."\r\n");
							print('<ul>'."\r\n");
							$i=0;
							foreach($suboption[2] as $subsuboption) {
								if($i==0) $extra='class="topline"';
								print('<li '.$extra.'>'.anchor($subsuboption[1], $this->lang->line($subsuboption[0])).'</li>'."\r\n");
								//print('<li '.$extra.'><a href="'.$subsuboption[1].'" >'.$this->lang->line($subsuboption[0]).'</a></li>'."\r\n");
								$i++;
							}
							print('</ul>'."\r\n");
							print('</li>'."\r\n");
						} else {
						print('<li>'.anchor($suboption[1], $this->lang->line($suboption[0]))."\r\n");						
						//print('<li><a href="'.$suboption[1].'" >'.$this->lang->line($suboption[0]).'</a>'."\r\n");						
						}
					}
					print('</ul>'."\r\n");*/
					print('</li>'."\r\n");
				} else {
					print('<li>'.img( array('src'=>'images/menu.png', "class"=>"menu_head".$i)).'&nbsp;'.anchor($option[1], $this->lang->line($option[0]), array('class'=>'menulink')).'</li>'."\r\n");
					//print('<li><a href="'.$option[1].'" class="menulink">'.$this->lang->line($option[0]).'</a></li>');				
				}
				$i++;
			}
	echo '</ul>';

	echo '</div>';
	//echo 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eleifend tellus eu lacus sagittis ut ultrices nunc vehicula. Nullam rhoncus pulvinar augue vitae bibendum. Proin fermentum accumsan lectus, placerat pellentesque sem lacinia vitae. Nulla facilisi. Vivamus tempor porttitor est sed tincidunt. In consequat fermentum quam, at aliquam ligula tristique ut. Curabitur condimentum risus at ipsum dignissim suscipit. Fusce sodales ligula in eros iaculis vel elementum dolor malesuada. Duis venenatis imperdiet luctus. In dui ligula, varius a commodo sed, lobortis sit amet lorem. Praesent eget enim augue, ut pretium orci. Donec rhoncus orci at libero luctus quis commodo neque gravida. Cras eros urna, varius vel tempus nec, sollicitudin volutpat velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed nec arcu dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur nisi ligula, consectetur ut lobortis a, tempus non odio. Phasellus non est in tortor lobortis congue. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.';
	//echo anchor('welcome/logout', $this->lang->line('logout'));
	echo "<br>".img( array('src'=>'images/menu_bottom.gif', "class"=>"menu_bottom"));
	//echo "".img( array('src'=>'images/menu_bottom2.gif', "class"=>"menu_bottom"));
	
}
?>