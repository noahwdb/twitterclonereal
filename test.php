<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Twitter Pagina</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        textarea {
            width: 100%;
            height: 80px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }
        button {
            background-color: #1da1f2;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .tweet {
            background: #e1e8ed;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Twitter Pagina</h2>
    <form method="POST">
        <textarea name="tweet" placeholder="Wat gebeurt er?"></textarea>
        <button type="submit">Tweet</button>
    </form>
    <div id="tweets">
        <?php
        $host = 'localhost';
        $dbname = 'twitter_clone';
        $username = 'root';
        $password = '';
        session_start();
        if (!isset($_SESSION['tweets'])) {
            $_SESSION['tweets'] = [];
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['tweet']))) {
            array_unshift($_SESSION['tweets'], htmlspecialchars($_POST['tweet']));
        }
        foreach ($_SESSION['tweets'] as $tweet) {
            echo "<div class='tweet'>" . $tweet . "</div>";
        }

        ?>
    </div>
</div>
</body>
</html>
