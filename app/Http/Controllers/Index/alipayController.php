<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/8
 * Time: 20:19
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;
class alipayController extends Controller{

    protected $config = [
        'app_id' => '2021000116698155',//你创建应用的APPID
        'notify_url' => 'http://jd.2004.com/alipay/AliPayReturn',//异步回调地址
        'return_url' => 'http://jd.2004.com/alipay/AliPayNotify',//同步回调地址
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl2/LMstf0UlcnWRP6RjSLELm9CwGbP9pWv0NcsUKkqjx/VnR/iUObLJQyAkQkZTsOpPXORo70/YEh82cfw4y3me9eLurbPvCb8oOxCM+sYRR08qPgPOkQdh5zbL43/uW2Nlyb1WCFZ3fI59f/xl/+KawiXst0Dj9tsNyBaLETKWy0NMZhfXlWhtd47t/Gu3tqciPwAEsLapjkEjg8dmN6fXOLGe2/rpkVxUtBELmki1D/AbgiGWRU+8JLv9B38sU/DFrzWZO+RbXtVpaJrH3NS10LHTQs5mBr7SXs8Q3WoRTINoJNA93D2GIo0r6nho6GVKq+7sS++n8LEtKFYW1WQIDAQAB',//是支付宝公钥，不是应用公钥,  公钥要写成一行,不要换行
        // 加密方式： **RSA2**
        'private_key' => 'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCTzrlvC3IB2p9aHWGCqoFOD5RpnSjAIDGGHRSD1aj91h0IISeoYdZwZ2xaJoTkAK96zgfsoFNBg9TnK2f8cSgcknVfmDH2/EBL5gLhvR2wYJns9c0rL9FgZVEMBuCCEqcNUbDEHeumj56+8Oi+aAA1k4duM6UyltNJn7wazCD2i8pyT8MASFpTTCnxGtpy+cJk9ls5S+cg0xQifjKUsW7iFfgpPevXX+axl5UmyfwH3ts/9xQINV7HEo19yeehKQSr5O+Q4NYfnlc6TgNYWq+ETeSWRT/nr5mffRX3PHujb6gr/MEgRlwXM9DlAr2agkom3FTV1kolgRybtx3D6fV9AgMBAAECggEAe2xC8dQH1j59lB02oIrKQKnHz005iv0W/Zto1xFh9NyHD5PH7tYL336tPrYtf8qGvbAvc8sI2otAC+z1/xlqWjl+I14OUuSeuAIQY/msQezYe8NhGG/skWbo/3b6oAL9VaTiS1GExmflMiIu51gm3JYdn8smZhEFy9PDmkjcOaJ+Rsqgw4YFTL5BCEQgHvqzSVWpGLEimUSGCyCRXYbmPoawYOJg9aXlq+FIAi+Nf4sURJIWalFDQXPOO1wppgK4tX/daTX0qGDevsnBgcoZvLK1H9P4Ipca2dIIS7Z+AXDVvj68w7gHrqoH/HTo8W+4zaGRJBKPgwR/U3nNtM43DQKBgQD/vl94rAciCP/CmOIIP0EvLpgHhfSZjqiiojBfLXZD9QrctYkU50VUM25X3UDR2RlfN+4mzYqScE/gyJANkkPAVJDXIDqCvnUxMRh7nKIBpdUfWoJZH7xEjUrhdBwPQQLi795UoDckfUT/doee0BmKIWlGBaMj4W6d+tjU1GQ4OwKBgQCT9KdUpxG69cjG6ZLy9gxFe9AKdg3npDxwG9oSmgl00kDPdMwo6yjul9HdjkjnzDRim8MsjqIIo4oDJWHfGaX+mMUm+CdbmNvCy5DLm4n+34NKvTyd6jhOcChcuZVfgaVrXKppNtT+ZXFv4VuDp5j5SqX+Bp6fTzg/p3atWW5lpwKBgCJnCZDCE3OQolcbGcziNXKTYgAhFPZTKnw2NSuYggBCRmPKR9Z4Bet9v6oyKTYRbkhQzciKfcmVMima0UYCFvsYZSOLQlO0Ky+i2xhFycVO+YxuMHqsuja+iwQpCl1C8ZB1lALuSnyuHUoAN941QDpEpFS3DWsWODsoM2Lt701hAoGADcO4AX8dfig5io/WVPYhBCHVo/OBragw2zksG4jrEkwxLVuvVqsx/qhvJM6E59Oul/HnwXBvkKAuScajiU7oi3wI82wotTPOVhv8F4Ub3HNM0poyVnqgzGNQzfeR9vWnvwo67FjmjdhAKmlryx6/c4nHUY+qGCYVlI5u0we75ocCgYA9A8/NDIwR+O+wBFhVRr5e2yiXhR/naw34axN9ZyHOvaS46JAOFhx0I4inCn+Sr5pULlU0OIVFT4Z8/DTP52RWgkN2gJOyl0UGLYlMpUQbP+BrpVjWZAFUgPBgQjSADnuQqW9KCvoQoNe/sOHZRRQkOBm+kQdBOXvOE36O/YGquw==',//密钥,密钥要写成一行,不要换行
        'log' => [ // optional
            'file' => './logs/alipay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
    ];
    public function Alipay($order)
    {
        $alipay = Pay::alipay($this->config)->web($order);

        return $alipay;// laravel 框架中请直接 `return $alipay`
    }

    public function AliPayReturn()
    {
        $data = Pay::alipay($this->config)->verify(); // 是的，验签就这么简单！

        // 订单号：$data->out_trade_no
        // 支付宝交易号：$data->trade_no
        // 订单总金额：$data->total_amount
        return Pay::alipay($this->config)->success();// laravel 框架中请直接 `return $alipay->success()`
    }

    public function AliPayNotify(Request $request)
    {
        $data = Pay::alipay($this->config)->verify(); // 是的，验签就这么简单！

        // 订单号：$data->out_trade_no
        // 支付宝交易号：$data->trade_no
        // 订单总金额：$data->total_amount
        $money = $request->total_amount;
        return view('order.paysuccess',['money'=>$money]);
    }

}
