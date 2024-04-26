<?php


namespace Main {

    use Main\Mailer\Exception as Exception;

    /**
     * Class Mailer
     * @package Main
     */
    class Mailer
    {

        /**
         * type of config
         * @var array|mixed|string|null
         */
        protected string $_type;


        /**
         * connection options for PHPMailer
         * @var array|Array|mixed|null
         */
        protected Array $_options;


        /**
         * Mailer constructor.
         * set the class props for the mail type and the connection options
         * @param array $settings
         */
        public function __construct(Array $settings)
        {
            $this->_type = ArrayMethods::array_get($settings, 'type', "");
            $this->_options = ArrayMethods::array_get($settings, 'options', []);
        }


        /**
         * checks if the options have already set or it needs to get the instance from the configuration to get the
         * options. Then it returns the mailer connection
         * @return Mailer\Mail
         * @throws Exception\Argument
         */
        public function initialize()
        {
            if (!$this->_type) {
                $configuration = Registry::get("MAILConfiguration");

                if ($configuration) {
                    $parsed = $configuration->parse("mail");

                    if (!empty($parsed['type'])) {
                        $this->_type = $parsed['type'];
                        $this->_options = $parsed;
                    }
                }
            }
            if (!$this->_type) {
                throw new Exception\Argument("Invalid type");
            }

            switch ($this->_type) {
                case "smtp":
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


