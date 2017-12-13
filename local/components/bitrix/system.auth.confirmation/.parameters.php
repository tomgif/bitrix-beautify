<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arComponentParameters = [
	'PARAMETERS' => [
		'USER_ID' => [
			'NAME' => GetMessage('CP_BSAC_USER_ID'),
			'TYPE' => 'STRING',
			'DEFAULT' => 'confirm_user_id',
		],
		'CONFIRM_CODE' => [
			'NAME' => GetMessage('CP_BSAC_CONFIRM_CODE'),
			'TYPE' => 'STRING',
			'DEFAULT' => 'confirm_code',
		],
		'LOGIN' => [
			'NAME' => GetMessage('CP_BSAC_LOGIN'),
			'TYPE' => 'STRING',
			'DEFAULT' => 'login',
		],
	],
];