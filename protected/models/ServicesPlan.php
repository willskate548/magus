<?php
/**
 * Modelo para a tabela "CampaignPhonebook".
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
 * 29/10/2012
 */

class ServicesPlan extends Model
{
    protected $_module = 'servicesplan';
    /**
     * Retorna a classe estatica da model.
     * @return SubModule classe estatica da model.
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
        return 'pkg_services_plan';
    }

    /**
     * @return nome da(s) chave(s) primaria(s).
     */
    public function primaryKey()
    {
        return array('id_services', 'id_plan');
    }

    /**
     * @return array validacao dos campos da model.
     */
    public function rules()
    {
        $rules = array(
            array('id_services, id_plan', 'required'),
            array('id_services, id_plan', 'numerical', 'integerOnly' => true),
        );
        return $this->getExtraField($rules);
    }

    public function beforeSave()
    {
        return parent::beforeSave();
    }

    public function afterSave()
    {
        return parent::afterSave();
    }
}
