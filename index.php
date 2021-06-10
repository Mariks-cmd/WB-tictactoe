<link rel="stylesheet" href="style.css">

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//Start
if (array_key_exists('action', $_GET) && $_GET['action'] == 'reset') {
    file_put_contents('data.json', '{"table": [], "count": 0}');
}
else {
    $data = getData();
    $table = $data['table'];
    $count = $data['count'];
    $symbol = ($count % 2 == 0) ? 'x' : 'o';

    if (array_key_exists('rid', $_GET) && array_key_exists('cid', $_GET)) {
        addValue($table, $_GET['rid'], $_GET['cid'], $count, $symbol);
    }
    validate($table, $symbol);
}
//End




?>

<div id="app">
    <div class="container">
        <a href="?rid=1&cid=1"><?=@$table[1][1]; ?></a>
        <a href="?rid=1&cid=2"><?=@$table[1][2]; ?></a>
        <a href="?rid=1&cid=3"><?=@$table[1][3]; ?></a>

        <a href="?rid=2&cid=1"><?=@$table[2][1]; ?></a>
        <a href="?rid=2&cid=2"><?=@$table[2][2]; ?></a>
        <a href="?rid=2&cid=3"><?=@$table[2][3]; ?></a>

        <a href="?rid=3&cid=1"><?=@$table[3][1]; ?></a>
        <a href="?rid=3&cid=2"><?=@$table[3][2]; ?></a>
        <a href="?rid=3&cid=3"><?=@$table[3][3]; ?></a>
    </div>

    <a href="?action=reset" class="btn">Reset</a>
</div>


<?php
    /**
     * Adds new entry to data.json storage
     * 
     * @param array &$table - values of tictactoe
     * @param integer $rid
     * @param integer $cid
     * @param integer $count - count of values in tictactoe
     * @param string $symbol - 'x' or 'o'
     */
    function addValue(&$table, $rid, $cid, $count, $symbol) {
        if (array_key_exists($rid, $table) && array_key_exists($cid, $table[$rid])) {
            return;
        }
        $table[$rid][$cid] = $symbol;
        $count = $count + 1;

        $data = json_encode(
            [
                "table" => $table,
                "count" => $count
            ]
        , JSON_PRETTY_PRINT);
        file_put_contents('data.json', $data);
    }

    /**
     * Gets table values and count
     * 
     * @return array ['table' => [...], 'count' => ...]
     */
    function getData() {
        if (file_exists('data.json')) {
            $data = file_get_contents('data.json');
            return json_decode($data, true);
        }
        return ['table' => [], 'count' => 0];
    }

    function validate($table, $symbol) {
        if (
            (@$table[1][1] == $symbol &&
            @$table[1][1] == @$table[1][2] &&
            @$table[1][1] == @$table[1][3])
            ||
            (@$table[2][1] == $symbol &&
            @$table[2][1] == @$table[2][2] &&
            @$table[2][1] == @$table[2][3])
            ||
            (@$table[3][1] == $symbol &&
            @$table[3][1] == @$table[3][2] &&
            @$table[3][1] == @$table[3][3])
            ||
            (@$table[1][1] == $symbol &&
            @$table[1][1] == @$table[2][1] &&
            @$table[1][1] == @$table[3][1])
            ||
            (@$table[1][2] == $symbol &&
            @$table[1][2] == @$table[2][2] &&
            @$table[1][2] == @$table[3][2])
            ||
            (@$table[1][3] == $symbol &&
            @$table[1][3] == @$table[2][3] &&
            @$table[1][3] == @$table[3][3])
            ||
            (@$table[1][1] == $symbol &&
            @$table[1][1] == @$table[2][2] &&
            @$table[1][1] == @$table[3][3])
            ||
            (@$table[1][3] == $symbol &&
            @$table[1][3] == @$table[2][2] &&
            @$table[1][3] == @$table[3][1])
        ) {
            echo $symbol . " is a winner!";
        }
        
    }
?>

