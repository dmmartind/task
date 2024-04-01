<?php


namespace Main {

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
            //var_dump("construct for Database");
            $this->_type = $this->array_get($settings, 'type', "");
            $this->_options = $this->array_get($settings, 'options', []);
        }

        /**
         * @return Database\Connector\Mysql
         * @throws Exception\Argument
         */
        public function initialize()
        {
            //var_dump("database init...");
            if (!$this->_type)
            {
                //var_dump("not type");
                $configuration = Registry::get("Configuration");
                //var_dump(":::::::::::::::::::::::::::::::::::::::::");
                //var_dump($configuration);

                if($configuration)
                {
                    //var_dump($configuration);
                    $parsed = $configuration->parse("database");
                    //var_dump("next!!!!");
                    //var_dump($parsed);

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

        public function array_get(Array $arr, $key, $default = null)
        {
            //var_dump("start");
            if(!is_array($arr))
                return $default;
            //var_dump("step1");
            if(is_null($key))
                return $arr;
            //var_dump("step2");
            //var_dump(array_keys($arr));
            //var_dump(in_array($key,array_keys($arr)));
            if(in_array($key,array_keys($arr)))
            {
                //var_dump("good");
                //var_dump($key);
                return $arr[$key];
            }
            else
            {
                //var_dump("not good");
                //var_dump($key);
                return $default;
            }

        }
    }
}