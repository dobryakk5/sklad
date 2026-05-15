<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'BOX_ID' => array(
		'NAME' => 'ID бокса',
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	),
	'FLOOR_CODE' => array(
		'NAME' => 'Символьный код этажа склада',
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	),
	'SHOW_MAP' => array(
		'NAME' => 'Показывать схему этажа',
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
);
?>