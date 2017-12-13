<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

?><p><?echo $arResult['MESSAGE_TEXT']?></p><?

/*switch ($arResult['MESSAGE_CODE']) {
	case 'E01': //When user not found
		break;
	case 'E02': //User was successfully authorized after confirmation
		break;
	case 'E03': //User already confirm his registration
		break;
	case 'E04': //Missed confirmation code
		break;
	case 'E05': //Confirmation code provided does not match stored one
		break;
	case 'E06': //Confirmation was successfull
		break;
	case 'E07': //Some error occured during confirmation
		break;
}*/

if ($arResult['SHOW_FORM']) {
?><form method="post" action="<?=$arResult['FORM_ACTION']?>">
<div class="auth-confirm">
	<div class="auth-confirm__row">
		<div class="auth-confirm__label">
			<?=GetMessage("CT_BSAC_LOGIN")?>:
		</div>
		<div class="auth-confirm__control">
			<input class="auth-confirm__input" type="text" name="<?=$arParams['LOGIN']?>" value="<?=$arResult['LOGIN']?>">
		</div>
	</div>
	<div class="auth-confirm__row">
		<div class="auth-confirm__label">
			<?=GetMessage("CT_BSAC_CONFIRM_CODE")?>:
		</div>
		<div class="auth-confirm__control">
			<input type="text" name="<?=$arParams['CONFIRM_CODE']?>" value="<?=$arResult['CONFIRM_CODE']?>">
		</div>
	</div>
	<div class="auth-confirm__row">
		<input type="submit" value="<?=GetMessage("CT_BSAC_CONFIRM")?>">
	</div>
</table>
	<input type="hidden" name="<?=$arParams['USER_ID']?>" value="<?=$arResult['USER_ID']?>">
</form><?

} elseif (!$USER->IsAuthorized()) {
	$APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "", []);
}