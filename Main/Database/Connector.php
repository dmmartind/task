<?php

namespace Main\Database {

    use Main\Database\Exception as Exception;

    /**
     * Class Connector
     * @package Framework\Database
     */
    class Connector
    {
        /**
         * Connector constructor.
         */
        public function __construct()
        {
            var_dump("construct for Database\connector");
        }

        /**
         * @return $this
         */
        public function initialize()
        {
            return $this;
        }
    }
}
