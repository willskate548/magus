<?php
/**
 * Modelo para a tabela "CallArchive".
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
 * 17/08/2019
 */

class CallArchive extends Model
{
    public $totalCall;
    protected $_module       = 'callarchive';
    public $sessiontimeFixed = 0;
    public $sessionbillFixed;
    public $sessiontimeMobile = 0;
    public $sessionbillMobile;
    public $sessiontimeRest = 0;
    public $sessionbillRest;
    public $sumbuycost;
    public $sumsessionbill;
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
        return 'pkg_cdr_archive';
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
            array('id_user, id_plan, calledstation', 'required'),
            array('id_user, id_plan, id_trunk, sessiontime, real_sessiontime, sipiax', 'numerical', 'integerOnly' => true),
            array('uniqueid, starttime, src, calledstation,
                terminatecauseid, buycost, sessionbill,  agent_bill, callerid', 'length', 'max' => 50),
        );
        return $this->getExtraField($rules);
    }

    public function relations()
    {
        return array(
            'idPrefix' => array(self::BELONGS_TO, 'Prefix', 'id_prefix'),
            'idPlan'   => array(self::BELONGS_TO, 'Plan', 'id_plan'),
            'idTrunk'  => array(self::BELONGS_TO, 'Trunk', 'id_trunk'),
            'idUser'   => array(self::BELONGS_TO, 'User', 'id_user'),
        );
    }
}
