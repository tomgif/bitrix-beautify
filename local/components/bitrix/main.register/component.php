<?php

/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @global CUserTypeManager $USER_FIELD_MANAGER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponent $this
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

global $USER_FIELD_MANAGER;

// apply default param values
$arDefaultValues = [
	'SHOW_FIELDS' => [],
	'REQUIRED_FIELDS' => [],
	'AUTH' => 'Y',
	'USE_BACKURL' => 'Y',
	'SUCCESS_PAGE' => '',
];

foreach ($arDefaultValues as $key => $value) {
	if (!is_set($arParams, $key)) {
		$arParams[$key] = $value;
	}
}

if (!is_array($arParams['SHOW_FIELDS'])) {
	$arParams['SHOW_FIELDS'] = [];
}

if (!is_array($arParams['REQUIRED_FIELDS'])) {
	$arParams['REQUIRED_FIELDS'] = [];
}

// if user registration blocked - return auth form
if (COption::GetOptionString('main', 'new_user_registration', 'N') == 'N') {
	$APPLICATION->AuthForm([]);
}

$arResult['EMAIL_REQUIRED'] = (COption::GetOptionString('main', 'new_user_email_required', 'Y') <> 'N');

$arResult['USE_EMAIL_CONFIRMATION'] = 'N';
if (COption::GetOptionString('main', 'new_user_registration_email_confirmation', 'N') == 'Y') {
	$arResult['USE_EMAIL_CONFIRMATION'] = 'Y';
}

// apply core fields to user defined
$arDefaultFields = [
	'LOGIN',
	'PASSWORD',
	'CONFIRM_PASSWORD',
];

if ($arResult['EMAIL_REQUIRED']) {
	$arDefaultFields[] = 'EMAIL';
}

$def_group = COption::GetOptionString('main', 'new_user_registration_def_group', '');
if ($def_group <> '') {
	$arResult['GROUP_POLICY'] = CUser::GetGroupPolicy(explode(',', $def_group));
} else {
	$arResult['GROUP_POLICY'] = CUser::GetGroupPolicy([]);
}

$arResult['SHOW_FIELDS'] = array_unique(array_merge($arDefaultFields, $arParams['SHOW_FIELDS']));
$arResult['REQUIRED_FIELDS'] = array_unique(array_merge($arDefaultFields, $arParams['REQUIRED_FIELDS']));

// use captcha?
$arResult['USE_CAPTCHA'] = 'N';
if (COption::GetOptionString('main', 'captcha_registration', 'N') == 'Y') {
	$arResult['USE_CAPTCHA'] = 'Y';
}

// start values
$arResult['VALUES'] = [];
$arResult['ERRORS'] = [];
$register_done = false;

// register user
if ($_SERVER['REQUEST_METHOD'] == 'POST'
	&& !empty($_REQUEST['register_submit_button'])
	&& !$USER->IsAuthorized()) {
	if (COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y') {
		//possible encrypted user password
		$sec = new CRsaSecurity();
		if ($arKeys = $sec->LoadKeys()) {
			$sec->SetKeys($arKeys);
			$errno = $sec->AcceptFromForm(['REGISTER']);
			if ($errno == CRsaSecurity::ERROR_SESS_CHECK) {
				$arResult['ERRORS'][] = GetMessage('main_register_sess_expired');
			} elseif($errno < 0) {
				$arResult['ERRORS'][] = GetMessage('main_register_decode_err', [
					'#ERRCODE#' => $errno
				]);
			}
		}
	}

	// check emptiness of required fields
	foreach ($arResult['SHOW_FIELDS'] as $key) {
		if ($key != 'PERSONAL_PHOTO' && $key != 'WORK_LOGO') {
			$arResult['VALUES'][$key] = $_REQUEST['REGISTER'][$key];
			if (in_array($key, $arResult['REQUIRED_FIELDS'])
				&& trim($arResult['VALUES'][$key]) == '') {
				$arResult['ERRORS'][$key] = GetMessage('REGISTER_FIELD_REQUIRED');
			}
		} else {
			$_FILES['REGISTER_FILES_' . $key]['MODULE_ID'] = 'main';
			$arResult['VALUES'][$key] = $_FILES['REGISTER_FILES_' . $key];
			if (in_array($key, $arResult['REQUIRED_FIELDS'])
				&& !is_uploaded_file($_FILES['REGISTER_FILES_'.$key]['tmp_name'])) {
				$arResult['ERRORS'][$key] = GetMessage('REGISTER_FIELD_REQUIRED');
			}
		}
	}

	if (isset($_REQUEST['REGISTER']['TIME_ZONE'])) {
		$arResult['VALUES']['TIME_ZONE'] = $_REQUEST['REGISTER']['TIME_ZONE'];
	}

	$USER_FIELD_MANAGER->EditFormAddFields('USER', $arResult['VALUES']);

	//this is a part of CheckFields() to show errors about user defined fields
	if (!$USER_FIELD_MANAGER->CheckFields('USER', 0, $arResult['VALUES'])) {
		$e = $APPLICATION->GetException();
		$arResult['ERRORS'][] = substr($e->GetString(), 0, -4); //cutting '<br>'
		$APPLICATION->ResetException();
	}

	// check captcha
	if ($arResult['USE_CAPTCHA'] == 'Y') {
		if (!$APPLICATION->CaptchaCheckCode($_REQUEST['captcha_word'], $_REQUEST['captcha_sid'])) {
			$arResult['ERRORS'][] = GetMessage('REGISTER_WRONG_CAPTCHA');
		}
	}

	if (count($arResult['ERRORS']) > 0) {
		if (COption::GetOptionString('main', 'event_log_register_fail', 'N') === 'Y') {
			$arError = $arResult['ERRORS'];
			foreach ($arError as $key => $error) {
				if(intval($key) == 0 && $key !== 0)  {
					$arError[$key] = str_replace('#FIELD_NAME#', '"' . $key . '"', $error);
				}
			}

			CEventLog::Log('SECURITY', 'USER_REGISTER_FAIL', 'main', false, implode('<br>', $arError));
		}
	} else { // if there;s no any errors - create user
		$bConfirmReq = (COption::GetOptionString('main', 'new_user_registration_email_confirmation', 'N') == 'Y' && $arResult['EMAIL_REQUIRED']);

		$arResult['VALUES']['CHECKWORD'] = md5(CMain::GetServerUniqID() . uniqid());
		$arResult['VALUES']['~CHECKWORD_TIME'] = $DB->CurrentTimeFunction();

		$arResult['VALUES']['ACTIVE'] = 'Y';
		if ($bConfirmReq) {
			$arResult['VALUES']['ACTIVE'] = 'N';
		}

		$arResult['VALUES']['CONFIRM_CODE'] = '';
		if ($bConfirmReq) {
			$arResult['VALUES']['CONFIRM_CODE'] = randString(8);
		}

		$arResult['VALUES']['LID'] = SITE_ID;
		$arResult['VALUES']['LANGUAGE_ID'] = LANGUAGE_ID;

		$arResult['VALUES']['USER_IP'] = $_SERVER['REMOTE_ADDR'];
		$arResult['VALUES']['USER_HOST'] = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		if ($arResult['VALUES']['AUTO_TIME_ZONE'] <> 'Y'
			&& $arResult['VALUES']['AUTO_TIME_ZONE'] <> 'N') {
			$arResult['VALUES']['AUTO_TIME_ZONE'] = '';
		}

		$def_group = COption::GetOptionString('main', 'new_user_registration_def_group', '');
		if ($def_group != '') {
			$arResult['VALUES']['GROUP_ID'] = explode(',', $def_group);
		}

		$bOk = true;

		$events = GetModuleEvents('main', 'OnBeforeUserRegister', true);
		foreach ($events as $arEvent) {
			if (ExecuteModuleEventEx($arEvent, [&$arResult['VALUES']]) === false) {
				if ($err = $APPLICATION->GetException())
					$arResult['ERRORS'][] = $err->GetString();

				$bOk = false;
				break;
			}
		}

		$ID = 0;
		$user = new CUser;
		if ($bOk) {
			$ID = $user->Add($arResult['VALUES']);
		}

		if (intval($ID) > 0) {
			$register_done = true;

			// authorize user
			if ($arParams['AUTH'] == 'Y'
				&& $arResult['VALUES']['ACTIVE'] == 'Y') {
				if (!$arAuthResult = $USER->Login($arResult['VALUES']['LOGIN'], $arResult['VALUES']['PASSWORD'])) {
					$arResult['ERRORS'][] = $arAuthResult;
				}
			}

			$arResult['VALUES']['USER_ID'] = $ID;

			$arEventFields = $arResult['VALUES'];
			unset($arEventFields['PASSWORD']);
			unset($arEventFields['CONFIRM_PASSWORD']);

			$event = new CEvent;
			$event->SendImmediate('NEW_USER', SITE_ID, $arEventFields);
			if ($bConfirmReq) {
				$event->SendImmediate('NEW_USER_CONFIRM', SITE_ID, $arEventFields);
			}
		} else {
			$arResult['ERRORS'][] = $user->LAST_ERROR;
		}

		if (count($arResult['ERRORS']) <= 0){
			if (COption::GetOptionString('main', 'event_log_register', 'N') === 'Y') {
				CEventLog::Log('SECURITY', 'USER_REGISTER', 'main', $ID);
			}
		} else {
			if (COption::GetOptionString('main', 'event_log_register_fail', 'N') === 'Y') {
				CEventLog::Log('SECURITY', 'USER_REGISTER_FAIL', 'main', $ID, implode('<br>', $arResult['ERRORS']));
			}
		}

		$events = GetModuleEvents('main', 'OnAfterUserRegister', true);
		foreach ($events as $arEvent) {
			ExecuteModuleEventEx($arEvent, [&$arResult['VALUES']]);
		}
	}
}

// if user is registered - redirect him to backurl or to success_page; currently added users too
if ($register_done) {
	if ($arParams['USE_BACKURL'] == 'Y'
		&& $_REQUEST['backurl'] <> '') {
		LocalRedirect($_REQUEST['backurl']);
	} elseif($arParams['SUCCESS_PAGE'] <> '') {
		LocalRedirect($arParams['SUCCESS_PAGE']);
	}
}

$arResult['VALUES'] = htmlspecialcharsEx($arResult['VALUES']);

// redefine required list - for better use in template
$arResult['REQUIRED_FIELDS_FLAGS'] = [];
foreach ($arResult['REQUIRED_FIELDS'] as $field) {
	$arResult['REQUIRED_FIELDS_FLAGS'][$field] = 'Y';
}

// check backurl existance
$arResult['BACKURL'] = htmlspecialcharsbx($_REQUEST['backurl']);

// get countries list
if (in_array('PERSONAL_COUNTRY', $arResult['SHOW_FIELDS'])
	|| in_array('WORK_COUNTRY', $arResult['SHOW_FIELDS'])) {
	$arResult['COUNTRIES'] = GetCountryArray();
}

// get date format
if (in_array('PERSONAL_BIRTHDAY', $arResult['SHOW_FIELDS'])) {
	$arResult['DATE_FORMAT'] = CLang::GetDateFormat('SHORT');
}

// ********************* User properties ***************************************************
$arResult['USER_PROPERTIES'] = ['SHOW' => 'N'];
$arUserFields = $USER_FIELD_MANAGER->GetUserFields('USER', 0, LANGUAGE_ID);
if (is_array($arUserFields) && count($arUserFields) > 0) {
	if (!is_array($arParams['USER_PROPERTY'])) {
		$arParams['USER_PROPERTY'] = array($arParams['USER_PROPERTY']);
	}

	foreach ($arUserFields as $FIELD_NAME => $arUserField) {
		if (!in_array($FIELD_NAME, $arParams['USER_PROPERTY'])
			&& $arUserField['MANDATORY'] != 'Y') {
			continue;
		}

		$arUserField['EDIT_FORM_LABEL'] = $arUserField['FIELD_NAME'];
		if (strLen($arUserField['EDIT_FORM_LABEL']) > 0) {
			$arUserField['EDIT_FORM_LABEL'] = $arUserField['EDIT_FORM_LABEL'];
		}
		
		$arUserField['EDIT_FORM_LABEL'] = htmlspecialcharsEx($arUserField['EDIT_FORM_LABEL']);
		$arUserField['~EDIT_FORM_LABEL'] = $arUserField['EDIT_FORM_LABEL'];
		$arResult['USER_PROPERTIES']['DATA'][$FIELD_NAME] = $arUserField;
	}
}
if (!empty($arResult['USER_PROPERTIES']['DATA'])) {
	$arResult['USER_PROPERTIES']['SHOW'] = 'Y';

	$arResult['bVarsFromForm'] = true;
	if (count($arResult['ERRORS']) <= 0) {
		$arResult['bVarsFromForm'] = false;
	}
}
// ******************** /User properties ***************************************************

// initialize captcha
if ($arResult['USE_CAPTCHA'] == 'Y') {
	$arResult['CAPTCHA_CODE'] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
}

// set title
if ($arParams['SET_TITLE'] == 'Y') {
	$APPLICATION->SetTitle(GetMessage('REGISTER_DEFAULT_TITLE'));
}

//time zones
$arResult['TIME_ZONE_ENABLED'] = CTimeZone::Enabled();
if ($arResult['TIME_ZONE_ENABLED']) {
	$arResult['TIME_ZONE_LIST'] = CTimeZone::GetZones();
}

$arResult['SECURE_AUTH'] = false;
if (!CMain::IsHTTPS() && COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y') {
	$sec = new CRsaSecurity();
	if ($arKeys = $sec->LoadKeys()) {
		$sec->SetKeys($arKeys);
		$sec->AddToForm('regform', [
			'REGISTER[PASSWORD]',
			'REGISTER[CONFIRM_PASSWORD]'
		]);
		$arResult['SECURE_AUTH'] = true;
	}
}

// all done
$this->IncludeComponentTemplate();