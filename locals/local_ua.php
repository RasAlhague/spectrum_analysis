<?php
// ���� ����������� ua

// ����������� ����� �����
$title_form_local = '��������� ������� �. ';
$device_form_local = '�������:';
$channel_form_local = '�����:';
$auto_form_local = '����';
$curves_form_local = '����:';
$max_form_local = '��������';
$smoothed_form_local = '����������';
$standard_form_local = '��������:';
$empty_form_local = '�������';
$show_image_form_local = '���������� ����������';
$display_limits_form_local = '³��������� ���:';
$mid_form_local = '������';
$display_conditional_range_form_local = '³��������� ������� ������';
$language_form_local = '����:';
$external_form_local = '��� &mdash; �������';
$mod_form_local = '����';
$level_form_local = '�����:';
$db_form_local = '�����';
$iterations_number_form_local = 'ʳ���. ��������:';
$all_form_local = '��';
$internal_form_local = '��� &mdash; ��������';
$med_form_local = '������';

// ����������� �����������
$device_name_local = '�������';
$dots_local = '�����';
$iterations_local = '��������';
$time_local = '���';
$sec_local = '�';
$for_local = '���';
$channel_local = '�����';
$hertz_local = '���';
$khertz_local = '���';
$frequency_local = '�������';
$signal_local = 'г���� �������, �����';
$maximum_local = '��������';
$average_local = '������';
$mode_local = '����';
$median_local = '������';

// ����������� ��������� ������������� ��� ������ ��� ������������� ���������
function suffix($number_channel)
{
	$suffix = '-�';
	/* ����������� ��������� ������������� ��� ������ ��� ������������� ���������
	if ($number_channel >= 1 and $number_channel <= 14 and $number_channel != 3)
	{
		$suffix = '-��';
	}
	else if ($number_channel == 3)
	{
		$suffix = '-��';
	}
	else
	{
		$suffix = false;
	} */
	return $suffix;
}