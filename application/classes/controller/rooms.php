<?php

include "FHEM.php";

class Controller_Rooms extends Controller_FHEM
{
    public function action_index()
    {
        $response = $this->sendAndReceiveJSON("jsonlist ROOMS\r\nquit\r\n");

        if (!isset($response['Results'])) {
            throw new HTTP_Exception_404("unable to acquire rooms");
        }

        foreach ($response['Results'] as $room) {
            if ("hidden" == $room || "HMS" == $room || "Plots" == $room) {
                continue;
            }
            $result[] = array(
                "id" => $room,
                "label" => $room,
                "temperature" => "?",
            );
        }

        $this->response->headers("Content-type", "application/json; charset=" . Kohana::$charset);
        $this->response->body(json_encode($result));
    }
}
