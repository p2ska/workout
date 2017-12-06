<?php

// andmebaasi klass

class DATABASE {
	var $db, $host, $database, $charset, $collation, $query, $result, $error_msg, $error, $rows, $insert_id;

	function connect($host = false, $database = false, $username = false, $password = false, $charset = false, $collation = false) {
	    $this->db = new mysqli($host, $username, $password, false);

		$this->db->select_db($database);

		$this->db->query("set names '". $charset. "' collate '". $collation. "'");

	    /*
		if (!$this->connection = @mysql_connect($host, $username, $password, false))
			die("Connection to database server has failed.<br/>". @mysql_error($this->connection));

		if (!@mysql_select_db($database, $this->connection))
			die("Database not found.<br/>". @mysql_error($this->connection));

		if ($charset && $collation)
			@mysql_query("set names '". $charset. "' collate '". $collation. "'");
		*/

	}

	function switch_db($database, $charset = false, $collation = false) {
		if (!$this->dv->select_db($database))
			die("Database not found.<br>". $this->db->error);

		if ($charset && $collation)
			$this->db->query("set names '". $charset. "' collate '". $collation. "'");
	}

	function query($query, $values = false, $return = false) {
		$this->rows = $this->error = $param_count = 0;
		$this->error_msg = "";

        $param = [];
		$using = false;

		$this->query = "prepare prep_query from '". $query. "'";

		if (!$this->result = $this->db->query($this->query))
			return $this->error();

		if ($values) {
			if (!is_array($values))
				$values = [ $values ];

			foreach ($values as $value) {
				$this->query = "set @param". $param_count. " = '". $this->db->real_escape_string($value). "'";
				$param[] = "@param". $param_count;

				if (!$this->result = $this->db->query($this->query))
					return $this->error();

				$param_count++;
			}

			$using = " using ". implode(", ", $param);
		}

		$this->query = "execute prep_query". $using;

		$this->result = $this->db->query($this->query);

        if (isset($this->result->num_rows))
		    $this->rows = $this->result->num_rows;

		if (isset($this->db->insert_id))
		    $this->insert_id = $this->db->insert_id;

		if ($return) {
			if (is_string($return)) {
				$o = $this->get_obj();

				if (isset($o->{ $return }))
					return $o->{ $return };
				else
					return $o;
			}
			else {
				return $this->get_all();
			}
		}

		$this->error = $this->db->errno;
		$this->error_msg = $this->db->error. " [". $this->query. "]";

		return $this->result;
	}

	function get_obj($field = false) {
		$o = $this->result->fetch_object();

		if (is_string($field) && isset($o->{ $field }))
			return $o->{ $field };
		else
			return $o;
	}

	function get_all($field = false) {
		$results = [];

		while ($o = $this->result->fetch_object())
			if ($o) {
				if (is_string($field) && isset($o->{ $field }))
					$results[] = $o->{ $field };
				else
					$results[] = $o;
			}

		return $results;
	}

	function error() {
		$this->error = $this->db->errno;
		$this->error_msg = $this->db->error. " [". $this->query. "]";

		return false;
	}

	function free() {
		$this->result->free();
	}

    function close() {
		$this->free();

		$this->db->close();
	}
}

?>
