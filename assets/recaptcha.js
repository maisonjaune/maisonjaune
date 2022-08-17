import $ from 'jquery';
import { load } from 'recaptcha-v3';

$(document).ready(() => {
    $('input[google-recaptcha]').each(function (index, element) {
        load(process.env.GOOGLE_RECAPTCHA_SITE_KEY).then((recaptcha) => {
            recaptcha.execute($(element).attr('google-recaptcha')).then((token) => {
                $(element).val(token);
            })
        });
    });
});
