<?php

/**
 * ©2024 David Martin. All Rights Reserve.
 */

namespace Main\Database\Query {

    use Main\Database as Database;
    use Main\Database\Exception as Exception;


    /**
     * Class Mysql
     * @package Main\Database\Query
     */
    class Mysql extends Database\Query
    {

        /**
         * Mysql constructor.
         * calls the query constructor with the mysqli connection
         * @param array $input
         */
        public function __construct(Array $input)
        {
            parent::__construct($input);
        }


        /**
         * returns the built sql statement
         * @return array
         */
        public function getSQL()
        {
            return $this->_sql;
        }


        /**
         * returns all rows affected by the statement
         * @return array
         * @throws Exception\Sql
         */
        public function all()
        {
            $sql = $this->_buildSelect();
            $this->_sql = $sql;
            $result = $this->_connector->execute($sql);

            if ($result === false) {
                $error = $this->_connector->getLastError();
                throw new Exception\Sql("There was an error with your SQL query: {$error}");
            }

            $rows = array();

            for ($i = 0; $i < $result->num_rows; $i++) {
                $rows[] = $result->fetch_array(MYSQLI_ASSOC);
            }

            return $rows;
        }


    }
}
