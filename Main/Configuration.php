<?php


namespace Main
{
    use Main\ArrayMethods as ArrayMethods;
    use Main\Configuration\Exception as Exception;
    class Configuration
    {
        protected $_type;
        protected $_name;

        public function __construct(Array $conf)
        {
            $this->_type = ArrayMethods::array_get($conf, 'type');
            $this->_name = ArrayMethods::array_get($conf, 'class');
        }

        public function initialize()
        {
            if(!$this->_type)
            {
                throw new Exception\Argument("Invalid type");
            }

            switch($this->_type)
            {
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


