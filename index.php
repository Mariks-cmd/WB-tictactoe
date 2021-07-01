<link rel="stylesheet" href="style.css">

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'DB.php';
$db = new DB();
$tictactoe_obj = $db->makeTableObject('tictactoe');

if (array_key_exists('action', $_GET) && $_GET['action'] == 'reset') {
    $tictactoe_obj->reset();
}
else {
    $table = &$tictactoe_obj->getTable();
    $amount = $tictactoe_obj->getAmount();
    $symbol = ($amount % 2 == 0) ? 'x' : 'o';

    if (array_key_exists('rid', $_GET) && array_key_exists('cid', $_GET)) {
        $rid = $_GET['rid'];
        $cid = $_GET['cid'];

        $tictactoe_obj->addEntry([
            'row' => $rid,
            'col' => $cid,
            'symbol' => $symbol
        ]);
    }
    
    if (checkWinner($table, $symbol)) {
        echo $symbol . " is a winner!";
        $tictactoe_obj->reset();
    }

}

?>

<div id="app">
    <div class="container">
        <?php
        for ($rid = 1; $rid <= 3; $rid++) {
            for ($cid = 1; $cid <= 3; $cid++) {
                echo "<a href='?rid=$rid&cid=$cid'>" . @$table[$rid][$cid] . "</a>";
            }
        }
        ?>
    </div>

    <a href="?action=reset" class="btn">Reset</a>
</div>


<?php
    function checkWinner($table, $symbol) {
        $win_cases = [
            //rows
            [[1,1], [1,2], [1,3]],
            [[2,1], [2,2], [2,3]],
            [[3,1], [3,2], [3,3]],
            //columns
            [[1,1], [2,1], [3,1]],
            [[1,2], [2,2], [3,2]],
            [[1,3], [2,3], [3,3]],
            //dioganal
            [[1,1], [2,2], [3,3]],
            [[1,3], [2,2], [3,1]],
        ];

        foreach ($win_cases as $case) {
            $first = $case[0];
            $second = $case[1];
            $third = $case[2];

            if ($symbol == @$table[$first[0]][$first[1]] &&
                $symbol == @$table[$second[0]][$second[1]] &&
                $symbol == @$table[$third[0]][$third[1]]
            ) {
                return true;
            }
        }

        return false;
    }
?>