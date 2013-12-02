<?php
// Файл локализации ua

// Локализация формы ввода
$title_form_local = 'Аналізатор спектра в. ';
$device_form_local = 'Пристрій:';
$channel_form_local = 'Канал:';
$auto_form_local = 'авто';
$curves_form_local = 'Криві:';
$max_form_local = 'Максимум';
$smoothed_form_local = 'згладжений';
$standard_form_local = 'Стандарт:';
$empty_form_local = 'порожній';
$show_image_form_local = 'показувати зображення';
$display_limits_form_local = 'Відображати межі:';
$mid_form_local = 'Середнє';
$display_conditional_range_form_local = 'Відображати умовный спектр';
$language_form_local = 'Мова:';
$external_form_local = 'МГц &mdash; зовнішня';
$mod_form_local = 'Мода';
$level_form_local = 'рівень:';
$db_form_local = 'дБмВт';
$iterations_number_form_local = 'Кільк. ітерацій:';
$all_form_local = 'всі';
$internal_form_local = 'МГц &mdash; внутрішня';
$med_form_local = 'Медіана';

// Локализация изображения
$device_name_local = 'Пристрій';
$dots_local = 'Точок';
$iterations_local = 'ітерацій';
$time_local = 'час';
$sec_local = 'с';
$for_local = 'для';
$channel_local = 'канал';
$hertz_local = 'МГц';
$khertz_local = 'кГц';
$frequency_local = 'Частота';
$signal_local = 'Рівень сигналу, дБмВт';
$maximum_local = 'Максимум';
$average_local = 'Середнє';
$mode_local = 'Мода';
$median_local = 'Медіана';

// Определение окончания числительного для канала при однобуквенном окончании
function suffix($number_channel)
{
	$suffix = '-й';
	/* Определение окончания числительного для канала при двухбуквенном окончании
	if ($number_channel >= 1 and $number_channel <= 14 and $number_channel != 3)
	{
		$suffix = '-ий';
	}
	else if ($number_channel == 3)
	{
		$suffix = '-ій';
	}
	else
	{
		$suffix = false;
	} */
	return $suffix;
}