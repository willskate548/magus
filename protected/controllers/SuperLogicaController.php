<?php

/**
 * Url for paypal ruturn http://http://billing3.cwiz.com.br/mbilling/index.php/superLogica .
 */
class SuperLogicaController extends CController
{

    public function actionIndex()
    {
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        // specify how many levels of call stack should be shown in each log message
        defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

        Yii::log(print_r($_REQUEST, true), 'error');
        echo json_encode(array("status" => 200));

        $modelMethodpay = Methodpay::model()->find("payment_method = 'SuperLogica'");

        $sql             = "SELECT * FROM pkg_method_pay WHERE payment_method = 'SuperLogica'";
        $result          = Yii::app()->db->createCommand($sql)->queryAll();
        $pppToken        = $modelMethodpay->SLAppToken;
        $accessToken     = $modelMethodpay->SLAccessToken;
        $secret          = $modelMethodpay->SLSecret;
        $validationtoken = $modelMethodpay->SLvalidationtoken;

        if (!isset($_POST['validationtoken']) || $validationtoken != $_POST['validationtoken']) {
            Yii::log('invalid token', 'info');
            exit();
        }

        if (!isset($_POST['data']['id_recebimento_recb'])) {
            Yii::log('No POST', 'info');
            exit();
        }

        if (!isset($_POST['data']['id_sacado_sac'])) {
            Yii::log('No exists id sacado', 'info');
            exit();
        }
        $id_recebimento_recb = $_POST['data']['id_recebimento_recb'];
        $id_sacado_sac       = $_POST['data']['id_sacado_sac'];

        $modelUser = User::model()->find('id_sacado_sac = :id_sacado_sac', array(':id_sacado_sac' => $id_sacado_sac));
        if (!count($modelUser)) {
            exit;
        }
        $id_user = $modelUser->id;

        $amount = $_POST['data']['vl_total_recb'];

        if ($_POST['data']['fl_status_recb'] == '1') {
            $modelboleto = Boleto::model()->find('id_user = :id_user AND description LIKE :description AND status = 0',
                array(
                    ':id_user'    => $modelUser->id,
                    'description' => "%superlogica%" . $id_recebimento_recb . "%",
                ));

            if (count($modelboleto)) {
                UserCreditManager::releaseUserCredit($modelboleto->id_user, $modelboleto->payment, 'Boleto Pago', $modelboleto->description);
            }

        }
    }
}
