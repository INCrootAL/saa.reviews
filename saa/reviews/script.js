BX.ready(function() {

    let button = document.querySelector('.inputbox.buttons-form [type="submit"] ');
    button.addEventListener( 'click', () => {
        event.preventDefault();
        
        let captchaSid = document.querySelector(".saa-reviews-service [name='captcha_code']").value;
        let captchaWord = document.querySelector(".saa-reviews-service [name='captcha_word']").value;

        var nameUser = document.querySelector("input[name='review_name']").value;
        var textReviews = document.querySelector("textarea[name='review_text_value']").value;

        if(captchaWord != "" && nameUser != "" && textReviews != "") {
            BX.ajax.runComponentAction('saa:reviews', 'verCaptcha', {
                mode: 'class',
                data: {
                    captchaSid: captchaSid,
                    captchaWord: captchaWord,
                    sessid: BX.message('bitrix_sessid'),
                },
            }).then(function (response) {
                if(response.data.success == true) {

                    if(nameUser != "" && textReviews != "") {
                        BX.ajax.runComponentAction('saa:reviews', 'addNewReiews', {
                            mode: 'class',
                            data: {
                                name: nameUser,
                                textReviews: textReviews,
                                sessid: BX.message('bitrix_sessid'),
                            },
                        }).then(function (response) {

                            if(response.data.success == true) {
                                document.querySelector(".center form").remove();
                                document.querySelector(".center").innerHTML = '<div class="success" style="color:#00796B; font-family:Beer money;font-size: 23px;">Спасибо за оставленый вами отзыв!<br>Скоро администратор проверит его и опубликует.</div>';
                            } else {
                                alert("Ошибка при сохранении! Пожалуйста перезагрузите страницу.");
                            }
                        })
                    }
                } else {
                    document.querySelector(".captchaImg").src = "/bitrix/tools/captcha.php?captcha_code="+response.data.error;
                    document.querySelector(".saa-reviews-service [name='captcha_code']").value = response.data.error;
                }
            })
        } else {
            document.querySelector(".saa-reviews-service [name='captcha_word']").style.border = "2px solid red";
            document.querySelector("input[name='review_name']").style.border = "2px solid red";
            document.querySelector("textarea[name='review_text_value']").style.border = "2px solid red";

            if(!document.querySelector('.saa-reviews-service-alert')) {
                let topElement = document.querySelector(".center");
                let newElement = BX.create('TABLE', {
                    attrs: {
                        style: "color:red; margin-bottom:20px",
                        className: 'saa-reviews-service-alert',
                    },
                    text: "Заполните все поля",
                });
                BX.prepend(newElement, topElement);
            }
        }
    })
})