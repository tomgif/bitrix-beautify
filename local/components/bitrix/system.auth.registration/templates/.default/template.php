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
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
?><div class="auth-form"><?

ShowMessage($arParams['~AUTH_RESULT']);

if ($arResult['USE_EMAIL_CONFIRMATION'] === 'Y'
	&& is_array($arParams['AUTH_RESULT'])
	&&  $arParams['AUTH_RESULT']['TYPE'] === 'OK') {
	?><p><?=GetMessage('AUTH_EMAIL_SENT')?></p><?
} else {
	if ($arResult['USE_EMAIL_CONFIRMATION'] === 'Y') {
	?><p><?=GetMessage('AUTH_EMAIL_WILL_BE_SENT')?></p><?
}
?><!--noindex--><form method="post" action="<?=$arResult['AUTH_URL']?>" name="bform" enctype="multipart/form-data"><?

if (strlen($arResult['BACKURL']) > 0) {
?><input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
}

?><input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="REGISTRATION">
	<div class="auth-form__row">
		<strong><?=GetMessage('AUTH_REGISTER')?></strong>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('AUTH_NAME')?>
		</div>
		<div class="auth-form__control">
			<input type="text" name="USER_NAME" value="<?=$arResult['USER_NAME']?>">
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('AUTH_LAST_NAME')?>
		</div>
		<div class="auth-form__control">
			<input type="text" name="USER_LAST_NAME" value="<?=$arResult['USER_LAST_NAME']?>">
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<span class="starrequired">*</span>
			<?=GetMessage('AUTH_LOGIN_MIN')?>
		</div>
		<div class="auth-form__control">
			<input type="text" name="USER_LOGIN" value="<?=$arResult['USER_LOGIN']?>">
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<span class="starrequired">*</span>
			<?=GetMessage('AUTH_PASSWORD_REQ')?>
		</div>
		<div class="auth-form__control">
			<input type="password" name="USER_PASSWORD" value="<?=$arResult['USER_PASSWORD']?>">
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<span class="starrequired">*</span>
			<?=GetMessage('AUTH_CONFIRM')?>
		</div>
		<div class="auth-form__control">
			<input type="password" name="USER_CONFIRM_PASSWORD" value="<?=$arResult['USER_CONFIRM_PASSWORD']?>">
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label"><?

		if ($arResult['EMAIL_REQUIRED']) {
			?><span class="starrequired">*</span><?
		}
			?><?=GetMessage('AUTH_EMAIL')?>
		</div>
		<div class="auth-form__control">
			<input type="text" name="USER_EMAIL" value="<?=$arResult['USER_EMAIL']?>">
		</div>
	</div><?

// ********************* User properties ***************************************************
if ($arResult['USER_PROPERTIES']['SHOW'] == 'Y') {
	?><div class="auth-form__row">
		<?=strlen(trim($arParams['USER_PROPERTY_NAME'])) > 0 ? $arParams['USER_PROPERTY_NAME'] : GetMessage('USER_TYPE_EDIT_TAB')?>	
	</div><?

	foreach ($arResult['USER_PROPERTIES']['DATA'] as $FIELD_NAME => $arUserField) {
	?><div class="auth-form__row">
		<div class="auth-form__label"><?

		if ($arUserField['MANDATORY'] == 'Y') {
			?><span class="starrequired">*</span><?
		}

		?><?=$arUserField['EDIT_FORM_LABEL']?>:</div>
		<div class="auth-form__control"><?
		$APPLICATION->IncludeComponent('bitrix:system.field.edit', $arUserField['USER_TYPE']['USER_TYPE_ID'], [
			'bVarsFromForm' => $arResult['bVarsFromForm'],
			'arUserField' => $arUserField,
			'form_name' => 'bform'
		], null, [
			'HIDE_ICONS' => 'Y'
		]);?></div>
	</div><?
	}
}

// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult['USE_CAPTCHA'] == 'Y') {
	?><div class="auth-form__row">
		<strong><?=GetMessage('CAPTCHA_REGF_TITLE')?></strong>
	</div>
	<div class="auth-form__row">
		<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
		<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<span class="starrequired">*</span>
			<?=GetMessage('CAPTCHA_REGF_PROMT')?>:
		</div>
		<div class="auth-form__control">
			<input type="text" name="captcha_word" value="">
		</div>
	</div><?
	}

	/* CAPTCHA */
	?><div class="auth-form__row"><?
	$APPLICATION->IncludeComponent('bitrix:main.userconsent.request', '', [
		'ID' => COption::getOptionString('main', 'new_user_agreement', ''),
		'IS_CHECKED' => 'Y',
		'AUTO_SAVE' => 'N',
		'IS_LOADED' => 'Y',
		'ORIGINATOR_ID' => $arResult['AGREEMENT_ORIGINATOR_ID'],
		'ORIGIN_ID' => $arResult['AGREEMENT_ORIGIN_ID'],
		'INPUT_NAME' => $arResult['AGREEMENT_INPUT_NAME'],
		'REPLACE' => [
			'button_caption' => GetMessage('AUTH_REGISTER'),
			'fields' => [
				rtrim(GetMessage('AUTH_NAME'), ':'),
				rtrim(GetMessage('AUTH_LAST_NAME'), ':'),
				rtrim(GetMessage('AUTH_LOGIN_MIN'), ':'),
				rtrim(GetMessage('AUTH_PASSWORD_REQ'), ':'),
				rtrim(GetMessage('AUTH_EMAIL'), ':'),
			]
		],
	]);
	?></div>
	<div class="auth-form__row">
		<div class="auth-form__control">
			<input type="submit" name="Register" value="<?=GetMessage('AUTH_REGISTER')?>">
		</div>
	</div>
	<p><?=$arResult['GROUP_POLICY']['PASSWORD_REQUIREMENTS'];?></p>
	<p><span class="starrequired">*</span><?=GetMessage('AUTH_REQ')?></p>
	<p><a href="<?=$arResult['AUTH_AUTH_URL']?>" rel="nofollow">
		<strong><?=GetMessage('AUTH_AUTH')?></strong>
	</a></p>

</form><!--/noindex--><?
}
?></div>