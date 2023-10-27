<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\controllers\admin;

use app\controllers\behaviors\SuperAdminFilter;
use app\bootstrap\response\ApiCode;
use app\forms\admin\CommunicationSettingEditForm;
use app\forms\admin\copyright\CopyrightEditForm;
use app\forms\admin\copyright\CopyrightForm;
use app\forms\admin\MessageRemindSettingEditForm;
use app\forms\admin\MessageRemindSettingForm;
use app\forms\admin\mall\FileForm;
use app\forms\admin\mall\MallOverrunForm;
use app\forms\admin\PaySettingEditForm;
use app\forms\admin\PaySettingForm;
use app\forms\admin\platform\PlatformSettingEditForm;
use app\forms\admin\platform\PlatformSettingForm;
use app\forms\common\CommonOption;
use app\forms\common\UploadForm;
use app\forms\common\attachment\CommonAttachment;
use app\forms\open3rd\ExtAppForm;
use app\forms\open3rd\Open3rdException;
use app\forms\PickLinkForm;
use app\helpers\ArrayHelper;
use app\jobs\RunQueueShJob;
use app\jobs\TestQueueServiceJob;
use app\models\Mall;
use app\models\Option;
use app\plugins\wxapp\models\WxappWxminiprograms;
use yii\web\UploadedFile;

class SettingController extends AdminController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'superAdminFilter' => [
                'class' => SuperAdminFilter::class,
                'safeRoutes' => [
                    'admin/setting/small-routine',
                    'admin/setting/upload-file',
                    'admin/setting/attachment',
                    'admin/setting/attachment-create-storage',
                    'admin/setting/attachment-enable-storage',
                ]
            ],
        ]);
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $setting = \Yii::$app->request->post('setting');
                $setting = json_decode($setting, true);
                if (CommonOption::set(Option::NAME_IND_SETTING, $setting)) {
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'msg' => '保存成功。',
                    ];
                } else {
                    return [
                        'code' => ApiCode::CODE_ERROR,
                        'msg' => '保存失败。',
                    ];
                }
            } else {
                $setting = CommonOption::get(Option::NAME_IND_SETTING);
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'data' => [
                        'setting' => $setting,
                    ],
                ];
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionAttachment()
    {
        if (\Yii::$app->request->isAjax) {
            $user = \Yii::$app->user->identity;
            $common = CommonAttachment::getCommon($user);
            $list = $common->getAttachmentList();
            return $this->asJson([
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'list' => $list,
                    'storageTypes' => $common->getStorageType()
                ]
            ]);
        } else {
            return $this->render('attachment');
        }
    }

    public function actionAttachmentCreateStorage()
    {
        try {
            $user = \Yii::$app->user->identity;
            $common = CommonAttachment::getCommon($user);
            $data = \Yii::$app->request->post();
            $common->attachmentCreateStorage($data);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function actionAttachmentEnableStorage($id)
    {
        $common = CommonAttachment::getCommon(\Yii::$app->user->identity);
        $common->attachmentEnableStorage($id);
        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '操作成功。',
        ]);
    }

    public function actionOverrun()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->post()) {
                $form = new MallOverrunForm();
                $form->form = \Yii::$app->request->post('form');

                return $this->asJson($form->save());
            } else {
                $form = new MallOverrunForm();
                return $this->asJson($form->setting());
            }
        } else {
            return $this->render('overrun');
        }
    }

    public function actionQueueService($action = null, $id = null, $time = null)
    {
        if (\Yii::$app->request->isAjax) {
            if ($action == 'create') {
                try {
                    $time = time();
                    $job = new TestQueueServiceJob();
                    $job->time = $time;
                    $id = \Yii::$app->queue->delay(0)->push($job);
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'data' => [
                            'id' => $id,
                            'time' => $time,
                        ],
                    ];
                } catch (\Exception $exception) {
                    return [
                        'code' => ApiCode::CODE_ERROR,
                        'msg' => '队列服务测试失败：' . $exception->getMessage(),
                    ];
                }
            }
            if ($action == 'test') {
                $done = \Yii::$app->queue->isDone($id);
                if ($done) {
                    $job = new TestQueueServiceJob();
                    $job->time = intval($time);
                    if (!$job->valid()) {
                        return [
                            'code' => ApiCode::CODE_ERROR,
                            'msg' => '队列服务测试失败：任务似乎已经运行，但没有得到预期结果，请检查redis是否连接正常并且数据正常。',
                        ];
                    } else {
                        return [
                            'code' => ApiCode::CODE_SUCCESS,
                            'data' => [
                                'done' => true,
                            ],
                        ];
                    }
                } else {
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'data' => [
                            'done' => false,
                        ],
                    ];
                }
            }
            if ($action == 'env') {
                $fs = [
                    'proc_open', 'proc_get_status', 'proc_close', 'proc_terminate', 'proc_nice',
                    'pcntl_fork', 'pcntl_waitpid', 'pcntl_wait', 'pcntl_signal', 'pcntl_signal_dispatch',
                    'pcntl_wifexited', 'pcntl_wifstopped', 'pcntl_wifsignaled', 'pcntl_wexitstatus',
                    'pcntl_wifcontinued', 'pcntl_wtermsig', 'pcntl_wstopsig', 'pcntl_exec', 'pcntl_alarm',
                    'pcntl_get_last_error', 'pcntl_errno', 'pcntl_strerror', 'pcntl_getpriority', 'pcntl_setpriority',
                    'pcntl_sigprocmask', 'pcntl_async_signals', 'pcntl_signal_get_handler', 'proc_open'
                    // 'pcntl_sigwaitinfo', 'pcntl_sigtimedwait',
                ];
                $notExistsFs = [];
                foreach ($fs as $f) {
                    if (!function_exists($f)) $notExistsFs[] = $f;
                }
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'data' => [
                        'not_exists_fs' => $notExistsFs,
                    ],
                ];
            }
            if ($action == 'createKafka') { // @czs
                try {
                    $time = time();
                    $job = new TestQueueServiceJob();
                    $job->time = $time;
                    $id = \Yii::$app->kafka->delay(0)->push($job);
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'data' => [
                            'id' => $id,
                            'time' => $time,
                        ],
                    ];
                } catch (\Exception $exception) {
                    return [
                        'code' => ApiCode::CODE_ERROR,
                        'msg' => '队列服务测试失败：' . $exception->getMessage(),
                    ];
                }
            }
            if ($action == 'testKafka') {
                if (\Yii::$app->kafka->isDone($id)) {
                    $job = new TestQueueServiceJob();
                    $job->time = intval($time);
                    if (!$job->valid()) {
                        return [
                            'code' => ApiCode::CODE_ERROR,
                            'msg' => '队列服务测试失败：任务似乎已经运行，但没有得到预期结果，请检查kafka是否连接正常并且数据正常。',
                        ];
                    } else {
                        return [
                            'code' => ApiCode::CODE_SUCCESS,
                            'data' => [
                                'done' => true,
                            ],
                        ];
                    }
                } else {
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'data' => [
                            'done' => false,
                        ],
                    ];
                }
            }
            if ($action == 'create-queue') {
                try {
                    $time = time();
                    $job = new RunQueueShJob();
                    $job->time = $time;
                    $id = \Yii::$app->queue->delay(0)->push($job);
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'data' => [
                            'id' => $id,
                            'time' => $time,
                        ],
                    ];
                } catch (\Exception $exception) {
                    return [
                        'code' => ApiCode::CODE_ERROR,
                        'msg' => '队列服务测试失败：' . $exception->getMessage(),
                    ];
                }
            }
            if ($action == 'test3') {
                $done = \Yii::$app->queue->isDone($id);
                if ($done) {
                    $job = new RunQueueShJob();
                    $job->time = intval($time);
                    if (!$job->valid()) {
                        return [
                            'code' => ApiCode::CODE_ERROR,
                            'msg' => '队列服务测试失败：任务似乎已经运行，但没有得到预期结果，请检查redis是否连接正常并且数据正常。',
                        ];
                    } else {
                        return [
                            'code' => ApiCode::CODE_SUCCESS,
                            'data' => [
                                'done' => true,
                            ],
                        ];
                    }
                } else {
                    return [
                        'code' => ApiCode::CODE_SUCCESS,
                        'data' => [
                            'done' => false,
                        ],
                    ];
                }
            }
        } else {
            return $this->render('queue-service');
        }
    }

    public function actionSmallRoutine()
    {
        return $this->render('small-routine');
    }

    // 上传业务域名文件
    public function actionUploadFile($name = 'file')
    {
        $form = new FileForm();
        $form->file = UploadedFile::getInstanceByName($name);
        return $this->asJson($form->save());
    }

    public function actionUploadLogo($name = 'file')
    {
        $form = new UploadForm();
        $form->file = UploadedFile::getInstanceByName($name);
        return $this->asJson($form->save());
    }

    public function actionPaySetting()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new PaySettingEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                return $form->save();
            } else {
                $form = new PaySettingForm();
                return $form->getSetting();
            }
        } else {
            return $this->render('pay-setting');
        }
    }

    public function actionCommunication()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new CommunicationSettingEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                return $form->save();
            } else {
                $form = new CommunicationSettingEditForm();
                return $form->getSetting();
            }
        } else {
            return $this->render('communication');
        }
    }

    public function actionMessageRemind()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new MessageRemindSettingEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                return $form->save();
            } else {
                $form = new MessageRemindSettingForm();
                return $form->search();
            }
        } else {
            return $this->render('message-remind');
        }
    }

    public function actionMessageRemindReset()
    {
        $form = new MessageRemindSettingForm();
        return $form->reset();
    }

    /**
     * 微信开放平台
     * @return array|string
     * @throws \Exception
     */
    public function actionWxapp()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new PlatformSettingEditForm();
                $form->attributes = \Yii::$app->request->post('platform');
                $form->attributes = \Yii::$app->request->post('web');
                return $form->save();
            } else {
                $form = new PlatformSettingForm();
                return $form->search();
            }
        } else {
            return $this->render('wxapp');
        }
    }

    /**
     * 获取代码模板列表
     * @return array
     */
    public function actionTemplateList()
    {
        try {
            $ext = ExtAppForm::instance(null, 1);
            $list = $ext->templateList();
            $list = ArrayHelper::toArray($list);
            $arr = $list['template_list'];
            if (isset($arr) && !empty($arr)) {
                $temp = array_column($arr, 'create_time');
                array_multisort($temp, SORT_DESC, $list['template_list']);
                foreach ($list['template_list'] as &$item) {
                    if (isset($item['create_time'])) {
                        $item['create_at'] = date("Y-m-d H:i:s", $item['create_time']);
                    }
                }
                unset($item);
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'list' => $list
                ]
            ];
        } catch (Open3rdException $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    /**
     * 删除模板
     * @return array
     */
    public function actionDelTemplate()
    {
        try {
            $templateId = \Yii::$app->request->post('template_id');
            if (empty($templateId)) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '请选择模板',
                ];
            }
            $ext = ExtAppForm::instance(null, 1);
            $res = $ext->deletetemplate($templateId);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '删除成功',
                'data' => $res
            ];
        } catch (Open3rdException $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    /**
     * 查询quota
     * @return array
     */
    public function actionQuota()
    {
        try {
            $mallId = \Yii::$app->request->get('mall_id');
            if (!$mallId) {
                throw new \Exception('请填写已授权三方的小程序商城id');
            }
            $mall = Mall::findOne($mallId);
            if (!$mall) {
                throw new \Exception('商城不存在');
            }
            \Yii::$app->setMall($mall);
            $extApp = WxappWxminiprograms::findOne(['mall_id' => $mallId, 'is_delete' => 0]);
            if (!$extApp) {
                throw new \Exception('该小程序不存在或未授权');
            }
            $ext = ExtAppForm::instance($extApp);
            $quota = $ext->quota();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => 'success',
                'data' => [
                    'rest' => $quota->rest,
                    'limit' => $quota->limit
                ]
            ];
        } catch (Open3rdException $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function actionCopyright()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isGet) {
                $form = new CopyrightForm();
                $res = $form->getDetail();
                return $this->asJson($res);
            } else {
                $form = new CopyrightEditForm();
                $form->data = \Yii::$app->request->post('form');
                $form->attributes = \Yii::$app->request->post();
                return $form->save();
            }
        } else {
            return $this->render('copyright');
        }
    }

    public function actionCopyrightLink()
    {
        $model = new PickLinkForm();
        return $model->getAdminCopyrightLink();
    }
}
