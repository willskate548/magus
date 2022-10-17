/**
 * Classe que define o store "TrunkSIPCodes"
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
 * 25/03/2021
 */
Ext.define('MBilling.store.TrunkSipCodes', {
    extend: 'Ext.data.Store',
    model: 'MBilling.model.TrunkSipCodes',
    groupField: 'ip'
});