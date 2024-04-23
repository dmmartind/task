<?php


namespace Main
{

    use Main\Mailer\Exception as Exception;
    /**
     * Class Mailer
     * @package Main
     */
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
         * @return Mailer\Mail
         * @throws Exception\Argument
         */
        public function initialize()
        {
            if (!$this->_type)
            {
                $configuration = Registry::get("MAILConfiguration");

                if($configuration)
                {
                    error_log("configu");
                    $parsed = $configuration->parse("mail");
                    error_log(print_r($parsed, true));

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
                case "smtp":
                    error_log("createmail");
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


