<?php 
require_once("includes/config.php");

$selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('Y-m');

if(isset($_POST['submit'])) {
    $selectedMonth = $_POST['month'];
}

$query = $con->prepare("SELECT * FROM MessageToAdmin WHERE DATE_FORMAT(date, '%Y-%m') = ?");
$query->execute([$selectedMonth]);
$messages = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>
    <link rel="stylesheet" type="text/css" href="assets/style/styleChat.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #e3f2fd, #90caf9);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        #container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 800px;
            max-height: 800px;
            padding: 20px;
            overflow: scroll;
        }

        #container::-webkit-scrollbar {
            display: none; 
        }

        #container {
            scrollbar-width: none; 
        }

        .chatBox {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
            background: #f5f5f5;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
            width: 100%;
        }

        .chatBox:hover {
            transform: scale(1.02);
            width: 100%;
        }

        .chatData {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            width: 100%;
            overflow: scroll;
        }

        .chatData .username {
            font-weight: bold;
            color: #333;
        }

        .detailsBox {
            display: none;
            padding: 10px;
            background: #ffffff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
            overflow: scroll;
        }

        .detailsBox.active {
            display: block;
        }

        .detailsBox p {
            margin: 5px 0;
        }

        .toggleButton {
            padding: 8px 16px;
            border: none;
            background-color: #ff5252;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .toggleButton:hover {
            background-color: #ff1744;
        }

        h1 a.back-link {
            position: absolute;
            top: 20px; 
            left: 20px; 
            text-decoration: none;
            color: #333;
            font-size: 18px;
        }

        h1 a.back-link i {
            margin-left: 5px;
        }

        .btn-toggleButton {
            padding: 8px 16px;
            margin: 7px 5px;
            border: none;
            background-color: #ff5252;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>

<h1><a href="admin.php" class="back-link"><i class="fas fa-arrow-left"></i>Back</a></h1>

<div id="container">
    <form method="POST">
        <label for="month">Select Month:</label>
        <select style='padding:20px 20px;margin:20px 20px;'  name="month" id="month">
            <?php
            // Generate options for the last 12 months
            for ($i = 0; $i >= -11; $i--) {
                $month = date('Y-m', strtotime("$i months"));
                $selected = ($month == $selectedMonth) ? 'selected' : '';
                echo "<option style='padding:20px 20px;margin:20px 20px;' value='$month' $selected>" . date('F Y', strtotime($month)) . "</option>";
            }
            ?>
        </select>
        <input style="margin: 10px 10px;padding:5px 5px;border-radius:5px" type="submit" name="submit" value="Show Messages">
    </form>

    <?php if(count($messages) > 0): ?>
        <?php foreach ($messages as $row): ?>
            <div class="chatBox">
                <div class="chatData">
                    <div class="username"><?php echo htmlspecialchars($row["username"]);?></div>
                    <button class="toggleButton">Show Details</button>
                </div>
                <div class="detailsBox">
                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($row["subject"]);?></p>
                    <p><strong>Message:</strong> <?php echo htmlspecialchars($row["message"]);?></p>
                    <p><strong>Date Sent:</strong> <?php echo $row["date"];?></p>
                    <p><strong>Message Id:</strong> <?php echo $row["id"];?></p>
                    <p><strong>Gmail :</strong> <?php echo $row["gmail"];?></p>
                </div>
                <button onclick="DeleteMessageInAd(<?php echo $row['id'] ?>)" class="btn-toggleButton">Delete</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No Messages Yet</p>
    <?php endif; ?>
</div>

<script>
    const toggleButtons = document.querySelectorAll('.toggleButton');
    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            const detailsBox = button.parentElement.nextElementSibling;
            detailsBox.classList.toggle('active');
            button.textContent = detailsBox.classList.contains('active') ? 'Hide Details' : 'Show Details';
        });
    });
</script>

</body>
</html>
