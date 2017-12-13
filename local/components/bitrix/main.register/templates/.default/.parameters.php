<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arTemplateParameters = [
	'USER_PROPERTY_NAME' => [
		'NAME' => GetMessage('USER_PROPERTY_NAME'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	],
];