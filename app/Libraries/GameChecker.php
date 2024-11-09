<?php
/*
 * Game yang tersedia:
	- Mobile Legends : mobile-legends
	- Hago : hago
	- Zepeto : zepeto
	- Lords Mobile : lords-mobile
	- Marvel Super War : marvel-super-war
	- Ragnarok M : ragnarok-m-eternal-love-big-cat-coin
	- Speed Drifters : speed-drifters
	- Laplace M : laplace-m
	- Valorant : valorant
	- Higgs Domino : higgs-domino
	- Point Blank : point-blank
	- Dragon Raja : dragon-raja
	- League of Legends: Wild Rift : league-of-legends-wild-rift (testing)
	- Free Fire : free-fire
	- Free Fire Max : free-fire-max
	- Tom and Jerry:chase : tom-and-jerry-chase
	- Cocofun : cocofun (testing)
	- 8 Ball Pool : 8-ball-pool (testing)
	- Auto Chess : auto-chess (testing)
	- Bullet Angel : bullet-angel (testing)
	- Arena of Valor : arena-of-valor
	- Call of Duty MOBILE : call-of-duty-mobile
	- Genshin Impact : genshin-impact
	- IndoPlay : indoplay
	- Domino Gaple Boyaa Qiuqiu : domino-gaple-qiuqiu-boyaa
*/

namespace App\Libraries;

class GameChecker
{
	const BASE_URL = 'https://api-sg.codashop.com/';

	public function validation($game = '', $userId = '', $zoneId = '')
	{
		$voucherName = $this->getVoucherGame($game);
		if(empty($voucherName)) {
			return [
                'status' => false,
                'data' => [
                    'code' => 'ERR_SCRIPT',
                    'msg' => 'Validasi gagal dilakukan (vcname)'
                    ]
                ];
		}

		$denom = $this->getDenomination($game);
		if(empty($denom)) {
			return [
                'status' => false,
                'data' => [
                    'code' => 'ERR_SCRIPT', 'msg' => 'Validasi gagal dilakukan (denom)'
                    ]
                ];
		}

		if ($zoneId <> '') {
			$validationZoneId = $this->validationZoneId($game, $zoneId);
			if (isset($validationZoneId['status']) AND $validationZoneId['status'] == false) {
				return $validationZoneId;
			}
		}
		$params = [];
        $params[] = "voucherPricePoint.id={$denom['pricePointId']}";
        $params[] = "voucherPricePoint.price={$denom['pricePointPrice']}";
        $params[] = 'voucherPricePoint.variablePrice=0';
        $params[] = 'n=13/4/2023-27';
        $params[] = 'email=2i2@oad.com';
        $params[] = 'userVariablePrice=0';
        $params[] = 'order.data.profile=eyJuYW1lIjoiICIsImRhdGVvZmJpcnRoIjoiIiwiaWRfbm8iOiIifQ==';
        $params[] = "user.userId={$userId}";
        $params[] = "user.zoneId={$zoneId}";
        $params[] = 'msisdn=';
        $params[] = "voucherTypeName={$voucherName}";
        // $params[] = "voucherTypeId=5";
        $params[] = 'shopLang=id_ID';
        $params[] = 'checkoutId=41e0c0e9-6b8b-4ace-8a84-1155b0e24e39';
        // $params[] = 'affiliateTrackingId=';
        // $params[] = 'impactClickId=';
        $params[] = 'anonymousId=71ca64ef-0d1f-424d-b78a-e0d0dcb5902d';
        // $params[] = 'userSessionId=MmkyQG9hZC5jb20%3D';
        $params[] = 'clevertapId=854b3cf2d36d4186a653806d6fcf1562';
        // $params[] = '';
        // $params[] = '';
        // $params[] = '';
		$response = $this->request('https://order-sg.codashop.com/initPayment.action', implode('&', $params), 'application/x-www-form-urlencoded');
// 		dd($response);
		if(is_array($response) && isset($response['success']) && $response['success'] && empty($response['errorMsg'])) {
			switch($game) {
                case 'mobile-legends':
					$username = $response['confirmationFields']['username'];
                case 'hago':
					$username = $response['confirmationFields']['username'];
					break;
                case 'higgs-domino':
					$username = $response['confirmationFields']['username'];
					break;
                case 'valorant':
					$username = $response['confirmationFields']['username'];
					break;
                case 'speed-drifters':
					$username = $response['confirmationFields']['username'];
					break;
                case 'lords-mobile':
					$username = $response['confirmationFields']['username'];
					break;
                case 'point-blank':
					$username = $response['confirmationFields']['username'];
					break;
                case 'dragon-raja':
					$username = $response['confirmationFields']['username'];
					break;
                case 'laplace-m':
					$username = $response['confirmationFields']['username'];
					break;
                case 'marvel-super-war':
					$username = $response['confirmationFields']['username'];
					break;
                case 'ragnarok-m-eternal-love-big-cat-coin':
					$username = $response['confirmationFields']['username'];
					break;
                case 'tom-and-jerry-chase':
					$username = $response['confirmationFields']['username'];
					break;
                case 'league-of-legends-wild-rift':
					$username = $response['confirmationFields']['username'];
					break;
                case 'zepeto':
					$username = $response['confirmationFields']['username'];
					break;
                case 'cocofun':
					$username = $response['confirmationFields']['username'];
					break;
                case '8-ball-pool':
					$username = $response['confirmationFields']['username'];
					break;
                case 'auto-chess':
					$username = $response['confirmationFields']['username'];
					break;
                case 'bullet-angel':
					$username = $response['confirmationFields']['username'];
					break;
                case 'free-fire':
					$username = $response['confirmationFields']['roles'][0]['role'];
					break;
                case 'free-fire-max':
					$username = $response['confirmationFields']['username'];
					break;
                case 'arena-of-valor':
					$username = $response['confirmationFields']['username'];
					break;
                case 'call-of-duty-mobile':
                    $username = $response['confirmationFields']['roles'][0]['role'];
                    break;
                case 'genshin-impact':
					$username = $response['confirmationFields']['username'];
                    break;
                case 'indoplay':
					$username = $response['confirmationFields']['username'];
					break;
                case 'domino-gaple-qiuqiu-boyaa':
                    $username = $response['confirmationFields']['inputRoleId'];
                    break;
                default:
                    $username = '';
            }
			// print_r($username);
			// echo json_encode($response);
			// exit;
            return [
                'status' => true,
                'data' => urldecode($username)
            ];
		} else {
			return [
                'status' => false,
                'data' => [
						'code' => $response['errorCode'] ?? $response['RESULT_CODE'],
						'msg' => 'Akun game tidak dapat ditemukan.'
                    ]
                ];
		}
	}

	private function getVoucherGame($game)
	{
		switch($game) {
			case 'mobile-legends':
                return strtoupper(str_replace('-', '_', $game));
				break;
			case 'lords-mobile':
			case 'marvel-super-war':
			case 'genshin-impact':
			case 'hago':
			case 'point-blank':
			case 'valorant':
                return 'VALORANT';
                break;
			case 'cocofun':
			case 'auto-chess':
			case 'bullet-angel':
				return strtoupper(str_replace('-', '_', $game));
				break;
			case 'higgs-domino':
				return 'HIGGS';
				break;
			case 'dragon-raja':
				return 'ZULONG_DRAGON_RAJA';
				break;
			case 'arena-of-valor':
				return 'AOV';
				break;
			case 'call-of-duty-mobile':
				return 'CALL_OF_DUTY';
				break;
			case 'free-fire':
                return 'FREEFIRE';
                break;
			case 'free-fire-max':
				return 'FREEFIRE';
				break;
			case 'laplace-m':
				return 'ZLONGAME';
				break;
			case 'ragnarok-m-eternal-love-big-cat-coin':
				return 'GRAVITY_RAGNAROK_M';
				break;
			case 'tom-and-jerry-chase':
				return 'TOM_JERRY_CHASE';
				break;
			case 'speed-drifters':
				return 'SPEEDDRIFTERS';
				break;
			case 'indoplay':
				return 'MANGOSOFT_INDOPLAY';
				break;
			case 'indoplay':
				return 'MANGOSOFT_INDOPLAY';
				break;
			case 'domino-gaple-qiuqiu-boyaa':
				return 'BOYAA_CAPSA_SUSUN';
				break;
			case 'livu':
				return 'RCLOVU';
				break;
			case 'league-of-legends-wild-rift':
				return 'WILD_RIFT';
				break;
			case 'zepeto':
				return 'NAVER_Z_CORPORATION';
				break;
			case '8-ball-pool':
				return 'EIGHT_BALL_POOL';
				break;

			default:
				return '';
		}
	}

	private function getDenomination($game)
	{
	    $response = $this->request( self::BASE_URL . 'spring/api/graphql', '{"operationName":"GetProductPageInfo","variables":{"productUrl":"/id/' .$game. '","shopLang":""},"query":"query GetProductPageInfo($productUrl: String!, $shopLang: String) {\n  getProductPageInfo(productUrl: $productUrl, shopLang: $shopLang) {\n    gameUserInput {\n      sectionTitle\n      imageHelperUrl\n      instructionText\n      fields {\n        data {\n          text\n          value\n          parentValue\n          __typename\n        }\n        placeHolder\n        publisher\n        logoutUrl\n        type\n        name\n        displayMode\n        displayOnly\n        parentField\n        regexName\n        hasParenthesis\n        hasCountryCode\n        length\n        value\n        scope\n        oauthUrl\n        responseType\n        clientId\n        __typename\n      }\n      voucherSectionTitle\n      voucherCategorySectionTitle\n      voucherItemSectionTitle\n      paymentSectionTitle\n      buySectionTitle\n      __typename\n    }\n    productInfo {\n      id\n      gvtId\n      name\n      shortName\n      productTagline\n      shortDescription\n      longDescription\n      metaDescription\n      logoLocation\n      productUrl\n      voucherTypeName\n      voucherTypeId\n      orderUrl\n      productTitle\n      variableDenomPriceMinAmount\n      variableDenomPriceMaxAmount\n      __typename\n    }\n    denominationGroups {\n      displayText\n      displayId\n      pricePoints {\n        id\n        bestdeal\n        paymentChannel {\n          id\n          displayName\n          imageUrl\n          status\n          tagline\n          sortOrder\n          tutorialType\n          tutorialURL\n          statusMessage\n          tutorialLabel\n          isPromotion\n          promotionText\n          isMno\n          infoMessages {\n            icon\n            text\n            __typename\n          }\n          openInNewTab\n          __typename\n        }\n        price {\n          currency\n          amount\n          __typename\n        }\n        isVariablePrice\n        hasDiscount\n        publisherPrice\n        __typename\n      }\n      strikethroughPrice\n      sortOrderId\n      hasStock\n      status\n      isVariableDenom\n      denomImageUrl\n      denomCategoryId\n      denomDetailsTitle\n      denomDetailsImageUrl\n      originalSku\n      voucherId\n      flashSalePromoDetail {\n        promoUsage\n        promoEndDate\n        __typename\n      }\n      __typename\n    }\n    paymentChannels {\n      id\n      displayName\n      imageUrl\n      status\n      sortOrder\n      isPromotion\n      promotionText\n      isMno\n      buyInputs {\n        label\n        buyInputFields {\n          type\n          required\n          placeHolder\n          minLength\n          maxLength\n          name\n          regexName\n          hasCountryCode\n          __typename\n        }\n        __typename\n      }\n      infoMessages {\n        icon\n        text\n        __typename\n      }\n      surchargeNote\n      surchargeLink\n      isRiskCheckingEnabled\n      __typename\n    }\n    faqs {\n      question\n      answer\n      __typename\n    }\n    confirmationDialogSchema {\n      confirmationFields {\n        label\n        value {\n          type\n          field\n          __typename\n        }\n        __typename\n      }\n      invalidUserErrorSchema {\n        errorHeader\n        errorMessage\n        fieldName\n        __typename\n      }\n      __typename\n    }\n    hrefLinks {\n      hrefLang\n      href\n      __typename\n    }\n    cashbackCampaign {\n      campaignId\n      percentage\n      paymentChannelIds\n      skus\n      description\n      cashbackDenomPrice {\n        paymentChannelId\n        voucherId\n        cashbackPrice\n        __typename\n      }\n      qualifyingUsers\n      __typename\n    }\n    displayImage\n    denominationCategories {\n      id\n      parentId\n      sortOrder\n      level\n      name\n      title\n      subTitle\n      description\n      imageUrl\n      __typename\n    }\n    isShowProvince\n    capturedPurchase {\n      purchaseDate\n      denomId\n      paymentChannelId\n      email\n      mobile\n      boletoFirstName\n      boletoLastName\n      boletoDOB\n      boletoCPFNumber\n      userId\n      zoneId\n      denomCategoryId\n      __typename\n    }\n    reviewSummary {\n      isDisabledInCMS\n      starLabel\n      starRatingUrl\n      trustScore\n      totalReviews\n      __typename\n    }\n    enablePromoCode\n    enableGifting\n    preselectEmailConsent\n    preselectSmsConsent\n    isDynamicProduct\n    __typename\n  }\n}\n"}', 'application/json');
// 		$response = $this->request( self::BASE_URL . 'spring/api/graphql', '{"operationName":"GetProductPageInfo","variables":{"productUrl":"/id/' .$game. '","shopLang":""},"query":"query GetProductPageInfo($productUrl: String!, $shopLang: String) {\n  getProductPageInfo(productUrl: $productUrl, shopLang: $shopLang) {\n    gameUserInput {\n      sectionTitle\n      imageHelperUrl\n      instructionText\n      fields {\n        data {\n          text\n          value\n          parentValue\n          __typename\n        }\n        placeHolder\n        type\n        name\n        displayMode\n        displayOnly\n        parentField\n        regexName\n        hasParenthesis\n        hasCountryCode\n        length\n        value\n        __typename\n      }\n      voucherSectionTitle\n      paymentSectionTitle\n      buySectionTitle\n      __typename\n    }\n    productInfo {\n      id\n      gvtId\n      name\n      shortName\n      productTagline\n      shortDescription\n      longDescription\n      metaDescription\n      logoLocation\n      productUrl\n      voucherTypeName\n      orderUrl\n      productTitle\n      variableDenomPriceMinAmount\n      variableDenomPriceMaxAmount\n      __typename\n    }\n    denominations {\n      id\n      voucherId\n      denom\n      displayText\n      pricePoints {\n        id\n        bestdeal\n        paymentChannel {\n          id\n          displayName\n          imageUrl\n          status\n          tagline\n          sortOrder\n          tutorialType\n          tutorialURL\n          statusMessage\n          tutorialLabel\n          __typename\n        }\n        price {\n          currency\n          amount\n          __typename\n        }\n        isVariablePrice\n        __typename\n      }\n      sortOrderId\n      hasStock\n      originalSku\n      status\n      __typename\n    }\n    denominationGroups {\n      displayText\n      displayId\n      pricePoints {\n        id\n        bestdeal\n        paymentChannel {\n          id\n          displayName\n          imageUrl\n          status\n          tagline\n          sortOrder\n          tutorialType\n          tutorialURL\n          statusMessage\n          tutorialLabel\n          isPromotion\n          promotionText\n          infoMessages {\n            icon\n            text\n            __typename\n          }\n          __typename\n        }\n        price {\n          currency\n          amount\n          __typename\n        }\n        isVariablePrice\n        __typename\n      }\n      strikethroughPrice\n      sortOrderId\n      hasStock\n      status\n      isVariableDenom\n      __typename\n    }\n    paymentChannels {\n      id\n      displayName\n      imageUrl\n      status\n      sortOrder\n      isPromotion\n      promotionText\n      buyInputs {\n        label\n        buyInputFields {\n          type\n          required\n          placeHolder\n          minLength\n          maxLength\n          name\n          regexName\n          __typename\n        }\n        __typename\n      }\n      infoMessages {\n        icon\n        text\n        __typename\n      }\n      __typename\n    }\n    faqs {\n      question\n      answer\n      __typename\n    }\n    confirmationDialogSchema {\n      confirmationFields {\n        label\n        value {\n          type\n          field\n          __typename\n        }\n        __typename\n      }\n      invalidUserErrorSchema {\n        errorHeader\n        errorMessage\n        fieldName\n        __typename\n      }\n      __typename\n    }\n    hrefLinks {\n      hrefLang\n      href\n      __typename\n    }\n    cashbackCampaign {\n      campaignId\n      percentage\n      paymentChannelIds\n      skus\n      __typename\n    }\n    __typename\n  }\n}\n"}', 'application/json');

		if(is_array($response)) {
			$result = [];

			foreach($response['data']['getProductPageInfo']['denominationGroups'] as $vResponse) {
				if(!empty($result)) {
					break;
				}

				foreach($vResponse['pricePoints'] as $vPricePoint) {
					if($vPricePoint['price']['amount'] > 20000 AND $vPricePoint['id'] == '27697') {
						$result = [
							'pricePointId' => $vPricePoint['id'],
							'pricePointPrice' => $vPricePoint['price']['amount']
						];
						break;
					}
				}
			}
			return $result;
		} else {
			return null;
		}
	}

	private function request($url, $params, $contentType)
	{
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $params,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_HTTPHEADER => [
				'Content-Type: ' . $contentType,
				'Accept: application/json',
				'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36'
			]
		]);

		$exec = curl_exec($ch);
		curl_close($ch);
		return json_decode($exec, true);
	}

	protected function validationZoneId($game = '', $zoneId = '')
	{
		switch ($game) {
			case 'genshin-impact':
                $zoneId = strtolower($zoneId);
				if (!in_array($zoneId, $this->listZoneId($game))) {
					return [
						'status' => false,
						'data' => [
							'msg' => 'Server tidak valid !!.'
						]
					];
				}
				return [
					'status' => true,
					'data' => $game
				];
				break;

			default:
				return [
					'status' => true,
					'data' => $game
				];
				break;
		}
	}

	protected function listZoneId($game = '')
	{
		switch ($game) {
			case 'genshin-impact':
				return [
					'os_asia', 'os_usa', 'os_euro', 'os_cht'
				];
				break;

			default:
				# code...
				break;
		}
	}
}
