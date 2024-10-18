<?$cpt = new CCaptcha();
$captchaPass = COption::GetOptionString("main", "captcha_password", "");
if(strlen($captchaPass) <= 0)
{
    $captchaPass = randString(10);
    COption::SetOptionString("main", "captcha_password", $captchaPass);
}
$cpt->SetCodeCrypt($captchaPass);
?>
<div class="saa-reviews-service">
    <div class="center">
        <form method="POST" action="<?= POST_FORM_ACTION_URI ?>">
            <?= bitrix_sessid_post()?>
            <div class="inputbox">
                <input class='form-control-name' name="review_name" type='text' required>
                <span>Ваше имя</span>
            </div>
            <div class="inputbox info-text">
                <textarea class='form-control-valuem-text' name='review_text_value' required></textarea>
                <span>Ваш отзыв</span>
            </div>
            <span clas="info-text-captch" style="color: #333;text-align: left;display: block;">Не забудьте про капчу, </br>она помогает нам понять, что вы не робот</span>
            <table> 
                <tr>
                    <td><input class="inptext" required="" id="captcha_word" name="captcha_word" type="text"></td>
                    <td><img class="captchaImg" src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($cpt->GetCodeCrypt());?>"></td>
                    <td style='display:none;'><input name="captcha_code" value="<?=htmlspecialchars($cpt->GetCodeCrypt());?>" type="hidden"><td>
                </tr>
            </table>
            <div class="inputbox buttons-form">
                <input type="submit" value="Отправить">
            </div>
        </form>
    </div>

    <? if (!empty($arResult['MESSAGE'])): ?>
        <div class="success"><?= $arResult['MESSAGE'] ?></div>
    <? endif; ?>

    <? if (!empty($arResult['ERROR'])): ?>
        <div class="error"><?= $arResult['ERROR'] ?></div>
    <? endif; ?>
</div>