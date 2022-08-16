
import $ from 'jquery';

// Google Tag Manager
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer', googleTagManagerKey);

// tarteaucitron.js
tarteaucitron.init({
    "privacyUrl": "",
    "hashtag": "#tarteaucitron",
    "cookieName": "tarteaucitron",
    "orientation": "bottom",
    "showAlertSmall": false,
    "cookieslist": true,
    "adblocker": false,
    "AcceptAllCta" : false,
    "DenyAllCta" : true,
    "highPrivacy": false,
    "handleBrowserDNTRequest": false,
    "removeCredit": false,
    "moreInfoLink": true,
    "useExternalCss": true,
    "showIcon": false,
    "readmoreLink": "/page/politique-de-cookies"
});

$('#cookies-management').on('click', function(e) {
    e.preventDefault();
    tarteaucitron.userInterface.openPanel();
});