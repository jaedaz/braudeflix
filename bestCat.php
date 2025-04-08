<?php
require_once("includes/config.php");
require_once("includes/headerAdmin.php");


$currentYear = date("Y");


if(isset($_GET['year']) && !empty($_GET['year'])){
    $selectedYear = $_GET['year'];
} else {
    $selectedYear = $currentYear;
}


$query = "SELECT cat.name AS category_name, COUNT(vop.videoId) AS view_count
    FROM videoProgress vop
    JOIN videos v ON vop.videoId = v.id
    JOIN entities e ON v.entityId = e.id
    JOIN categories cat ON e.categoryId = cat.id
    WHERE YEAR(vop.dateModified) = :selectedYear
    GROUP BY cat.name
    ORDER BY view_count DESC
    LIMIT 6
";

$stmt = $con->prepare($query);
$stmt->bindValue(":selectedYear", $selectedYear, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$jsonData = json_encode($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        .chart-container {
            width: 80%;
            max-width: 1000px;
            margin: 0 150px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }
        #categoryChart {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: opacity 0.3s;
        }
    </style>
</head>
<body>

<div class="chart-container">
    <h1>This is for the year: <?php echo $selectedYear; ?></h1>
    <canvas id="categoryChart"></canvas>
    <select id="yearSelect" onchange="updateYear(this.value)">
        <?php 

        for ($i = $currentYear; $i >= 2000; $i--) {
            echo "<option value='$i' " . ($i == $selectedYear ? 'selected' : '') . ">$i</option>";
        }
        ?>
    </select>
</div>

<script>
    function updateYear(year) {
        window.location.href = 'admin_page.php?year=' + year;
    }

    // Fetch data from PHP
    const data = <?php echo $jsonData; ?>;

    if (data.length > 0) {
        // Prepare data for Chart.js
        const labels = data.map(item => item.category_name);
        const viewCounts = data.map(item => item.view_count);

        // Setup the chart using Chart.js
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Views',
                    data: viewCounts,
                    backgroundColor: 'rgba(255, 255, 255, 0.2)',
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1,
                    fill: false,
                    tension: 0.1 
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.2)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.2)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    }
                }
            }
        });
    } else {
        // Handle the case where there's no data
        document.querySelector('.chart-container').innerHTML = '<p style="color:#fff;">No data available for the current year.</p>';
    }
</script>
</body>
</html>
