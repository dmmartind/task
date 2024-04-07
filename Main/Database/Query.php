<?php


namespace Main\Database {

    use Main\Database\Exception as Exception;
    use http\Params;

    /**
     * Class Query
     * @package Framework\Database
     */
    class Query
    {
        /**
         * @var mixed
         */
        protected $_connector;

        /**
         * @var
         */
        protected $_order;

        /**
         * @var
         */
        protected $_direction;

        /**
         * @var
         */
        protected $_offset;

        /**
         * @var
         */
        protected $_fields;

        /**
         * @var
         */
        protected $_from;

        /**
         * @var
         */
        protected $_limit;

        /**
         * @var array
         */
        protected $_join = [];

        /**
         * @var array
         */
        protected $_where = [];

        /**
         * Query constructor.
         * @param array $input
         */
        public function __construct(Array $input)
        {
            //var_dump("construct for Query");
            $this->_connector = $input["connector"];
        }

        /**
         * @param $from
         * @param array $fields
         * @return $this
         * @throws Exception\Argument
         */
        public function from($from, $fields = array("*"))
        {
            if (empty($from)) {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_from = $from;

            if ($fields) {
                $this->_fields[$from] = $fields;
            }

            return $this;
        }

        /**
         * @param $join
         * @param $on
         * @param array $fields
         * @return $this
         * @throws Exception\Argument
         */
        public function join($join, $on, $fields = array())
        {
            if (empty($join)) {
                throw new Exception\Argument("Invalid argument");
            }

            if (empty($on)) {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_fields += array($join => $fields);
            $this->_join[] = "JOIN {$join} ON {$on}";

            return $this;
        }

        /**
         * @return $this
         * @throws Exception\Argument
         */
        public function where()
        {
            $arguments = func_get_args();
            //var_dump($arguments);

            if (sizeof($arguments) < 1) {
                throw new Exception\Argument("Invalid argument");
            }

            for($x = 0; $x < count($arguments); $x++)
            {
                $arguments[$x] = preg_replace("#\?#", "%s", $arguments[$x]);
            }

            //var_dump($arguments);


            foreach ($arguments as $i => $parameter) {
                if(strpos($arguments[$i],'%s'))
                    continue;
                $arguments[$i] = $this->_quote($arguments[$i]);
            }

            //var_dump($arguments);

            for($x = 0; $x < count($arguments); $x= $x+2)
            {
                $this->_where[] = sprintf($arguments[$x], $arguments[$x + 1]);

            }



            //var_dump($this->_where);

            return $this;
        }

        /**
         * @param $value
         * @return int|string
         */
        protected function _quote($value)
        {
            //var_dump($value);
            if (is_string($value)) {
                $escaped = $this->_connector->escape($value);
                //var_dump($escaped);
                $test = "'{$escaped}'";
                trim($test,'\'"');
                //var_dump($test);
                return "'{$escaped}'";
            }

            if (is_array($value)) {
                $buffer = array();

                foreach ($value as $i) {
                    array_push($buffer, $this->_quote($i));
                }

                $buffer = join(", ", $buffer);
                return "({$buffer})";
            }

            if (is_null($value)) {
                return "NULL";
            }

            if (is_bool($value)) {
                return (int)$value;
            }

            return $this->_connector->escape($value);
        }

        /**
         * @param $order
         * @param string $direction
         * @return $this
         * @throws Exception\Argument
         */
        public function order($order, $direction = "asc")
        {
            if (empty($order))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_order = $order;
            $this->_direction = $direction;

            return $this;
        }

        /**
         * @param $limit
         * @param int $page
         * @return $this
         * @throws Exception\Argument
         */
        public function limit($limit, $page = 1)
        {
            if (empty($limit))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_limit = $limit;
            $this->_offset = $limit * ($page - 1);

            return $this;
        }

        /**
         * @return string
         */
        protected function _buildSelect()
        {
            $fields = array();
            $where = $order = $limit = $join = "";
            $template = "SELECT %s FROM %s %s %s %s %s";

            foreach ($this->_fields as $table => $tfields)
            {
                foreach ($tfields as $field => $alias)
                {
                    if (is_string($field))
                    {
                        $fields[] = "{$field} AS {$alias}";
                    }
                    else
                    {
                        $fields[] = $alias;
                    }
                }
            }

            $fields = join(", ", $fields);

            $temp_join = $this->_join;
            if (!empty($temp_join))
            {
                $join = join(" ", $temp_join);
            }

            $temp_where = $this->_where;
            if (!empty($temp_where))
            {
                $joined = join(" AND ", $temp_where);
                $where = "WHERE {$joined}";
            }

            $temp_order = $this->_order;
            if (!empty($temp_order))
            {
                $temp_direction = $this->_direction;
                $order = "ORDER BY {$temp_order} {$temp_direction}";
            }

            $temp_limit = $this->_limit;
            if (!empty($temp_limit))
            {
                $temp_offset = $this->_offset;

                if ($temp_offset)
                {
                    $limit = "LIMIT {$temp_limit}, {$temp_offset}";
                }
                else
                {
                    $limit = "LIMIT {$temp_limit}";
                }
            }

            //var_dump(sprintf($template, $fields, $this->_from, $join, $where, $order, $limit));
            return sprintf($template, $fields, $this->_from, $join, $where, $order, $limit);
        }

        /**
         * @param $data
         * @return int
         */
        public function save($data)
        {
            $isInsert = sizeof($this->_where) == 0;

            if ($isInsert)
            {
                $sql = $this->_buildInsert($data);
            }
            else
            {
                $sql = $this->_buildUpdate($data);
            }

            //var_dump($sql);

            $result = $this->_connector->execute($sql);

            if ($result === false)
            {
                throw new Exception\Sql();
            }

            if ($isInsert)
            {
                return $this->_connector->getLastInsertId();
            }

            return 0;
        }

        /**
         * @param $data
         * @return string
         */
        protected function _buildInsert($data)
        {
            $fields = array();
            $values = array();
            $template = "INSERT INTO `%s` (`%s`) VALUES (%s)";

            foreach ($data as $field => $value)
            {
                $fields[] = $field;
                $values[] = $this->_quote($value);
            }

            $fields = join("`, `", $fields);
            $values = join(", ", $values);

            return sprintf($template, $this->_from, $fields, $values);
        }

        /**
         * @return mixed
         */
        public function delete()
        {
            $sql = $this->_buildDelete();
            $result = $this->_connector->execute($sql);

            if ($result === false)
            {
                throw new Exception\Sql();
            }

            return $this->_connector->getAffectedRows();
        }

        /**
         * @return string
         */
        protected function _buildDelete()
        {
            $where = $limit ="";
            $template = "DELETE FROM %s %s %s";

            $temp_where = $this->_where;
            if (!empty($_where))
            {
                $joined = join(", ", $temp_where);
                $where = "WHERE {$joined}";
            }

            $_limit = $this->_limit;
            if (!empty($_limit))
            {
                $_offset = $this->_offset;
                $limit = "LIMIT {$_limit} {$_offset}";
            }

            return sprintf($template, $this->_from, $where, $limit);
        }

        protected function _buildUpdate($data)
        {
            $parts = array();
            $where = $limit = "";
            $template = "UPDATE %s SET %s %s %s";

            foreach ($data as $field => $value)
            {
                $parts[] = "{$field} = ".$this->_quote($value);
            }

            $parts = join(", ", $parts);

            $temp_where = $this->_where;
            if (!empty($_where))
            {
                $joined = join(", ", $temp_where);
                $where = "WHERE {$joined}";
            }

            $_limit = $this->_limit;
            if (!empty($_limit))
            {
                $_offset = $this->_offset;
                $limit = "LIMIT {$_limit} {$_offset}";
            }

            return sprintf($template, $this->_from, $parts, $where, $limit);
        }

        public static function findFirst($array)
        {
            if (sizeof($array) == 0)
            {
                return null;
            }

            $keys = array_keys($array);
            return $array[$keys[0]];
        }

        public function first()
        {
            $limit = $this->_limit;
            $offset = $this->_offset;

            $this->limit(1);

            $all = $this->all();
            $first = $this->findFirst($all);

            if ($limit)
            {
                $this->_limit = $limit;
            }
            if ($offset)
            {
                $this->_offset = $offset;
            }

            return $first;
        }

        public function count()
        {
            $limit = $this->limit;
            $offset = $this->offset;
            $fields = $this->fields;

            $this->_fields = array($this->from => array("COUNT(1)" => "rows"));

            $this->limit(1);
            $row = $this->first();

            $this->_fields = $fields;

            if ($fields)
            {
                $this->_fields = $fields;
            }
            if ($limit)
            {
                $this->_limit = $limit;
            }
            if ($offset)
            {
                $this->_offset = $offset;
            }

            return $row["rows"];
        }






    }
}


