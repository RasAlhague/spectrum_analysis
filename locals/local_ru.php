<?php
// Файл локализации ru

// Локализация формы ввода
$title_form_local = 'Анализатор спектра в. ';
$device_form_local = 'Устройство:';
$channel_form_local = 'Канал:';
$auto_form_local = 'авто';
$curves_form_local = 'Кривые:';
$max_form_local = 'Максимум';
$smoothed_form_local = 'сглаженный';
$standard_form_local = 'Стандарт:';
$empty_form_local = 'пустой';
$show_image_form_local = 'показывать изображение';
$display_limits_form_local = 'Отображать границы:';
$mid_form_local = 'Среднее';
$display_conditional_range_form_local = 'Отображать условный спектр';
$language_form_local = 'Язык:';
$external_form_local = 'МГц &mdash; внешняя';
$mod_form_local = 'Мода';
$level_form_local = 'уровень:';
$db_form_local = 'дБмВт';
$iterations_number_form_local = 'Кол-во итераций:';
$all_form_local = 'все';
$internal_form_local = 'МГц &mdash; внутренняя';
$med_form_local = 'Медиана';

// Локализация изображения
$device_name_local = 'Устройство';
$dots_local = 'Точек';
$iterations_local = 'итераций';
$time_local = 'время';
$sec_local = 'с';
$for_local = 'для';
$channel_local = 'канал';
$hertz_local = 'МГц';
$khertz_local = 'кГц';
$frequency_local = 'Частота';
$signal_local = 'Уровень сигнала, дБмВт';
$maximum_local = 'Максимум';
$average_local = 'Среднее';
$mode_local = 'Мода';
$median_local = 'Медиана';

// Определение окончания числительного для канала при однобуквенном окончании
function suffix($number_channel)
{
	$suffix = '-й';
	/* Определение окончания числительного для канала при двухбуквенном окончании
	if ($number_channel == 1 or $number_channel == 4 or $number_channel == 5 or ($number_channel >= 9 and $number_channel <= 14))
	{
		$suffix = '-ый';
	}
	else if ($number_channel == 2 or ($number_channel >= 6 and $number_channel <= 8))
	{
		$suffix = '-ой';
	}
	else if ($number_channel == 3)
	{
		$suffix = '-ий';
	}
	else
	{
		$suffix = false;
	} */
	return $suffix;
}