<?php
    class conectar{
        public static function conexion(){
            $con = new mysqli ('localhost', 'root', '', 'comidas');
            $con->set_charset('utf8');

            return $con;
        }
    }