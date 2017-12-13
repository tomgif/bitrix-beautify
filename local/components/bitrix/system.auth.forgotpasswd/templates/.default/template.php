<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

ShowMessage($arParams['~AUTH_RESULT']);

?><div class="auth-forgot">
<form name="bform" method="post" target="_top" action="<?=$arResult['AUTH_URL']?>"><?

if (strlen($arResult['BACKURL']) > 0) {
	?><input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
}
?><input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p><?=GetMessage('AUTH_FORGOT_PASSWORD_1')?></p>
		<div class="auth-forgot__row"> 
			<strong><?=GetMessage('AUTH_GET_CHECK_STRING')?></strong>
		</div>
		<div class="auth-forgot__row">
			<div class="auth-forgot__label"><?=GetMessage('AUTH_LOGIN')?></div>
			<div class="auth-forgot__control">
				<input type="text" name="USER_LOGIN" value="<?=$arResult['LAST_LOGIN']?>">&nbsp;<?=GetMessage('AUTH_OR')?>
			</div>
		</div>
		<div class="auth-forgot__row"> 
			<div class="auth-forgot__label"><?=GetMessage('AUTH_EMAIL')?></div>
			<div class="auth-forgot__control">
				<input type="text" name="USER_EMAIL">
			</div>
		</div><?

	if ($arResult['USE_CAPTCHA']) {
		?><div class="auth-forgot__row">
			<div class="auth-forgot__label">
				<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
			</div>
		</div>
		<div class="auth-forgot__row">
			<div class="auth-forgot__label"><?=GetMessage('system_auth_captcha')?></div>
			<div class="auth-forgot__control">
				<input type="text" name="captcha_word" value="">
			</div>
		</div><?
	}
	?><div class="auth-forgot__row"> 
			<input type="submit" name="send_account_info" value="<?=GetMessage('AUTH_SEND')?>">
		</div>
	<p><a href="<?=$arResult['AUTH_AUTH_URL']?>">
		<strong><?=GetMessage('AUTH_AUTH')?></strong>
	</a></p> 
</form>
</div>