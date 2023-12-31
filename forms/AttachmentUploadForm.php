<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/29 17:07
 */

namespace app\forms;

use app\forms\admin\mall\MallOverrunForm;
use app\forms\common\attachment\AttachmentUpload;
use app\forms\common\attachment\CommonAttachment;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;
use app\models\User;
use yii\web\UploadedFile;

class AttachmentUploadForm extends Model
{
    /** @var UploadedFile */
    public $file;

    public $type;

    public $attachment_group_id;

    protected $docExt = ['txt', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'csv', 'pdf', 'md'];
    protected $imageExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp',];
    protected $videoExt = ['mp4', 'ogg', 'm4a',];

    public function rules()
    {
        return [
            [['file'], 'file'],
            [['file'], 'validateExt'],
            [['attachment_group_id'], 'integer'],
            [['type'], 'string'],
        ];
    }

    public function validateExt($a, $p)
    {
        $supportExt = array_merge($this->docExt, $this->imageExt, $this->videoExt);
        if (!in_array($this->file->extension, $supportExt)) {
            $this->addError($a, '不支持的文件类型: ' . $this->file->extension);
        }

        $option = CommonOption::get(Option::NAME_OVERRUN, 0, 'admin', (new MallOverrunForm())->getDefault());
        if (in_array($this->file->extension, $this->imageExt)) {
            if (($option['is_img_overrun'] == 'false' || $option['is_img_overrun'] == false) && $this->file->size > ($option['img_overrun'] * 1024 * 1024)) {
                $this->addError($a, '图片大小超出限制,当前大小为: '
                    . (round($this->file->size / 1024 / 1024, 4)) . 'MB,最大限制为:'
                    . $option['img_overrun'] . 'MB');
            }
        }

        if (in_array($this->file->extension, $this->videoExt)) {
            if (($option['is_video_overrun'] == 'false' || $option['is_video_overrun'] == false) && $this->file->size > ($option['video_overrun'] * 1024 * 1024)) {
                $this->addError($a, '视频大小超出限制,当前大小为: '
                    . (round($this->file->size / 1024 / 1024, 4)) . 'MB,最大限制为:'
                    . $option['video_overrun'] . 'MB');
            }
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        try {
            $mall = \Yii::$app->mall;
        } catch (\Exception $e) {
            $mall = null;
        }

        $user = null;
        if (!\Yii::$app->user->isGuest) {
            $user = \Yii::$app->user->identity;
        }
        try {
            $storage = CommonAttachment::getCommon($user, $mall)->getAttachment();
        } catch (\Exception $exception) {
            return [
                'code' => 1,
                'msg' => $exception->getMessage(),
            ];
        }

        if ($this->type === 'image') {
            $type = 1;
        } elseif ($this->type === 'video') {
            $type = 2;
        } else {
            if (in_array($this->file->extension, $this->imageExt)) {
                $type = 1;
            } elseif (in_array($this->file->extension, $this->videoExt)) {
                $type = 2;
            } elseif (in_array($this->file->extension, $this->docExt)) {
                $type = 3;
            } else {
                $type = 0;
            }
        }

        $mallId = 0;
        $mchId = 0;
        if (!\Yii::$app->user->isGuest) {
            /** @var User $user */
            $user = \Yii::$app->user->identity;
            $userIdentity = $user->identity;
            if (
                $userIdentity
                && ($userIdentity->is_super_admin || $userIdentity->is_admin || $userIdentity->is_operator)
            ) {
                $mallId = $mall ? $mall->id : 0;
            } elseif (\Yii::$app->mchId && $mall) {
                $mallId = $mall->id;
            }
            $mchId = \Yii::$app->mchId ?: 0;
        }
        try {
            $attachmentUpload = new AttachmentUpload([
                'storage' => $storage,
                'file' => $this->file,
                'type' => $type,
                'mall_id' => $mallId,
                'mch_id' => $mchId,
                'attachment_group_id' => $this->attachment_group_id ?: 0,
                'mall' => $mall
            ]);
            $attachment = $attachmentUpload->upload();
            $attachment->thumb_url = $attachment->thumb_url ?: $attachment->url;
            return [
                'code' => 0,
                'data' => $attachment,
            ];
        } catch (\Exception $exception) {
            return [
                'code' => 1,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public static function getInstanceFromFile($localFilePath)
    {
        return AttachmentUpload::getInstanceFromFile($localFilePath);
    }

    public function getDefaultTag()
    {
        return [
            'navbar' => '标签栏',
            'user_order' => '我的订单',
            'user_account' => '我的账户',
            'auth_page' => '授权页面',
            'quick_navigation' => '快捷导航',
            'topic' => '专题',
        ];
    }
}
