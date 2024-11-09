<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (!function_exists('user')):
    function user(): Object|Bool{
        if (Auth::check()) return Auth::user();
        else return false;
    }
endif;

if (!function_exists('admin')):
    function admin(): Object|Bool{
        if (Auth::guard('admin')->check()) return Auth::guard('admin')->user();
        else return false;
    }
endif;

if (!function_exists('getConfig')):
    function getConfig(String $name): ?String{
        $row = DB::table('website_configs')->select('value')->where('name', $name)->first();
        if (is_null($row)) {
            return null;
        } else {
            return $row->value;
        }
        return null;
    }
endif;

if (!function_exists('setConfig')):
    function setConfig(String $name, $value = null){
        $row = DB::table('website_configs')->select('value')->where('name', $name)->first();
        if (is_null($row) OR $row == false) {
            return DB::table('website_configs')->insert(['name' => $name, 'value' => $value]);
        } else {
            return DB::table('website_configs')->where('name', $name)->update(['value' => $value]);
        }
        return false;
    }
endif;

if (!function_exists('convertString')):
    function convertString(String $string = null, String $convertTo = null){
        $convert = strtr($string, [
            '{{ WEBSITE_TITLE }}' => getConfig('title'),
            '{{ PRICE }}' => '<b>Rp ' . currency($convertTo) . '</b>',
            '{{ YEAR }}' => date('Y'),
        ]);
        return $convert;
    }
endif;

if (!function_exists('currency')) {
    function currency($value = 0){
      $currency = number_format($value, 0, ".", ".");
      return $currency;
    }
}

if (!function_exists('generate_api_key')):
    function generate_api_key(): String {
        return implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
    }
endif;

if (!function_exists('sum_date')):
    function sum_date(String $date = '', $parameter = null) {
        return date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($date)).$parameter));
    }
endif;

if (!function_exists('diff_date')):
    function diff_date($date_a = '', $date_b = '') {
        $date_a = \Carbon\Carbon::parse(date_create($date_a));
        $date_b = \Carbon\Carbon::parse(date_create($date_b));
        $diff = date_diff($date_a, $date_b);
        return $diff->format("%R%a");
    }
endif;


if (!function_exists('date_diffs')):
    function date_diffs($start = ' ', $end = '', $opt = '?') {
        $start = \Carbon\Carbon::parse(date_create($start));
        $end = \Carbon\Carbon::parse(date_create($end));
        $diff = date_diff(date_create($start),date_create($end));
        $diffY = $diff->y;
        $diffM = $diff->m;
        $diffD = $diff->d;
        $diffH = $diff->h;
        $diffI = $diff->i;
        $diffS = $diff->s;
        $array = [
            'year' => ($diffY + $diffM / 12 + $diffD / 365.25),
            'month' => ($diffY * 12 + $diffM + $diffD/30 + $diffH / 24),
            'day' => ($diffY * 365.25 + $diffM * 30 + $diffD + $diffH/24 + $diffI / 60),
            'hour' => (($diffY * 365.25 + $diffM * 30 + $diffD) * 24 + $diffH + $diffI/60),
            'minute' => ((($diffY * 365.25 + $diffM * 30 + $diffD) * 24 + $diffH) * 60 + $diffI + $diffS/60),
            'second' => (((($diffY * 365.25 + $diffM * 30 + $diffD) * 24 + $diffH) * 60 + $diffI)*60 + $diffS)
        ];
        return (isset($array[$opt])) ? (($diff->invert) ? (-1 * $array[$opt]) : $array[$opt]) : '';
    }
endif;

if (!function_exists('list_date_range')):
    function list_date_range(String $start, String $end, String $format = 'Y-m-d'): Array {
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end);
        $end = new DateTime($end);
        $end = $end->add(new DateInterval('P1D'))->format('Y-m-d');

        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            new DateTime($end)
        );
        $date_list = [];
        foreach ($period as $key => $value) {
            $date_list[] = $value->format($format);
        }
        return $date_list;
    }
endif;

if (!function_exists('format_datetime')):
    function format_datetime(String $i = null): String {
        if (is_null($i)) return $i;
        $i = \Carbon\Carbon::parse($i);
        $month = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $split = explode(" ", $i);
        $date = explode("-", $split[0]);
        $format_date = $date[2].' '.$month[$date[1]].' '.$date[0];
        return $format_date.', '.$split[1];
    }
endif;

if (!function_exists('format_date')):
    function format_date(String $i = null): String {
        if (is_null($i)) return $i;
        \Carbon\Carbon::parse($i);
        $month = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $split = explode(" ", $i);
        $date = explode("-", $split[0]);
        $format_date = $date[2].' '.$month[$date[1]].' '.$date[0];
        return $format_date;
    }
endif;

if (!function_exists('makeValidator')):
    function makeValidator(array $data, array $rules, array $messages = [], array $customAttributes = []) {
        return \Illuminate\Support\Facades\Validator::make($data, $rules, $messages, $customAttributes);
    }
endif;

if (!function_exists('makeSlug')):
    function makeSlug($title, $separator = '-', $language = 'en'): String{
        return Illuminate\Support\Str::slug(strtolower($title), $separator, $language);
    }
endif;

if (!function_exists('parseCarbon')):
    function parseCarbon($date = ''){
        return \Carbon\Carbon::parse($date);
    }
endif;

if (!function_exists('convertPercent')):
    function convertPercent($value): Float{
        return $value / 100;
    }
endif;

if (!function_exists('getSiteMap')):
    function getSiteMap(): Object{
        return DB::table('pages')
            ->select('slug', 'title')
            ->orderBy('id', 'ASC')
            ->get();
    }
endif;

if (!function_exists('getPopularCategory')):
    function getPopularCategory($limit = 6): Object{
        return DB::table('service_categories')
            ->where('is_active', 1)
            ->select('service_type', 'slug', 'name', 'img')
            ->orderBy('counter', 'DESC')
            ->limit($limit)
            ->get();
    }
endif;

if (!function_exists('isXMLRequest')):
    function isXMLRequest(): Bool{
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')  return true;
        return false;
    }
endif;

if(!function_exists('onlineHours')):
    function onlineHours($start, $stop, $format = 'Hi'):Bool {
        if($start == $stop) return true;
        if(date($format) >= preg_replace('/\D/', '', $start) && date($format) <= preg_replace('/\D/', '', $stop)) return true;
        return false;
    }
endif;

if(!function_exists('getServicePriceByLevel')):
    function getServicePriceByLevel($value) {
        $getLevel =  Auth::check() == true ? Auth::user()->level : 'public';
        return is_array($value) ? $value['price']->{$getLevel} : $value->price->{$getLevel};
    }
endif;

if(!function_exists('setResponse')):
    function setResponse(Bool $status, String $msg):Array {
        return [
            'status' => $status,
            'msg' => $msg
        ];
        exit;
    }
endif;

if(!function_exists('get_numerics')):
    function get_numerics ($str){
        preg_match_all('/\d+/', $str, $matches);
        if (isset($matches[0][0]) AND $matches[0][0]) return (int) $matches[0][0];
        if (isset($matches[0]) AND $matches[0]) return (int) $matches[0];
        return 0;
    }
endif;

if(!function_exists('qrImage')):
    function qrImage($val, $ize = 512) {
        if($val == '') return 'https://dummyimage.com/512x512/ffffff/ffffff.png';
        $val = urlencode($val); $ize = $ize.'x'.$ize;
        return "https://chart.googleapis.com/chart?chs=$ize&cht=qr&chl=$val";
    }
endif;

if(!function_exists('get_icon')):
    function get_icon($category, $service) {
        if (preg_match("/mobile legend/i", strtolower($category)) AND preg_match("/grandmaster/i", strtolower($service)) AND preg_match("/diamond/i", strtolower($service)) == false) return url('cdn/icon/mlbb_gm.png');
        if (preg_match("/mobile legend/i", strtolower($category)) AND preg_match("/epic/i", strtolower($service)) AND preg_match("/diamond/i", strtolower($service)) == false) return url('cdn/icon/mlbb_epic.png');
        if (preg_match("/mobile legend/i", strtolower($category)) AND preg_match("/legend/i", strtolower($service)) AND preg_match("/diamond/i", strtolower($service)) == false) return url('cdn/icon/mlbb_legend.png');
        if (preg_match("/mobile legend/i", strtolower($category)) AND preg_match("/honor/i", strtolower($service)) AND preg_match("/diamond/i", strtolower($service)) == false) return url('cdn/icon/mlbb_honor.png');
        if (preg_match("/mobile legend/i", strtolower($category)) AND preg_match("/glory/i", strtolower($service)) AND preg_match("/diamond/i", strtolower($service)) == false) return url('cdn/icon/mlbb_glory.png');
        if (preg_match("/mobile legend/i", strtolower($category)) AND preg_match("/immortal/i", strtolower($service)) AND preg_match("/diamond/i", strtolower($service)) == false) return url('cdn/icon/mlbb_immortal.png');
        if (preg_match("/mobile legend/i", strtolower($category)) AND preg_match("/mythic/i", strtolower($service)) AND preg_match("/diamond/i", strtolower($service)) == false) return url('cdn/icon/mlbb_mythic.png');

        if (preg_match("/mobile legend/i", strtolower($category)) AND (get_numerics($service) > 0 AND get_numerics($service) < 100)) return url('cdn/icon/mlbb_diamond_1.png');
        if (preg_match("/mobile legend/i", strtolower($category)) AND (get_numerics($service) >= 101 AND get_numerics($service) <= 1000)) return url('cdn/icon/mlbb_diamond_2.png');
        if (preg_match("/mobile legend/i", strtolower($category)) AND get_numerics($service) >= 1001) return url('cdn/icon/mlbb_diamond_3.png');

        if (preg_match("/mobile legend/i", strtolower($category)) AND (preg_match("/twilight pass/i", strtolower($category)) || preg_match("/startlight member/i", strtolower($category)))) return url('cdn/icon/mlbb_diamond_3.png');

        if (preg_match("/free fire/i", strtolower($category))) return url('cdn/icon/free_fire_diamond.png');

        if (preg_match("/league of legend/i", strtolower($category))) return url('cdn/icon/lol_wild_core.webp');

        if (preg_match("/arena breakout/i", strtolower($category))) return url('cdn/icon/arena_breakout.webp');

        if (preg_match("/sausage/i", strtolower($category))) return url('cdn/icon/sausage_candy.png');
        if (preg_match("/pubg/i", strtolower($category)) AND get_numerics($service) < 100) return url('cdn/icon/pubg_uc_1.png');
        if (preg_match("/pubg/i", strtolower($category)) AND (get_numerics($service) >= 101 AND get_numerics($service) <= 1000)) return url('cdn/icon/pubg_uc_2.png');
        if (preg_match("/pubg/i", strtolower($category)) AND get_numerics($service) >= 1001) return url('cdn/icon/pubg_uc_3.png');
        if (preg_match("/pubg/i", strtolower($category)) AND (preg_match("/pass/i", strtolower($category)))) return url('cdn/icon/pubg_pass.png');
        if (preg_match("/arena of valor/i", strtolower($category))) return url('cdn/icon/aov_voucher.png');
        if (preg_match("/genshin impact/i", strtolower($category)) AND get_numerics($service) < 100) return url('cdn/icon/gs_gc_1.png');
        if (preg_match("/genshin impact/i", strtolower($category)) AND (get_numerics($service) >= 101 AND get_numerics($service) <= 1000)) return url('cdn/icon/gs_gc_2.png');
        if (preg_match("/genshin impact/i", strtolower($category)) AND get_numerics($service) >= 1001) return url('cdn/icon/gs_gc_3.png');
        if (preg_match("/valoran/i", strtolower($category)) AND preg_match("/poin/i", strtolower($service))) return url('cdn/icon/valo_point.png');
        return null;
    }
endif;

if (!function_exists('lastTransaction')):
    function lastTransaction($user_id = null) {
        $row = DB::table('orders')
            ->select('created_at')
            ->where('user_id', $user_id)
            ->orderBy('id', 'DESC')->first();

        return $row ? $row->created_at : rangeDate('Y-m-d H:i:s', '-3 minutes');
    }
endif;

if (!function_exists('rangeDate')):
    function rangeDate($value = 'Y-m-d H:i:s', $range = '-0 days') {
        return date($value, strtotime($range, strtotime(date('Y-m-d H:i:s'))));
    }
endif;

if (!function_exists('getFirstText')):
    function getFirstText(string $string) {
        // Memecah string menjadi array kata
        $words = explode(" ", $string);

        // Mengambil kata pertama
        $firstWord = $words[0];

        // Menampilkan kata pertama
        return $firstWord;
    }
endif;

if (!function_exists('getTypeData')):
    function getTypeData($value)
    {
        $type = gettype($value);

        if (filter_var($value, FILTER_VALIDATE_INT)) {
            return intval($value);
        } elseif (filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return floatval($value);
        } elseif (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
            return boolval($value);
        } else {
            return strval($value);
        }
    }
endif;

if (!function_exists('getRandomString')):
    function hideString(string $string) {
        $first_two_digits = substr($string, 0, 2);

        // Extract the last two digits
        $last_two_digits = substr($string, -2);

        // Generate the masked phone number
        $masked_string = $first_two_digits . '****' . $last_two_digits;

        return $masked_string;
    }
endif;
