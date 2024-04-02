<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Média de Pedidos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/tabela.css">
</head>

<body>
    <?php include("../include/header-paginas.php"); ?>

    <form method="post" class="container">
        <button type="submit" name="submit">Executar Consulta</button>
    </form>

    <?php
      ini_set('display_errors', '0');
      if (isset($_POST['submit'])) {
        /** @var mysqli $db_connection */
        include('../conexao.php');

        if (!($db_connection instanceof mysqli)) {
          die("<p style=\"text-align: center;\">Erro de conexão: Não foi possível conectar ao banco de dados</p>");
        }

        try {
          $stmt = $db_connection->prepare("SELECT user.user_name AS Name, user.user_city AS City, user.user_country AS Country, orders.order_date AS Date, orders.order_total AS Total FROM user JOIN orders ON user.user_id = orders.order_user_id WHERE user.user_id IN (1, 3, 5) ORDER BY user.user_name");

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
                    <th>Name</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Date</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Name']); ?></td>
                    <td><?= htmlspecialchars($row['City']); ?></td>
                    <td><?= htmlspecialchars($row['Country']); ?></td>
                    <td><?= htmlspecialchars($row['Date']); ?></td>
                    <td><?= htmlspecialchars($row['Total']); ?></td>
                </tr>
                <?php endwhile; } catch (Exception $e) {
                    $logMessage = date('Y-m-d H:i:s') . ' - Erro: ' . $e->getMessage() . "\n";
                    file_put_contents('error_log_resposta_4.txt', $logMessage, FILE_APPEND);
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

    <?php
      }
      include("../include/footer.php");
    ?>
</body>

</html>