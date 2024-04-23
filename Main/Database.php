<?php


namespace Main {

    use Main\ArrayMethods as ArrayMethods;
    use Main\Database\Exception as Exception;


    class Database
    {

        protected string $_type;


        protected Array $_options;


        public function __construct(Array $settings)
        {
            $this->_type = ArrayMethods::array_get($settings, 'type', "");
            $this->_options = ArrayMethods::array_get($settings, 'options', []);
        }


        public function initialize()
        {
            if (!$this->_type)
            {
                $configuration = Registry::get("DBConfiguration");

                if($configuration)
                {
                    $parsed = $configuration->parse("database");

                    if(!empty($parsed['type']))
                    {
                        $this->_type = $parsed['type'];
                        $this->_options = $parsed;
                    }
                }
            }
            if (!$this->_type)
            {
                throw new Exception\Argument("Invalid type");
            }

            switch ($this->_type) {
                case "mysql":
                    return new Database\Connector\Mysql($this->_options);
                    break;
                default:
                {
                    throw new Exception\Argument("Invalid type");
                    break;
                }
            }
        }
    }
}