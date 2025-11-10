<?php

//Dbh.php [Class to access the database.]
//pathologicalplay [MMXXV]

class Dbh {

private $db;

public function __construct() {
$this->connect_to_db();
}

public function __destruct() {
$this->close_db_connection();
}

//Establish a connection to the database.

private $servername = "";
private $username = "";
private $password = "";
private $db_name = "";

public function connect_to_db() {
$this->db = new mysqli ($this->servername, $this->username, $this->password, $this->db_name);
$this->db->set_charset ('utf8');
if ($this->db->connect_errno) {
die ("A critical error occurred, preventing the connection to the database from being established.");
}
}

private function close_db_connection() {
return $this->db->close();
}

//Establish a connection to the database.

//Executing a statement.

private function execute_query($sql, $param_type = null, $params = null) {
$conn = $this->db;
$stmt = $conn->prepare($sql);
if ($params !== null && $param_type !== null) {
if (! is_array($params)) {
$params = [$params];
}
$stmt->bind_param($param_type, ...$params);
}
$stmt->execute();
return $stmt;
}

//Executing a statement.

//Normal query.

private function normal_query($sql) {
$conn = $this->db;
$stmt = $conn->query($sql);
return $stmt;
}

//Normal query.

//Get all data by id.

public function get_all_data_by_id($sql, $param_type = null, ...$params) {
$stmt = $this->execute_query($sql, $param_type, $params);
$result = $stmt->get_result();
return $result->fetch_all(MYSQLI_ASSOC) ?: false;
}

//Get all data by id.

//Get all data by value.

public function get_all_data_by_value($sql, $param_type = null, ...$params) {
$stmt = $this->execute_query($sql, $param_type, $params);
$result = $stmt->get_result();
return $result->fetch_all(MYSQLI_ASSOC) ?: false;
}

//Get all data by value.

//Get all data.

public function get_all_data($sql) {
$stmt = $this->normal_query($sql);
$result = $stmt->fetch_all(MYSQLI_ASSOC);
return $result ?: false;
}

//Get all data.

//Get a dataset.

public function get_data($sql, $param_type = null, ...$params) {
$stmt = $this->execute_query($sql, $param_type, $params);
$result = $stmt->get_result()->fetch_assoc();
return $result ?: false;
}

//Get a dataset.

//Inserting data.

public function insert_data($sql, $param_type = null, ...$params) {
$stmt = $this->execute_query($sql, $param_type, $params);
if ($stmt) {
return true;
} else {
return false;
}
}

//Inserting data.

//Inserting data and return.

public function insert_data_and_return($sql, $param_type = null, ...$params) {
$stmt = $this->execute_query($sql, $param_type, $params);
if ($stmt) {
return $stmt;
} else {
return false;
}
}

//Inserting data and return.

//Updating data.

public function update_data($sql, $param_type = null, ...$params) {
$stmt = $this->execute_query($sql, $param_type, $params);
if ($stmt) {
return true;
} else {
return false;
}
}

//Updating data.

//Removing data.

public function remove_data($sql, $param_type = null, ...$params) {
$stmt = $this->execute_query($sql, $param_type, $params);
if ($stmt) {
return true;
} else {
return false;
}
}

//Removing data.

//Method to get a single value from the DB.

public function get_single_value($sql, $param_type, $params) {
$stmt = $this->execute_query($sql, $param_type, $params);
$stmt->bind_result($result);
$stmt->fetch();
return $result;
}

//Method to get a single value from the DB.

//All counting methods.

public function check_if_id_exists($sql, $param_type, $params) {
$stmt = $this->execute_query($sql, $param_type, $params);
$stmt->bind_result($result);
$stmt->fetch();
return (INT) $result;
}

public function count_entire_data_from_table($sql) {
$stmt = $this->normal_query($sql);
$result = $stmt->num_rows;
if ($result > 0) {
return (INT) $result;
} else {
return false;
}
}

//All counting methods.

}
