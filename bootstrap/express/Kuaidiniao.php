<?php

namespace app\bootstrap\express;

use Curl\Curl;
use yii\base\BaseObject;

class Kuaidiniao extends BaseObject
{
    /**
     * 揽件
     */
    const STATUS_UNKNOWN = -1;

    /**
     * 揽件
     */
    const STATUS_PICKEDUP = 0;

    /**
     * 发出
     */
    const STATUS_DEPART = 1;

    /**
     * 在途
     */
    const STATUS_TRANSPORTING = 2;

    /**
     * 派件
     */
    const STATUS_DELIVERING = 3;

    /**
     * 签收
     */
    const STATUS_DELIVERED = 4;

    /**
     * 自取
     */
    const STATUS_SELFPICKUP = 5;

    /**
     * 疑难
     */
    const STATUS_REJECTED = 6;

    /**
     * 退回
     */
    const STATUS_RETURNING = 7;

    /**
     * 退签
     */
    const STATUS_RETURNED = 8;

    public $EBusinessID;
    public $SelectType; //免费 OR 收费
    public $AppKey;

    public static function getSupportedExpresses()
    {
        return [
            '京东' => 'JD',
            '京东快运' => 'JDKY',
            '顺丰' => 'SF',
            '申通' => 'STO',
            '韵达' => 'YD',
            '圆通' => 'YTO',
            '中通' => 'ZTO',
            '百世' => 'HTKY',
            'EMS' => 'EMS',
            '天天' => 'HHTT',
            '邮政' => 'YZPY',
            '宅急送' => 'ZJS',
            '国通' => 'GTO',
            '全峰' => 'QFKD',
            '优速' => 'UC',
            '中铁' => 'ZTWL',
            '亚马逊' => 'AMAZON',
            '城际' => 'CJKD',
            '德邦' => 'DBL',
            '汇丰' => 'HFWL',
            '百世快运' => 'BTWL',
            '安捷' => 'AJ',
            '安能' => 'ANE',
            '安信达' => 'AXD',
            '北青小红帽' => 'BQXHM',
            '百福东方' => 'BFDF',
            'CCES' => 'CCES',
            '城市100' => 'CITY100',
            'COE东方' => 'COE',
            '长沙创一' => 'CSCY',
            '成都善途' => 'CDSTKY',
            'D速' => 'DSWL',
            '大田' => 'DTWL',
            '快捷' => 'FAST',
            '联邦' => 'FEDEX',
            'FEDEX' => 'FEDEX_GJ',
            '飞康达' => 'FKD',
            '广东邮政' => 'GDEMS',
            '共速达' => 'GSD',
            '高铁' => 'GTSD',
            '恒路' => 'HLWL',
            '天地华宇' => 'HOAU',
            '华强' => 'hq568',
            '华夏龙' => 'HXLWL',
            '好来运' => 'HYLSD',
            '京广' => 'JGSD',
            '九曳供应链' => 'JIUYE',
            '佳吉' => 'JJKY',
            '嘉里' => 'JLDT',
            '捷特' => 'JTKD',
            '急先达' => 'JXD',
            '晋越' => 'JYKD',
            '加运美' => 'JYM',
            '佳怡' => 'JYWL',
            '跨越' => 'KYWL',
            '龙邦' => 'LB',
            '联昊通' => 'LHT',
            '民航' => 'MHKD',
            '明亮' => 'MLWL',
            '能达' => 'NEDA',
            '平安达腾飞' => 'PADTF',
            '全晨' => 'QCKD',
            '全日通' => 'QRT',
            '如风达' => 'RFD',
            '赛澳递' => 'SAD',
            '圣安' => 'SAWL',
            '盛邦' => 'SBWL',
            '上大' => 'SDWL',
            '盛丰' => 'SFWL',
            '盛辉' => 'SHWL',
            '速通' => 'ST',
            '速腾' => 'STWL',
            '速尔' => 'SURE',
            '唐山申通' => 'TSSTO',
            '全一' => 'UAPEX',
            '万家' => 'WJWL',
            '万象' => 'WXWL',
            '新邦' => 'XBWL',
            '信丰' => 'XFEX',
            '希优特' => 'XYT',
            '新杰' => 'XJ',
            '源安达' => 'YADEX',
            '远成' => 'YCWL',
            '义达' => 'YDH',
            '越丰' => 'YFEX',
            '原飞航' => 'YFHEX',
            '亚风' => 'YFSD',
            '运通' => 'YTKD',
            '亿翔' => 'YXKD',
            '增益' => 'ZENY',
            '汇强' => 'ZHQKD',
            '众通' => 'ZTE',
            '中邮' => 'ZYWL',
            '速必达' => 'SUBIDA',
            '瑞丰' => 'RFEX',
            '快客' => 'QUICK',
            'CNPEX中邮' => 'CNPEX',
            '鸿桥供应链' => 'HOTSCM',
            '海派通' => 'HPTEX',
            '澳邮专线' => 'AYCA',
            '泛捷' => 'PANEX',
            'PCA Express' => 'PCA',
            'UEQ Express' => 'UEQ',
            '程光' => 'CG',
            '富腾达' => 'FTD',
            '中通快运' => 'ZTOKY',
            '品骏' => 'PJ',
            'EWE' => 'EWE',
            '特急送' => 'TJS',
            '承诺达' => 'CND',
            '万家康' => 'WJK',
            '速派快递' => 'FASTGO',
            '秦远海运' => 'QYHY',
            '壹米滴答' => 'YMDD',
            '易达通' => 'YDT',
            '极兔' => 'JTSD',
            '澳德物流' => 'AUODEXPRESS',
        ];
    }

    public static function getExpressCode($expressName)
    {
        if (isset(static::getSupportedExpresses()[$expressName])) {
            return static::getSupportedExpresses()[$expressName];
        } else {
            throw new \Exception("Unsupported express name: {$expressName}");
        }
    }

    protected static function getJsonResponse(Curl $curl)
    {
        $responseRaw = $curl->response;
        $response = json_decode($responseRaw);
        if ($response == false) {
            throw new \Exception('Response data cannot be decoded as json.'. $responseRaw);
        }
        return $response;
    }

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $orderId;

    /**
     * JD物流查询特殊参数
     *
     * @var string
     */
    public $customerName;

    /**
     * Express company name, not ended-with `物流` / `快递` / `快运` / `速递` / `速运`
     *
     * @var string
     */
    public $express;

    /**
     * @var string|int
     */
    public $status;

    public function track()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $requestData = [
            'ShipperCode' => static::getExpressCode($this->express),
            'LogisticCode' => $this->id,
            'OrderCode' => $this->orderId,
        ];
        $requestData['ShipperCode'] === 'SF' && $requestData['CustomerName'] = $this->customerName;
        $requestData = json_encode($requestData);

        if ($this->SelectType === 'paid') {
            $postContent3 = [
                'RequestData' => urlencode($requestData),
                'EBusinessID' => $this->EBusinessID,
                'RequestType' => '8001',
                'DataSign' => base64_encode(md5($requestData . $this->AppKey)),
                'DataType' => '2',
            ];
            $curl->post(
                'https://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx',
                $postContent3
            );
            $response = static::getJsonResponse($curl);
        } else {
            //free
            $postContent2 = [
                'RequestData' => urlencode($requestData),
                'EBusinessID' => $this->EBusinessID,
                'RequestType' => '1002',
                'DataSign' => base64_encode(md5($requestData . $this->AppKey)),
                'DataType' => '2',
            ];
            $curl->post(
                'https://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx',
                $postContent2
            );
            $response = static::getJsonResponse($curl);
        }
        if ($response->Success == false) {
            throw new \Exception($response->Reason);
        }
        $statusMap = [
            2 => self::STATUS_TRANSPORTING,
            3 => self::STATUS_DELIVERED,
            4 => self::STATUS_REJECTED,
        ];
        $this->status = $statusMap[intval($response->State)];
        foreach ($response->Traces as $trace) {
            $this->append($trace->AcceptTime, $trace->AcceptStation);
        }
        return $this;
    }

    protected $data = [];

    const DATETIME = 'time';
    const DESCRIPTION = 'desc';
    const MEMO = 'memo';

    public function append($dateTime, $description, $memo = '')
    {
        $this->data[] = [static::DATETIME => $dateTime, static::DESCRIPTION => $description, static::MEMO => $memo];
    }

    public function toArray()
    {
        usort($this->data, function ($left, $right) {
            if ($left[static::DATETIME] == $right[static::DATETIME]) {
                return 0;
            }
            return $left[static::DATETIME] < $right[static::DATETIME] ? 1 : 0; // 倒序
        });
        return $this->data;
    }
}
