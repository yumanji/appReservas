<body>
<table width="565px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $header; ?></td>
  </tr>
  <tr>
    <td style="font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-style: normal; font-weight: normal; color: #000; padding-top: 5px; padding-bottom: 10px;">
			<?php if(isset($content) && $content!="") echo $content; ?>
		</td>
  </tr>
  <tr>
    <td><?php echo $footer; ?></td> 
  </tr>
</table>
</body>
