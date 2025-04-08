<?php
require_once("payPal-PHP-SDK/autoload.php");


// إعداد بيانات الاعتماد (Client ID و Client Secret)
$clientId = 'ATK3ggDVbgEYgjhMSOtzh666lM-jRIRl0IqMuL5iYiDkVX8YprZl8zS601gUiH25NQp5g2AZET9p9PKp';
$clientSecret = 'EJOJOvrserGeurgDRoUUQ7LcMQLbB6Pxl-C6xdPUMZVW8Nv_IGuBaPsWNZfbYfa3NziVhwS3W9WpndAl';

// إعداد ApiContext
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $clientId,
        $clientSecret
    )
);

// تعيين مسار ملف الشهادات (CA Cert)
$cacertPath = 'C:/wamp644/www/braudeflix/cacert.pem';
if (!file_exists($cacertPath)) {
    die("CACert file not found: $cacertPath");
}

$apiContext->setConfig([
    'http.CURLOPT_SSL_VERIFYPEER' => true,
    'http.CURLOPT_SSL_VERIFYHOST' => 2,
    'http.CURLOPT_CAINFO' => $cacertPath,
    'mode' => 'sandbox' // تأكد من تعيين الوضع إلى 'sandbox' أو 'live' حسب الحاجة
]);

?>


