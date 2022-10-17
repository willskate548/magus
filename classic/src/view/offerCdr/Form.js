/**
 * Classe que define o form de "OfferCdr"
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
 * 17/08/2012
 */
Ext.define('MBilling.view.offerCdr.Form', {
    extend: 'Ext.ux.form.Panel',
    alias: 'widget.offercdrform',
    initComponent: function() {
        var me = this;
        me.columns = [{
            xtype: 'userlookup',
            ownerForm: me,
            name: 'id_user',
            fieldLabel: t('Username')
        }, {
            xtype: 'offercombo',
            name: 'id_offer',
            fieldLabel: t('Offer')
        }, {
            name: 'used_secondes',
            fieldLabel: t('Duration')
        }, {
            xtype: 'datefield',
            name: 'date_consumption',
            fieldLabel: t('Date'),
            format: 'Y-m-d H:i:s'
        }]
        me.callParent(arguments);
    }
});