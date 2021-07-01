<?php
class Table
{
    private $table_name;
    private $table = [];
    private $connection;
    private $count = 0;

    public function __construct($connection, $table_name) {
        $this->table_name = $table_name;
        $this->connection = $connection;
    }

    public function &getTable() {
        $sql = "SELECT * FROM " . $this->table_name;
        $result = $this->connection->query($sql);

        /*if ($result->num_rows > 0) {
            //$this->table = $result->fetch_all(MYSQLI_ASSOC);
        }*/

        while ($row = $result->fetch_assoc()) {
            $this->table[$row['row']][$row['col']] = $row['symbol'];
            $this->count++;
        }

        return $this->table;
    }

    public function getEntry(int $id) {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id=$id";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    public function addEntry($entry) {
        $row = $this->connection->real_escape_string(@$entry['row']);
        $col = $this->connection->real_escape_string(@$entry['col']);
        $symbol = $this->connection->real_escape_string(@$entry['symbol']);

        if (array_key_exists($row, $this->table) && array_key_exists($col, $this->table[$row])) {
            return;
        }
        $sql = "INSERT INTO " . $this->table_name . " (row, col, symbol) VALUES ('$row', '$col', '$symbol')";

        if ($this->connection->query($sql) === TRUE) {
            $this->table[$row][$col] = $symbol;
            $this->count++;
        } else {
            echo "Error: " . $sql . "<br>" . $this->connection->error;
        }
    }

    public function updateEntry(int $id, $entry) {
        $allowed_keys = ['name' => null, 'message' => null];
        $entry = array_intersect_key($entry, $allowed_keys);
        $name = $this->connection->real_escape_string(@$entry['name']);
        $message = $this->connection->real_escape_string(@$entry['message']);

        $sql = "UPDATE " . $this->table_name . " SET name='$name', message='$message' WHERE id=$id";

        if ($this->connection->query($sql) === TRUE) {
            echo "Record ID:$id updated successfully";

            $this->table[$id]['name'] = $name;
            $this->table[$id]['message'] = $message;
        } else {
            echo "Error: " . $sql . "<br>" . $this->connection->error;
        }
    }

    public function reset() {
        $sql = "DELETE FROM " . $this->table_name . "";

        if ($this->connection->query($sql) === TRUE) {
            $this->table = [];
            $this->count = 0;
        } else {
            echo "Error: " . $sql . "<br>" . $this->connection->error;
        }
    }

    public function getAmount() {
        return $this->count;
    }
}