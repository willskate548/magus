<?php
/**
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2021 MagnusSolution. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 *
 */

class QueueAgi
{
    public function callQueue(&$agi, &$MAGNUS, &$CalcAgi, $DidAgi = null, $type = 'queue', $startTime = 0)
    {
        $agi->verbose("Queue module", 5);

        $agi->answer();
        $startTime           = $startTime > 0 ? $startTime : time();
        $MAGNUS->destination = $DidAgi->modelDid->did;

        $sql = "SELECT  *, pkg_queue.id AS id, pkg_queue.id_user AS id_user  FROM pkg_queue
                            LEFT JOIN pkg_user ON pkg_queue.id_user = pkg_user.id
                            WHERE pkg_queue.id = " . $DidAgi->modelDestination[0]['id_queue'] . " LIMIT 1 ";
        $modelQueue = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

        $agi->set_variable("UNIQUEID", $MAGNUS->uniqueid);
        $agi->set_variable("QUEUCALLERID", $MAGNUS->CallerID);
        $agi->set_variable("IDQUEUE", $modelQueue->id);
        $agi->set_variable("USERNAME", $modelQueue->username);
        $agi->set_variable('CHANNEL(language)', $modelQueue->language);

        $queueName = $modelQueue->name;

        $sql = "INSERT INTO pkg_queue_status (id_queue, callId, queue_name, callerId, time, channel, status)
                        VALUES (" . $modelQueue->id . ", '" . $MAGNUS->uniqueid . "', '$queueName', '" . $MAGNUS->CallerID . "',
                        '" . date('Y-m-d H:i:s') . "', '" . $MAGNUS->channel . "', 'ringing')";
        $agi->exec($sql);

        $ring_or_moh = $modelQueue->ring_or_moh == 'ring' ? 'r' : '';

        $max_wait_time = $modelQueue->max_wait_time > 0 ? $modelQueue->max_wait_time : '';
        $agi->verbose("Queue", $queueName . ',' . $ring_or_moh . 'tc,,,' . $max_wait_time . ',/var/www/html/mbilling/resources/asterisk/mbilling.php');
        $agi->execute("Queue", $queueName . ',' . $ring_or_moh . 'tc,,,' . $max_wait_time . ',/var/www/html/mbilling/resources/asterisk/mbilling.php');

        $linha = exec(" egrep $MAGNUS->uniqueid /var/log/asterisk/queue_log | tail -1");
        $linha = explode('|', $linha);
        $agi->verbose(print_r($linha, true), 25);
        if ($linha[4] == 'EXITWITHTIMEOUT') {
            if (strlen($modelQueue->max_wait_time_action)) {

                $data        = explode('/', strtoupper($modelQueue->max_wait_time_action));
                $actionType  = $data[0];
                $destination = $data[1];
                switch ($actionType) {
                    case 'SIP':
                        $sql              = "SELECT * FROM pkg_sip WHERE UPPER(name) = '" . $destination . "' LIMIT 1";
                        $MAGNUS->modelSip = $agi->query($sql)->fetch(PDO::FETCH_OBJ);
                        $MAGNUS->dnid     = $MAGNUS->destination     = $MAGNUS->sip_account     = $MAGNUS->modelSip->name;
                        $callToSip        = SipCallAgi::processCall($MAGNUS, $agi, $CalcAgi, 'fromqueue');
                        if ($callToSip['dialstatus'] == 'ANSWER') {
                            $linha[4] = 'COMPLETEAGENT';
                        }
                        break;
                    case 'QUEUE':
                        $sql                                     = "SELECT * FROM pkg_queue WHERE UPPER(name) = '" . $destination . "' LIMIT 1";
                        $modelQueue                              = $agi->query($sql)->fetch(PDO::FETCH_OBJ);
                        $DidAgi->modelDestination[0]['id_queue'] = $modelQueue->id;
                        $MAGNUS->stopRecordCall($agi);

                        $sql = "DELETE FROM pkg_queue_status WHERE callId = " . $MAGNUS->uniqueid;
                        $agi->exec($sql);

                        QueueAgi::callQueue($agi, $MAGNUS, $CalcAgi, $DidAgi);
                        $noCDR = true;
                        break;
                    case 'IVR':
                        $sql                                   = "SELECT * FROM pkg_ivr WHERE UPPER(name) = '" . $destination . "' LIMIT 1";
                        $modelIrv                              = $agi->query($sql)->fetch(PDO::FETCH_OBJ);
                        $DidAgi->modelDestination[0]['id_ivr'] = $modelIrv->id;
                        IvrAgi::callIvr($agi, $MAGNUS, $CalcAgi, $DidAgi, 'queue');
                        break;
                    case 'LOCAL':
                        $agi->execute("DIAL " . $modelQueue->max_wait_time_action);
                        break;
                }

                $MAGNUS->destination = $DidAgi->modelDid->did;
            }
        }

        $MAGNUS->stopRecordCall($agi);

        $sql = "DELETE FROM pkg_queue_status WHERE callId = " . $MAGNUS->uniqueid;
        $agi->exec($sql);

        $stopTime = time();

        $CalcAgi->sessiontime = $stopTime - $startTime;

        $siptransfer = $agi->get_variable("SIPTRANSFER");

        if ($linha[4] == 'ABANDON') {
            $MAGNUS->sip_account = $linha[4];
        } else {
            $MAGNUS->sip_account = substr($linha[3], 4) . '_WT ' . $agi->get_variable("QEHOLDTIME", true);
        }

        $CalcAgi->terminatecauseid = 1;

        if ($agi->get_variable("ISFROMCALLBACKPRO", true) || isset($noCDR)) {
            return;
        }

        $agi->verbose('$siptransfer => ' . $siptransfer['data'], 5);
        if ($siptransfer['data'] != 'yes' && $type == 'queue') {

            if (!is_null($DidAgi)) {
                $DidAgi->billDidCall($agi, $MAGNUS, $CalcAgi->sessiontime, $CalcAgi);
            }

            $sql = "SELECT id FROM pkg_prefix WHERE prefix = SUBSTRING('$MAGNUS->destination',1,length(prefix))
                                ORDER BY LENGTH(prefix) DESC  ";
            $modelPrefix = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

            $MAGNUS->id_user           = $modelQueue->id_user;
            $MAGNUS->id_plan           = $modelQueue->id_plan;
            $MAGNUS->id_trunk          = null;
            $CalcAgi->starttime        = date("Y-m-d H:i:s", time() - $CalcAgi->sessiontime);
            $CalcAgi->sessiontime      = $CalcAgi->sessiontime;
            $CalcAgi->real_sessiontime = intval($CalcAgi->sessiontime);
            $CalcAgi->terminatecauseid = $CalcAgi->terminatecauseid;
            $CalcAgi->sessionbill      = $DidAgi->sell_price;
            $CalcAgi->sipiax           = 8;
            $CalcAgi->buycost          = $DidAgi->buy_price;
            $CalcAgi->id_prefix        = $modelPrefix->id;
            $CalcAgi->saveCDR($agi, $MAGNUS);

            if (isset($DidAgi->modelDid->id)) {
                $sql = "UPDATE pkg_did_destination SET secondusedreal = secondusedreal + $CalcAgi->sessiontime
                                WHERE id = " . $DidAgi->modelDid->id . " LIMIT 1";
                $agi->exec($sql);
            }

        }
        if ($type == 'queue') {
            exit;
        } else {
            return;
        }

    }

    public function recIvrQueue($agi, $MAGNUS, $CalcAgi)
    {

        $agi->verbose('recIvrQueue');
        $operator = preg_replace("/SIP\//", "", $agi->get_variable("MEMBERNAME", true));

        $MAGNUS->uniqueid    = $agi->get_variable("UNIQUEID", true);
        $MAGNUS->destination = $agi->request['agi_extension'];
        $MAGNUS->accountcode = $agi->get_variable("USERNAME", true);
        $id_queue            = $agi->get_variable("IDQUEUE", true);
        $callerid            = $agi->get_variable("QUEUCALLERID", true);
        $holdtime            = $agi->get_variable("QEHOLDTIME", true);
        $MAGNUS->record_call = $agi->get_variable("RECORD_CALL_DID", true);
        $did                 = $agi->get_variable("DID_NUMBER", true);

        $sql      = "SELECT id_user, mohsuggest FROM pkg_sip WHERE name = '$operator' LIMIT 1";
        $modelSip = $agi->query($sql)->fetch(PDO::FETCH_OBJ);
        if (isset($modelSip->mohsuggest) && strlen($modelSip->mohsuggest) > 1) {
            $agi->execute('SetMusicOnHold', $modelSip->mohsuggest);
        }

        $sql = "UPDATE pkg_queue_status SET status = 'answered', agentName = '$operator' ,
                    holdtime = '$holdtime'  WHERE callId = '$MAGNUS->uniqueid' ";
        $agi->exec($sql);

        $agi->verbose("\n\n" . $MAGNUS->uniqueid . " $operator answer the call from QUEUE \n\n", 6);

        $sql       = "SELECT mix_monitor_format FROM pkg_user WHERE id = $modelSip->id_user LIMIT 1";
        $modelUser = $agi->query($sql)->fetch(PDO::FETCH_OBJ);

        $MAGNUS->mix_monitor_format = $modelUser->mix_monitor_format;

        if (strlen($did) > 0) {
            $MAGNUS->startRecordCall($agi, $did, true);
        } else {
            $MAGNUS->startRecordCall($agi);
        }

        exit;
    }

    public function pauseQueue($agi, $MAGNUS)
    {

        $sql        = "SELECT * FROM pkg_queue_member WHERE interface = 'SIP/" . $MAGNUS->sip_account . "'";
        $modelQueue = $agi->query($sql)->fetchAll(PDO::FETCH_OBJ);
        if (isset($modelQueue[0])) {
            $asmanager = new AGI_AsteriskManager();
            $asmanager->connect('localhost', 'magnus', 'magnussolution');
            $agi->verbose($MAGNUS->dnid);
            foreach ($modelQueue as $queue) {
                if ($MAGNUS->dnid == '*181') {
                    $sql     = "UPDATE pkg_queue_member SET paused = 0 WHERE interface = 'SIP/" . $MAGNUS->sip_account . "'";
                    $command = 'queue unpause member ' . $queue->interface;
                    $agi->verbose($sql);
                    $asmanager->command($command);
                    $agi->stream_file('agent-loginok', '#');

                } else {
                    $sql     = "UPDATE pkg_queue_member SET paused = 1  WHERE interface = 'SIP/" . $MAGNUS->sip_account . "'";
                    $command = 'queue pause member ' . $queue->interface . ' queue ' . $queue->queue_name;
                    $agi->verbose($sql);
                    $asmanager->command($command);
                    $agi->stream_file('agent-loggedoff', '#');
                }
                $agi->exec($sql);
            }
        }

        $asmanager->disconnect();
    }
}
