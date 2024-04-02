<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Média de Pedidos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/tabela.css">
    <style>
    .red {
        color: red;
    }

    .green {
        color: green;
    }

    .gray {
        color: gray;
    }
    </style>
</head>

<body>
    <?php
      include("../include/header-paginas.php");

      ini_set('display_errors', '0');
      /** @var mysqli $db_connection */
      include('../conexao.php');

      if (!($db_connection instanceof mysqli)) {
        die("<p style=\"text-align: center;\">Erro de conexão: Não foi possível conectar ao banco de dados</p>");
      }

      try {
        $stmt = $db_connection->prepare("SELECT DATE(order_date) as date, AVG(order_total) as average FROM orders GROUP BY DATE(order_date)");

        if ($stmt === false) {
          die("<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>");
        }
  
        $stmt->execute();
  
        $result = $stmt->get_result();
    ?>

    <main class="container">
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
                    <td><?= htmlspecialchars($row['date']); ?></td>
                    <td><?= htmlspecialchars($row['average']); ?></td>
                </tr>
                <?php endwhile; } catch (Exception $e) {
                    $logMessage = date('Y-m-d H:i:s') . ' - Erro: ' . $e->getMessage() . "\n";
                    file_put_contents('error_log_resposta_1.txt', $logMessage, FILE_APPEND);
                    echo "<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>";
                } finally {
                    if ($stmt !== null) {
                        $stmt->close();
                    }
                    $db_connection->close();
                } ?>
            </tbody>
        </table>
    </main>

    <?php include("../include/footer.php"); ?>
</body>

</html>