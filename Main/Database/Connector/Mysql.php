<?php

namespace Main\Database\Connector {

    use Main\Database as Database;
    use Main\Database\Exception as Exception;
    use http\Params;
    use MySQLi;


    /**
     * Class Mysql
     * @package Main\Database\Connector
     */
    class Mysql extends Database\Connector
    {

        /**
         * hold mySQLi instance
         * @var
         */
        protected $_service;


        /**
         * hold the bool value whether a db is connected or not
         * @var bool
         */
        protected $_isConnected = false;


        /**
         * hold the host
         * @var mixed
         */
        protected $_host;


        /**
         * holds the username
         * @var mixed
         */
        protected $_username;


        /**
         * holds the password
         * @var mixed
         */
        protected $_password;


        /**
         * hold the db name
         * @var mixed
         */
        protected $_schema;


        /**
         * hold the port
         * @var mixed|string
         */
        protected $_port = "3306";


        /**
         * holds the charset of the db
         * @var string
         */
        protected $_charset = "utf8";


        /**
         * hold the type of engine type
         * @var string
         */
        protected $_engine = "InnoDB";


        /**
         * Mysql constructor.
         * captures the options from the ini and set the class props
         * @param array $options
         */
        public function __construct(Array $options)
        {
            $this->_host = $options['host'];
            $this->_username = $options['username'];
            $this->_password = $options['password'];
            $this->_schema = $options['schema'];
            $this->_port = $options['port'];
        }

        /**
         * returns the current mysqli instance or false if none is avalable
         * @return bool|mysqli
         */
        public function getService()
        {
            $isEmpty = empty($this->_service);
            $isInstance = $this->_service instanceof MySQLi;

            if ($this->_isConnected && $isInstance && !$isEmpty) {
                return $this->_service;
            }

            return false;
        }

        /**
         * creates a new mysqli instance or return the current instance
         * @return $this
         * @throws Exception\Service
         */
        public function connect()
        {
            if (!$this->_isValidService()) {
                $this->_service = new MySQLi(
                    $this->_host,
                    $this->_username,
                    $this->_password,
                    $this->_schema,
                    $this->_port
                );

                if ($this->_service->connect_error) {
                    throw new Exception\Service("Unable to connect to service");
                }

                $this->_isConnected = true;
                return $this;
            }
        }

        /**
         * retuns the bool of whether an mysql has been created
         * @return bool
         */
        public function _isValidService()
        {
            $isEmpty = empty($this->_service);
            $isInstance = $this->_service instanceof MySQLi;

            if ($this->_isConnected && $isInstance && !$isEmpty) {
                return true;
            }

            return false;
        }

        /**
         * closes the connection
         * @return $this
         */
        public function disconnect()
        {
            if ($this->_isValidService()) {
                $this->_isConnected = false;
                $this->_service->close();
            }
            return $this;
        }


        /**
         * escape strings for statements
         * @param $value
         * @return mixed
         * @throws Exception\Service
         */
        public function escape($value)
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
            return $this->_service->real_escape_string($value);
        }


        /**
         * executes sql statements
         * @param $sql
         * @return mixed
         * @throws Exception\Service
         */
        public function execute($sql)
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_service->query($sql);
        }


        /**
         * gets lasst row id inserted
         * @return mixed
         * @throws Exception\Service
         */
        public function getLastInsertId()
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_service->insert_id;
        }


        /**
         * returns number of affected row by an executed sql statetment
         * @return mixed
         * @throws Exception\Service
         */
        public function getAffectedRows()
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_service->affected_rows;
        }


        /**
         * returns the last error
         * @return mixed
         * @throws Exception\Service
         */
        public function getLastError()
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_service->error;
        }


        /**
         * return the new instance of query with db connection
         * @return Database\Query\Mysql
         */
        public function query()
        {
            return new Database\Query\Mysql(
                [
                    "connector" => $this
                ]
            );
        }


    }
}
