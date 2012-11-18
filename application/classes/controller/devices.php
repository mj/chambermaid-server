<?php

include "FHEM.php";

class Controller_Devices extends Controller_FHEM
{
    public function action_details()
    {
        $result = array();
        $device = $this->request->param("device");

        $response = $this->sendAndReceiveJSON("json " . $device . "\r\nquit\r\n");

        if (!isset($response['ResultSet']) || !isset($response['ResultSet']['Results'])) {
            throw new HTTP_Exception_404("no such device");
        }

        $result = $this->parse_device($response['ResultSet']['Results']);
        
        $this->response->headers("Content-type", "application/json; charset=" . Kohana::$charset);
        $this->response->body(json_encode($result));
    }

    public function action_in_room()
    {
        $result = array();

        $room = $this->request->param("room");

        // There seems to be no easy way to get all devices in a room
        $response = $this->sendAndReceiveJSON("jsonlist\r\nquit\r\n");

        if (!isset($response['Results'])) {
            throw new HTTP_Exception_404("no such device");
        }

        foreach ($response['Results'] as $list) {
            foreach ($list['devices'] as $device) {
                if (isset($device['ATTR']['room']) && $room == $device['ATTR']['room'] && $device['TYPE'] != "FileLog" && $device['TYPE'] != "CUL") {
                    $result[] = $this->parse_device($device);
                }
            }
        }

        $this->response->headers("Content-type", "application/json; charset=" . Kohana::$charset);
        $this->response->body(json_encode($result));
    }

    protected function parse_device(array $device) {
        $result = array();
        
        if (isset($device['ATTR'])) {
            $device['ATTRIBUTES'] = $device['ATTR'];
        }

        $result['type'] = $device['TYPE'];
        $result['id'] = $device['NAME'];
        $result['label'] = $device['ATTRIBUTES']['alias'];
        $result['room'] = $device['ATTRIBUTES']['room'];

        foreach ($device['READINGS'] as $key => $reading) {
            if (isset($reading['VAL'])) {
                $result[strtolower($key)] = $reading['VAL'];
            }
        }

        return $result;
    }
}
