/**
 * Classe que define a lista de "CallShopCdr"
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
 * 01/10/2013
 */
Ext.define('MBilling.view.sipTrace.Controller', {
    extend: 'Ext.ux.app.ViewController',
    alias: 'controller.siptrace',
    onNewFilter: function(btn) {
        var me = this,
            module = me.getView();
        Ext.widget('siptracefilter', {
            title: 'SipTrace',
            list: me.list
        });
    },
    onDeleteLog: function(btn) {
        var me = this;
        Ext.Msg.confirm(me.titleConfirmation, t('Confirm delete all log file?'), function(btn) {
            if (btn === 'yes') {
                Ext.Ajax.request({
                    url: 'index.php/sipTrace/destroy',
                    scope: me,
                    success: function(response) {
                        Ext.ux.Alert.alert(me.titleSuccess, t('Success: The SipTrace file was deleted'), 'success');
                        me.store.load();
                    }
                });
            }
        });
    },
    onClearAll: function(btn) {
        var me = this;
        Ext.Ajax.request({
            url: 'index.php/sipTrace/clearAll',
            scope: me,
            success: function(response) {
                Ext.ux.Alert.alert(me.titleSuccess, t('Success'), 'success');
                me.store.load();
            }
        });
    },
    onExportPcap: function(btn) {
        var me = this;
        window.open('index.php/sipTrace/export');
    },
    onDetails: function(btn) {
        var me = this,
            callids = [];
        Ext.each(me.list.getSelectionModel().getSelection(), function(record) {
            callids.push(record.get('callid'));
        });
        window.open('index.php/sipTrace/details?callid=' + Ext.encode(callids));
    }
});