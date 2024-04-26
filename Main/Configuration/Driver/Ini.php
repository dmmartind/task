<?php


namespace Main\Configuration\Driver {

    use Main\Configuration as Configuration;
    use Main\Configuration\Exception as Exception;
    use http\Params;

    /**
     * Class Ini
     * @package Main\Configuration\Driver
     */
    class Ini extends Configuration\Driver
    {

        /**
         * name of the ini file
         * @var string
         */
        protected $name;

        /**
         * hold the path of the ini file
         * @var
         */
        protected $path;


        /**
         * Ini constructor.
         * @param string $name
         */
        public function __construct(string $name)
        {
            $this->name = $name;
            $this->buildPath($name);
        }


        /**
         * build the path to the ini file
         * @param $name
         */
        public function buildPath($name)
        {
            $this->path = $this->name . ".ini";
        }

        /**
         * parses the ini file
         * @param null $class
         * @return mixed
         * @throws Exception\Argument
         * @throws Exception\Syntax
         */
        public function parse($class = null)
        {
            if (empty($this->path)) {
                if ($class !== null) {
                    $this->name = $class;
                    $this->path = $this->name . ".ini";
                } else {
                    throw new Exception\Argument("\$path argument is not valid");
                }
            }

            $dir = realpath(APP_PATH . DIRECTORY_SEPARATOR . "Application" . DIRECTORY_SEPARATOR . "Configuration");
            $iniFile = $dir . DIRECTORY_SEPARATOR . $this->path;

            $myfile = fopen($iniFile, "r") or die("Unable to open file!");
            $file = fread($myfile, filesize($iniFile));
            fclose($myfile);

            if (!isset($this->_parsed[$this->path])) {
                $config = [];
                $pairs = parse_ini_string($file);

                if ($pairs == false) {
                    throw new Exception\Syntax("Could not parse configuration file");
                }

                foreach ($pairs as $key => $value) {
                    $config = $this->pair($config, $key, $value);
                }

                $this->_parsed[$this->path] = $config;
            }

            return $this->_parsed[$this->path];
        }

        /**
         * returns the key/value pair
         * @param $config
         * @param $key
         * @param $value
         * @return mixed
         */
        protected function pair($config, $key, $value)
        {
            if (strstr($key, ".")) {
                $parts = explode(".", $key, 3);
                $config[$parts[2]] = $value;
            } else {
                $config[$key] = $value;
            }

            return $config;
        }
    }
}
