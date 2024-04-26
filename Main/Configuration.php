<?php

/**
 * ©2024 David Martin. All Rights Reserve.
 */

namespace Main {

    use Main\ArrayMethods as ArrayMethods;
    use Main\Configuration\Exception as Exception;


    /**
     * Class Configuration
     * @package Main
     */
    class Configuration
    {
        /**
         * type of config file
         * @var array|mixed|null
         */
        protected $_type;

        /**
         * name of config file
         * @var array|mixed|null
         */
        protected $_name;


        /**
         * sets the type of file being read and what class the config is for
         * Configuration constructor.
         * @param array $conf
         */
        public function __construct(Array $conf)
        {
            $this->_type = ArrayMethods::array_get($conf, 'type');
            $this->_name = ArrayMethods::array_get($conf, 'class');
        }


        /**
         * checks the type and return the configuration parser
         * @return Configuration\Driver\Ini
         * @throws Exception\Argument
         */
        public function initialize()
        {
            if (!$this->_type) {
                throw new Exception\Argument("Invalid type");
            }

            switch ($this->_type) {
                case "ini":
                {
                    return new Configuration\Driver\Ini($this->_name);
                    break;
                }
                default:
                {
                    throw new Exception\Argument("Invalid type");
                    break;
                }
            }
        }

    }
}


