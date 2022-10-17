<?php
/**
 * Acoes do modulo "Call".
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusSolution. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 * 17/08/2012
 */

class SendCreditSummaryController extends Controller
{
    public $attributeOrder = 't.date DESC';

    public function actionRead($asJson = true, $condition = null)
    {

        $model = new SendCreditSummary();

        $model->date = isset($_POST['SendCreditSummary']['date'])
        ? $_POST['SendCreditSummary']['date']
        : date('Y-m-d');
        $model->stopdate = isset($_POST['SendCreditSummary']['stopdate'])
        ? $_POST['SendCreditSummary']['stopdate']
        : date('Y-m-d');

        $model->service = isset($_POST['SendCreditSummary']['service'])
        ? $_POST['SendCreditSummary']['service']
        : 'All';

        $model->number = isset($_POST['SendCreditSummary']['number'])
        ? $_POST['SendCreditSummary']['number']
        : '';

        if (Yii::app()->session['isAdmin'] != 1) {
            $_POST['SendCreditSummary']['id'] = Yii::app()->session['id_user'];
        } else {
            $_POST['SendCreditSummary']['id'] = isset($_POST['SendCreditSummary']['id'])
            ? $_POST['SendCreditSummary']['id']
            : 1;
        }

        $this->filter = 'confirmed IN (0,1) AND earned IS NOT NULL ';
        if (isset($_POST['SendCreditSummary']['id']) && $_POST['SendCreditSummary']['id'] > 1) {
            $this->filter .= ' AND id_user = :id_user';
            $this->paramsFilter['id_user'] = $_POST['SendCreditSummary']['id'];
        }

        $this->filter .= ' AND date > :date AND date < :stopdate';
        $this->paramsFilter['date']     = $model->date;
        $this->paramsFilter['stopdate'] = $model->stopdate . ' 23:59:59';

        if ($model->service != 'all') {

            $this->filter .= ' AND service LIKE :service';
            $this->paramsFilter['service'] = $model->service;

        }

        if (is_numeric($model->number)) {
            $this->filter .= ' AND number LIKE :number';
            $this->paramsFilter['number'] = $model->number . '%';
        }
        /*
        $modelSendCreditSummary = SendCreditSummary::model()->findAll();
        foreach ($modelSendCreditSummary as $key => $value) {

        $modelRefill = Refill::model()->find('invoice_number = :key', [':key' => $value->id]);
        if (isset($modelRefill->description)) {
        # code...

        $res                   = explode('-', $modelRefill->description);
        $res                   = explode(' ', $res[0]);
        $value->received_amout = $res[2] . ' ' . $res[3];

        $value->save();
        }

        }
         */
        $modelSendCreditSummary = SendCreditSummary::model()->findAll(array(
            'select'    => $this->select,
            'condition' => $this->filter,
            'params'    => $this->paramsFilter,
            'order'     => 'date DESC, service',
        ));

        $this->render('index', array(
            'model'                  => $model,
            'modelSendCreditSummary' => $modelSendCreditSummary,
        ));
        exit;

        parent::actionRead();
    }
}
