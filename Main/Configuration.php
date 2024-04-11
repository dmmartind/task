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
            //var_dump("construct");
            $this->_type = ArrayMethods::array_get($conf, 'type');
            $this->_name = ArrayMethods::array_get($conf, 'class');
            //var_dump($this->_type);
            //var_dump($this->_name);
            //var_dump("---------------------------------------");
        }

        public function initialize()
        {
            //var_dump("config init");
            if(!$this->_type)
            {
                //var_dump($this->_type);
                throw new Exception\Argument("Invalid type");
            }

            switch($this->_type)
            {
                case "ini":
                {
                    //var_dump("call driver/ini");
                    //var_dump($this->_name);
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


