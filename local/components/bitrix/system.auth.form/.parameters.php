<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arComponentParameters = [
	'PARAMETERS' => [
		'REGISTER_URL' => [
			'NAME' => GetMessage('COMP_AUTH_FORM_REGISTER_URL'), 
			'TYPE' => 'STRING',
			'DEFAULT' => '',
		],
		'FORGOT_PASSWORD_URL' => [
			'NAME' => GetMessage('COMP_AUTH_FORM_FORGOT_PASSWORD_URL'), 
			'TYPE' => 'STRING',
			'DEFAULT' => '',
		],
		'PROFILE_URL' => [
			'NAME' => GetMessage('COMP_AUTH_FORM_PROFILE_URL'), 
			'TYPE' => 'STRING',
			'DEFAULT' => '',
		],
		'SHOW_ERRORS' => [
			'NAME' => GetMessage('COMP_AUTH_FORM_SHOW_ERRORS'), 
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		],
	],
];