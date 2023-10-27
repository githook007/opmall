<?php
/**
 * 电子发票发票
 */
namespace app\plugins\invoice\forms\common;

use app\common\util\ErrorCode;
use app\bootstrap\response\ApiCode;
use app\web\service\OrderService;
use Goldencloud\Client;
use think\facade\Db;

class Invoice
{
    /**
     * @var 配置文件
     */
    private $config;

    public function __construct($params)
    {
        $this->config = [
            'appkey' => $params['appkey'],
            'secretKey' => $params['secretKey'],
            'seller_taxpayer_num' => $params['seller_taxpayer_num'],  // 模拟销售方商户税号
            'terminal_code' => $params['terminal_code'],  // 盘税号
            'seller_name' => $params['seller_name'],
            'seller_address' => $params['seller_address'],
            'seller_tel' => $params['seller_tel'],
            'seller_bank_name' => $params['seller_bank_name'],
            'seller_bank_account' => $params['seller_bank_account'],
            'drawer' => $params['drawer'],
            'tax_code' => $params['tax_code'],
            'tax_rate' => $params['tax_rate']
        ];
    }

    /**
     * 生成发票  -- 发票开具
     * @param string $userId 用户id
     */
    public function goInvoice($orderData)
    {
        $data = $this->dataList($orderData);  // 组装参数
        $url = 'https://apigw-test.goldentec.com/tax-api/invoice/blue/v1';
        $res = $this->post($url, json_encode($data), $this->sign('/tax-api/invoice/blue/v1', $data));
        if ($res['code'] == 0){
            $pdfUrl = 'https://apigw-test.goldentec.com/tax-api/invoice/query/v1';
            $pdfData = [
                'seller_taxpayer_num' => $this->config['seller_taxpayer_num'],
                'order_sn' => $res['data']['order_sn']
            ];
            $pdfRes = $this->post($pdfUrl, json_encode($pdfData), $this->sign('/tax-api/invoice/query/v1', $pdfData));
            $model = \app\plugins\invoice\models\Invoice::findOne([
                'id' => $orderData['id'],
            ]);
            $model->order_sn = $res['data']['order_sn'];
            $model->status = 2;
            $model->updated_time = time();
            $model->examine_time = time();
            $model->invoice_id = $res['data']['invoice_id'];
            if($pdfRes['code'] == 0){

                $pdfPath = $this->downImgRar($pdfRes['data']['pdf_url']);
                if(!$pdfPath){
                    return [
                        'code' => ApiCode::CODE_ERROR,
                        'msg' => '文件下载失败，请联系管理员'
                    ];
                }
                $file_path = '/web/uploads/invoice/img';
                if (!$this->mkdirs(\Yii::$app->basePath.$file_path)) {
                    return [
                        'code' => ApiCode::CODE_ERROR,
                        'msg' => '文件下载失败，请联系管理员'
                    ];
                }

                $data = $this->pdf2png('/web/uploads/invoice/pdf/'.$pdfPath, $file_path);
                if($data){
                    $model->pdf_url = '/web/uploads/invoice/pdf/'.$pdfPath;
                    $model->pdf_img = $data;
                }
            }else{
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '开票失败，请联系管理员'
                ];
            }
            // print_r($model->toArray());die;
            if ($model->save()) {
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '开票成功'
                ];
            }
        }else{
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $res['message'] ? $res['message'] : '开票失败，请联系管理员'
            ];
        }
    }

    /**
     * 发送邮箱
     */
    public function sendEmail($data){
        $emailUrl = 'https://apigw-test.goldentec.com/tax-api/invoice/send-email/v1';
        $emailData = [
            'seller_taxpayer_num' => $this->config['seller_taxpayer_num'],
            'order_sn' => $data['order_sn'],
            'email' => $data['buyer_email']
        ];
        $emailRes = $this->post($emailUrl, json_encode($emailData), $this->sign('/tax-api/invoice/send-email/v1', $emailData));
        if($emailRes['code'] == 0){
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '发送成功'
            ];
        }else{
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '发送失败，请联系管理员'
            ];
        }
    }

    /**
     * 组装参数  -- 发票开具
     * @param array $orderData 订单数据
     * * @return array json 返回数据
     */
    public function dataList($orderData){
        if (empty($orderData)) {
            return ErrorCode::code("params_error", "数据");
        }
        $tax_amount = $this->config['tax_rate'] / 100;
        $total_price = (100 + $this->config['tax_rate']) / 100;
        $list = [
            'seller_taxpayer_num' => $this->config['seller_taxpayer_num'],  // 模拟销售方商户税号
            'invoice_type_code' => $orderData['invoice_type_code'],
            'title_type' => $orderData['title_type'],
            'buyer_title' => $orderData['buyer_title'],
            'order_id' => $orderData['order']['order_no'],
            'buyer_taxpayer_num' => $orderData['buyer_taxpayer_num'],  // 购方纳税人识别号
            'buyer_bank_account' => $orderData['buyer_bank_account'],  // 购方银行账号
            'buyer_phone' => $orderData['buyer_phone'],
            'user_openid' => $orderData['user']['id'],
            'callback_url' => 'http://www.yii.com//callback',
            'terminal_code' => $this->config['terminal_code'],  // 盘税号
            'amount_has_tax' => (string)$orderData['total_pay_price'],
            'amount_without_tax' => (string)round($orderData['total_pay_price'] / $total_price, 2),
            'template' => "1",
            'payee' => $orderData['payee'],  // 收款人姓名
            'buyer_address' => $orderData['buyer_address'],
            'buyer_bank_name' => $orderData['buyer_bank_name'],
            'seller_name' => $this->config['seller_name'],
            'seller_address' => $this->config['seller_address'],
            'seller_tel' => $this->config['seller_tel'],
            'seller_bank_name' => $this->config['seller_bank_name'],
            'seller_bank_account' => $this->config['seller_bank_account'],
            'drawer' => $this->config['drawer'],
        ];
        $list['tax_amount'] = (string)round($list['amount_without_tax'] * $tax_amount, 2);
        if (!empty($orderData['buyer_email'])){
            $list['buyer_email'] = $orderData['buyer_email'];
        }
        $list['items'] = [];
        foreach ($orderData['order']['detail'] as $k => &$v){
            $list['items'][$k] = [
                'name' => $v['goods_info']['goods_attr']['name'],
                'tax_rate' => (string)$tax_amount,
                'models' => strlen($v['goods_info']['attr_list'][0]['attr_name']) > 18 ? mb_substr($v['goods_info']['attr_list'][0]['attr_name'], 0, 18) : $v['goods_info']['attr_list'][0]['attr_name'],
                'unit' => $v['goods_info']['attr_list'][0]['attr_group_name'],
                'total_price' => (string)round($v['total_price'] / $total_price, 2),
                'price' => (string)round($v['total_price'] / $total_price / $v['num'], 8),
                'total' => (string)$v['num'],
                'tax_code' => $this->config['tax_code'],
            ];
            $list['items'][$k]['tax_amount'] = (string)round($list['items'][$k]['total_price'] * $tax_amount, 2);
        }
        return $list;
    }

    /**
     * 生成签名+公共参数
     */
    public function sign($url, $list){
        $data = [
            'algorithm' => 'HMAC-SHA256',
            'appkey' => $this->config['appkey'],
            'nonce' => $this->get_num(6),
            'timestamp' => time(),
        ];
        $secretKey = $this->config['secretKey'];
        $srcStr = $this->ToUrlParams($data, '|').'|'.$url.'|'.json_encode($list);
        $signStr = base64_encode(hash_hmac('sha256', $srcStr, $secretKey, true));
        $data['signature'] = $signStr;

        return $this->ToUrlParams($data, ',');
    }

    /**
     * 随机32位字符串
     */
    public function get_num($length){
        $str = '0123456789';
        $len = strlen($str)-1;
        $randstr = '';
        for ($i=0;$i<$length;$i++) {
            $num=mt_rand(0,$len);
            $randstr .= $str[$num];
        }
        return $randstr;

    }

    /**
     * post请求
     * @param string $url 请求地址
     * @param array $data 请求数据
     * @return string json 返回数据
     */
    public function post($url, $data, $Authorization)
    {
        $ch = curl_init();
        $this_header = [
            'Authorization: '. $Authorization,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
            'charset=UTF-8'
        ];
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//如果不加验证,就设false,商户自行处理
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $output = curl_exec($ch);
        curl_close($ch);
        return  json_decode($output, true);
    }


    /**
     * 拼接处理参数
     * @param array $array 参数
     * @return string
     */
    public function ToUrlParams(array $array, $Symbol)
    {
        $buff = "";
        foreach ($array as $k => $v)
        {
            if($v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . $Symbol;
            }
        }
        $buff = trim($buff, $Symbol);
        return $buff;
    }

    //远程路径，名称
    function downImgRar($url){
        $file_path = \Yii::$app->basePath.'/web/uploads/invoice/pdf';
        if (!$this->mkdirs($file_path)) {
            return false;
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $rawdata=curl_exec ($ch);
        curl_close ($ch);
        // 使用中文文件名需要转码
        $name = md5(time());
        $fp = fopen($file_path.'/'.iconv('UTF-8', 'GBK', $name).".pdf",'w');
        fwrite($fp, $rawdata);
        fclose($fp);
        // 返回路径
        return $name.".pdf";
    }



    function mkdirs($dir, $mode = 0777) {
        if (!is_dir($dir)) {
            if (!mkdir($dir, $mode, true)){
                return false;
            }
        }
        return true;
    }

    /**
     * 将pdf转化为单一png图片
     * @param string $pdf  pdf所在路径 （/www/pdf/abc.pdf pdf所在的绝对路径）
     * @param string $path 新生成图片所在路径 (/www/pngs/)
     *
     * @throws Exception
     */
    function pdf2png($pdf, $pathFirst)
    {
        $pdf = \Yii::$app->basePath.$pdf;
        $path = \Yii::$app->basePath.$pathFirst;
        $urlFirst = '';
        try {
            $im = new \Imagick();
            $im->setCompressionQuality(100);
            $im->setResolution(100, 100);//设置分辨率 值越大分辨率越高
            $im->readImage($pdf);

            $canvas = new \Imagick();
            $imgNum = $im->getNumberImages();
            foreach ($im as $k => $sub) {
                $sub->setImageFormat('png');
                $sub->stripImage();
                $sub->trimImage(0);
                $width  = $sub->getImageWidth() + 10;
                $height = $sub->getImageHeight() + 10;
                if ($k + 1 == $imgNum) {
                    $height += 10;
                } //最后添加10的height
                $canvas->newImage($width, $height, new \ImagickPixel('white'));
                $canvas->compositeImage($sub, \Imagick::COMPOSITE_DEFAULT, 5, 5);
            }

            $canvas->resetIterator();
            $timeMicro = microtime(true);
            $url = $path . '/' . $timeMicro . '.png';
            $urlFirst = $pathFirst . '/' . $timeMicro . '.png';
            $canvas->appendImages(true)->writeImage($url);
        } catch (Exception $e) {
            return false;
        }
        return $urlFirst;
    }

}