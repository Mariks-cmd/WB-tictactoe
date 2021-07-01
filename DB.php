<?php
class DB
{
    private $connection;
 
    public function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "web_bootcamp_comments";

        // Create connection
        $this->connection = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function __deconstruct() {
        $this->connection->close();
    }

    public function makeTableObject($table_name) {
        include_once 'Table.php';
        return new Table($this->connection, $table_name);
    }


}