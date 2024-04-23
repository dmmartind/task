<?php

namespace Main\Database
{
    use Main\ArrayMethods as ArrayMethods;
    use Main\Database\Exception as Exception;
    use http\Params;


    class Query
    {

        protected $_connector;


        protected $_from;


        protected $_fields;


        protected $_limit;


        protected $_offset;


        protected $_order;


        protected $_direction;


        protected $_join = array();


        protected $_where = array();


        protected $_sql = [];


        public function __construct(Array $input)
        {
            $this->_connector = $input["connector"];
        }


        protected function _quote($value)
        {
            if (is_string($value))
            {
                $escaped = $this->_connector->escape($value);
                return "'{$escaped}'";
            }

            if (is_array($value))
            {
                $buffer = array();

                foreach ($value as $i)
                {
                    array_push($buffer, $this->_quote($i));
                }

                $buffer = join(", ", $buffer);
                return "({$buffer})";
            }

            if (is_null($value))
            {
                return "NULL";
            }

            if (is_bool($value))
            {
                return (int) $value;
            }

            return $this->_connector->escape($value);
        }


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

            return sprintf($template, $fields, $this->_from, $join, $where, $order, $limit);
        }


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

            if (!empty($temp_where))
            {
                $joined = join(" AND ", $temp_where);
                $where = "WHERE {$joined}";
            }

            $temp_limit = $this->_limit;

            if (!empty($temp_limit))
            {
                $temp_offset = $this->_offset;
                $limit = "LIMIT {$temp_limit} {$temp_offset}";

            }

            return sprintf($template, $this->_from, $parts, $where, $limit);
        }


        protected function _buildDelete()
        {
            $where = $limit ="";
            $template = "DELETE FROM %s %s %s";

            $temp_where = $this->_where;
            if (!empty($temp_where))
            {
                $joined = join(" AND ", $temp_where);
                $where = "WHERE {$joined}";
            }

            $temp_limit = $this->_limit;
            if (!empty($temp_limit))
            {
                $_offset = $this->_offset;
                $limit = "LIMIT {$temp_limit} {$_offset}";
            }

            return sprintf($template, $this->_from, $where, $limit);
        }


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

            $this->_sql = $sql;

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


        public function delete()
        {
            $sql = $this->_buildDelete();
            $this->_sql = $sql;
            $result = $this->_connector->execute($sql);

            if ($result === false)
            {
                throw new Exception\Sql();
            }

            return $this->_connector->getAffectedRows();
        }


        public function from($from, $fields = array("*"))
        {
            if (empty($from))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_from = $from;

            if ($fields)
            {
                $this->_fields[$from] = $fields;
            }

            return $this;
        }


        public function join($join, $on, $fields = array())
        {
            if (empty($join))
            {
                throw new Exception\Argument("Invalid argument");
            }

            if (empty($on))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_fields += array($join => $fields);
            $this->_join[] = "JOIN {$join} ON {$on}";

            return $this;
        }


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


        public function where()
        {
            $arguments = func_get_args();

            if (sizeof($arguments) < 1)
            {
                throw new Exception\Argument("Invalid argument");
            }

            $arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);

            foreach (array_slice($arguments, 1, null, true) as $i => $parameter)
            {
                $arguments[$i] = $this->_quote($arguments[$i]);
            }

            $this->_where[] = call_user_func_array("sprintf", $arguments);

            return $this;
        }


        public function first()
        {
            $limit = $this->_limit;
            $offset = $this->_offset;

            $this->limit(1);

            $all = $this->all();
            $first = ArrayMethods::getFirst($all);

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
            $limit = $this->_limit;
            $offset = $this->_offset;
            $fields = $this->_fields;

            $this->_fields = array($this->_from => array("COUNT(1)" => "rows"));

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