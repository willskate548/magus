/**
 * Classe que define a model "CallShop"
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
Ext.define('MBilling.model.CallShop', {
    extend: 'Ext.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'id_user',
        type: 'int'
    }, 'idUserusername', {
        name: 'name',
        type: 'string'
    }, {
        name: 'accountcode',
        type: 'string'
    }, {
        name: 'regexten',
        type: 'string'
    }, {
        name: 'amaflags',
        type: 'string'
    }, {
        name: 'callgroup',
        type: 'string'
    }, {
        name: 'callerid',
        type: 'string'
    }, {
        name: 'directmedia',
        type: 'string'
    }, {
        name: 'context',
        type: 'string'
    }, {
        name: 'DEFAULTip',
        type: 'string'
    }, {
        name: 'dtmfmode',
        type: 'string'
    }, {
        name: 'fromuser',
        type: 'string'
    }, {
        name: 'fromdomain',
        type: 'string'
    }, {
        name: 'host',
        type: 'string'
    }, {
        name: 'insecure',
        type: 'string'
    }, {
        name: 'language',
        type: 'string'
    }, {
        name: 'mailbox',
        type: 'string'
    }, {
        name: 'md5secret',
        type: 'string'
    }, {
        name: 'nat',
        type: 'string'
    }, {
        name: 'deny',
        type: 'string'
    }, {
        name: 'permit',
        type: 'string'
    }, {
        name: 'pickupgroup',
        type: 'string'
    }, {
        name: 'port',
        type: 'string'
    }, {
        name: 'qualify',
        type: 'string'
    }, {
        name: 'rtptimeout',
        type: 'string'
    }, {
        name: 'rtpholdtimeout',
        type: 'string'
    }, {
        name: 'secret',
        type: 'string'
    }, {
        name: 'type',
        type: 'string'
    }, {
        name: 'disallow',
        type: 'string'
    }, {
        name: 'allow',
        type: 'string'
    }, {
        name: 'regseconds',
        type: 'date',
        dateFormat: 'timestamp'
    }, {
        name: 'ipaddr',
        type: 'string'
    }, {
        name: 'fullcontact',
        type: 'string'
    }, {
        name: 'setvar',
        type: 'string'
    }, {
        name: 'regserver',
        type: 'string'
    }, {
        name: 'lastms',
        type: 'string'
    }, {
        name: 'defaultuser',
        type: 'string'
    }, {
        name: 'auth',
        type: 'string'
    }, {
        name: 'subscribemwi',
        type: 'string'
    }, {
        name: 'vmexten',
        type: 'string'
    }, {
        name: 'cid_number',
        type: 'string'
    }, {
        name: 'callingpres',
        type: 'string'
    }, {
        name: 'usereqphone',
        type: 'string'
    }, {
        name: 'mohsuggest',
        type: 'string'
    }, {
        name: 'allowtransfer',
        type: 'string'
    }, {
        name: 'autoframing',
        type: 'string'
    }, {
        name: 'maxcallbitrate',
        type: 'string'
    }, {
        name: 'outboundproxy',
        type: 'string'
    }, {
        name: 'rtpkeepalive',
        type: 'string'
    }, {
        name: 'useragent',
        type: 'string'
    }, {
        name: 'calllimit',
        type: 'int'
    }, {
        name: 'status',
        type: 'int'
    }, 'callshopnumber', {
        name: 'callshoptime',
        type: 'int'
    }],
    proxy: {
        type: 'uxproxy',
        module: 'callShop'
    }
});