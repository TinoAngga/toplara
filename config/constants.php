<?php
return [
    'options' => [
        'asset_img_service_category' => 'cdn/service-category/',
        'asset_img_service_category_guide' => 'cdn/service-category/guide/',
        'asset_img_payment_method' => 'cdn/payment-method/',
        'asset_img_qr_code' => 'cdn/qrcode/',
        'asset_img_banner' => 'cdn/banner/',
        'asset_img_page' => 'cdn/page/',
        'asset_img_website' => 'cdn/website/',
        'payment_method_type_arr' => [
            'bank_transfer',
            'e_wallet',
            'qris',
            'virtual_account',
            'convience_store',
        ],
        'joki' => [
            'mobile-legends' => [
                'login' => [
                    'Moonton',
                    'Facebook',
                    'VK',
                    // 'Gmail',
                    'Tiktok'
                ],
            ]
        ],
        'payment_method_type' => [
            'bank_transfer' => 'Bank Transfer',
            'e_wallet' => 'E Wallet',
            'qris' => 'QRIS',
            'virtual_account' => 'Virtual Account',
            'convience_store' => 'Convience Store'
        ],
        'payment_method_type_list' => [
            'bank_transfer' => [
                'text' => 'bank transfer',
                'icon' => 'mdi mdi-bank'
            ],
            'qris' => [
                'text' => 'qris',
                'icon' => 'mdi mdi-qrcode'
            ],
            'e_wallet' => [
                'text' => 'e wallet',
                'icon' => 'mdi mdi-wallet'
            ],
            'virtual_account' => [
                'text' => 'virtual account',
                'icon' => 'mdi mdi-credit-card'
            ],
            'convience_store' => [
                'text' => 'convience store',
                'icon' => 'mdi mdi-store'
            ]
        ],
        'payment_gateway_arr' => [
            'tripay',
            'xendit',
            'paydisini'
        ],
        'payment_gateway' => [
            'tripay' => 'Tripay',
            'xendit' => 'Xendit',
            'paydisini' => 'Paydisini'
        ],
        'member_level' => [
            'public' => 'PUBLIK',
            'reseller' => 'RESELLER',
            'h2h' => 'H2H'
        ],
        'member_level_arr' => [
            'public',
            'reseller',
            'h2h',
        ],
        'admin_level' => [
            'admin' => 'ADMIN',
            'super-admin' => 'SUPER ADMIN',
        ],
        'admin_level_arr' => [
            'admin',
            'super-admin'
        ],
        'status' => [
            'order' => ['pending', 'proses', 'sukses', 'gagal', 'kadaluarsa'],
            'deposit' => ['pending', 'sukses', 'gagal', 'kadaluarsa'],
            'upgrade_level' => ['pending', 'sukses', 'gagal', 'kadaluarsa']
        ],
        'option_monetery' => '15',
        'option_ratings' => '16',
        'option_textarea' => '17',
        'tripay_merchant_code' => 'T5201',
        'tripay_api_key' => 'DEV-EbU1I7lhTzqWJR3MoynYOO0KYzgiDsm1bFRxqiWD',
        'tripay_private_key' => 'LkYI9-io7x5-M7adO-d3Yr2-r09vh',
        'xendit_api_key' => 'xnd_production_LAtrkSNtxzlybiQBLu6PejnaNPuEswd02Kde52ygtuRktBkqKnfpUhFYOHQ3Kd',
        'xendit_private_key' => '',
        'xendit_callback_token' => '6x7rAjAHbr85NwddBBqcO4AB866C0qLmmhK2y13Mjy27F6oy'
    ]
];
?>
