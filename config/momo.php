<?php

return [
    'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'), // Mã mặc định test Sandbox MoMo
    'access_key'   => env('MOMO_ACCESS_KEY', 'klm05TvNCzhcg7hT'),
    'secret_key'   => env('MOMO_SECRET_KEY', 'at67qH6mk8g5HI1dEZD8j6H1zcgjTN2i'),
    'endpoint'     => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
    'return_url'   => env('APP_URL', 'http://localhost:8000') . '/payment/momo-return', 
    'mock_mode'    => env('MOMO_MOCK_MODE', false),
];
