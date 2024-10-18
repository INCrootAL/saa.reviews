<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
    Bitrix\Main\Engine\Controller;

//print_r($arParams);

class FeedbackReview extends \CBitrixComponent implements \Bitrix\Main\Engine\Contract\Controllerable
{
    public function configureActions() {}

    public function addNewReiewsAction ($name, $textReviews) {
        $this->name = $name;
        $this->textReviews = $textReviews;

        if($name != "" && $textReviews != "") {

            if(self::addReiews($name, $textReviews) == true) {
                
                $response['success'] = true;

            } else {

                $response['error'] = "Ошибка при создании отзыва";

            };

            return $response;

        } else {
            return "Не заполнены параметры";
        };

    }

    public function verCaptchaAction ($captchaSid, $captchaWord) {
        if (CModule::IncludeModule("main")) {
            global $APPLICATION;

            $this->captchaSid = $captchaSid;
            $this->captchaWord = $captchaWord;

            if ($APPLICATION->CaptchaCheckCode($captchaWord, $captchaSid)) {
                $response['success'] = true;
            } else {
                $captchaCode = $APPLICATION->CaptchaGetCode();
                $response['error'] = $captchaCode;
            }

            return $response;
        }
    }

    static function addReiews($name, $textReviews) {

        if (CModule::IncludeModule("iblock")) {

            $arLoadProductArray = Array(
                "NAME" => $name,
                "PREVIEW_PICTURE" => "",
                "ACTIVE" => "Y", 
                "IBLOCK_ID" => 10, 
                "PREVIEW_TEXT" => $textReviews,
                "PROPERTY_VALUES" => Array(
                    "VERF_ADMIN" => "4",
                ),
            );

            $el = new CIBlockElement;

            $PRODUCT_ID = $el->Add($arLoadProductArray);
            if($PRODUCT_ID) {
                return true;
            } else {
                return false;
            }
            
        } else {
            return false;
        }
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams["ELEMENT_ID"] = intval($arParams["ELEMENT_ID"]);
        return $arParams;
    }

    public function executeComponent()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && check_bitrix_sessid()) {
            $title = trim($_POST['review_title']);
            $text = trim($_POST['review_text']);

            if (!empty($title) && !empty($text)) {
                $el = new CIBlockElement;
                $arLoadProductArray = [
                    "IBLOCK_ID" => 10,
                    "NAME" => $title,
                    "DETAIL_TEXT" => $text,
                ];

                if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                    $this->arResult['MESSAGE'] = "Отзыв успешно добавлен.";
                } else {
                    $this->arResult['ERROR'] = "Ошибка добавления отзыва: " . $el->LAST_ERROR;
                }
            } else {
                $this->arResult['ERROR'] = "Заполните все поля.";
            }
        }

        $this->includeComponentTemplate();
    }
}
?>