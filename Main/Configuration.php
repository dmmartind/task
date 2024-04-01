<?php


namespace Main
{
    use Main\Configuration\Exception as Exception;
    class Configuration
    {
        protected $_type;
        protected $_name;

        public function __construct(Array $conf)
        {
            //var_dump("construct");
            $this->_type = $this->array_get($conf, 'type');
            $this->_name = $this->array_get($conf, 'class');
            //var_dump($this->_type);
            //var_dump($this->_name);
            //var_dump("---------------------------------------");
        }

        public function initialize()
        {
            //var_dump("config init");
            if(!$this->_type)
            {
                //var_dump($this->_type);
                throw new Exception\Argument("Invalid type");
            }

            switch($this->_type)
            {
                case "ini":
                {
                    //var_dump("call driver/ini");
                    //var_dump($this->_name);
                    return new Configuration\Driver\Ini($this->_name);
                    break;
                }
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


