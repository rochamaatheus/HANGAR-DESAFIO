<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização de País</title>
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
          $stmt = $db_connection->prepare("UPDATE user SET user_country = 'Canada' WHERE user_id = 4");

          if ($stmt === false) {
            die("<p>Ocorreu um erro. Por favor, tente novamente mais tarde.</p>");
          }

          $stmt->execute();

          $stmt = $db_connection->prepare("SELECT user_id, user_name, user_city, user_country FROM User WHERE user_id = 4");

          $stmt->execute();

          $result = $stmt->get_result();
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>City</th>
                <th>Country</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_id']); ?></td>
                <td><?= htmlspecialchars($row['user_name']); ?></td>
                <td><?= htmlspecialchars($row['user_city']); ?></td>
                <td><?= htmlspecialchars($row['user_country']); ?></td>
            </tr>
            <?php endwhile; } catch (Exception $e) {
                $logMessage = date('Y-m-d H:i:s') . ' - Erro: ' . $e->getMessage() . "\n";
                file_put_contents('error_log_resposta_5.txt', $logMessage, FILE_APPEND);
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
