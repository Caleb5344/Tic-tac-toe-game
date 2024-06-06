<?php
session_start();

if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['turn'] = 'X';
    $_SESSION['winner'] = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cell']) && $_SESSION['winner'] === '') {
    $cell = (int)$_POST['cell'];
    if ($_SESSION['board'][$cell] === '') {
        $_SESSION['board'][$cell] = $_SESSION['turn'];
        $_SESSION['turn'] = $_SESSION['turn'] === 'X' ? 'O' : 'X';
        checkWinner();
    }
}

function checkWinner() {
    $winningCombinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // rows
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // columns
        [0, 4, 8], [2, 4, 6]             // diagonals
    ];

    foreach ($winningCombinations as $combination) {
        if ($_SESSION['board'][$combination[0]] !== '' &&
            $_SESSION['board'][$combination[0]] === $_SESSION['board'][$combination[1]] &&
            $_SESSION['board'][$combination[1]] === $_SESSION['board'][$combination[2]]) {
            $_SESSION['winner'] = $_SESSION['board'][$combination[0]];
            return;
        }
    }

    if (!in_array('', $_SESSION['board'], true)) {
        $_SESSION['winner'] = 'Draw';
    }
}

if (isset($_POST['reset'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['turn'] = 'X';
    $_SESSION['winner'] = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        text-align: center;
    }

    .board {
        display: grid;
        grid-template-columns: repeat(3, 100px);
        gap: 10px;
    }

    .cell {
        width: 100px;
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2rem;
        cursor: pointer;
        border: 1px solid #333;
    }

    .cell:hover {
        background-color: #f0f0f0;
    }

    .winner {
        margin-top: 20px;
        font-size: 1.5rem;
    }

    .reset-button {
        margin-top: 20px;
        padding: 10px 20px;
        font-size: 1rem;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tic Tac Toe</h1>
        <form method="POST">
            <div class="board">
                <?php for ($i = 0; $i < 9; $i++): ?>
                <button type="submit" name="cell" value="<?= $i ?>" class="cell">
                    <?= $_SESSION['board'][$i] ?>
                </button>
                <?php endfor; ?>
            </div>
            <?php if ($_SESSION['winner'] !== ''): ?>
            <div class="winner">
                <?php if ($_SESSION['winner'] === 'Draw'): ?>
                It's a Draw!
                <?php else: ?>
                <?= $_SESSION['winner'] ?> wins!
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <button type="submit" name="reset" class="reset-button">Reset Game</button>
        </form>
    </div>
</body>

</html>