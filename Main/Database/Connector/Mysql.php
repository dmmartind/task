<?php

namespace Main\Database\Connector {

    use Main\Database as Database;
    use Main\Database\Exception as Exception;
    use http\Params;
    use MySQLi;


    class Mysql extends Database\Connector
    {

        protected $_service;


        protected $_isConnected = false;


        protected $_host;


        protected $_username;


        protected $_password;


        protected $_schema;


        protected $_port = "3306";


        protected $_charset = "utf8";


        protected $_engine = "InnoDB";


        public function __construct(Array $options)
        {
            $this->_host = $options['host'];
            $this->_username = $options['username'];
            $this->_password = $options['password'];
            $this->_schema = $options['schema'];
            $this->_port = $options['port'];
        }

        public function getService()
        {
            $isEmpty = empty($this->_service);
            $isInstance = $this->_service instanceof MySQLi;

            if ($this->_isConnected && $isInstance && !$isEmpty) {
                return $this->_service;
            }

            return false;
        }

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

        public function _isValidService()
        {
            $isEmpty = empty($this->_service);
            $isInstance = $this->_service instanceof MySQLi;

            if ($this->_isConnected && $isInstance && !$isEmpty) {
                return true;
            }

            return false;
        }

        public function disconnect()
        {
            if ($this->_isValidService()) {
                $this->_isConnected = false;
                $this->_service->close();
            }
            return $this;
        }


        public function escape($value)
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }
            return $this->_service->real_escape_string($value);
        }


        public function execute($sql)
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_service->query($sql);
        }


        public function getLastInsertId()
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_service->insert_id;
        }


        public function getAffectedRows()
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_service->affected_rows;
        }


        public function getLastError()
        {
            if (!$this->_isValidService()) {
                throw new Exception\Service("Not connected to a valid service");
            }

            return $this->_service->error;
        }


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
