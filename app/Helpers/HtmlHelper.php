<?php
if (!function_exists('badgeStatus')):
    function badgeStatus(String $status = '', Int $is_paid = 0){
        switch ($status) {
            case 'pending':
                return "<button class=\"btn btn-warning font-weight-bold text-white\">Pending</button>";
                break;
            case 'proses':
                return "<button class=\"btn btn-info font-weight-bold text-white\">Proses</button>";
                break;
            case 'gagal':
                return "<button class=\"btn btn-danger font-weight-bold text-white\">Gagal</button>";
                break;
            case 'kadaluarsa':
                return "<button class=\"btn btn-danger font-weight-bold text-white\">Kadaluarsa</button>";
                break;
            case 'sukses':
                return "<button class=\"btn btn-success font-weight-bold text-white\">Sukses</button>";
                break;
            default:
                return "<button class=\"btn btn-secondary font-weight-bold text-white\">" . $status . "</button>";
                break;
        }
    }
endif;

if (!function_exists('clipboardCopy')):
    function clipboardCopy(String $data, String $element){
        return "<div class=\"input-group\">
                    <input type=\"text\" class=\"form-control\" value=\"$data\" id=\"$element\" readonly>
                    <div class=\"input-group-append\">
                        <button class=\"btn btn-primary text-white\" type=\"button\" onclick=\"copy('$element')\"><i class=\"fa fa-copy fa-fw\"></i></button>
                    </div>
                </div>";
    }
endif;

if (!function_exists('isPaid')):
    function isPaid(Int $i = 0){
        switch ($i) {
            case 0:
                return "<button class=\"btn btn-danger btn-sm text-white\"><i class=\"fa fa-times fa-fw\"></i></button>";
                break;
            case 1:
                return "<button class=\"btn btn-success btn-sm text-white\"><i class=\"fa fa-check fa-fw\"></i></button>";
                break;
            default:
                # code...
                break;
        }
    }
endif;

if (!function_exists('isPaidText')):
    function isPaidText(Int $i = 0){
        switch ($i) {
            case 0:
                return "<button class=\"btn btn-warning btn-sm text-white\"> MENUNGGU PEMBAYARAN</button>";
                break;
            case 1:
                return "<button class=\"btn btn-success btn-sm text-white\"> LUNAS</button>";
                break;
            default:
                # code...
                break;
        }
    }
endif;

// if (!function_exists('isOrderProcessed')):
//     function isOrderProcessed(Int $i = 0){
//         switch ($i) {
//             case 0:
//                 return "<button class=\"btn btn-danger btn-sm text-white\"><i class=\"fa fa-times fa-fw\"></i></button>";
//                 break;
//             case 1:
//                 return "<button class=\"btn btn-success btn-sm text-white\"><i class=\"fa fa-check fa-fw\"></i></button>";
//                 break;
//             default:
//                 # code...
//                 break;
//         }
//     }
// endif;

if (!function_exists('isRefund')):
    function isRefund(Int $i = 0){
        switch ($i) {
            case 0:
                return "<button class=\"btn btn-danger btn-sm text-white\"><i class=\"fa fa-times fa-fw\"></i></button>";
                break;
            case 1:
                return "<button class=\"btn btn-success btn-sm text-white\"><i class=\"fa fa-check fa-fw\"></i></button>";
                break;
            default:
                # code...
                break;
        }
    }
endif;

if (!function_exists('isPaidAdmin')):
    function isPaidAdmin(Int $id, String $invoice, Int $_paid = 0, String $status = null){
        switch ($_paid) {
            case 0:
                if ($status == 'gagal' || $status == 'kadaluarsa') {
                    return "<button disabled class=\"btn btn-danger btn-sm text-white font-weight-bold\"><i class=\"fa fa-times fa-fw\"></i></button>";
                }
                return "<button onclick=\"paid('".url('admin/'.request()->segment(2).'/'.$id.'/paid')."', '".$invoice."')\" class=\"btn btn-warning btn-sm text-white font-weight-bold\"><i class=\"fa fa-refresh fa-fw\"></i></button>";
                break;
            case 1:
                return "<button disabled class=\"btn btn-success btn-sm text-white font-weight-bold\"><i class=\"fa fa-check fa-fw\"></i></button>";
                break;
            default:
                # code...
                break;
        }
    }
endif;

if (!function_exists('getStatusInArray')):
    function getStatusInArray($status = null){
        $status = strtolower($status);
        if($status == 'success' || $status == 'sukses' || $status == 'berhasil') return 'sukses';
        elseif($status == 'failed' || $status == 'gagal' || $status == 'error' || $status == 'cancel' || $status == 'refund') return 'gagal';
        elseif($status == 'processing' || $status == 'inprogress' || $status == 'proses'|| $status == 'process' || 'pending') return 'proses';
        else return 'pending';
        // if (in_array($status, ['success', 'sukses', 'berhasil'])) return 'sukses';
        // if (in_array($status, ['failed', 'gagal', 'error', 'cancel', 'refund'])) return 'gagal';
        // if (in_array($status, ['processing', 'inprogress', 'proses', 'process'])) return 'proses';
        // if (in_array($status, ['pending'])) return 'pending';
        return 'pending';
    }
endif;
if (!function_exists('getBgStatus')):
    function getBgStatus($status = null){
        $status = strtolower($status);
        if($status == 'sukses') return 'bg-success';
        elseif($status == 'gagal' || $status == 'kadaluarsa') return 'bg-danger';
        elseif($status == 'proses') return 'bg-info';
        else return 'bg-warning'; // pending
    }
endif;
?>
