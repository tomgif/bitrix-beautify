<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

ShowMessage($arParams['~AUTH_RESULT']);
ShowMessage($arResult['ERROR_MESSAGE']);

?><div class="auth-form"><?

if ($arResult['AUTH_SERVICES']) {
	?><div class="auth-form__title"><?=GetMessage('AUTH_TITLE')?></div><?
}

?><div class="auth-form__note"><?=GetMessage('AUTH_PLEASE_AUTH')?></div>

<form name="form_auth" method="post" target="_top" action="<?=$arResult['AUTH_URL']?>">
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="AUTH"><?

if (strlen($arResult['BACKURL']) > 0) {
	?><input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
}

foreach ($arResult['POST'] as $key => $value) {
	?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
}

?><div class="auth-form__table">
	<div class="auth-form__row">
		<div class="auth-form__label"><?=GetMessage('AUTH_LOGIN')?></div>
		<div class="auth-form__control">
			<input class="auth-form__input" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult['LAST_LOGIN']?>">
		</div>
	</div>

	<div class="auth-form__row">
		<div class="auth-form__label"><?=GetMessage('AUTH_PASSWORD')?></div>
		<div class="auth-form__control">
			<input class="auth-form__input" type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off">
		</div>
	</div><?

if ($arResult['CAPTCHA_CODE']) {
	?><div class="auth-form__row">
		<div class="auth-form__control">
			<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
		</div>
	</div>
	
	<div class="auth-form__row">
		<div class="auth-form__label"><?=GetMessage('AUTH_CAPTCHA_PROMT')?>:</div>
		<div class="auth-form__control">
			<input class="auth-form__input" type="text" name="captcha_word" maxlength="50" value="" size="15">
		</div>
	</div><?
}

if ($arResult['STORE_PASSWORD'] == 'Y') {
	?><div class="auth-form__row">
		<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y">
		<label for="USER_REMEMBER">&nbsp;<?=GetMessage('AUTH_REMEMBER_ME')?></label>
	</div><?
}
	?><div class="auth-form__row">
		<input type="submit" name="Login" value="<?=GetMessage('AUTH_AUTHORIZE')?>">
	</div>
</div><?

if ($arParams['NOT_SHOW_LINKS'] != 'Y') {
	?><!--noindex--><p><a href="<?=$arResult['AUTH_FORGOT_PASSWORD_URL']?>" rel="nofollow"><?=GetMessage('AUTH_FORGOT_PASSWORD_2')?></a></p><!--/noindex--><?
}

if ($arParams['NOT_SHOW_LINKS'] != 'Y'
	&& $arResult['NEW_USER_REGISTRATION'] == 'Y'
	&& $arParams['AUTHORIZE_REGISTRATION'] != 'Y'
) {
	?><!--noindex--><p><a href="<?=$arResult['AUTH_REGISTER_URL']?>" rel="nofollow"><?=GetMessage('AUTH_REGISTER')?></a><br><?=GetMessage('AUTH_FIRST_ONE')?></p><!--/noindex--><?
}

?></form>
</div><?

if ($arResult['AUTH_SERVICES']) {
	$APPLICATION->IncludeComponent('bitrix:socserv.auth.form', '', [
		'AUTH_SERVICES' => $arResult['AUTH_SERVICES'],
		'CURRENT_SERVICE' => $arResult['CURRENT_SERVICE'],
		'AUTH_URL' => $arResult['AUTH_URL'],
		'POST' => $arResult['POST'],
		'SHOW_TITLES' => $arResult['FOR_INTRANET'] ? 'N' : 'Y',
		'FOR_SPLIT' => $arResult['FOR_INTRANET'] ? 'Y' : 'N',
		'AUTH_LINE' => $arResult['FOR_INTRANET'] ? 'N' : 'Y',
	], $component, [
		'HIDE_ICONS' => 'Y'
	]);
}