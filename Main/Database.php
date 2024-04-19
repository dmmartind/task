<?php


namespace Main {

    use Main\ArrayMethods as ArrayMethods;
    use Main\Database\Exception as Exception;

    /**
     * Class Database
     * @package Framework
     */
    class Database
    {
        /**
         * @var mixed|string
         */
        protected string $_type;

        /**
         * @var Array|mixed
         */
        protected Array $_options;

        /**
         * Database constructor.
         * @param array $settings
         */
        public function __construct(Array $settings)
        {
            $this->_type = ArrayMethods::array_get($settings, 'type', "");
            $this->_options = ArrayMethods::array_get($settings, 'options', []);
        }

        /**
         * @return Database\Connector\Mysql
         * @throws Exception\Argument
         */
        public function initialize()
        {
            if (!$this->_type)
            {
                $configuration = Registry::get("Configuration");

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