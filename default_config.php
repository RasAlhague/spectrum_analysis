<?php

// Ввод параметров
// 0 - Ubiquiti AirView2;
// 1 - Wi-detector;
// 2 - Metageek Wi-Spy 2.4x с дискретой 500,0 кГц;
// 3 - Metageek Wi-Spy 2.4x с дискретой 50,0 кГц, лучше не использовать сглаживание ($smooth = false);
// 4 - модуль CYWUSB6935 через LPT-порт
$device_type = 0;
$local = 'en'; // Доступные локализации: en, ru, ua
$signal_level_max = -30; // Максимальный уровень сигнала
$signal_level_min = -120; // Минимальный уровень сигнала
$smooth = false; // Сглаженные максимальные значения (для $device_type = 3 желательно использовать $smooth = false)

$max_view = false; // Отображать кривую максимальных значений
$mid_view = false; // Отображать кривую средних арифметических значений
$mod_view = false; // Отображать кривую модальных значений
$med_view = false; // Отображать кривую медианных значений

$number_channel_auto = true; // Автоматическое определение номера канала
$number_channel = 8; // Номер канала вручную (работает при $number_channel_auto = false и показывает достоверный результат при ширине канала 2 не более 22 МГц)
$type_channel = 'n'; // В зависимости от типа устанавливает значение по умолчанию для условного спектра 802.11a, приоритеней, чем $weight_channel_hit
					// b1 - не показывает спектр 802.11a, ищет максимальный канал на 10 МГц; 
					// b2 - не показывает спектр 802.11a, ищет максимальный канал на 20 МГц; 
					// g - показывает спектр 802.11a, ищет максимальный канал на 18 МГц; 
					// n - показывает спектр 802.11a, ищет максимальный канал на 40 МГц, 

$weight_channel_display = false; // Отображать рамки канала
$weight_channel_border = 0; // Внешняя ширина канала (обычно не менее 11 МГц). При значении меньше 3 МГц спектр не отображается
$weight_channel_hit = 18; // Внутренняя ширина канала (обычно не менее 10 МГц). При значении меньше 3 МГц спектр не отображается

$level_display = false; // Отображать условный спектра для 802.11a
$level_auto = true; // Автоматическое определение максимального уровня
$level = -42; // Уровень сигнала в канале вручную

$count = 0; // Количество пакетов для вычисления, при $count = 0 стоится по всем точкам