<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // 支付
    public function index(){
    	$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = 'yang教育';
		$aop->rsaPrivateKey = 'MIIEpgIBAAKCAQEArmjl8dLGhMbe6b/jE2q7Y4oZm3EUM08dwCgLq5OXdXK7Tx511sDbzJbTOMS9rksDFYRVXMuiNeEy+X9NDMlz8CVZwvS/lyaabkal1ykI8XBAnPfK7AY/99/AqUuti8ab67i0hvorq8HdF6gqNDdzec72qNGk+CiCPPGbdrLNCKSpX6j96eZhbaDaN8GygGEzyE1OR+/xcNjKf1wE84g8kk9lHcoK2QNw+mvcjhru1zQZnKPWD6ePLHKrHtr6pKkv5eM06K2A2tv5HpYKlOCXFNKi2FfTZcc5QBwzRp3gv4BQLl92EbBNlejoHyXamHy74E4+kQ1L56g+PT4ihAGdRQIDAQABAoIBAQCmRjtyoI/CZhZ+owHJsSeVbkObfeLUR8kFOShnGv56ajdI5rFRW+ww0Fnu9SIg2ELIcLExFrI+y8PdORAr8KMnf3Rj+RHu+E6ic5gH2Ic+JtZyz4oWGp9BoX/75ro9V8uunxj81eRsixZNR3V08qiUqEtgBv5P187Sa0TblPsEEOTCYKgnTjaICVMJCnvsDj1HLkVodR2narN9arL22iT3gWoM47sSNvRun2TobEAWFJINE0Kt/adffZc0sHvg2q25Pj+U271DRpCxXCjfRugf9NadsoycoBEdskqgY6TrBDZXGZilcyXHLVzXQ7MVhEiVp2VnsVp813qY3DppDwIBAoGBANPMVNBzfCBvPrwxJJthZLH08T+kB/+L7QbhYJu5tRgRusi1hJ5jrmOElAKACyr02eNfB9fcYDB9FZZe2dJqI/R5LhAuKTdqETjJTCXNIBPUQWegy7Xr2W4ZBjJHlI0ATz3z0PKk8TPbH6qcozFS+EyEt35dTvQ0e7zO4ykBgo5NAoGBANLPB27KTUlb/81QxMwpoWkF21E39MTe68j1o6DxZWrWkKvR9UfJoVVrRdeIH7no9F5a03SGiOelLpLfTyrpM/KCW5896X+HdYLO6OSaMNjXbSXmjAHhU2qH5k9TalRobCHqgwq9AWXuGrYM1VoKfGEXWBk7c5lDp0NprEB8ffbZAoGBALhx3Ha/65wPZRHctiV+loOHbUTf43s/bwar5UcYXcX9Qq2hrkGFS4wtG/xlZ9Rb8RhaXOk2aKoxdEhEh+r/NhqkIWJD4O76Ns8+NktBLKs4EFFfrafbIboInuXQgmScnWW1XATDca6YKCabTF8bA1MXzPiF58kxn4SYoyjFwN2NAoGBAJd0kIWDqQVUqLLtHYcFPedDgu+WQTAUbNiDyty9sjyRDX20qgG6lkPVf2c7cHfUK4WCwbtNUR0EazKwZ6OPyneoOoVtKM4sFzw0xdRAB2ozVELPobVUGudF3i0N5C3inBW99AoKAvAlomE7VSmMsRHcgLoYIZRyq8BeMpheGO35AoGBAI03PhfFZ+V9goSN3ZbxXPVLUr8RCXNtYcLWXgwK0Wy8dpu5CxYYrpZUGp+8tHIPLzf78zYyCEJSDq3/0u7PG26qk628RkSU3ADC3uIuGFDXIiamPStyzIl/XncDFgrVl1cQpbtnv784c1g8LZzZjA3QB3BndjbfcliRG09JW6dW';
		$aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArmjl8dLGhMbe6b/jE2q7Y4oZm3EUM08dwCgLq5OXdXK7Tx511sDbzJbTOMS9rksDFYRVXMuiNeEy+X9NDMlz8CVZwvS/lyaabkal1ykI8XBAnPfK7AY/99/AqUuti8ab67i0hvorq8HdF6gqNDdzec72qNGk+CiCPPGbdrLNCKSpX6j96eZhbaDaN8GygGEzyE1OR+/xcNjKf1wE84g8kk9lHcoK2QNw+mvcjhru1zQZnKPWD6ePLHKrHtr6pKkv5eM06K2A2tv5HpYKlOCXFNKi2FfTZcc5QBwzRp3gv4BQLl92EbBNlejoHyXamHy74E4+kQ1L56g+PT4ihAGdRQIDAQAB';
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset='GBK';
		$aop->format='json';
		$request = new AlipayTradePagePayRequest ();

		$request->setBizContent(
			
		);
		$result = $aop->pageExecute ( $request); 

		$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
		$resultCode = $result->$responseNode->code;
		if(!empty($resultCode)&&$resultCode == 10000){
			echo "成功";
		} else {
			echo "失败";
		}
    }
}
