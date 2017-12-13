<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

CJSCore::Init();

?><div class="auth-form"><?

if ($arResult['SHOW_ERRORS'] == 'Y'
	&& $arResult['ERROR']) {
	ShowMessage($arResult['ERROR_MESSAGE']);
}

if ($arResult['FORM_TYPE'] == 'login') {
?><form name="system_auth_form<?=$arResult['RND']?>" method="post" target="_top" action="<?=$arResult['AUTH_URL']?>"><?

	if ($arResult['BACKURL'] <> '') {
	?><input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
	}

	foreach ($arResult['POST'] as $key => $value) {
	?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
	}
	?><input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="AUTH">

	<div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('AUTH_LOGIN')?>:
		</div>
		<div class="auth-form__control">
			<input type="text" name="USER_LOGIN" value="">
			<script>
			BX.ready(function() {
				var loginCookie = BX.getCookie('<?=CUtil::JSEscape($arResult['~LOGIN_COOKIE_NAME'])?>');
				if (loginCookie) {
					var form = document.forms['system_auth_form<?=$arResult['RND']?>'];
					var loginInput = form.elements['USER_LOGIN'];
					loginInput.value = loginCookie;
				}
			});
			</script>
		</div>
	</div>

	<div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('AUTH_PASSWORD')?>:
		</div>
		<div class="auth-form__control">
			<input type="password" name="USER_PASSWORD">
		</div>
	</div><?

if ($arResult['STORE_PASSWORD'] == 'Y') {
	
	?><div class="auth-form__row">
		<label class="auth-form__label" for="USER_REMEMBER_frm" title="<?=GetMessage('AUTH_REMEMBER_ME')?>">
			<?=GetMessage('AUTH_REMEMBER_SHORT')?>
		</label>
		<div class="auth-form__control">
			<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y">
		</div>
	</div><?
}

if ($arResult['CAPTCHA_CODE']) {
	?><div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('AUTH_CAPTCHA_PROMT')?>:<br>
			<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
		</div>
		<div class="auth-form__control">
			<input type="text" name="captcha_word" value="">
		</div>
	</div><?
}
	?><div class="auth-form__row">
		<input type="submit" name="Login" value="<?=GetMessage('AUTH_LOGIN_BUTTON')?>">
	</div><?

if ($arResult['NEW_USER_REGISTRATION'] == 'Y') {
	?><div class="auth-form__row">
		<!--noindex--><a href="<?=$arResult['AUTH_REGISTER_URL']?>" rel="nofollow"><?=GetMessage('AUTH_REGISTER')?></a><!--/noindex-->
	</div><?
}
	?><div class="auth-form__row">
		<!--noindex--><a href="<?=$arResult['AUTH_FORGOT_PASSWORD_URL']?>" rel="nofollow"><?=GetMessage('AUTH_FORGOT_PASSWORD_2')?></a><!--/noindex-->
	</div><?

if ($arResult['AUTH_SERVICES']) {
	?><div class="auth-form__row"><?=GetMessage('socserv_as_user_form')?><?
	$APPLICATION->IncludeComponent('bitrix:socserv.auth.form', 'icons', [
		'AUTH_SERVICES' => $arResult['AUTH_SERVICES'],
		'SUFFIX' => 'form',
	], $component, [
		'HIDE_ICONS' => 'Y'
	]);
	?></div><?
}

?></form><?

if ($arResult['AUTH_SERVICES']) {
	$APPLICATION->IncludeComponent('bitrix:socserv.auth.form', '', [
		'AUTH_SERVICES' => $arResult['AUTH_SERVICES'],
		'AUTH_URL' => $arResult['AUTH_URL'],
		'POST' => $arResult['POST'],
		'POPUP' => 'Y',
		'SUFFIX' => 'form',
	], $component, [
		'HIDE_ICONS' => 'Y'
	]);
} elseif ($arResult['FORM_TYPE'] == 'otp') {

?><form name="system_auth_form<?=$arResult['RND']?>" method="post" target="_top" action="<?=$arResult['AUTH_URL']?>"><?

if ($arResult['BACKURL'] <> '') {
	?><input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
}

?><input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="OTP">
		<div class="auth-form__row">
			<div class="auth-form__label">
				<?=GetMessage('auth_form_comp_otp')?>
			</div>
			<div class="auth-form__control">
				<input type="text" name="USER_OTP" value="">
			</div>
		</div><?

if ($arResult['CAPTCHA_CODE']) {
	?><div class="auth-form__row">
		<div class="auth-form__label">
			<?=GetMessage('AUTH_CAPTCHA_PROMT')?>:
			<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
		</div>
		<div class="auth-form__control">
			<input type="text" name="captcha_word" value="">
		</div>
	</div><?
}

if ($arResult['REMEMBER_OTP'] == 'Y') {?>
	<div class="auth-form__row">
		<label class="auth-form__label" for="OTP_REMEMBER_frm" title="<?=GetMessage('auth_form_comp_otp_remember_title')?>"><?=GetMessage('auth_form_comp_otp_remember')?></label>
		<div class="auth-form__control">
			<input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y">
		</div>
	</div><?
}

?><div class="auth-form__row">
		<input type="submit" name="Login" value="<?=GetMessage('AUTH_LOGIN_BUTTON')?>">
	</div>
	<div class="auth-form__row">
		<!--noindex--><a href="<?=$arResult['AUTH_LOGIN_URL']?>" rel="nofollow"><?echo GetMessage('auth_form_comp_auth')?></a><!--/noindex-->
	</div>
</form><?

} else {

?><form action="<?=$arResult['AUTH_URL']?>">
	<div class="auth-form__row">
			<?=$arResult['USER_NAME']?><br>
			[<?=$arResult['USER_LOGIN']?>]<br>
			<a href="<?=$arResult['PROFILE_URL']?>" title="<?=GetMessage('AUTH_PROFILE')?>"><?=GetMessage('AUTH_PROFILE')?></a><br>
	</div>
	<div class="auth-form__row"><?

	foreach ($arResult['GET'] as $key => $value) {
		?><input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
	}
	
		?><input type="hidden" name="logout" value="yes">
		<input type="submit" name="logout_butt" value="<?=GetMessage('AUTH_LOGOUT_BUTTON')?>">
	</div>
</form><?
}
?></div>