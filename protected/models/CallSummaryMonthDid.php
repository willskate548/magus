<?php
/**
 * Modelo para a tabela "Call".
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
 * 17/08/2012
 */

class CallSummaryMonthDid extends Model
{
    protected $_module = 'callsummarymonthdid';

    public $sumsessiontime;
    public $sumaloc_all_calls;
    public $sumnbcall;
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
        return 'pkg_cdr_summary_month_did';
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
            array('month, sessiontime, aloc_all_calls, nbcall, sessionbill, id_did', 'length', 'max' => 50),
        );
        return $this->getExtraField($rules);
    }

    /**
     * @return array regras de relacionamento.
     */
    public function relations()
    {
        return array(
            'idDid' => array(self::BELONGS_TO, 'Did', 'id_did'),
        );
    }

}
