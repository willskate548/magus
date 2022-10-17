/**
 * Classe que define a lista de "Alarm"
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
 * Please submit bug reports, patches, etc to https://github.com/magnussolution/magnusbilling7/issues
 * =======================================
 * Magnusbilling.org <info@magnussolution.com>
 * 03/01/2021
 */
Ext.define('MBilling.view.alarm.List', {
    extend: 'Ext.ux.grid.Panel',
    alias: 'widget.alarmlist',
    store: 'Alarm',
    initComponent: function() {
        var me = this;
        me.buttonUpdateLot = false;
        me.buttonCsv = false;
        me.allowPrint = false;
        me.columns = [{
            header: t('ID'),
            dataIndex: 'id',
            flex: 1,
            hidden: true,
            hideable: App.user.isAdmin
        }, {
            header: t('Type'),
            dataIndex: 'type',
            renderer: function(value) {
                switch (value) {
                    case 1:
                        value = t('ALOC');
                        break;
                    case 2:
                        value = t('ASR');
                        break;
                    case 3:
                        value = t('Calls per minute');
                        break;
                    case 4:
                        value = t('Consecutive number');
                        break;
                    case 5:
                        value = t('Online calls on same number');
                        break;
                    case 6:
                        value = t('Same number and CallerID');
                        break;
                }
                return value
            },
            flex: 2,
            filter: {
                type: 'list',
                options: [
                    ['1', t('ALOC')],
                    ['2', t('ASR')],
                    ['3', t('Calls per minute')],
                    ['4', t('Consecutive number')],
                    ['5', t('Online calls on same number')]
                ]
            }
        }, {
            header: t('Period'),
            dataIndex: 'period',
            renderer: function(value) {
                switch (value) {
                    case 3600:
                        value = t('1 Hour');
                        break;
                    case 7200:
                        value = t('2 Hour');
                        break;
                    case 43200:
                        value = t('12 Hour');
                        break;
                    case 86400:
                        value = t('24 Hour');
                        break;
                }
                return value
            },
            flex: 2,
            filter: {
                type: 'list',
                options: [
                    ['3600', t('1 Hour')],
                    ['7200', t('2 Hours')],
                    ['43200', t('12 Hours')],
                    ['86400', t('24 Hours')]
                ]
            }
        }, {
            header: t('Condition'),
            dataIndex: 'condition',
            renderer: function(value) {
                switch (value) {
                    case 1:
                        value = t('Bigger than');
                        break;
                    case 2:
                        value = t('Less than');
                        break;
                }
                return value
            },
            flex: 2,
            filter: {
                type: 'list',
                options: [
                    ['1', t('Bigger than')],
                    ['2', t('Less than')]
                ]
            }
        }, {
            header: t('Amount'),
            dataIndex: 'amount',
            flex: 2
        }, {
            header: t('Status'),
            dataIndex: 'status',
            renderer: Helper.Util.formatBooleanActive,
            comboRelated: 'booleancombo',
            flex: 2,
            filter: {
                type: 'list',
                options: [
                    [1, t('Active')],
                    [0, t('Inactive')]
                ]
            }
        }]
        me.callParent(arguments);
    }
});