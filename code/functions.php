<?php

// Функции для формирования максимального и среднего значений, моды и медианы

// Отступ в процентах
function signal_level($min, $max, $n) {
	return($min + $n * ($max - $min) / 100);
}

// Опеределение массива максимальных значений
function max_frequency_array($count, $all, $smooth, $dots) {
	$max_frequency = array(); // Максимальные значения (хорошо для слабых сигналов)
	$max_smooth_frequency = array(); // Максимальные сглаженные значения
	
	// Создание массива с максимальными значениями
	for ($i = 0; $i<=$count-1; $i++) {
		for ($j = 0; $j<=$dots; $j++) {
			if ($all[$i][$j] > $max_frequency[$j])
					$max_frequency[$j] = (int)$all[$i][$j];
		}
	}
	if ($smooth == true) {
		// Сглаживание графика максимальной кривой по пяти точкам в моменты выбросов
		$max_smooth_frequency[] = $max_frequency[0];
		$max_smooth_frequency[] = $max_frequency[1];
		for ($j = 2; $j<=$dots-2; $j++) {
			if ( abs($max_frequency[$j-1] - $max_frequency[$j]) >= 10 and abs($max_frequency[$j] - $max_frequency[$j+1]) >= 10 ) {
				$max_smooth_frequency[$j] = round(($max_frequency[$j-2] + $max_frequency[$j-1] + $max_frequency[$j] + $max_frequency[$j+1] + $max_frequency[$j+2]) / 5);
			} else {
				$max_smooth_frequency[$j] = $max_frequency[$j];
			}
		}
		$max_smooth_frequency[] = $max_frequency[$dots-1];
		$max_smooth_frequency[] = $max_frequency[$dots];
	}
	
	if ($smooth) {
		return $max_smooth_frequency;
	} else {
		return $max_frequency;
	}
}

// Опеределение максимального значения в ширине канала
function max_frequency_array_max($count, $all, $smooth, $dots, $device_type, $number_channel, $weight_channel)
{
	$max_frequency_array_channel = max_frequency_array($count, $all, $smooth, $dots);
	$level_max = -110;
	
	$correction = correction($number_channel);
		
	switch ($device_type) {
	case 0:
		$i_begin = $number_channel * 10 + 16 + $correction - $weight_channel;
		$i_end   = $number_channel * 10 + 16 + $correction + $weight_channel;
		break;
		
	case 1:
		$i_begin = $number_channel * 5 + 8 + ($correction - $weight_channel) / 2;
		$i_end   = $number_channel * 5 + 8 + ($correction + $weight_channel) / 2;
		break;
		
	case 2:
		$i_begin = $number_channel * 10 + 14 + $correction - $weight_channel;
		$i_end   = $number_channel * 10 + 14 + $correction + $weight_channel;
		break;
		
	case 3:
		$i_begin = 10 * ($number_channel * 10 + 14 + $correction - $weight_channel);
		$i_end   = 10 * ($number_channel * 10 + 14 + $correction + $weight_channel);
		break;
		
	case 4:
		$i_begin = $number_channel * 5 + 7 + ($correction - $weight_channel) / 2;
		$i_end   = $number_channel * 5 + 7 + ($correction + $weight_channel) / 2;
		break;
	
	case 5:
		$i_begin = 4 * ($number_channel * 10 + 14 -66 + $correction - $weight_channel);
		$i_end   = 4 * ($number_channel * 10 + 14 -66 + $correction + $weight_channel);
		break;
	}
	
	for ($i = $i_begin; $i <= $i_end; $i++) {
		if ($level_max < $max_frequency_array_channel[$i]) {
			$level_max = $max_frequency_array_channel[$i];
		}
	}
	return $level_max;
}

// Кривая со средними значениями
function calc_mid($count, $all, $number_channel, $weight_channel_border, $weight_channel_hit, $average_local, $dots) {
	$mid_frequency = array(); // Среднее арифметическое
		
	// Создание массива со средними значениями
	for ($j = 0; $j<=$dots; $j++) {
		$summa = 0;
		for ($i = 0; $i<=$count-1; $i++) {
			$summa += (int)$all[$i][$j];
		}
		$mid_frequency[$j] = round($summa * 10 / $count) / 10;
	}
	
	return $mid_frequency;
}

// Кривая с модой
function calc_mod($count, $all, $number_channel, $weight_channel_border, $weight_channel_hit, $mode_local, $dots) {
	$mod_frequency = array(); // Мода
	$temp_mod = array(); // Временный массив
	
	// Создание массива с модой
	for ($j = 0; $j<=$dots; $j++) {
		$max = 0;
		unset($temp_mod);
		$dbm1_max = -110;
		$dbm2_max = -110;
		
		for ($i = 0; $i<=$count-1; $i++) {
			$temp = $all[$i][$j];
			if ($temp_mod[$temp] == 0) {
				$temp_mod[$temp] = 1;
			} else {
				$temp_mod[$temp] += 1;
			}
			
			if ($temp_mod[$temp] > $max) {
				$max = $temp_mod[$temp];
				$dbm1_max = $temp;
			} else if ($temp_mod[$temp] == $max) {
				$dbm2_max = $temp;
			}
		}
		if ((int)$dbm1_max > (int)$dbm2_max) {
			$mod_frequency[$j] = (int)$dbm1_max;
		} else {
			$mod_frequency[$j] = (int)$dbm2_max;
		}
	}
	
	return $mod_frequency;
}

// Кривая с медианой
function calc_med($count, $all, $number_channel, $weight_channel_border, $weight_channel_hit, $median_local, $dots) {
	$med_frequency = array(); // Медиана (хорошо для постоянной передачи)
	$temp_med = array(); // Временный массив
	
	// Создание массива с медианой
	for ($j = 0; $j<=$dots; $j++) {
		unset($temp_med);
		for ($i = 0; $i<=$count-1; $i++) {
			$temp_med[$i] = (int)$all[$i][$j];
		}
		sort($temp_med);
		if ($count%2 == 1) {
			$med_frequency[$j] = $temp_med[$count/2];
		} else {
			$med_frequency[$j] = ( round( ($temp_med[round($count/2)-1] + $temp_med[round($count/2)]) * 10 )/2 ) / 10;
		}
	}
	
	return $med_frequency;
}

// Рисование условного спектра для 802.11a
function spectrum_g($number_channel, $level, $dots, $device_type, $type_channel) {
	$number_position_g = array();
	$number_position_g_temp = array(); // Временный массив
	
	$number_position_g['exist'] = true;
	
	// Уточнение для 802.11g и n
	if ($type_channel == 'g') {
		$number_channel_min = 1;
		$number_channel_max = 14;
		$add_channel_weight = 0;
	} else if ($type_channel == 'n') {
		$number_channel_min = 3;
		$number_channel_max = 11;
		$add_channel_weight = 16.5;
	} else {
		$number_position_g['exist'] = false;
		return $number_position_g;
	}
	
	// Коррекция для 14-го канала
	$correction = correction($number_channel);
	
	if ($number_channel >= $number_channel_min and $number_channel <= $number_channel_max) {
		switch ($device_type) {
		case 0:
			$number_position_g['min_f'] 		= 0;
			$number_position_g['min_f_30'] 		= $number_channel * 10 + 16 - 60 - $add_channel_weight;
			$number_position_g_temp['min_f_30'] = -30;
			$number_position_g['min_f_20'] 		= $number_channel * 10 + 16 - 40 - $add_channel_weight;
			$number_position_g_temp['min_f_20'] = -20;
			$number_position_g['min_f_11'] 		= $number_channel * 10 + 16 - 22 - $add_channel_weight;
			$number_position_g_temp['min_f_11'] = -11;
			$number_position_g['min_f_9'] 		= $number_channel * 10 + 16 - 18 - $add_channel_weight;
			$number_position_g['max_f_9'] 		= $number_channel * 10 + 16 + 18 + $add_channel_weight;
			$number_position_g['max_f_11'] 		= $number_channel * 10 + 16 + 22 + $add_channel_weight;
			$number_position_g_temp['max_f_11'] = 11;
			$number_position_g['max_f_20'] 		= $number_channel * 10 + 16 + 40 + $add_channel_weight;
			$number_position_g_temp['max_f_20'] = 20;
			$number_position_g['max_f_30'] 		= $number_channel * 10 + 16 + 60 + $add_channel_weight;
			$number_position_g_temp['max_f_30'] = 30;
			$number_position_g['max_f'] 		= $dots;
			
			$correction_scale = 2;
			break;
		
		case 1:
			$number_position_g['min_f'] 		= 0;
			$number_position_g['min_f_30'] 		= $number_channel * 5 + 8 - 30 - $add_channel_weight/2;
			$number_position_g_temp['min_f_30'] = -30;
			$number_position_g['min_f_20'] 		= $number_channel * 5 + 8 - 20 - $add_channel_weight/2;
			$number_position_g_temp['min_f_20'] = -20;
			$number_position_g['min_f_11'] 		= $number_channel * 5 + 8 - 11 - $add_channel_weight/2;
			$number_position_g_temp['min_f_11'] = -11;
			$number_position_g['min_f_9'] 		= $number_channel * 5 + 8 - 9 - $add_channel_weight/2;
			$number_position_g['max_f_9'] 		= $number_channel * 5 + 8 + 9 + $add_channel_weight/2;
			$number_position_g['max_f_11'] 		= $number_channel * 5 + 8 + 11 + $add_channel_weight/2;
			$number_position_g_temp['max_f_11'] = 11;
			$number_position_g['max_f_20'] 		= $number_channel * 5 + 8 + 20 + $add_channel_weight/2;
			$number_position_g_temp['max_f_20'] = 20;
			$number_position_g['max_f_30'] 		= $number_channel * 5 + 8 + 30 + $add_channel_weight/2;
			$number_position_g_temp['max_f_30'] = 30;
			$number_position_g['max_f'] 		= $dots;
			
			$correction_scale = 1;
			$correction /= 2;
			break;
			
		case 2:
			$number_position_g['min_f'] 		= 0;
			$number_position_g['min_f_30'] 		= $number_channel * 10 + 14 - 60 - $add_channel_weight;
			$number_position_g_temp['min_f_30'] = -30;
			$number_position_g['min_f_20'] 		= $number_channel * 10 + 14 - 40 - $add_channel_weight;
			$number_position_g_temp['min_f_20'] = -20;
			$number_position_g['min_f_11'] 		= $number_channel * 10 + 14 - 22 - $add_channel_weight;
			$number_position_g_temp['min_f_11'] = -11;
			$number_position_g['min_f_9'] 		= $number_channel * 10 + 14 - 18 - $add_channel_weight;
			$number_position_g['max_f_9'] 		= $number_channel * 10 + 14 + 18 + $add_channel_weight;
			$number_position_g['max_f_11'] 		= $number_channel * 10 + 14 + 22 + $add_channel_weight;
			$number_position_g_temp['max_f_11'] = 11;
			$number_position_g['max_f_20'] 		= $number_channel * 10 + 14 + 40 + $add_channel_weight;
			$number_position_g_temp['max_f_20'] = 20;
			$number_position_g['max_f_30'] 		= $number_channel * 10 + 14 + 60 + $add_channel_weight;
			$number_position_g_temp['max_f_30'] = 30;
			$number_position_g['max_f'] 		= $dots;
			
			$correction_scale = 2;
			break;
			
		case 3:
			$number_position_g['min_f'] 		= 0;
			$number_position_g['min_f_30'] 		= 10 * ($number_channel * 10 + 14 - 60 - $add_channel_weight);
			$number_position_g_temp['min_f_30'] = -30;
			$number_position_g['min_f_20'] 		= 10 * ($number_channel * 10 + 14 - 40 - $add_channel_weight);
			$number_position_g_temp['min_f_20'] = -20;
			$number_position_g['min_f_11'] 		= 10 * ($number_channel * 10 + 14 - 22 - $add_channel_weight);
			$number_position_g_temp['min_f_11'] = -11;
			$number_position_g['min_f_9'] 		= 10 * ($number_channel * 10 + 14 - 18 - $add_channel_weight);
			$number_position_g['max_f_9'] 		= 10 * ($number_channel * 10 + 14 + 18 + $add_channel_weight);
			$number_position_g['max_f_11'] 		= 10 * ($number_channel * 10 + 14 + 22 + $add_channel_weight);
			$number_position_g_temp['max_f_11'] = 11;
			$number_position_g['max_f_20'] 		= 10 * ($number_channel * 10 + 14 + 40 + $add_channel_weight);
			$number_position_g_temp['max_f_20'] = 20;
			$number_position_g['max_f_30'] 		= 10 * ($number_channel * 10 + 14 + 60 + $add_channel_weight);
			$number_position_g_temp['max_f_30'] = 30;
			$number_position_g['max_f'] 		= $dots;
			
			$correction_scale = 20;
			$correction *= 10;
			break;
			
		case 4:
			$number_position_g['min_f'] 		= 0;
			$number_position_g['min_f_30'] 		= $number_channel * 5 + 7 - 30 - $add_channel_weight/2;
			$number_position_g_temp['min_f_30'] = -30;
			$number_position_g['min_f_20'] 		= $number_channel * 5 + 7 - 20 - $add_channel_weight/2;
			$number_position_g_temp['min_f_20'] = -20;
			$number_position_g['min_f_11'] 		= $number_channel * 5 + 7 - 11 - $add_channel_weight/2;
			$number_position_g_temp['min_f_11'] = -11;
			$number_position_g['min_f_9'] 		= $number_channel * 5 + 7 - 9 - $add_channel_weight/2;
			$number_position_g['max_f_9'] 		= $number_channel * 5 + 7 + 9 + $add_channel_weight/2;
			$number_position_g['max_f_11'] 		= $number_channel * 5 + 7 + 11 + $add_channel_weight/2;
			$number_position_g_temp['max_f_11'] = 11;
			$number_position_g['max_f_20'] 		= $number_channel * 5 + 7 + 20 + $add_channel_weight/2;
			$number_position_g_temp['max_f_20'] = 20;
			$number_position_g['max_f_30'] 		= $number_channel * 5 + 7 + 30 + $add_channel_weight/2;
			$number_position_g_temp['max_f_30'] = 30;
			$number_position_g['max_f'] 		= $dots;
			
			$correction_scale = 1;
			$correction /= 2;
			break;
		}
		
		$number_position_g['min_dBm_9'] = $level;
		$number_position_g['max_dBm_9'] = $level;
		
		if ($number_position_g['min_f_11'] < 0) {
			$number_position_g_temp['min_f_11'] -= $number_position_g['min_f_11'] / $correction_scale;
			$number_position_g['min_f_11'] = 0;
			$number_position_g['min_dBm_11'] = $level + 10 * $number_position_g_temp['min_f_11'] + 90;
		} else {
			$number_position_g['min_dBm_11'] = $level + 10 * $number_position_g_temp['min_f_11'] + 90;
			
			if ($number_position_g['min_f_20'] < 0) {
				$number_position_g_temp['min_f_20'] -= $number_position_g['min_f_20'] / $correction_scale;
				$number_position_g['min_f_20'] = 0;
				$number_position_g['min_dBm_20'] = $level + 8 * $number_position_g_temp['min_f_20'] / 9 - 92 / 9;
			} else {
				$number_position_g['min_dBm_20'] = $level + 8 * $number_position_g_temp['min_f_20'] / 9 - 92 / 9;
				
				if ($number_position_g['min_f_30'] < 0) {
					$number_position_g_temp['min_f_30'] -= $number_position_g['min_f_30'] / $correction_scale;
					$number_position_g['min_f_30'] = 0;
					$number_position_g['min_dBm_30'] = $level + 6 * $number_position_g_temp['min_f_30'] / 5 - 4;
				} else {
					$number_position_g['min_dBm_30'] = $level + 6 * $number_position_g_temp['min_f_30'] / 5 - 4;
					$number_position_g['min_dBm'] = $level - 40;
				}
			}
		}
		
		// Так как на некоторых устройсвах 14-й канал виден частично, то требуется переопределять только максимальные значения, 
		// а минимальные - только смещать на 7 МГц
		$number_position_g['min_f_30'] += $correction;	
		$number_position_g['min_f_20'] += $correction;
		$number_position_g['min_f_11'] += $correction;
		$number_position_g['min_f_9'] += $correction;
		$number_position_g['max_f_9'] += $correction;
		$number_position_g['max_f_11'] += $correction;
		$number_position_g['max_f_20'] += $correction;
		$number_position_g['max_f_30'] += $correction;

		// Если основная полоса канала не вмещается
		if ($number_position_g['max_f_9'] > $dots) {
			$number_position_g['max_f_9'] = $dots;
		}
		
		if ($number_position_g['max_f_11'] > $dots)	{
			$number_position_g_temp['max_f_11'] = $number_position_g_temp['max_f_11'] - ($number_position_g['max_f_11'] - $dots) / $correction_scale;
			$number_position_g['max_f_11'] = $dots;
			$number_position_g['max_dBm_11'] = $level - 10 * $number_position_g_temp['max_f_11'] + 90;
		} else {
			$number_position_g['max_dBm_11'] = $level - 10 * $number_position_g_temp['max_f_11'] + 90;
			
			if ($number_position_g['max_f_20'] > $dots) {
				$number_position_g_temp['max_f_20'] = $number_position_g_temp['max_f_20'] - ($number_position_g['max_f_20'] - $dots) / $correction_scale;
				$number_position_g['max_f_20'] = $dots;
				$number_position_g['max_dBm_20'] = $level - 8 * $number_position_g_temp['max_f_20'] / 9 - 92 / 9;
			} else {
				$number_position_g['max_dBm_20'] = $level - 8 * $number_position_g_temp['max_f_20'] / 9 - 92 / 9;
				
				if ($number_position_g['max_f_30'] > $dots) {
					$number_position_g_temp['max_f_30'] = $number_position_g_temp['max_f_30'] - ($number_position_g['max_f_30'] - $dots) / $correction_scale;
					$number_position_g['max_f_30'] = $dots;
					$number_position_g['max_dBm_30'] = $level - 6 * $number_position_g_temp['max_f_30'] / 5 - 4;
				} else {
					$number_position_g['max_dBm_30'] = $level - 6 * $number_position_g_temp['max_f_30'] / 5 - 4;
					$number_position_g['max_dBm'] = $level - 40;
				}
			}
		}
	} else {
		$number_position_g['exist'] = false;
	}
	
	return $number_position_g;
}

// Описание графика кривой
function grafic($frequencies, $colour, $curves_name_local) {
	$grafic = new solid_dot();
	$grafic->size(2)->halo_size(1)->colour($colour);

	$line = new line();
	$line->set_default_dot_style($grafic);
	$line->set_values($frequencies);
	$line->set_width(2);
	$line->set_colour($colour);
	$line->set_key($curves_name_local, 16);
	
	return $line;
}

// Коррекция для 14-го канала
function correction($number_channel) {
	$correction = 0;
	if ($number_channel == 14) {
		$correction = 14;
	}
	return $correction;
}