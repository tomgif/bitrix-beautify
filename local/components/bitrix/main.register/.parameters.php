<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arFormFields = [
	'EMAIL' => 1,
	'TITLE' => 1,
	'NAME' => 1,
	'SECOND_NAME' => 1,
	'LAST_NAME' => 1,
	'AUTO_TIME_ZONE' => 1,
	'PERSONAL_PROFESSION' => 1,
	'PERSONAL_WWW' => 1,
	'PERSONAL_ICQ' => 1,
	'PERSONAL_GENDER' => 1,
	'PERSONAL_BIRTHDAY' => 1,
	'PERSONAL_PHOTO' => 1,
	'PERSONAL_PHONE' => 1,
	'PERSONAL_FAX' => 1,
	'PERSONAL_MOBILE' => 1,
	'PERSONAL_PAGER' => 1,
	'PERSONAL_STREET' => 1,
	'PERSONAL_MAILBOX' => 1,
	'PERSONAL_CITY' => 1,
	'PERSONAL_STATE' => 1,
	'PERSONAL_ZIP' => 1,
	'PERSONAL_COUNTRY' => 1,
	'PERSONAL_NOTES' => 1,
	'WORK_COMPANY' => 1,
	'WORK_DEPARTMENT' => 1,
	'WORK_POSITION' => 1,
	'WORK_WWW' => 1,
	'WORK_PHONE' => 1,
	'WORK_FAX' => 1,
	'WORK_PAGER' => 1,
	'WORK_STREET' => 1,
	'WORK_MAILBOX' => 1,
	'WORK_CITY' => 1,
	'WORK_STATE' => 1,
	'WORK_ZIP' => 1,
	'WORK_COUNTRY' => 1,
	'WORK_PROFILE' => 1,
	'WORK_LOGO' => 1,
	'WORK_NOTES' => 1,
];

if (!CTimeZone::Enabled()) {
	unset($arFormFields['AUTO_TIME_ZONE']);
}

$arUserFields = [];
foreach ($arFormFields as $value => $dummy) {
	$arUserFields[$value] = '[' . $value . '] ' . GetMessage('REGISTER_FIELD_' . $value);
}

$arRes = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('USER', 0, LANGUAGE_ID);
$userProp = [];
if (!empty($arRes)) {
	foreach ($arRes as $key => $val) {
		$userProp[$val['FIELD_NAME']] = $val['FIELD_NAME'];

		if (strLen($val['EDIT_FORM_LABEL']) > 0) {
			$userProp[$val['FIELD_NAME']] = $val['EDIT_FORM_LABEL'];
		}
	}
}

$arComponentParameters = [
	'PARAMETERS' => [
		'SHOW_FIELDS' => [
			'NAME' => GetMessage('REGISTER_SHOW_FIELDS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'VALUES' => $arUserFields,
			'PARENT' => 'BASE',
		],
		'REQUIRED_FIELDS' => [
			'NAME' => GetMessage('REGISTER_REQUIRED_FIELDS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'VALUES' => $arUserFields,
			'PARENT' => 'BASE',
		],
		'AUTH' => [
			'NAME' => GetMessage('REGISTER_AUTOMATED_AUTH'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'PARENT' => 'ADDITIONAL_SETTINGS',
		],
		'USE_BACKURL' => [
			'NAME' => GetMessage('REGISTER_USE_BACKURL'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'PARENT' => 'ADDITIONAL_SETTINGS',
		],
		'SUCCESS_PAGE' => [
			'NAME' => GetMessage('REGISTER_SUCCESS_PAGE'),
			'TYPE' => 'STRING',
			'DEFAULT' => '',
			'PARENT' => 'ADDITIONAL_SETTINGS',
		],
		'SET_TITLE' => [],
		'USER_PROPERTY' => [
			'PARENT' => 'ADDITIONAL_SETTINGS',
			'NAME' => GetMessage('USER_PROPERTY'),
			'TYPE' => 'LIST',
			'VALUES' => $userProp,
			'MULTIPLE' => 'Y',
			'DEFAULT' => [],
		],
	],
];