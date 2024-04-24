<?php


namespace Main
{

    use Main\Mailer\Exception as Exception;

    class Mailer
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
                $configuration = Registry::get("MAILConfiguration");

                if($configuration)
                {
                    $parsed = $configuration->parse("mail");

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


