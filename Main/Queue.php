<?php


namespace Main
{
    abstract class Queue
    {
        protected $queueDBTable;

        public function __construct()
        {
        }

        abstract public function addItem(Array $queueData);

        abstract public function getItem();

    }
}


