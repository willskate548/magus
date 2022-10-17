/**
 * Classe que define a model "Callerid"
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusBilling. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v3
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 * 19/09/2012
 */
Ext.define('MBilling.model.TrunkChart', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'day',
        type: 'date',
        dateFormat: 'Y-m-d'
    }, {
        name: 'id_trunk',
        type: 'int'
    }, {
        name: 'sessiontime',
        type: 'int'
    }, {
        name: 'sessionbill',
        type: 'float'
    }, {
        name: 'buycost',
        type: 'float'
    }, {
        name: 'aloc_all_calls',
        type: 'int'
    }, {
        name: 'nbcall',
        type: 'int'
    }, {
        name: 'lucro',
        type: 'float'
    }, 'idTrunktrunkcode'],
    proxy: {
        type: 'uxproxy',
        actionRead: 'chart',
        module: 'callSummaryDayTrunk'
    }
});