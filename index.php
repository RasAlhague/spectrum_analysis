<html>
<head>

<?php 
	// Номер версии
	$version = 6.1;
	
	// Подключение файла с локализацией
	if ($_POST['local'] == en or $_POST['local'] == ru or $_POST['local'] == ua)
	{
		include '/locals/local_'.$_POST['local'].'.php';
	}
	else
	{
		include '/locals/local_en.php';
	}
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title><?php echo $title_form_local.$version; ?></title>
	<script type="text/javascript" src="js/swfobject.js"></script>
	
	<style type="text/css">
		.alltext
		{
			font: 8pt sans-serif; 
		}
		select
		{
			font: 8pt sans-serif; 
		}
	</style>

</head>
<body>	
	
<?php 
	if(isset($_POST['submit'])) { 
	$request = $_POST['device_type'].'|'.$_POST['local'].'|'.$_POST['smooth'];
	$request .= '|'.$_POST['max_view'].'|'.$_POST['mid_view'].'|'.$_POST['mod_view'].'|'.$_POST['med_view'];
	$request .= '|'.$_POST['type_channel'].'|'.$_POST['number_channel'].'|'.$_POST['number_channel_auto'];
	$request .= '|'.$_POST['weight_channel_display'].'|'.$_POST['weight_channel_border'].'|'.$_POST['weight_channel_hit'];
	$request .= '|'.$_POST['level_display'].'|'.$_POST['level_auto'].'|'.$_POST['level'].'|'.$_POST['count'];
	//$request .= '|'.$_POST['signal_level_max'].'|'.$_POST['signal_level_min'];
?>
	<script type="text/javascript">
		swfobject.embedSWF(
		"open-flash-chart.swf", "my_chart", "1260", "670",
		"9.0.0", "expressInstall.swf",
		{"data-file":"spectrum_analysis_v<?php echo $version; ?>.php?request=<?php echo $request; ?>"} );
	</script>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table border="0" class="alltext" style="float: left; margin-left: 50px;" >
			<tr>
				<td valign="baseline">
					<?php echo $device_form_local; ?>
				</td>
				<td valign="baseline">
					<select name="device_type">
						<option <?php echo $_POST['device_type'] == '0' ? 'selected' : ''; ?> value="0">AirView2</option>
						<option <?php echo $_POST['device_type'] == '1' ? 'selected' : ''; ?> value="1">Wi-detector</option>
						<option <?php echo $_POST['device_type'] == '2' ? 'selected' : ''; ?> value="2">Wi-Spy 2.4x (500)</option>
						<option <?php echo $_POST['device_type'] == '3' ? 'selected' : ''; ?> value="3">Wi-Spy 2.4x (50)</option>
						<option <?php echo $_POST['device_type'] == '4' ? 'selected' : ''; ?> value="4">CYWUSB6935</option>
						<option <?php echo $_POST['device_type'] == '5' ? 'selected' : ''; ?> value="5">eZ430-RF2500</option>
					</select>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<?php echo $channel_form_local; ?>
					<input type="text" name="number_channel" value="<?php echo $_POST['number_channel_auto'] ? '' : $_POST['number_channel']; ?>" style="width: 30px">
					<input type="checkbox" <?php echo $_POST['number_channel_auto'] ? 'checked' : ''; ?> name="number_channel_auto" value="true"><?php echo $auto_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<?php echo $curves_form_local; ?>
				</td>
				<td valign="baseline">
					<input type="checkbox" <?php echo $_POST['max_view'] ? 'checked' : ''; ?> name="max_view" value="false"><?php echo $max_form_local; ?>
					<input type="checkbox" <?php echo $_POST['smooth'] ? 'checked' : ''; ?> name="smooth" value="true"><?php echo $smoothed_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<?php echo $standard_form_local; ?>
					<select name="type_channel">
						<option <?php echo $_POST['type_channel'] == '' ? 'selected' : ''; ?> value=""><?php echo $empty_form_local; ?></option>
						<option <?php echo $_POST['type_channel'] == 'b1' ? 'selected' : ''; ?> value="b1">802.11b 10 МГц</option>
						<option <?php echo $_POST['type_channel'] == 'b2' ? 'selected' : ''; ?> value="b2">802.11b 20 МГц</option>
						<option <?php echo $_POST['type_channel'] == 'g' ? 'selected' : ''; ?> value="g">802.11g 18 МГц</option>
						<option <?php echo $_POST['type_channel'] == 'n' ? 'selected' : ''; ?> value="n">802.11n 40 МГц</option>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="baseline">
				</td>
				<td valign="baseline">
					<input type="checkbox" <?php echo $_POST['image'] ? 'checked' : ''; ?> name="image" value="true"><?php echo $show_image_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<input type="checkbox" <?php echo ($_POST['weight_channel_display'] and ($_POST['number_channel_auto'] or $_POST['number_channel'] != '')) ? 'checked' : ''; ?> name="weight_channel_display" value="true"><?php echo $display_limits_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
				</td>
				<td valign="baseline">
					<input type="checkbox" <?php echo $_POST['mid_view'] ? 'checked' : ''; ?> name="mid_view" value="false"><?php echo $mid_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<input type="checkbox" <?php echo ($_POST['level_display'] and ($_POST['type_channel'] == g or $_POST['type_channel'] == n)) ? 'checked' : ''; ?> name="level_display" value="true"><?php echo $display_conditional_range_form_local; ?>
				</td>
			</tr>
			<tr>
				<td valign="baseline">
					<?php echo $language_form_local; ?>
				</td>
				<td valign="baseline">
					<select name="local">
						<option <?php echo $_POST['local'] == 'en' ? 'selected' : ''; ?> value="en">English</option>
						<option <?php echo $_POST['local'] == 'ru' ? 'selected' : ''; ?> value="ru">Русский</option>
						<option <?php echo $_POST['local'] == 'ua' ? 'selected' : ''; ?> value="ua">Українська</option>
					</select>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<input type="text" name="weight_channel_border" value="<?php echo $_POST['weight_channel_border']; ?>" style="width: 30px"><?php echo $external_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
				</td>
				<td valign="baseline">
					<input type="checkbox" <?php echo $_POST['mod_view'] ? 'checked' : ''; ?> name="mod_view" value="false"><?php echo $mod_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<?php echo $level_form_local; ?>
					<input type="text" name="level" value="<?php echo $_POST['level_auto'] ? '' : $_POST['level']; ?>" style="width: 30px"><?php echo $db_form_local; ?>
					<input type="checkbox" <?php echo $_POST['level_auto'] ? 'checked' : ''; ?> name="level_auto" value="true"><?php echo $auto_form_local; ?>
				</td>
			</tr>
			<tr>
				<td valign="baseline">
					<?php echo $iterations_number_form_local; ?>
				</td>
				<td valign="baseline">
					<input type="text" name="count" value="<?php echo $_POST['count'] == 0 ? '' : $_POST['count']; ?>" style="width: 40px">
					<input type="checkbox" <?php echo $_POST['count'] == 0 ? 'checked' : ''; ?> name="count" value="0"><?php echo $all_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<input type="text" name="weight_channel_hit" value="<?php echo $_POST['weight_channel_hit']; ?>" style="width: 30px"><?php echo $internal_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
				</td>
				<td valign="baseline">
					<input type="checkbox" <?php echo $_POST['med_view'] ? 'checked' : ''; ?> name="med_view" value="false"><?php echo $med_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline" align="right">
					<input type="submit" name="submit" value="     OK     " />
				</td>
			</tr>
		</table>
		
		<!--
		Максимум:
		<input type="text" name="signal_level_max" value="<?php echo $_POST['signal_level_max']; ?>">
		Минимум:
		<input type="text" name="signal_level_min" value="<?php echo $_POST['signal_level_min']; ?>"-->
	</form>
			
	<img src="images/
	<?php 
	if ($_POST['image'])
	{
		switch ($_POST['device_type'])
		{
		case 0:
			echo 'airview2.png';
			break;
			
		case 1:
			echo 'wi-detector.png';
			break;
			
		case 2:
			echo 'wi-spy.png';
			break;
			
		case 3:
			echo 'wi-spy.png';
			break;
	
		default:
			echo 'empty.png';
		}
	}
	else
	{
		echo 'empty.png';
	}
	?>" style="margin-left: 30px;" />
		
	<br style="clear:both";/>
	
<?php }else{ ?>
		
	<script type="text/javascript">
		swfobject.embedSWF(
		"open-flash-chart.swf", "my_chart", "1260", "670",
		"9.0.0", "expressInstall.swf",
		{"data-file":"spectrum_analysis_v<?php echo $version; ?>.php?request=<?php echo $request; ?>"} );
	</script>

	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table border="0" class="alltext" style="float: left; margin-left: 50px;" >
			<tr>
				<td valign="baseline">
					<?php echo $device_form_local; ?>
				</td>
				<td valign="baseline">
					<select name="device_type">
						<option selected value="-1">--</option>
						<option value="0">AirView2</option>
						<option value="1">Wi-detector</option>
						<option value="2">Wi-Spy 2.4x (500)</option>
						<option value="3">Wi-Spy 2.4x (50)</option>
						<option value="4">CYWUSB6935</option>
						<option value="5">eZ430-RF2500</option>
					</select>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<?php echo $channel_form_local; ?>
					<input type="text" name="number_channel" value="" style="width: 30px">
					<input type="checkbox" name="number_channel_auto" value="true"><?php echo $auto_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<?php echo $curves_form_local; ?>
				</td>
				<td valign="baseline">
					<input type="checkbox" checked name="max_view" value="false"><?php echo $max_form_local; ?>
					<input type="checkbox" name="smooth" value="true"><?php echo $smoothed_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<?php echo $standard_form_local; ?>
					<select name="type_channel">
						<option selected value=""><?php echo $empty_form_local; ?></option>
						<option value="b1">802.11b 10 МГц</option>
						<option value="b2">802.11b 20 МГц</option>
						<option value="g">802.11g 18 МГц</option>
						<option value="n">802.11n 40 МГц</option>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="baseline">
				</td>
				<td valign="baseline">
					<input type="checkbox" name="image" value="true"><?php echo $show_image_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<input type="checkbox" name="weight_channel_display" value="true"><?php echo $display_limits_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
				</td>
				<td valign="baseline">
					<input type="checkbox" name="mid_view" value="false"><?php echo $mid_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<input type="checkbox" name="level_display" value="true"><?php echo $display_conditional_range_form_local; ?>
				</td>
			</tr>
			<tr>
				<td valign="baseline">
					<?php echo $language_form_local; ?>
				</td>
				<td valign="baseline">
					<select name="local">
						<option selected value="en">English</option>
						<option value="ru">Русский</option>
						<option value="ua">Українська</option>
					</select>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<input type="text" name="weight_channel_border" value="" style="width: 30px"><?php echo $external_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
				</td>
				<td valign="baseline">
					<input type="checkbox" name="mod_view" value="false"><?php echo $mod_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<?php echo $level_form_local; ?>
					<input type="text" name="level" value="" style="width: 30px"><?php echo $db_form_local; ?>
					<input type="checkbox" name="level_auto" value="true"><?php echo $auto_form_local; ?>
				</td>
			</tr>
			<tr>
				<td valign="baseline">
					<?php echo $iterations_number_form_local; ?>
				</td>
				<td valign="baseline">
					<input type="text" name="count" value="" style="width: 40px">
					<input type="checkbox" checked name="count" value="0"><?php echo $all_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
					<input type="text" name="weight_channel_hit" value="" style="width: 30px"><?php echo $internal_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline">
				</td>
				<td valign="baseline">
					<input type="checkbox" name="med_view" value="false"><?php echo $med_form_local; ?>
				</td>
				<td width="20px">
				</td>
				<td valign="baseline" align="right">
					<input type="submit" name="submit" value="     OK     " />
				</td>
			</tr>
		</table>
	</form>

	<br style="clear:both";/>
	
<?php } ?>

	<div id="my_chart"></div>
	<!--a href="http://test1.ru/spectrum_analysis_v<?php echo $version; ?>.php?request=<?php echo $request; ?>" target="_blank">Генерировать JSON</a-->
 
</body>
</html>