<?php
/**
 * Modelo para a tabela "Campaign".
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusSolution. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v3
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 * 28/10/2012
 */

class Campaign extends Model
{
    protected $_module = 'campaign';
    public $id_phonebook;
    public $callsPlaced;
    public $callsringing;
    public $callsInTransfer;
    public $callsTransfered;
    public $callsTotalNumbers;
    public $callsDialedtoday;
    public $callsRemaningToDial;

    /**
     * Retorna a classe estatica da model.
     * @return Prefix classe estatica da model.
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return nome da tabela.
     */
    public function tableName()
    {
        return 'pkg_campaign';
    }

    /**
     * @return nome da(s) chave(s) primaria(s).
     */
    public function primaryKey()
    {
        return 'id';
    }

    /**
     * @return array validacao dos campos da model.
     */
    public function rules()
    {
        $rules = array(
            array('name, id_user', 'required'),
            array('id_user, digit_authorize, id_plan,restrict_phone, secondusedreal, enable_max_call, nb_callmade, type, monday, tuesday, wednesday, thursday, friday, saturday, sunday, status, frequency, auto_reprocess, max_frequency', 'numerical', 'integerOnly' => true),
            array('name, audio, audio_2,record_call, callerid', 'length', 'max' => 100),
            array('startingdate, expirationdate', 'length', 'max' => 50),
            array('daily_start_time, daily_stop_time', 'length', 'max' => 8),
            array('forward_number', 'length', 'max' => 160),
            array('description', 'length', 'max' => 300),
            array('tts_audio,tts_audio2,asr_audio,asr_options', 'length', 'max' => 200),
            array('from', 'length', 'max' => 20),
        );
        return $this->getExtraField($rules);
    }

    /**
     * @return array regras de relacionamento.
     */
    public function relations()
    {
        return array(
            'idUser' => array(self::BELONGS_TO, 'User', 'id_user'),
            'idPlan' => array(self::BELONGS_TO, 'Plan', 'id_plan'),
        );
    }

    public function checkCampaignActive($id_campaign, $nbpage, $name_day)
    {
        $sql = "SELECT pkg_phonenumber.id as pkg_phonenumber_id, pkg_phonenumber.number, pkg_campaign.id as pkg_campaign_id, pkg_campaign.forward_number,
            pkg_user.id , pkg_user.id_plan, pkg_user.username, pkg_campaign.type, pkg_campaign.description, pkg_phonenumber.name, try, pkg_user.credit, restrict_phone , pkg_user.id_user AS id_agent
            FROM pkg_phonenumber , pkg_phonebook , pkg_campaign_phonebook, pkg_campaign, pkg_user
            WHERE pkg_phonenumber.id_phonebook = pkg_phonebook.id AND pkg_campaign_phonebook.id_phonebook = pkg_phonebook.id
            AND pkg_campaign_phonebook.id_campaign = pkg_campaign.id AND pkg_campaign.id_user = pkg_user.id AND pkg_campaign.status = 1
            AND pkg_campaign.startingdate <= '" . date('Y-m-d H:i:s') . "' AND pkg_campaign.expirationdate > '" . date('Y-m-d H:i:s') . "'
            AND pkg_campaign.$name_day = 1 AND  pkg_campaign.daily_start_time <= '" . date('H:i:s') . "'  AND pkg_campaign.daily_stop_time > '" . date('H:i:s') . "'
            AND pkg_phonenumber.status = 1  AND  pkg_phonenumber.creationdate < '" . date('Y-m-d H:i:s') . "' AND pkg_user.credit > 1
            AND pkg_campaign.id = :id_campaign  LIMIT 0, :nbpage";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":id_campaign", $id_campaign, PDO::PARAM_INT);
        $command->bindValue(":nbpage", $nbpage, PDO::PARAM_INT);
        return $command->queryAll();
    }
}
