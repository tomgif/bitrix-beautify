<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arComponentDescription = [
	'NAME' => GetMessage('CD_BSAC_NAME'),
	'DESCRIPTION' => GetMessage('CD_BCI1_DESCRIPTION'),
	'PATH' => [
		'ID' => 'utility',
		'CHILD' => [
			'ID' => 'user',
			'NAME' => GetMessage('MAIN_USER_GROUP_NAME')
		],
	],
];