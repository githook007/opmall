<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\commands;

use app\forms\mall\goods\GoodsEditForm;
use app\models\Attachment;
use app\models\GoodsCats;
use app\models\Mall;
use app\models\User;
use Grafika\Grafika;
use Grafika\ImageInterface;
use function GuzzleHttp\Psr7\mimetype_from_filename;
use yii\console\Controller;
use yii\web\UploadedFile;

class ExportController extends Controller
{
    protected $host;

    public function init(){
        ini_set("memory_limit", "-1");
        $params = \Yii::$app->request->getParams();
        $this->host = isset($params[1]) ? $params[1] : "http://www.mallstore.com";
    }

    public function actionExcel()
    {
        $file = \Yii::$app->basePath . "/data.xlsx";

        $inputFileType = \PHPExcel_IOFactory::identify($file);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($file);
        $sheet = $objPHPExcel->getSheet(0);
        $data = $sheet->toArray(); //该方法读取不到图片，图片需单独处理

        $imageFilePath = \Yii::$app->basePath .'/web/temp_xls/'; //图片本地存储的路径
        if (!file_exists($imageFilePath)) {
            mkdir($imageFilePath, 0777, true);
        }
        // 处理图片
        foreach ($sheet->getDrawingCollection() as $img) {
            list($startColumn, $startRow) = \PHPExcel_Cell::coordinateFromString($img->getCoordinates()); //获取图片所在行和列
            $imageFileName = $img->getCoordinates() . mt_rand(10000, 99999);
            switch($img->getExtension()) {
                case 'jpg':
                case 'jpeg':
                    $imageFileName .= '.jpeg';
                    $source = imagecreatefromjpeg($img->getPath());
                    imagejpeg($source, $imageFilePath.$imageFileName);
                    break;
                case 'gif':
                    $imageFileName .= '.gif';
                    $source = imagecreatefromgif($img->getPath());
                    imagejpeg($source, $imageFilePath.$imageFileName);
                    break;
                case 'png':
                    $imageFileName .= '.png';
                    $source = imagecreatefrompng($img->getPath());
                    imagejpeg($source, $imageFilePath.$imageFileName);
                    break;
            }
            $startColumn = $this->ABC2decimal($startColumn);
            $data[$startRow-1][$startColumn] = $imageFilePath . $imageFileName;
        }
        \Yii::$app->setMall(Mall::findOne(1));
        \Yii::$app->user->setIdentity(User::findOne([1]));
        $temp = [];
        unset($data[0]);
        foreach ($data as $item){
            try {
                if (empty($item[4])) {
                    continue;
                }
                $item = array_map(function ($var){
                    return trim($var);
                }, $item);
                $cats = $pics = [];
                // 处理分类
                if (!empty($item[5]) && !isset($temp[0][$item[5]])) {
                    $this->cat($item[5], $temp[0], 0);
                }
                if (isset($temp[0][$item[5]]) && !empty($item[0]) && !isset($temp[1][$temp[0][$item[5]]][$item[0]])) {
                    $this->cat($item[0], $temp[1], $temp[0][$item[5]]);
                }
                if (isset($temp[1][$temp[0][$item[5]]][$item[0]]) && !empty($item[1]) && !isset($temp[1][$temp[1][$temp[0][$item[5]]][$item[0]]][$item[1]])) {
                    $this->cat($item[1], $temp[2], $temp[1][$temp[0][$item[5]]][$item[0]]);
                }
                if (!empty($item[5])) {
                    $cats[] = $temp[0][$item[5]];
                }else{
                    continue;
                }
                if (!empty($item[0])) {
                    $cats[] = $temp[1][$temp[0][$item[5]]][$item[0]];
                }
                if (!empty($item[1])) {
                    $cats[] = $temp[2][$temp[1][$temp[0][$item[5]]][$item[0]]][$item[1]];
                }
                $pics[] = $this->saveImg($item[3]);
                $this->goods([
                    "title" => $item[4], "pics" => $pics, "num" => $item[6] ?? 1, "cats" => $cats,
                    "storage" => $item[7] ?? '', "goods_no" => strval($item[2] ?? '')
                ]);
            }catch (\Exception $e){
                echo $e->getMessage()."<pre>";print_r($cats);echo "<pre>";print_r($item);echo "<pre>";print_r($temp);die("2");
            }
        }
        remove_dir($imageFilePath);
        echo "成功", PHP_EOL;
    }
    
    public function saveImg($fileName){
        if (!file_exists($fileName)) {
            return \Yii::$app->basePath."/web/statics/img/mall/default_img.png";
        }
        $localFilePath = str_replace('\\', '/', $fileName);
        $pathInfo = pathinfo($localFilePath);
        $name = $pathInfo['basename'];
        $size = filesize($localFilePath);
        $type = mimetype_from_filename($localFilePath);
        $file = new UploadedFile(['name' => $name, 'type' => $type, 'tempName' => $localFilePath, 'error' => 0, 'size' => $size]);
        
        try {
            $mall = \Yii::$app->mall;
            $mallFolder = "mall{$mall->id}/";
        } catch (\Exception $e) {
            $mall = null;
            $mallFolder = '';
        }
        $dateFolder = date('Ymd');
        $saveFileFolder = '/uploads/' . $mallFolder . $dateFolder;
        $saveThumbFolder = '/uploads/thumbs/' . $mallFolder . $dateFolder;
        $saveFileName = md5_file($file->tempName) . '.' . $file->getExtension();
        $baseWebPath = \Yii::$app->basePath . '/web';
        $baseWebUrl = $this->host . "/web";
        $saveFile = $baseWebPath . $saveFileFolder . '/' . $saveFileName;
        $saveThumbFile = $baseWebPath . $saveThumbFolder . '/' . $saveFileName;
        if (!is_dir($baseWebPath . $saveFileFolder)) {
            if (!make_dir($baseWebPath . $saveFileFolder)) {
                throw new \Exception('上传失败，无法创建文件夹`' . $baseWebPath . $saveFileFolder . '`，请检查目录写入权限。');
            }
        }
        if (!is_dir($baseWebPath . $saveThumbFolder)) {
            if (!make_dir($baseWebPath . $saveThumbFolder)) {
                throw new \Exception('上传失败，无法创建文件夹`' . $baseWebPath . $saveThumbFolder . '`，请检查目录写入权限。');
            }
        }
        if (!$file->saveAs($saveFile)) {
            if (!copy($file->tempName, $saveFile)) {
                throw new \Exception('文件保存失败，请检查目录写入权限。');
            }
        }
        $url = $baseWebUrl . $saveFileFolder . '/' . $saveFileName;
        try {
            $editor = Grafika::createEditor(get_supported_image_lib());
            /** @var ImageInterface $image */
            $editor->open($image, $saveFile);
            $editor->resizeFit($image, 200, 200);
            $editor->save($image, $saveThumbFile);
            $thumbUrl = $baseWebUrl . $saveThumbFolder . '/' . $saveFileName;
        } catch (\Exception $e) {
            $thumbUrl = '';
        }

        $attachment = new Attachment();
        $attachment->storage_id = 0;
        $attachment->user_id = 0;
        $attachment->name = $file->name;
        $attachment->size = $file->size;
        $attachment->type = 1;
        $attachment->is_delete = 0;
        $attachment->url = $url;
        $attachment->thumb_url = $thumbUrl;
        $attachment->attachment_group_id = 0;
        $attachment->mall_id = $mall ? $mall->id : 0;
        $attachment->mch_id = \Yii::$app->mchId ? \Yii::$app->mchId : 0;

        if ($attachment->save()) {
            return $attachment->url;
        } else {
            throw new \Exception(isset($attachment->errors) ? current($attachment->errors)[0] : '数据异常！');
        }
    }

    public function goods($list){
        $t = \Yii::$app->db->beginTransaction();
        try {
            $picList = [];
            foreach ($list['pics'] as $item) {
                $picList[] = [
                    'pic_url' => $item
                ];
            }
            $form = new GoodsEditForm();
            $form->attributes = [
                'name' => $list['title'],
                'price' => 0,
                'original_price' => 0,
                'cost_price' => 0,
                'detail' => '-',
                'cover_pic' => count($list['pics']) >= 1 ? $list['pics'][0] : '',
                'pic_url' => $picList,
                'unit' => '件',
                'attr' => [],
                'goods_num' => $list['num'],
                'attrGroups' => [],
                'video_url' => '',
                'status' => 1,
                'use_attr' => 0,
                'goods_no' => $list["goods_no"],
                'member_price' => [],
                'cats' => $list["cats"],
                'mchCats' => [],
                "storage" => $list["storage"]
            ];
            $res = $form->save();
            if ($res['code'] == 1) {
                throw new \Exception($res['msg']);
            }
            $t->commit();
            return true;
        } catch (\Exception $exception) {
            $t->rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function cat($name, &$temp, $parent_id = 0){
        $name = trim($name);
        $cat = GoodsCats::findOne(["mall_id" => 1, "parent_id" => $parent_id, "name" => $name]);
        if(!$cat) {
            $cat = new GoodsCats();
            $cat->mall_id = 1;
            $cat->mch_id = 0;
            $cat->parent_id = $parent_id;
            $cat->name = $name;
            $cat->sort = 100;
            $cat->advert_params = \Yii::$app->serializer->encode([]);
//        $cat->status = $this->status;
            $cat->save();
        }
        if($parent_id) {
            $temp[$parent_id][$name] = $cat->id ?? 0;
        }else{
            $temp[$name] = $cat->id ?? 0;
        }
    }

    public function ABC2decimal($abc){
        $ten = 0;
        $len = strlen($abc);
        for($i=1;$i<=$len;$i++){
            $char = substr($abc,0-$i,1);//反向获取单个字符

            $int = ord($char);
            $ten += ($int-65)*pow(26,$i-1);
        }
        return $ten;
    }
}
