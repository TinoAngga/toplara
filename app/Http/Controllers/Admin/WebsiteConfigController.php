<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ConfigTestMail;
use App\Models\WebsiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use function Ramsey\Uuid\v1;

class WebsiteConfigController extends Controller
{
    public function index($type = null)
    {
        switch ($type) {
            case 'primary':
                $page = 'Konfigurasi Utama';
                return view('admin.website-config.primary', compact('page'));
                break;
            case 'profit':
                $page = 'Konfigurasi Keuntungan';
                return view('admin.website-config.profit', compact('page'));
                break;
            case 'invoice':
                $page = 'Konfigurasi Invoice';
                return view('admin.website-config.invoice', compact('page'));
                break;
            case 'payment-gateway':
                $page = 'Payment Gateway';
                return view('admin.website-config.payment-gateway', compact('page'));
                break;
            case 'mail':
                $page = 'Mail';
                return view('admin.website-config.mail', compact('page'));
                break;
            case 'meta-tags':
                $page = 'Meta Tags';
                return view('admin.website-config.meta-tags', compact('page'));
                break;
            case 'additional-scripts':
                $page = 'Tag Tambahan';
                return view('admin.website-config.additional-scripts', compact('page'));
                break;
            case 'social-media':
                $page = 'Sosial Media';
                return view('admin.website-config.social-media', compact('page'));
                break;
            case 'about-section':
                $page = 'Section Tentang Kami';
                return view('admin.website-config.about-section', compact('page'));
                break;
            case 'whatsapp-notification':
                $page = 'Notifikasi Whatsapp';
                return view('admin.website-config.whatsapp-notification', compact('page'));
                break;
            default:
                abort(404);
                break;
        }
        return view('admin.website-config.index');
    }
    public function update(Request $request, $type = null)
    {
        switch ($type) {
            case 'primary':
                return $this->setPrimary($request);
                break;
            case 'profit':
                return $this->setProfit($request);
                break;
            case 'invoice':
                return $this->setInvoice($request);
                break;
            case 'payment-gateway':
                return $this->setPaymentGateway($request);
                break;
            case 'mail':
                return $this->setMail($request);
                break;
            case 'meta-tags':
                return $this->setMetaTags($request);
                break;
            case 'additional-scripts':
                return $this->setAdditionalScripts($request);
                break;
            case 'social-media':
                return $this->setSocialMedia($request);
                break;
            case 'about-section':
                return $this->setAboutSection($request);
                break;
            case 'whatsapp-notification':
                return $this->setWhatsappNotification($request);
                break;
            default:
                return response()->json([
                    'status' => false,
                    'type' => 'alert',
                    'msg' => 'Aksi tidak di temukan !!.'
                ]);
                break;
        }
    }
    protected function setPrimary($data){
        $validator = makeValidator($data->all(), [
            'title' => 'required',
            'bartitle' => 'required',
            'short_title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'favicon' => 'mimes:jpeg,png,jpg,gif,svg,ico,webp|max:2048',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:2048',
            'logo_sm' => 'image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:2048',
            // 'main_template' => 'required|in:dark-horizontal,light-horizontal',
            'payment_template' => 'required|in:with-collapse,without-collapse',
        ], [], [
            'title' => 'required',
            'bartitle'  => 'Bar Title Website',
            'short_title'  => 'Short Title Website',
            'short_description'  => 'Short Description Website',
            'description' => 'Description Website',
            'favicon'  => 'Favicon',
            'logo'  => 'Logo',
            'logo_sm'  => 'Logo Small',
            // 'main_template' => 'Theme',
            'payment_template' => 'Payment Template',
        ]);
        if ($validator->fails()) return response()->json([
            'status' => false,
            'type' => 'validation',
            'msg' => $validator->errors()->toArray()
        ]);
        setConfig('title', $data->title);
        setConfig('bartitle', $data->bartitle);
        setConfig('short_title', $data->short_title);
        setConfig('short_description', ltrim($data->short_description));
        setConfig('description', ltrim($data->description));
        // setConfig('main_template', $data->main_template);
        setConfig('payment_template', $data->payment_template);
        setConfig('footer_description', $data->footer_description);
        // SET FAVICON
        if ($data->favicon) {
			$image_name = 'favicon.'.$data->favicon->getClientOriginalExtension().'';
			$data->favicon->move(config('constants.options.asset_img_website'), $image_name);
            if (file_exists(config('constants.options.asset_img_website') . (!is_null(getConfig('favicon') ? getConfig('favicon') : '-')))) {
                unlink(config('constants.options.asset_img_website') . (!is_null(getConfig('favicon') ? getConfig('favicon') : '-')));
            }
			setConfig('favicon', $image_name);
		}
        // SET LOGO
		if ($data->logo) {
			$image_name = 'logo.'.$data->logo->getClientOriginalExtension().'';
			$data->logo->move(config('constants.options.asset_img_website'), $image_name);
            if (file_exists(config('constants.options.asset_img_website') . (!is_null(getConfig('logo') ? getConfig('logo') : '-')))) {
                unlink(config('constants.options.asset_img_website')  . (!is_null(getConfig('logo') ? getConfig('logo') : '-')));
            }
			setConfig('logo', $image_name);
		}
        // SET LOGO SMALL
        if ($data->logo_sm) {
			$image_name = 'logo_sm.'.$data->logo_sm->getClientOriginalExtension().'';
			$data->logo_sm->move(config('constants.options.asset_img_website'), $image_name);
            if (file_exists(config('constants.options.asset_img_website') . (!is_null(getConfig('logo_sm') ? getConfig('logo_sm') : '-')))) {
                unlink(config('constants.options.asset_img_website')  . (!is_null(getConfig('logo_sm') ? getConfig('logo_sm') : '-')));
            }
			setConfig('logo_sm', $image_name);
		}
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi utama !!.'
        ]);
    }
    protected function setProfit($data){
        $validator = makeValidator($data->all(), [
            'profit_type_ppob' => 'required|in:flat,percent',
            'profit_public_ppob' => 'required',
            'profit_reseller_ppob' => 'required',
            'profit_h2h_ppob' => 'required',
            'profit_setting_ppob_by' => 'required|in:config,service',

            'profit_type_game' => 'required|in:flat,percent',
            'profit_public_game' => 'required',
            'profit_reseller_game' => 'required',
            'profit_h2h_game' => 'required',
            'profit_setting_game_by' => 'required|in:config,service'
        ], [], [
            'profit_type_ppob' => 'Profit Tipe PPOB',
            'profit_public_ppob' => 'Profit Public PPOB',
            'profit_reseller_ppob' => 'Profit Reseller PPOB',
            'profit_h2h_ppob' => 'Profit H2H PPOB',
            'profit_setting_ppob_by' => 'Profit Setting PPOB By',
            'profit_type_game' => 'Profit Tipe Game',
            'profit_public_game' => 'Profit Public Game',
            'profit_reseller_game' => 'Profit Reseller Game',
            'profit_h2h_game' => 'Profit H2H Game',
            'profit_setting_game_by' => 'Profit Setting Game By'
        ]);
        if ($validator->fails()) return response()->json([
            'status' => false,
            'type' => 'validation',
            'msg' => $validator->errors()->toArray()
        ]);
        setConfig('profit_type_ppob', $data->profit_type_ppob);
        setConfig('profit_public_ppob', $data->profit_public_ppob);
        setConfig('profit_reseller_ppob', $data->profit_reseller_ppob);
        setConfig('profit_h2h_ppob', $data->profit_h2h_ppob);
        setConfig('profit_setting_ppob_by', $data->profit_setting_ppob_by);

        setConfig('profit_type_game', $data->profit_type_game);
        setConfig('profit_public_game', $data->profit_public_game);
        setConfig('profit_reseller_game', $data->profit_reseller_game);
        setConfig('profit_h2h_game', $data->profit_h2h_game);
        setConfig('profit_setting_game_by', $data->profit_setting_game_by);
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi utama !!.'
        ]);
    }
    protected function setMail($data)
    {
        $validator = makeValidator($data->all(), [
            'mail_host' => 'required',
            'mail_from' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required'
        ], [], [
            'mail_host'  => 'Host',
            'mail_from'  => 'Email Dari',
            'mail_port'  => 'Port',
            'mail_username'  => 'Username',
            'mail_password'  => 'Password',
        ]);
        if ($validator->fails()) return response()->json([
            'status' => false,
            'type' => 'validation',
            'msg' => $validator->errors()->toArray()
        ]);
        setConfig('mail_host', $data->mail_host);
        setConfig('mail_from', $data->mail_from);
        setConfig('mail_encryption', $data->mail_encryption);
        setConfig('mail_port', $data->mail_port);
        setConfig('mail_auth', $data->mail_auth);
        setConfig('mail_username', $data->mail_username);
        setConfig('mail_password', $data->mail_password);
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi mail !!.'
        ]);
    }
    protected function setInvoice($data)
    {
        $validator = makeValidator($data->all(), [
            'order_invoice_code' => 'required',
            'deposit_invoice_code' => 'required',
            'upgrade_level_invoice_code' => 'required',
        ], [], [
            'order_invoice_code'  => 'Order Invoice Code',
            'deposit_invoice_code'  => 'Deposit Invoice Code',
            'upgrade_level_invoice_code'  => 'Upgrade Level Invoice Code'
        ]);
        if ($validator->fails()) return response()->json([
            'status' => false,
            'type' => 'validation',
            'msg' => $validator->errors()->toArray()
        ]);
        setConfig('order_invoice_code', $data->order_invoice_code);
        setConfig('deposit_invoice_code', $data->deposit_invoice_code);
        setConfig('upgrade_level_invoice_code', $data->upgrade_level_invoice_code);
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi mail !!.'
        ]);
    }
    protected function setPaymentGateway($data)
    {
        $validator = makeValidator($data->all(), [
            'tripay_merchant_code' => 'required',
            'tripay_api_key' => 'required',
            'tripay_private_key' => 'required',
        ], [], [
            'tripay_merchant_code'  => 'Tripay Merchant Code',
            'tripay_api_key'  => 'Tripay API Key',
            'tripay_private_key'  => 'Tripay Private Key',
        ]);
        if ($validator->fails()) return response()->json([
            'status' => false,
            'type' => 'validation',
            'msg' => $validator->errors()->toArray()
        ]);
        setConfig('tripay_merchant_code', $data->tripay_merchant_code);
        setConfig('tripay_api_key', $data->tripay_api_key);
        setConfig('tripay_private_key', $data->tripay_private_key);

        setConfig('paydisini_api_key', $data->paydisini_api_key);

        setConfig('linkqu_client_id', $data->linkqu_client_id);
        setConfig('linkqu_client_secret', $data->linkqu_client_secret);
        setConfig('linkqu_client_username', $data->linkqu_client_username);
        setConfig('linkqu_client_pin', $data->linkqu_client_pin);
        setConfig('linkqu_client_server_key', $data->linkqu_client_server_key);
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi payment gateway !!.'
        ]);
    }
    public function mailTest()
    {
        Mail::send(new ConfigTestMail());
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan test kirim email !!.'
        ]);
    }
    protected function setMetaTags($data){
        $validator = makeValidator($data->all(), [
            'meta_tags_description' => 'required',
            'meta_tags_keyword' => 'required',
            'meta_tags_image' => 'image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:5000',
        ], [], [
            'meta_tags_description' => 'required',
            'meta_tags_keyword' => 'required',
            'meta_tags_image' => 'image|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:5000',
        ]);
        if ($validator->fails()) return response()->json([
            'status' => false,
            'type' => 'validation',
            'msg' => $validator->errors()->toArray()
        ]);
        setConfig('meta_tags_description', $data->meta_tags_description);
        setConfig('meta_tags_keyword', $data->meta_tags_keyword);
        // SET META TAGS IMAGE
        if ($data->meta_tags_image) {
			$image_name = makeSlug(getConfig('title')) . '.' . $data->meta_tags_image->getClientOriginalExtension().'';
			$data->meta_tags_image->move(config('constants.options.asset_img_website'), $image_name);
            if (file_exists(config('constants.options.asset_img_website') . (!is_null(getConfig('meta_tags_image') ? getConfig('meta_tags_image') : '-')))) {
                unlink(config('constants.options.asset_img_website') . (!is_null(getConfig('meta_tags_image') ? getConfig('meta_tags_image') : '-')));
            }
			setConfig('meta_tags_image', $image_name);
		}
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi meta tags !!.'
        ]);
    }
    protected function setAdditionalScripts($data){
        setConfig('additional_head_scripts', trim($data->additional_head_scripts));
        setConfig('additional_body_scripts', trim($data->additional_body_scripts));
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi tag tambahan !!.'
        ]);
    }
    protected function setSocialMedia($data){
        setConfig('social_media_whatsapp', $data->social_media_whatsapp);
        setConfig('social_media_facebook_url', $data->social_media_facebook_url);
        setConfig('social_media_facebook_name', $data->social_media_facebook_name);
        setConfig('social_media_instagram', $data->social_media_instagram);
        setConfig('contact_email_address', $data->contact_email_address);
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi sosial media !!.'
        ]);
    }

    protected function setAboutSection($data){
        $input = [];
        foreach ($data->about_section['icon'] as $key => $value) {
            $input[] = [
                'title' => $data->about_section['title'][$key],
                'description' => $data->about_section['description'][$key],
                'icon' => $data->about_section['icon'][$key],
                'bg_color' => $data->about_section['bg_color'][$key],
            ];
        }
        setConfig('about_section', json_encode($input));
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi section tentang kami !!.'
        ]);
    }

    protected function setWhatsappNotification($data){
        setConfig('whatsapp_gateway_api_key', $data->whatsapp_gateway_api_key);

        setConfig('whatsapp_gateway_sender_number', $data->whatsapp_gateway_sender_number);
        setConfig('whatsapp_gateway_admin_target_number', $data->whatsapp_gateway_admin_target_number);

        // for member
        setConfig('whatsapp_gateway_place_order_text', $data->whatsapp_gateway_place_order_text);
        setConfig('whatsapp_gateway_status_order_text', $data->whatsapp_gateway_status_order_text);

        // for admin
        setConfig('whatsapp_gateway_order_admin_text', $data->whatsapp_gateway_order_admin_text);
        return response()->json([
            'status' => true,
            'type' => 'alert',
            'msg' => 'Berhasil melakukan konfigurasi notifikasi whatsapp !!.'
        ]);
    }
}
