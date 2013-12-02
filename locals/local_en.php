<?php
// Файл локализации en

// Локализация формы ввода
$title_form_local = 'Spectrum Analysis v. ';
$device_form_local = 'Device:';
$channel_form_local = 'Channel:';
$auto_form_local = 'auto';
$curves_form_local = 'Curves:';
$max_form_local = 'Maximum';
$smoothed_form_local = 'smoothed';
$standard_form_local = 'Standard:';
$empty_form_local = 'empty';
$show_image_form_local = 'show image';
$display_limits_form_local = 'Display limits:';
$mid_form_local = 'Average';
$display_conditional_range_form_local = 'Display conditional range';
$language_form_local = 'Language:';
$external_form_local = 'MHz &mdash; external';
$mod_form_local = 'Mode';
$level_form_local = 'level:';
$db_form_local = 'dBm';
$iterations_number_form_local = 'Number of iterations:';
$all_form_local = 'all';
$internal_form_local = 'MHz &mdash; internal';
$med_form_local = 'Median';

// Локализация изображения
$device_name_local = 'Device';
$dots_local = 'Dots';
$iterations_local = 'iterations';
$time_local = 'time';
$sec_local = 's';
$for_local = 'for';
$channel_local = 'channel';
$hertz_local = 'MHz';
$khertz_local = 'kHz';
$frequency_local = 'Frequency';
$signal_local = 'Signal, dBm';
$maximum_local = 'Maximum';
$average_local = 'Average';
$mode_local = 'Mode';
$median_local = 'Median';

// Определение окончания числительного для канала (en)
function suffix($number_channel)
{
	if ($number_channel == 1)
	{
		$suffix = 'st';
	}
	else if ($number_channel == 2)
	{
		$suffix = 'nd';
	}
	else if ($number_channel == 3)
	{
		$suffix = 'rd';
	}
	else if ($number_channel >= 4 and $number_channel <= 14)
	{
		$suffix = 'th';
	}
	else
	{
		$suffix = false;
	}
	return $suffix;
}