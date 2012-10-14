<?php

abstract class Controller_FHEM extends Controller
{
    public function sendAndReceiveJSON($cmd)
    {
        $response = "";
        // TODO: Figure out if there is something like configuration
        //       files available in this Kohona thing.
        $socket = fsockopen("localhost", 7072);
        fwrite($socket, $cmd);
        while (!feof($socket)){
            $response .= fgets($socket);
        }
        fclose($socket);

        return json_decode($response, true);
    }

    public function send($cmd)
    {
        // TODO: Figure out if there is something like configuration
        //       files available in this Kohona thing.
        $socket = fsockopen("localhost", 7072);
        fwrite($socket, $cmd);
        fclose($socket);        
    }
}
