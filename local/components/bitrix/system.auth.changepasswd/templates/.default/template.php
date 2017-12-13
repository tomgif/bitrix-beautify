<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

?><div class="auth-form"><?

ShowMessage($arParams['~AUTH_RESULT']);
?><form method="post" action="<?=$arResult['AUTH_FORM']?>" name="bform"><?

	if (strlen($arResult['BACKURL']) > 0) {
	?><input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
	}

	?><input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="CHANGE_PWD">
	<div class="auth-form__row">
		<div class="auth-form__label">
			<strong><?=GetMessage('AUTH_CHANGE_PASSWORD')?></strong>
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<span class="starrequired">*</span>
			<?=GetMessage('AUTH_LOGIN')?>
		</div>
		<div class="auth-form__control">
			<input class="auth-form__input" type="text" name="USER_LOGIN" value="<?=$arResult['LAST_LOGIN']?>">
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<span class="starrequired">*</span>
			<?=GetMessage('AUTH_CHECKWORD')?>
		</div>
		<div class="auth-form__control">
			<input class="auth-form__input" type="text" name="USER_CHECKWORD" value="<?=$arResult['USER_CHECKWORD']?>">
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<span class="starrequired">*</span>
			<?=GetMessage('AUTH_NEW_PASSWORD_REQ')?>
		</div>
		<div class="auth-form__control">
			<input class="auth-form__input" type="password" name="USER_PASSWORD" value="<?=$arResult['USER_PASSWORD']?>">
		</div>
	</div>
	<div class="auth-form__row">
		<div class="auth-form__label">
			<span class="starrequired">*</span>
			<?=GetMessage('AUTH_NEW_PASSWORD_CONFIRM')?>
		</div>
		<div class="auth-form__control">
			<input class="auth-form__input" type="password" name="USER_CONFIRM_PASSWORD" value="<?=$arResult['USER_CONFIRM_PASSWORD']?>">
		</div>
	</div><?

	if ($arResult['USE_CAPTCHA']) {
		?><div class="auth-form__row">
			<div class="auth-form__label">
				<input class="auth-form__input" type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
			</div>
		</div>
		<div class="auth-form__row">
			<div class="auth-form__label">
				<span class="starrequired">*</span>
				<?=GetMessage('system_auth_captcha')?>
			</div>
			<div class="auth-form__control">
				<input type="text" name="captcha_word" value="">
			</div>
		</div><?
	}

	?><div class="auth-form__row">
		<input type="submit" name="change_pwd" value="<?=GetMessage('AUTH_CHANGE')?>">
	</div>
</form>

<p><?=$arResult['GROUP_POLICY']['PASSWORD_REQUIREMENTS'];?></p>

<p><span class="starrequired">*</span><?=GetMessage('AUTH_REQ')?></p>

<p><a href="<?=$arResult['AUTH_AUTH_URL']?>">
	<strong><?=GetMessage('AUTH_AUTH')?></strong>
</a></p>

</div>