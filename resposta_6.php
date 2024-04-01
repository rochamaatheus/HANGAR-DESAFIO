<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Média de Pedidos</title>
</head>
<body>
    <form method="post">
        <button type="submit" name="submit">Executar Consulta</button>
    </form>

    <?php
      ini_set('display_errors', '0');
      if (isset($_POST['submit'])) {
        /** @var mysqli $db_connection */
        include('./conexao.php');

        if (!($db_connection instanceof mysqli)) {
          die("<p>Erro de conexão: Não foi possível conectar ao banco de dados</p>");
        }

        try {
          $stmt = $db_connection->prepare("SELECT YEAR(orders.order_date) AS Year, MONTH(orders.order_date) AS Month, SUM(orders.order_total) AS Total FROM user JOIN orders ON user.user_id = orders.order_user_id WHERE user.user_id IN (1, 3, 5) GROUP BY YEAR(orders.order_date), MONTH(orders.order_date) ORDER BY Year, Month");

          if ($stmt === false) {
            die("<p>Ocorreu um erro. Por favor, tente novamente mais tarde.</p>");
          }

          $stmt->execute();

          $result = $stmt->get_result();
    ?>

    <table>
        <thead>
            <tr>
                <th>Ano</th>
                <th>Mês</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['Year']); ?></td>
                <td><?= htmlspecialchars($row['Month']); ?></td>
                <td><?= htmlspecialchars($row['Total']); ?></td>
            </tr>
            <?php endwhile; } catch (Exception $e) {
                $logMessage = date('Y-m-d H:i:s') . ' - Erro: ' . $e->getMessage() . "\n";
                file_put_contents('error_log_resposta_6.txt', $logMessage, FILE_APPEND);
                echo "<p>Ocorreu um erro. Por favor, tente novamente mais tarde.</p>";
            } finally {
                if ($stmt !== null) {
                    $stmt->close();
                }
                $db_connection->close();
            } ?>
        </tbody>
    </table>

    <?php
      }
    ?>
</body>
</html>
