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
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

?><div class="auth-form"><?

if ($USER->IsAuthorized()) {
?><p><?=GetMessage('MAIN_REGISTER_AUTH')?></p><?
} else {

	if (count($arResult['ERRORS']) > 0) {
		foreach ($arResult['ERRORS'] as $key => $error) {
			if (intval($key) == 0 && $key !== 0) {
				$arResult['ERRORS'][$key] = str_replace('#FIELD_NAME#', '&quot;' . GetMessage('REGISTER_FIELD_' . $key) . '&quot;', $error);
			}
		}

		ShowError(implode("<br>", $arResult['ERRORS']));

	} elseif ($arResult['USE_EMAIL_CONFIRMATION'] === 'Y') {
	?><p><?=GetMessage('REGISTER_EMAIL_WILL_BE_SENT')?></p><?
	}

?><form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data"><?

	if ($arResult['BACKURL'] <> '') {
	?><input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
	}

?><div class="auth-form__row">
	<strong><?=GetMessage('AUTH_REGISTER')?></strong>
</div><?

foreach ($arResult['SHOW_FIELDS'] as $FIELD) {
	
	if ($FIELD == 'AUTO_TIME_ZONE'
		&& $arResult['TIME_ZONE_ENABLED'] == true) {
	?><div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('main_profile_time_zones_auto')?><?

		if ($arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y') {
			?>*<?
		}
		?></div>
		<div class="auth-form__control">
			<select name="REGISTER[AUTO_TIME_ZONE]" onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
				<option value=""><?=GetMessage('main_profile_time_zones_auto_def')?></option>
				<option value="Y"<?=$arResult['VALUES'][$FIELD] == 'Y' ? ' selected="selected"' : ''?>><?=GetMessage('main_profile_time_zones_auto_yes')?></option>
				<option value="N"<?=$arResult['VALUES'][$FIELD] == 'N' ? ' selected="selected"' : ''?>><?=GetMessage('main_profile_time_zones_auto_no')?></option>
			</select>
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('main_profile_time_zones_zones')?>
		</div>
		<div class="auth-form__control">
			<select name="REGISTER[TIME_ZONE]"<?

			if (!isset($_REQUEST['REGISTER']['TIME_ZONE'])) {
				echo 'disabled="disabled"';
			}

			?>><?

			foreach ($arResult['TIME_ZONE_LIST'] as $tz => $tz_name) {
			?><option value="<?=htmlspecialcharsbx($tz)?>"<?=$arResult['VALUES']['TIME_ZONE'] == $tz ? ' selected="selected"' : ''?>><?=htmlspecialcharsbx($tz_name)?></option><?
			}

			?></select>
		</div>
	</div><?

	} else {

	?><div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('REGISTER_FIELD_' . $FIELD)?>:<?
			if ($arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y') {
			?>*<?
			}
		?></div>
		<div class="auth-form__control"><?

	switch ($FIELD) {
		case 'PASSWORD':
			?><input type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult['VALUES'][$FIELD]?>"><?
			break;
		case 'CONFIRM_PASSWORD':
			?><input type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult['VALUES'][$FIELD]?>"><?
			break;
		case 'PERSONAL_GENDER':
			?><select name="REGISTER[<?=$FIELD?>]">
				<option value=""><?=GetMessage('USER_DONT_KNOW')?></option>
				<option value="M"<?=$arResult['VALUES'][$FIELD] == 'M' ? ' selected="selected"' : ''?>><?=GetMessage('USER_MALE')?></option>
				<option value="F"<?=$arResult['VALUES'][$FIELD] == 'F' ? ' selected="selected"' : ''?>><?=GetMessage('USER_FEMALE')?></option>
			</select><?
			break;

		case 'PERSONAL_COUNTRY':
		case 'WORK_COUNTRY':
			?><select name="REGISTER[<?=$FIELD?>]"><?
			foreach ($arResult['COUNTRIES']['reference_id'] as $key => $value) {
				?><option value="<?=$value?>"<?
				if ($value == $arResult['VALUES'][$FIELD]) {
					?> selected="selected"<?
				}?>><?=$arResult['COUNTRIES']['reference'][$key]?></option><?
			}
			?></select><?
			break;
		case 'PERSONAL_PHOTO':
		case 'WORK_LOGO':
			?><input type="file" name="REGISTER_FILES_<?=$FIELD?>"><?
			break;

		case 'PERSONAL_NOTES':
		case 'WORK_NOTES':
			?><textarea name="REGISTER[<?=$FIELD?>]"><?=$arResult['VALUES'][$FIELD]?></textarea><?
			break;
		default:
			if ($FIELD == 'PERSONAL_BIRTHDAY') {
				?><small><?=$arResult['DATE_FORMAT']?></small><br><?
			}

			?><input type="text" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult['VALUES'][$FIELD]?>"><?

			if ($FIELD == 'PERSONAL_BIRTHDAY') {
				$APPLICATION->IncludeComponent('bitrix:main.calendar', '', [
					'SHOW_INPUT' => 'N',
					'FORM_NAME' => 'regform',
					'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
					'SHOW_TIME' => 'N'
				], null, [
					'HIDE_ICONS' => 'Y'
				]);
			}
	}
	?></div>
	</div><?
	}
}

// ********************* User properties ***************************************************
if ($arResult['USER_PROPERTIES']['SHOW'] == 'Y') {
?><div class="auth-form__row"><?=strlen(trim($arParams['USER_PROPERTY_NAME'])) > 0 ? $arParams['USER_PROPERTY_NAME'] : GetMessage('USER_TYPE_EDIT_TAB')?></div><?


foreach ($arResult['USER_PROPERTIES']['DATA'] as $FIELD_NAME => $arUserField) {
?><div class="auth-form__row">
	<div class="auth-form__label"><?=$arUserField['EDIT_FORM_LABEL']?>:<?

	if ($arUserField['MANDATORY'] == 'Y') {
		?>*<?
	}

	?></div>
	<div class="auth-form__control"><?
		$APPLICATION->IncludeComponent('bitrix:system.field.edit', $arUserField['USER_TYPE']['USER_TYPE_ID'], [
			'bVarsFromForm' => $arResult['bVarsFromForm'],
			'arUserField' => $arUserField,
			'form_name' => 'regform'
		], null, [
			'HIDE_ICONS' => 'Y'
		]);?></div></div><?
	}
}

// ******************** /User properties ***************************************************
/* CAPTCHA */
if ($arResult['USE_CAPTCHA'] == 'Y') {
?><div class="auth-form__row">
		<strong><?=GetMessage('REGISTER_CAPTCHA_TITLE')?></strong>
	</div>
	<div class="auth-form__row">
		<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
		<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('REGISTER_CAPTCHA_PROMT')?>: *
		</div>
		<div class="auth-form__control">
			<input type="text" name="captcha_word" value="">
		</div>
	</div><?

}
/* !CAPTCHA */
?>
	<div class="auth-form__row">
		<input type="submit" name="register_submit_button" value="<?=GetMessage('AUTH_REGISTER')?>">
	</div>
</form>
<p><?=$arResult['GROUP_POLICY']['PASSWORD_REQUIREMENTS'];?></p>
<p>* <?=GetMessage('AUTH_REQ')?></p><?
}
?></div>