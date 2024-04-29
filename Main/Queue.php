<?php


namespace Main {

    /**
     * Class Queue
     * @package Main
     */
    abstract class Queue
    {
        /**
         * @var
         */
        protected $queueDBTable;

        /**
         * Queue constructor.
         */
        public function __construct()
        {
        }

        /**
         * @param array $queueData
         * @return mixed
         */
        abstract public function addItem(Array $queueData);

        /**
         * @return mixed
         */
        abstract public function getItem();

    }
}


