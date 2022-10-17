/**
 * Classe que define a lista de "Callerid"
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
 * 19/09/2012
 */
Ext.define('MBilling.view.callerid.List', {
    extend: 'Ext.ux.grid.Panel',
    alias: 'widget.calleridlist',
    store: 'Callerid',
    fieldSearch: 'cid',
    buttonImportCsv: true,
    initComponent: function() {
        var me = this;
        me.columns = [{
            header: t('ID'),
            dataIndex: 'id',
            flex: 1,
            hidden: true,
            hideable: App.user.isAdmin
        }, {
            header: t('CallerID'),
            dataIndex: 'cid',
            flex: 4
        }, {
            header: t('Username'),
            dataIndex: 'idUserusername',
            filter: {
                type: 'string',
                field: 'idUser.username'
            },
            flex: 4,
            hidden: App.user.isClient,
            hideable: !App.user.isClient
        }, {
            header: t('Name'),
            dataIndex: 'name',
            flex: 4
        }, {
            header: t('Description'),
            dataIndex: 'description',
            flex: 5,
            hidden: window.isTablet
        }, {
            header: t('Status'),
            dataIndex: 'activated',
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