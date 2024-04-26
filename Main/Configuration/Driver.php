<?php


namespace Main\Configuration {


    /**
     * class to add additional pre-processing
     * Class Driver
     * @package Main\Configuration
     */
    class Driver
    {

        /**
         * @var array
         */
        protected $_parsed = [];


        /**
         * @return $this
         */
        public function initialize()
        {
            return $this;
        }

    }
}


