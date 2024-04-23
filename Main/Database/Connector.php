<?php

namespace Main\Database {

    use Main\Database\Exception as Exception;


    class Connector
    {

        public function __construct()
        {
            var_dump("construct for Database\connector");
        }


        public function initialize()
        {
            return $this;
        }
    }
}
