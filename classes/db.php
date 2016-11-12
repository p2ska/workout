<?php

// andmebaasi klass

class DATABASE {
	var $connection, $host, $database, $charset, $collation, $query, $result, $error_msg, $error, $rows, $insert_id;

	function connect($host = false, $database = false, $username = false, $password = false, $charset = false, $collation = false) {
		if (!$this->connection = @mysql_connect($host, $username, $password, false))
			die("Connection to database server has failed.<br/>". @mysql_error($this->connection));

		if (!@mysql_select_db($database, $this->connection))
			die("Database not found.<br/>". @mysql_error($this->connection));

		if ($charset && $collation)
			@mysql_query("set names '". $charset. "' collate '". $collation. "'");
	}

	function switch_db($database, $charset = false, $collation = false) {
		if (!@mysql_select_db($database, $this->connection))
			die("Database not found.<br>". @mysql_error($this->connection));

		if ($charset && $collation)
			$this->query("set names '". $charset. "' collate '". $collation. "'");
	}

	function query($query, $values = false, $return = false) {
		$this->rows = $this->error = $param_count = 0;
		$this->error_msg = "";

        $param = [];
		$using = false;

		if ($this->result)
			@mysql_free_result($this->result);

		$this->query = "prepare prep_query from '". $query. "'";

		if (!$this->result = @mysql_query($this->query, $this->connection))
			return $this->error();

		if ($values) {
			if (!is_array($values))
				$values = [ $values ];

			foreach ($values as $value) {
				$this->query = "set @param". $param_count. " = '". mysql_real_escape_string($value). "'";
				$param[] = "@param". $param_count;

				if (!$this->result = @mysql_query($this->query, $this->connection))
					return $this->error();

				$param_count++;
			}

			$using = " using ". implode(", ", $param);
		}

		$this->query = "execute prep_query". $using;

		$this->result = @mysql_query($this->query, $this->connection);

		$this->rows = @mysql_num_rows($this->result);
		$this->insert_id = @mysql_insert_id($this->connection);

		if ($return) {
			if (is_string($return)) {
				$obj = $this->get_obj();

				if (isset($obj->{ $return }))
					return $obj->{ $return };
				else
					return false;
			}
			else {
				return $this->get_all();
			}
		}

		$this->error = @mysql_errno($this->connection);
		$this->error_msg = @mysql_error($this->connection). " [". $this->query. "]";

		return $this->result;
	}

	function error() {
		$this->error = @mysql_errno($this->connection);
		$this->error_msg = @mysql_error($this->connection). " [". $this->query. "]";

		return false;
	}

	function get_obj($field = false) {
		$obj = @mysql_fetch_object($this->result);

		if ($field && is_string($field) && isset($obj->{ $field }))
			return $obj->{ $field };
		else
			return $obj;
	}

	function get_all($field = false) {
		$results = [];

		while ($obj = @mysql_fetch_object($this->result))
			if ($obj) {
				if ($field && is_string($field) && isset($obj->{ $field }))
					$results[] = $obj->{ $field };
				else
					$results[] = $obj;
			}

		return $results;
	}

	function get_value($table, $value, $where = false, $arg = false) {
		if ($arg && !is_array($arg))
			$arg = [ $arg ];

		$q = "select ". $value. " as value from ". $table. ($where ? " where ". $where : ""). " limit 1";

		$this->query($q, $arg);

		if ($this->rows) {
			$result = $this->get_obj();

			return $result->value;
		}
		else {
			return false;
		}
	}

	function free() {
		@mysql_free_result($this->result);
	}

    function close() {
		@mysql_close($this->connection);
	}
}

?>
