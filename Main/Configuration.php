<?php


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
         * @var array|mixed|null
         */
        protected $_type;

        /**
         * @var array|mixed|null
         */
        protected $_name;


        /**
         * Configuration constructor.
         * @param array $conf
         */
        public function __construct(Array $conf)
        {
            $this->_type = ArrayMethods::array_get($conf, 'type');
            $this->_name = ArrayMethods::array_get($conf, 'class');
        }


        /**
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


