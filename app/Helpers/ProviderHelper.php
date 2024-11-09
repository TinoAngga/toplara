<?php
if (!function_exists('filterServiceCategoryType')):
    function filterServiceCategoryType(String $x, $serviceName = '', $serviceBrand = ''): String {
        $defaultType   = 'lainnya';
        $type1  = ['PULSA','PULSA-REGULER','PULSA-TRANSFER'];
        $type2  = ['CHINA TOPUP','MALAYSIA TOPUP','PHILIPPINES TOPUP','SINGAPORE TOPUP','THAILAND TOPUP','VIETNAM TOPUP','PULSA-INTERNASIONAL'];
        $type3  = ['DATA','PAKET-INTERNET'];
        $type4  = ['PAKET SMS & TELPON','PAKET-TELEPON'];
        $type5  = ['PLN','TOKEN-PLN'];
        $type6  = ['E-MONEY','SALDO-EMONEY'];
        $type7  = ['VOUCHER','PAKET-LAINNYA'];
        $type8  = ['STREAMING','TV','STREAMING-TV'];
        $type9  = ['GAMES','VOUCHER-GAME'];
        $type10 = ['PASCABAYAR'];

        $brand1 = ['GOOGLE PLAY INDONESIA','GOOGLE PLAY US REGION','GARENA','GEMSCOOL','MEGAXUS','PLAYSTATION','STEAM WALLET (IDR)','WAVE GAME','POINT BLANK','PUBG','PUBG MOBILE','UNIPIN VOUCHER'];

        if(in_array(strtoupper($x),$type1)) $defaultType = (stristr(strtolower($serviceName),'transfer')) ? 'pulsa-transfer' : 'pulsa-reguler';
        if(in_array(strtoupper($x),$type2)) $defaultType = 'pulsa-internasional';
        if(in_array(strtoupper($x),$type3)) $defaultType = 'paket-internet';
        if(in_array(strtoupper($x),$type4)) $defaultType = 'paket-telepon';
        if(in_array(strtoupper($x),$type5)) $defaultType = 'token-pln';
        if(in_array(strtoupper($x),$type6)) $defaultType = 'saldo-emoney';
        if(in_array(strtoupper($x),$type7)) $defaultType = (in_array($serviceBrand, $brand1)) ? 'voucher-game' : 'lainnya';
        if(in_array(strtoupper($x),$type8)) $defaultType = 'streaming-tv';
        if(in_array(strtoupper($x),$type9)) $defaultType = 'voucher-game';
        if(in_array(strtoupper($x),$type10)) $defaultType = 'pascabayar';
        return $defaultType;
    }
endif;
## FOR GAME
if (!function_exists('settingServiceGameDigiflazz')):
    function settingServiceGameDigiflazz(Array $array): Array {
        $arr['type'] = filterServiceCategoryType($array['category'], $array['product_name'], $array['brand']);
        $arr['category'] = $array['brand'];
        $arr['provider_service_code'] = $array['buyer_sku_code'];
        $arr['name'] = $array['product_name'];
        $arr['price'] = $array['price'];
        $arr['status'] = ($array['buyer_product_status'] == true) ? 'on' : 'off';
        return $arr;
    }
endif;
if (!function_exists('settingServiceGameIndoCuan')):
    function settingServiceGameIndoCuan(Array $array): Array {
        $arr['category'] = $array['game'];
        $arr['provider_service_code'] = $array['code'];
        $arr['name'] = $array['name'];
        $arr['price'] = $array['price']['special'];
        $arr['status'] = ($array['status'] == 'available') ? 'on' : 'off';
        return $arr;
    }
endif;
if (!function_exists('settingServiceGameVIP')):
    function settingServiceGameVIP(Array $array): Array {
        $arr['category'] = $array['game'];
        $arr['provider_service_code'] = $array['code'];
        $arr['name'] = $array['name'];
        $arr['price'] = $array['price']['special'];
        $arr['status'] = ($array['status'] == 'available') ? 'on' : 'off';
        return $arr;
    }
endif;
## FOR PPOB
if (!function_exists('settingServicePPOBDigiflazz')):
    function settingServicePPOBDigiflazz(Array $array): Array {
        $arr['type'] = filterServiceCategoryType($array['category'], $array['product_name'], $array['brand']);
        $arr['category'] = $array['brand'];
        $arr['sub_category'] = $array['type'];
        $arr['provider_service_code'] = $array['buyer_sku_code'];
        $arr['name'] = $array['product_name'];
        $arr['price'] = $array['price'];
        $arr['description'] = $array['desc'];
        $arr['status'] = ($array['buyer_product_status'] == true) ? 'on' : 'off';
        return $arr;
    }
endif;
if (!function_exists('settingServicePPOBIndoCuan')):
    function settingServicePPOBIndoCuan(Array $array): Array {
        $arr['type'] = $array['type'];
        $arr['category'] = $array['brand'];
        // $arr['sub_category'] = $array['category'];
        $arr['provider_service_code'] = $array['code'];
        $arr['name'] = $array['name'];
        $arr['price'] = $array['price']['special'];
        $arr['description'] = $array['note'];
        $arr['status'] = ($array['status'] == 'available') ? 'on' : 'off';
        return $arr;
    }
endif;
if (!function_exists('settingServicePPOBVIP')):
    function settingServicePPOBVIP(Array $array): Array {
        $arr['type'] = $array['type'];
        $arr['category'] = $array['brand'];
        $arr['sub_category'] = $array['category'];
        $arr['provider_service_code'] = $array['code'];
        $arr['name'] = $array['name'];
        $arr['price'] = $array['price']['special'];
        $arr['description'] = $array['note'];
        $arr['status'] = ($array['status'] == 'available') ? 'on' : 'off';
        return $arr;
    }
endif;

