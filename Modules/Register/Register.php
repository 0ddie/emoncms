<?php

function RegiController()
{
    global $mysqli, $redis, $user, $session, $route, $max_node_id_limit, $feed_settings;
    echo "Wales";
    //   actions i   n the input module that can be performed with less than write privileges
    //  static $timerstart = time( void );
    //  $timerout = timerstart +5;
    if (!$session['write']) return array('content' => false);

    global $feed, $timestore;
    $result = false;
    echo "here";
    include "Modules/feed/feed_model.php";
    $feed = new Feed($mysqli, $redis, $feed_settings);

    require "Modules/input/input_model.php"; // 295
    $input = new Input($mysqli, $redis, $feed);

    require "Modules/input/process_model.php"; // 886
    $process = new Process($mysqli, $input, $feed);

    $process->set_timezone_offset($user->get_timezone($session['userid']));

    if ($route->format == 'html') {
        if ($route->action == 'api') $result = view("Modules/input/Views/input_api.php", array());
        if ($route->action == 'view') $result = view("Modules/input/Views/input_view.php", array());


        if ($route->format == 'json') {
            /*

            input/bulk.json?data=[[0,16,1137],[2,17,1437,3164],[4,19,1412,3077]]

            The first number of each node is the time offset (see below).

            The second number is the node id, this is the unique identifer for the wireless node.

            All the numbers after the first two are data values. The first node here (node 16) has only one data value: 1137.

            Optional offset and time parameters allow the sender to set the time
            reference for the packets.
            If none is specified, it is assumed that the last packet just arrived.
            The time for the other packets is then calculated accordingly.

            offset=-10 means the time of each packet is relative to [now -10 s].
            time=1387730127 means the time of each packet is relative to 1387730127
            (number of seconds since 1970-01-01 00:00:00 UTC)

            Examples:

            // legacy mode: 4 is 0, 2 is -2 and 0 is -4 seconds to now.
              input/bulk.json?data=[[0,16,1137],[2,17,1437,3164],[4,19,1412,3077]]
            // offset mode: -6 is -16 seconds to now.
              input/bulk.json?data=[[-10,16,1137],[-8,17,1437,3164],[-6,19,1412,3077]]&offset=-10
            // time mode: -6 is 1387730121
              input/bulk.json?data=[[-10,16,1137],[-8,17,1437,3164],[-6,19,1412,3077]]&time=1387730127
            // sentat (sent at) mode:
              input/bulk.json?data=[[520,16,1137],[530,17,1437,3164],[535,19,1412,3077]]&offset=543

            See pull request for full discussion:
            https://github.com/emoncms/emoncms/pull/118
            */
            //  do {
            if ($route->action == 'create') {
                $valid = true;

                if (!isset($_GET['data']) && isset($_POST['data'])) {
                    $data = json_decode(post('data'));
                } else {
                    $data = json_decode(get('data'));
                }

                $userid = $session['userid'];
                $dbinputs = $input->get_inputs($userid);

                echo "Node is here!";
                /*

                 if (isset($_GET['timeout'])) {
                 $time_ref = (int) $_GET['timeout'];
                 } elseif (isset($_POST['timeout'])) {
                 $time_ref = (int) $_POST['timeout'];
                        }
                  //This section should add the node to the current feeds
                 *$json = '{"Type":"Power","From":"dsh;jggdhsfklgjdfhkdflgkjfglk","To":"hfhjfhjfdjkksksljfj"}';

                 var_dump(json_decode($json, true));
                   if ($route->action == "create" && $session['write']) {
                  $result = $feed->create($session['userid'],get('name'),get('datatype'),get('engine'),json_decode(get('options')));
                 */
                return array('content' => $result);
            }
            // So valid Json string to send should be: Register/create.json?data=123
            // }//while($timerout != $timerstart);
        }
    }
    /**
     * Created by PhpStorm.
     * User: Michael
     * Date: 01/03/2015
     * Time: 14:54
     *
     * function registry_controller (){
     *
     * global $mysqli, $redis, $user, $session, $route, $max_node_id_limit, $feed_settings,$nodeid;
     * // There are no actions in the input module that can be performed with less than write privileges
     *
     *
     * if (!$session['write']) return array('content'=>false);
     *
     *
     * global $feed, $timestore_adminkey;
     * $result = false;
     * include "Modules/feed/feed_model.php";
     * $feed = new Feed($mysqli,$redis, $feed_settings);
     * require "Modules/input/input_model.php"; // 295
     * $input = new Input($mysqli,$redis, $feed);
     * require "Modules/input/process_model.php"; // 886
     * $process = new Process($mysqli,$input,$feed);
     *
     * $process->set_timezone_offset($user->get_timezone($session['userid']));
     * }
     *
     * /*function node_id_assign () {
     * global $nodeid, $mysqli;
     *
     * $nodenumber = 5;/*Number of elements in database
     *
     * $nodeid = $nodenumber + 1;
     *
     *
     * // Check the number of elements in the Node ID database.
     * }
     *
     *
     * public $ipadd = "123.123.123.123";
     * public $port = 80;
     *
     * /**
     * @param $ipadd
     * @param $port

    //socket_connect ( resource $socket , string $address [,$port ] );
     *
     *
     * function registerReply (){
     *
     * $socket = $lastNodeID;
     * $address = "123.123.123.123";
     * $port = 80;
     *
     *
     * $st="Message to sent";
     * $length = strlen($st);
     *
     * while (true) {
     *
     * $sent = socket_write($socket, $st, $length);
     *
     * if ($sent === false) {
     *
     * break;
     * }
     *
     * // Check if the entire message has been sented
     * if ($sent < $length) {
     *
     * // If not sent the entire message.
     * // Get the part of the message that has not yet been sented as message
     * $st = substr($st, $sent);
     *
     * // Get the length of the not sented part
     * $length -= $sent;
     *
     * } else {
     *
     * break;
     * }
     *
     * }
     *
     * };
     */
    RegiController();
}

