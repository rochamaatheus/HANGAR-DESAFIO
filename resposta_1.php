<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Big Bang Theory Inc</title>
    <style>
        .red { color: red; }
        .green { color: green; }
        .gray { color: gray; }
    </style>
</head>
<body>
    <?php
      /** @var mysqli $db_connection */
      include('./conexao.php');

      $stmt = $db_connection->prepare("SELECT DATE(order_date) as date, AVG(order_total) as average FROM orders GROUP BY DATE(order_date)");

      if ($stmt === false) {
        die('Erro ao preparar a consulta: ' . htmlspecialchars($db_connection->error));
      }

      $stmt->execute();

      $result = $stmt->get_result();
    ?>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Média</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
                $color = '';
                if ($row['average'] < 3000) {
                 $color = 'red';
                } elseif ($row['average'] > 3000) {
                 $color = 'green';
                } else {
                 $color = 'gray';
                }
            ?>
            <tr class="<?= $color; ?>">
                <td><?= $row['date']; ?></td>
                <td><?= $row['average']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
