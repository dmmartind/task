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
            //var_dump("driver/ini construct");
            //var_dump($name);
            $this->name = $name;
            $this->buildPath($name);

            //$this->parse();

        }

        public function buildPath($name)
        {
            //var_dump("build");
            //var_dump($name);
            $this->path = $this->name .".ini";
            //var_dump($this->path);
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
            //var_dump("config parse");
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

            //var_dump($this->name);
            //var_dump($this->path);

            $dir = realpath(__DIR__ . '/../../../Application/Configuration');
            //var_dump($this->path);

            $myfile = fopen($dir . "\\" . $this->path, "r") or die("Unable to open file!");
            $file = fread($myfile,filesize($dir . "\\Database.ini"));
            fclose($myfile);

            if(!isset($this->_parsed[$this->path]))
            {
                $config = [];
                $pairs = parse_ini_string($file);

                //var_dump($pairs);

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
