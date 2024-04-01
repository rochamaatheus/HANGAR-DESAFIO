<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendas por País</title>
</head>
<body>
    <?php
      /** @var mysqli $db_connection */
      include('./conexao.php');

      if (!($db_connection instanceof mysqli)) {
        die("<p>Erro de conexão: Não foi possível conectar ao banco de dados</p>");
      }
    ?>
    <form method="post">
        <label for="country">País:</label>
        <select name="country" id="country">
            <option value="" disabled selected>Selecione um País</option>

            <?php
              $stmt = $db_connection->prepare("SELECT DISTINCT user_country FROM user ORDER BY user_country");
              if ($stmt === false) {
                die("<p>Erro ao preparar a consulta: " . htmlspecialchars($db_connection->error) . "</p>");
              }
              $stmt->execute();
              $countries = $stmt->get_result();
              while ($row = $countries->fetch_assoc()) {
                echo "<option value=\"{$row['user_country']}\">{$row['user_country']}</option>";
              }
              $stmt->close();
            ?>

        </select>
        <button type="submit">Filtrar</button>
    </form>

    <?php
      $country = $_POST['country'] ?? '';

      if (empty($country)) {
        echo "<p>Por favor, selecione um país.</p>";
      } else {
        try {
            $stmt = $db_connection->prepare("SELECT user.user_country, SUM(orders.order_total) as total_sales FROM orders INNER JOIN user ON orders.order_user_id = user.user_id WHERE user.user_country LIKE ? GROUP BY user.user_country");
            $stmt->bind_param('s', $country);
    
            if ($stmt === false) {
                throw new Exception("Erro ao preparar a consulta: " . $db_connection->error);
            }
    
            $stmt->execute();
    
            $result = $stmt->get_result();
    ?>

    <table>
        <thead>
            <tr>
                <th>País</th>
                <th>Total de Vendas</th>
            </tr>
        </thead>
        <tbody>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>

            <tr>
                <td><?= $row['user_country']; ?></td>
                <td><?= $row['total_sales']; ?></td>
            </tr>

            <?php 
              endwhile; 
              $stmt->close();
              $db_connection->close();    
            ?>

        </tbody>
    </table>
    
    <?php 
        } catch (Exception $e) { 
            echo "<p>Ocorreu um erro. Por favor, tente novamente mais tarde.</p>"; 
        }
      }
    ?>
</body>
</html>
