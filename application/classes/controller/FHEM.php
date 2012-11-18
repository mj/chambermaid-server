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

    protected function respond_with_json($result)
    {
        $response = json_encode($result);

        if (isset($_GET['callback'])) {
            $response = $_GET['callback'] . "(" . $response . ");";
        }

        $this->response->headers("Content-type", "application/json; charset=" . Kohana::$charset);
        $this->response->body($response);
    }
}
