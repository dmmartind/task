<?php


namespace Main
{
    class Mailer
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
            $this->_type = ArrayMethods::array_get($settings, 'type', "");
            $this->_options = ArrayMethods::array_get($settings, 'options', []);
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
                    $parsed = $configuration->parse("mail");
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
                    return new Mailer\Mail($this->_options);
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

