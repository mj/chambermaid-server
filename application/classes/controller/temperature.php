<?php

include "FHEM.php";

class Controller_Temperature extends Controller_FHEM
{
    public function action_set()
    {
        $device = $this->request->param("device");
        $temperature = $this->request->param("temperature");

        $cmd = sprintf("\r\nset %s desired-temp %0.2f\r\nquit\r\n",
            $device,
            $temperature
            );

        $this->send($cmd);

        $this->respond_with_json(true);
    }
}
