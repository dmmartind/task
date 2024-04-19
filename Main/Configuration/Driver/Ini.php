<?php


namespace Main\Configuration\Driver
{
    use Main\Configuration as Configuration;
    use Maiun\Configuration\Exception as Exception;
    use http\Params;

    class Ini extends Configuration\Driver
    {
        protected $name;
        protected $path;

        public function __construct(string $name)
        {
            $this->name = $name;
            $this->buildPath($name);

        }

        public function buildPath($name)
        {
            $this->path = $this->name .".ini";
        }

        protected function pair($config,$key, $value)
        {
            if (strstr($key, "."))
            {
                $parts = explode(".", $key, 3);
                $config[$parts[2]] = $value;
            }
            else
            {
                $config[$key] = $value;
            }

            return $config;
        }

        public function parse($class = null)
        {
            if(empty($this->path))
            {
                if($class !== null)
                {
                    $this->name = $class;
                    $this->path = $this->name .".ini";
                }
                else
                {
                    throw new Exception\Argument("\$path argument is not valid");
                }

            }


            $dir = realpath(__DIR__ . '/../../../Application/Configuration');

            $myfile = fopen($dir . "\\" . $this->path, "r") or die("Unable to open file!");
            $file = fread($myfile,filesize($dir . "\\Database.ini"));
            fclose($myfile);

            if(!isset($this->_parsed[$this->path]))
            {
                $config = [];
                $pairs = parse_ini_string($file);

                if($pairs == false)
                {
                    throw new Exception\Syntax("Could not parse configuration file");
                }

                foreach ($pairs as $key => $value)
                {
                    $config = $this->pair($config, $key, $value);
                }

                $this->_parsed[$this->path] = $config;
            }

            return $this->_parsed[$this->path];
        }
    }

}
