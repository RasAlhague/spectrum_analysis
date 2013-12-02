<?php

include '/php-ofc-library/open-flash-chart.php';
include '/code/functions.php';
include '/code/classes.php';

list($device_type, $local, $smooth, $max_view, $mid_view, $mod_view, $med_view, $type_channel, $number_channel, $number_channel_auto, $weight_channel_display, $weight_channel_border, $weight_channel_hit, $level_display, $level_auto, $level, $count/*, $signal_level_max, $signal_level_min*/) = explode("|", $_GET['request']);

// Значение параметров по умолчанию и их описание
if ($device_type == '-1')
	include '/default_config.php';

$signal_level_max = -30; // Максимальный уровень сигнала
$signal_level_min = -120; // Минимальный уровень сигнала

// Автоматическое приведение ширины канала для построение условного спектра для 802.11a или его отключение
switch ($type_channel)
{
case 'b1':
	$weight_channel_hit = 10;
	$level_display = false;
	break;
	
case 'b2':
	$weight_channel_hit = 20;
	$level_display = false;
	break;
	
case 'g':
	$weight_channel_hit = 18;
	break;
	
case 'n':
	$weight_channel_hit = 40;
	break;
	
default:
	$type_channel = '';
	$level_display = false;
}

// Приведение внешней ширины канала к внутренней ширине при ширине спектра меньше 3 МГц
if ($weight_channel_border <= 3)
	$weight_channel_border = $weight_channel_hit;

// Подключение файла с локализацией
if ($local == en or $local == ru or $local == ua)
{
	include '/locals/local_'.$local.'.php';
}
else
{
	include '/locals/local_en.php';
}

// Параметры для разных устройств
$curves_data_class = new CurvesDataClass();
$curves_data_class->init($device_type, $khertz_local);

// Инициализация массивов
$all = array();
// Временные массивы
$borders = array(); // границ
$array_channel_energy = array(); // суммарных энергий каналов
$number_position = array(); // номера позиции
$number_position_g = array(); // номера позиции для 802.11a

// Массив с результатами по частотам
$all = $curves_data_class->parser();

// Количество пакетов для вычисления
$count == 0 ? $count = count($all) : $count;

// Расчет границ каналов
$curves_data_class->channel_borders($number_channel, $weight_channel_border, $weight_channel_hit);

// Определение канала c максимальным уровнем сигнала
if ($number_channel_auto)
{
	$max_frequency = max_frequency_array($count, $all, $smooth, $curves_data_class->dots);
	$number_channel = $curves_data_class->max_number_channel($max_frequency, $weight_channel_border, $weight_channel_hit, $type_channel);
}

// Вызов результатов для каждого графика (максимум, средние, мода и медиана)
if ($max_view)
{
	$max_frequency = max_frequency_array($count, $all, $smooth, $curves_data_class->dots);
	
	// График максимального значения
	$colour = '#a5a5a5';
	$line_max = grafic($max_frequency, $colour, $maximum_local);
	// Вызов уровеней сигнала для границ канала
	$borders_max = $curves_data_class->borders($max_frequency);
}
if ($mid_view)
{
	$mid_frequency = calc_mid($count, $all, $number_channel, $weight_channel_border, $weight_channel_hit, $average_local, $curves_data_class->dots);
	
	// График среднего значения
	$colour = '#535353';
	$line_mid = grafic($mid_frequency, $colour, $average_local);
	//Вызов уровеней сигнала для границ канала
	$borders_mid = $curves_data_class->borders($mid_frequency);
}
if ($mod_view)
{
	$mod_frequency = calc_mod($count, $all, $number_channel, $weight_channel_border, $weight_channel_hit, $mode_local, $curves_data_class->dots);
	
	// График моды
	$colour = '#797979';
	$line_mod = grafic($mod_frequency, $colour, $mode_local);
	//Вызов уровеней сигнала для границ канала
	$borders_mod = $curves_data_class->borders($mod_frequency);
}
if ($med_view)
{
	$med_frequency = calc_med($count, $all, $number_channel, $weight_channel_border, $weight_channel_hit, $median_local, $curves_data_class->dots);
	
	// График медианы
	$colour = '#303030';
	$line_med = grafic($med_frequency, $colour, $median_local);
	//Вызов уровеней сигнала для границ канала
	$borders_med = $curves_data_class->borders($med_frequency);
}
if (!$max_view and !$mid_view and !$mod_view and !$med_view)
{
	$max_frequency = max_frequency_array($count, $all, $smooth, $curves_data_class->dots);
	
	// График максимального значения
	$colour = '#a5a5a5';
	$line_max = grafic($max_frequency, $colour, $maximum_local);
	// Вызов уровеней сигнала для границ канала
	$borders_max = $curves_data_class->borders($max_frequency);
}

// Черно-белая диаграмма
//$title = new title($file_name);

// Интервал по шкале абсцисс (уровень сигнала)
$y = new y_axis();
$y->set_range($signal_level_min, $signal_level_max, 10);
$y->set_colours('#000000', '#e0e0e0');

$x_labels = new x_axis_labels();
$x_labels->set_steps($curves_data_class->steps_number);
$x_labels->set_colour('#000000');
$x_labels->set_labels($curves_data_class->frequency_labels);

$x = new x_axis();
$x->set_steps($curves_data_class->steps_number);
$x->set_colours ('#000000', '#e0e0e0');
$x->set_labels($x_labels);
$x->set_range(0, $curves_data_class->dots);

// Добавление легенды с названием устройства
$device_name_view = new ofc_tags();
$device_name_view->font('Verdana', 10)
				 ->colour('#000000')
				 ->align_x_left()
				 ->align_y_center();

$t = new ofc_tag($curves_data_class->dots+1, -28);
$t->text($device_name_local.': '.$curves_data_class->device_name);
$device_name_view->append_tag($t);

// Добавление легенды с количеством интервалов и временем
$count_view = new ofc_tags();
$count_view->font('Verdana', 10)
		   ->colour('#000000')
		   ->align_x_left()
		   ->align_y_center();

$t = new ofc_tag($curves_data_class->dots+1, -29.3);
$t->text($dots_local.': '.$curves_data_class->dots.', '.$iterations_local.': '.$count.', '.$time_local.': '.
	$curves_data_class->time_const*$count.' '.$sec_local.', '.$for_local.' IEEE 802.11'.substr($type_channel, 0, 1));
$count_view->append_tag($t);
	
// Добавление текста к каналу
// Номер канала
$tags_channel = new ofc_tags();
$tags_channel->font('Verdana', 14)
			 ->colour('#797979')
			 ->align_x_center();
	
$t = new ofc_tag($curves_data_class->indent_x($number_channel), signal_level($signal_level_min, $signal_level_max, -0.2));

// Подпись номера канала в зависимости от его ширины, близости к правому краю и типа канала
if ($type_channel == 'n')
{
	$number_channel_text = '('.($number_channel-2).'+'.($number_channel+2).')';
}
else
{
	$number_channel_text = $number_channel;
}

$suffix = suffix($number_channel);

if ($weight_channel_hit < 7 or $weight_channel_border < 7 or ($curves_data_class->positions['max_border'] - $curves_data_class->indent_x($number_channel)) < 5)
{
	$t->text($number_channel_text.$suffix);
}
else
{
	$t->text($number_channel_text.$suffix.' '.$channel_local);
}
$tags_channel->append_tag($t);

if ($weight_channel_border != $weight_channel_hit)
{
	// Внутренняя ширина
	$tags_hit = new ofc_tags();
	$tags_hit->font('Verdana', 10)
			 ->colour('#797979')
			 ->align_x_center();
    
	$t = new ofc_tag($curves_data_class->indent_x($number_channel), signal_level($signal_level_min, $signal_level_max, 6.1));
	
	$t->text($curves_data_class->label_hit().' '.$hertz_local);
	$tags_hit->append_tag($t);
}

// Внешняя ширина
$tags_border = new ofc_tags();
$tags_border->font('Verdana', 10)
			->colour('#797979')
			->align_x_center();

$t = new ofc_tag($curves_data_class->indent_x($number_channel), signal_level($signal_level_min, $signal_level_max, 3.2));

$t->text($curves_data_class->label_border().' '.$hertz_local);
$tags_border->append_tag($t);

// Стрелки
$a_border_left = new ofc_arrow($curves_data_class->positions['min_border'], 
							signal_level($signal_level_min, $signal_level_max, 4), 
							$curves_data_class->positions['max_border'], 
							signal_level($signal_level_min, $signal_level_max, 4), 
							'#797979', 6);
							
$a_border_right = new ofc_arrow($curves_data_class->positions['max_border'], 
							signal_level($signal_level_min, $signal_level_max, 4), 
							$curves_data_class->positions['min_border'], 
							signal_level($signal_level_min, $signal_level_max, 4), 
							'#797979', 6);
$a_hit_left = new ofc_arrow($curves_data_class->positions['min_hit'], 
							signal_level($signal_level_min, $signal_level_max, 7), 
							$curves_data_class->positions['max_hit'], 
							signal_level($signal_level_min, $signal_level_max, 7), 
							'#797979', 6);
							
$a_hit_right = new ofc_arrow($curves_data_class->positions['max_hit'], 
							signal_level($signal_level_min, $signal_level_max, 7),
							$curves_data_class->positions['min_hit'], 
							signal_level($signal_level_min, $signal_level_max, 7), 
							'#797979', 6);

// Создание временного массива для границ каналов по существующим кривым (порядок взят: максимальное, мода, медиана и среднее)
if ($borders_max['exist'] and count($borders_max) == 5)
{
	$borders = $borders_max;
}
else if ($borders_mod['exist'] and count($borders_mod) == 5)
{
	$borders = $borders_mod;
}
else if ($borders_med['exist'] and count($borders_med) == 5)
{
	$borders = $borders_med;
}
else if ($borders_mid['exist'] and count($borders_mid) == 5)
{
	$borders = $borders_mid;
}
else
{
	$borders = $borders_max;
}

// Границы канала
if ($weight_channel_display and $curves_data_class->positions['exist'])
{
	$a_min_border = new ofc_arrow($curves_data_class->positions['min_border'], 
								$signal_level_min, 
								$curves_data_class->positions['min_border'], 
								$borders['min_border'], 
								'#797979', 0);
	$a_min_hit = new ofc_arrow($curves_data_class->positions['min_hit'], 
								$signal_level_min, 
								$curves_data_class->positions['min_hit'], 
								$borders['min_hit'], 
								'#797979', 0);
	$a_max_hit = new ofc_arrow($curves_data_class->positions['max_hit'], 
								$signal_level_min, 
								$curves_data_class->positions['max_hit'], 
								$borders['max_hit'], 
								'#797979', 0);
	$a_max_border = new ofc_arrow($curves_data_class->positions['max_border'], 
								$signal_level_min, 
								$curves_data_class->positions['max_border'], 
								$borders['max_border'], 
								'#797979', 0);
}

$x_legend = new x_legend($frequency_local.', '.$hertz_local);
$x_legend->set_style('{font-size: 20px; color: #000000}');

$y_legend = new y_legend($signal_local);
$y_legend->set_style('{font-size: 20px; color: #000000}');

$chart = new open_flash_chart();
$chart->set_title($title);
if ($weight_channel_display and $curves_data_class->positions['exist'])
{
	$chart->add_element($a_min_border);
	$chart->add_element($a_max_border);
	
	$chart->add_element($tags_channel);
	
	if ($weight_channel_border != $weight_channel_hit)
	{
		$chart->add_element($a_min_hit);
		$chart->add_element($a_max_hit);
		$chart->add_element($tags_hit);
		$chart->add_element($a_hit_left);
		$chart->add_element($a_hit_right);
	}
		
	$chart->add_element($tags_border);
	$chart->add_element($a_border_left);
	$chart->add_element($a_border_right);
}

if (count($borders_max) == 5)
{
	$chart->add_element($line_max);
}
if (count($borders_mid) == 5)
{
	$chart->add_element($line_mid);
}
if (count($borders_mod) == 5)
{
	$chart->add_element($line_mod);
}
if (count($borders_med) == 5)
{
	$chart->add_element($line_med);
}
$chart->add_element($device_name_view);
$chart->add_element($count_view);
$chart->set_x_axis($x);
$chart->set_x_legend($x_legend);
$chart->set_y_legend($y_legend);
$chart->set_y_axis($y);
$chart->set_bg_colour('#FFFFFF');

// Вывод условного спектра для 802.11a

//echo '<br />'.$device_type.'<br />';

if ($level_display)
{
	// Автоматическое определение максимального уровня
	if ($level_auto)
	{
		$level = max_frequency_array_max($count, $all, $smooth, $curves_data_class->dots, $device_type, $number_channel, $weight_channel_hit);
	}

	$number_position_g = spectrum_g($number_channel, $level, $curves_data_class->dots, $device_type, $type_channel);

	if ($number_position_g['exist'] == true)
	{
		if ($number_position_g['min_f_30'] > 0)
		{
			$a_1 = new ofc_arrow($number_position_g['min_f'], 
								 $number_position_g['min_dBm'], 
								 $number_position_g['min_f_30'], 
								 $number_position_g['min_dBm_30'], 
								 '#bbbbbb', 0);
			$chart->add_element($a_1);
		}
		if ($number_position_g['min_f_20'] > 0)
		{
			$a_2 = new ofc_arrow($number_position_g['min_f_30'], 
								 $number_position_g['min_dBm_30'], 
								 $number_position_g['min_f_20'], 
								 $number_position_g['min_dBm_20'], 
								 '#bbbbbb', 0);
			$chart->add_element($a_2);
		}
		if ($number_position_g['min_f_11'] > 0)
		{
			$a_3 = new ofc_arrow($number_position_g['min_f_20'], 
								 $number_position_g['min_dBm_20'], 
								 $number_position_g['min_f_11'], 
								 $number_position_g['min_dBm_11'], 
								 '#bbbbbb', 0);
			$chart->add_element($a_3);
		}
		if ($number_position_g['min_f_9'] > 0)
		{
			$a_4 = new ofc_arrow($number_position_g['min_f_11'], 
								 $number_position_g['min_dBm_11'], 
								 $number_position_g['min_f_9'], 
								 $number_position_g['min_dBm_9'], 
								 '#bbbbbb', 0);
			$chart->add_element($a_4);
		}
		$a_5 = new ofc_arrow($number_position_g['min_f_9'], 
							 $number_position_g['min_dBm_9'], 
							 $number_position_g['max_f_9'], 
							 $number_position_g['max_dBm_9'], 
							 '#bbbbbb', 0);
		$chart->add_element($a_5);
		if ($number_position_g['max_f_9'] < $curves_data_class->dots)
		{
			$a_6 = new ofc_arrow($number_position_g['max_f_9'], 
								 $number_position_g['max_dBm_9'], 
								 $number_position_g['max_f_11'], 
								 $number_position_g['max_dBm_11'], 
								 '#bbbbbb', 0);
			$chart->add_element($a_6);
		}
		if ($number_position_g['max_f_11'] < $curves_data_class->dots)
		{
			$a_7 = new ofc_arrow($number_position_g['max_f_11'], 
								 $number_position_g['max_dBm_11'], 
								 $number_position_g['max_f_20'], 
								 $number_position_g['max_dBm_20'], 
								 '#bbbbbb', 0);
			$chart->add_element($a_7);
		}
		if ($number_position_g['max_f_20'] < $curves_data_class->dots)
		{
			$a_8 = new ofc_arrow($number_position_g['max_f_20'], 
								 $number_position_g['max_dBm_20'], 
								 $number_position_g['max_f_30'], 
								 $number_position_g['max_dBm_30'], 
								 '#bbbbbb', 0);
			$chart->add_element($a_8);
		}
		if ($number_position_g['max_f_30'] < $curves_data_class->dots)
		{
			$a_9 = new ofc_arrow($number_position_g['max_f_30'], 
								 $number_position_g['max_dBm_30'], 
								 $number_position_g['max_f'], 
								 $number_position_g['max_dBm'], 
								 '#bbbbbb', 0);
			$chart->add_element($a_9);
		}
	}
}

echo $chart->toPrettyString();