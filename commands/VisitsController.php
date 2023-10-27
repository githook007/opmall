<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/7 16:56
 */

namespace app\commands;

use app\forms\open3rd\Visits;
use yii\console\Controller;

class VisitsController extends Controller
{
    public function actionIndex($testCode = 200)
    {
        $visits = new Visits();
        $visits->getVisits();
    }
}
