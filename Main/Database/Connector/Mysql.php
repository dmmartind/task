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
         * @var
         */
        protected $_service;


        /**
         * @var bool
         */
        protected $_isConnected = false;


        /**
         * @var mixed
         */
        protected $_host;


        /**
         * @var mixed
         */
        protected $_username;


        /**
         * @var mixed
         */
        protected $_password;


        /**
         * @var mixed
         */
        protected $_schema;


        /**
         * @var mixed|string
         */
        protected $_port = "3306";


        /**
         * @var string
         */
        protected $_charset = "utf8";


        /**
         * @var string
         */
        protected $_engine = "InnoDB";


        /**
         * Mysql constructor.
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
         * @return bool
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
