<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

if (!isset($arParams['~MESSAGE']) || strlen($arParams['~MESSAGE']) <= 0) {
	return;
}

$arParams['~MESSAGE'] = str_replace('<br>', '\n', $arParams['~MESSAGE']);
$arParams['~MESSAGE'] = str_replace('<br />', '\n', $arParams['~MESSAGE']);

$arParams['~MESSAGE'] = htmlspecialcharsbx($arParams['~MESSAGE']);

$arParams['~MESSAGE'] = str_replace('\n', '<br />', $arParams['~MESSAGE']);
$arParams['~MESSAGE'] = str_replace('&amp;', '&', $arParams['~MESSAGE']);

$arParams['MESSAGE'] = $arParams['~MESSAGE'];

$arParams['STYLE'] = 'errortext';
if (isset($arParams['STYLE']) && strlen($arParams['STYLE']) > 0) {
	$arParams['STYLE'] = htmlspecialcharsbx($arParams['STYLE']);
}

$this->IncludeComponentTemplate();