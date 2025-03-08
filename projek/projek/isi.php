<?php
include "config.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$sql_rooms = "SELECT rooms.id, rooms.name AS room_name, rooms.location, SUM(items.quantity) AS total_quantity
              FROM rooms
              LEFT JOIN items ON rooms.id = items.room_id
              GROUP BY rooms.id";
$result_rooms = $conn->query($sql_rooms);

$sql_chart1 = "SELECT rooms.name AS room_name, SUM(items.quantity) AS total_quantity 
               FROM rooms 
               LEFT JOIN items ON rooms.id = items.room_id 
               GROUP BY rooms.id";
$result_chart1 = $conn->query($sql_chart1);
$chart1_labels = array();
$chart1_data = array();
if ($result_chart1->num_rows > 0) {
  while ($row = $result_chart1->fetch_assoc()) {
    $chart1_labels[] = $row['room_name'];
    $chart1_data[] = $row['total_quantity'] ? $row['total_quantity'] : 0;
  }
}

$sql_chart2 = "SELECT categories.name AS category_name, SUM(items.quantity) AS total_quantity 
               FROM items 
               LEFT JOIN categories ON items.category_id = categories.id 
               GROUP BY categories.id";
$result_chart2 = $conn->query($sql_chart2);
$chart2_labels = array();
$chart2_data = array();
if ($result_chart2->num_rows > 0) {
  while ($row = $result_chart2->fetch_assoc()) {
    $chart2_labels[] = $row['category_name'];
    $chart2_data[] = $row['total_quantity'] ? $row['total_quantity'] : 0;
  }
}

$sql_chart3 = "SELECT rooms.name AS room_name, SUM(items.price * items.quantity) AS total_value 
               FROM rooms 
               LEFT JOIN items ON rooms.id = items.room_id 
               GROUP BY rooms.id";
$result_chart3 = $conn->query($sql_chart3);
$chart3_labels = array();
$chart3_data = array();
if ($result_chart3->num_rows > 0) {
  while ($row = $result_chart3->fetch_assoc()) {
    $chart3_labels[] = $row['room_name'];
    $chart3_data[] = $row['total_value'] ? $row['total_value'] : 0;
  }
}

$sql_chart4 = "SELECT item_condition, SUM(quantity) AS total_quantity 
               FROM items 
               GROUP BY item_condition";
$result_chart4 = $conn->query($sql_chart4);
$chart4_labels = array();
$chart4_data = array();
if ($result_chart4->num_rows > 0) {
  while ($row = $result_chart4->fetch_assoc()) {
    $chart4_labels[] = ucfirst($row['item_condition']);
    $chart4_data[] = $row['total_quantity'] ? $row['total_quantity'] : 0;
  }
}

$sql_chart5 = "SELECT categories.name AS category_name, SUM(items.price * items.quantity) AS total_value 
               FROM items 
               LEFT JOIN categories ON items.category_id = categories.id 
               GROUP BY categories.id";
$result_chart5 = $conn->query($sql_chart5);
$chart5_labels = array();
$chart5_data = array();
if ($result_chart5->num_rows > 0) {
  while ($row = $result_chart5->fetch_assoc()) {
    $chart5_labels[] = $row['category_name'];
    $chart5_data[] = $row['total_value'] ? $row['total_value'] : 0;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Inventaris</title>
  <link rel="stylesheet" href="style.css">
</head>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    background: #f4f7f6;
    color: #333;
    padding: 20px;
}

.header {
    text-align: center;
    padding: 20px;
    background: #469ced;
    color: #fff;
    border-radius: 8px;
    margin-bottom: 20px;
}

.charts-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin-bottom: 20px;
}

.chart-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin: 10px;
    padding: 20px;
    flex: 1 1 300px;
    max-width: 400px;
    text-align: center;
}

.chart-card canvas {
    max-width: 100%;
}

.chart-title {
    margin-top: 10px;
    font-weight: bold;
}

.table-section {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.table-section h2 {
    margin-bottom: 10px;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th,
table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

table th {
    background: #f2f2f2;
}

table tr:nth-child(even) {
    background: #f9f9f9;
}

a {
    color: #469ced;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
<body>
  <header class="header">
    <h1>Dashboard Inventaris</h1>
  </header>
  <section class="charts-section">
    <div class="chart-card">
      <canvas id="chart1"></canvas>
      <div class="chart-title">Inventaris per Ruangan</div>
    </div>
    <div class="chart-card">
      <canvas id="chart2"></canvas>
      <div class="chart-title">Inventaris per Kategori</div>
    </div>
    <div class="chart-card">
      <canvas id="chart3"></canvas>
      <div class="chart-title">Total Harga Inventaris per Ruangan</div>
    </div>
    <div class="chart-card">
      <canvas id="chart4"></canvas>
      <div class="chart-title">Distribusi Kondisi Barang</div>
    </div>
    <div class="chart-card">
      <canvas id="chart5"></canvas>
      <div class="chart-title">Total Harga Inventaris per Kategori</div>
    </div>
  </section>
  <section class="table-section">
    <h2>Daftar Ruangan</h2>
    <table>
      <tr>
        <th>Nama Ruangan</th>
        <th>Lokasi</th>
        <th>Total Quantity</th>
        <th>Aksi</th>
      </tr>
      <?php
      if ($result_rooms->num_rows > 0) {
        while ($row = $result_rooms->fetch_assoc()) {
          echo "<tr>";
          echo "<td>".$row['room_name']."</td>";
          echo "<td>".$row['location']."</td>";
          echo "<td>".$row['total_quantity']."</td>";
          echo "<td><a href='detail_ruangan.php?id=".$row['id']."'>Lihat Lebih Detail</a></td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='4'>Tidak ada data ruangan.</td></tr>";
      }
      ?>
    </table>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    var chart1Labels = <?php echo json_encode($chart1_labels); ?>;
    var chart1Data = <?php echo json_encode($chart1_data); ?>;
    var ctx1 = document.getElementById('chart1').getContext('2d');
    var chart1 = new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: chart1Labels,
        datasets: [{
          label: 'Total Quantity',
          data: chart1Data,
          backgroundColor: 'rgba(75, 192, 192, 0.7)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });

    var chart2Labels = <?php echo json_encode($chart2_labels); ?>;
    var chart2Data = <?php echo json_encode($chart2_data); ?>;
    var ctx2 = document.getElementById('chart2').getContext('2d');
    var chart2 = new Chart(ctx2, {
      type: 'pie',
      data: {
        labels: chart2Labels,
        datasets: [{
          label: 'Total Quantity',
          data: chart2Data,
          backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: { responsive: true }
    });

    var chart3Labels = <?php echo json_encode($chart3_labels); ?>;
    var chart3Data = <?php echo json_encode($chart3_data); ?>;
    var ctx3 = document.getElementById('chart3').getContext('2d');
    var chart3 = new Chart(ctx3, {
      type: 'bar',
      data: {
        labels: chart3Labels,
        datasets: [{
          label: 'Total Harga',
          data: chart3Data,
          backgroundColor: 'rgba(153, 102, 255, 0.7)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });

    var chart4Labels = <?php echo json_encode($chart4_labels); ?>;
    var chart4Data = <?php echo json_encode($chart4_data); ?>;
    var ctx4 = document.getElementById('chart4').getContext('2d');
    var chart4 = new Chart(ctx4, {
      type: 'doughnut',
      data: {
        labels: chart4Labels,
        datasets: [{
          label: 'Total Quantity',
          data: chart4Data,
          backgroundColor: [
            'rgba(255, 159, 64, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)'
          ],
          borderColor: [
            'rgba(255, 159, 64, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: { responsive: true }
    });

    var chart5Labels = <?php echo json_encode($chart5_labels); ?>;
    var chart5Data = <?php echo json_encode($chart5_data); ?>;
    var ctx5 = document.getElementById('chart5').getContext('2d');
    var chart5 = new Chart(ctx5, {
      type: 'bar',
      data: {
        labels: chart5Labels,
        datasets: [{
          label: 'Total Harga',
          data: chart5Data,
          backgroundColor: 'rgba(255, 206, 86, 0.7)',
          borderColor: 'rgba(255, 206, 86, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>
</body>
</html>
