<?php
Class ApiView {

    public function response($data, $status) {
        header("Content-Type: application/json");
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        echo json_encode($data);
    }
        
    private function _requestStatus($code){
        $status = array(
            200 => "OK",
            201 => "Recurso creado",
            400 => "La solicitud no se puede interpretar",
            404 => "No encontrado",
            500 => "Error interno del servidor"
          );
        return (isset($status[$code]))? $status[$code] : $status[500];
    }
}