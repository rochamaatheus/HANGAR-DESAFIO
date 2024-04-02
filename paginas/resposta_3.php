<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendas por País</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/tabela.css">
    <style>
    form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    form label {
        font-weight: bold;
        margin-bottom: .5rem;
    }

    form select {
        padding: .5rem;
        font-size: 1rem;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    </style>
</head>

<body>
    <?php
      include('../include/header-paginas.php');
      /** @var mysqli $db_connection */
      include('../conexao.php');

      if (!($db_connection instanceof mysqli)) {
        die("<p style=\"text-align: center;\">Erro de conexão: Não foi possível conectar ao banco de dados</p>");
      }
    ?>

    <form method="post" class="container">
        <label for="country">País:</label>
        <select name="country" id="country">
            <option value="" disabled selected>Selecione um País</option>

            <?php
              try {
                $stmt = $db_connection->prepare("SELECT DISTINCT user_country FROM user ORDER BY user_country");
                if ($stmt === false) {
                  die("<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>");
                }
                $stmt->execute();
                $countries = $stmt->get_result();
                while ($row = $countries->fetch_assoc()) {
                    echo "<option value=\"" . htmlspecialchars($row['user_country']) . "\">" . htmlspecialchars($row['user_country']) . "</option>";
                }
              } catch (Exception $e) {
                echo "<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>";
              } finally {
                $stmt->close();
              }
            ?>

        </select>
        <button type="submit">Filtrar</button>
    </form>

    <?php
      $country = $_POST['country'] ?? '';

      if (empty($country)) {
        echo "<p style=\"text-align: center;\">Por favor, selecione um país.</p>";
      } else {
        try {
            $stmt = $db_connection->prepare("SELECT user.user_country, SUM(orders.order_total) as total_sales FROM orders INNER JOIN user ON orders.order_user_id = user.user_id WHERE user.user_country LIKE ? GROUP BY user.user_country");
            $stmt->bind_param('s', $country);
    
            if ($stmt === false) {
                die("<p style=\"text-align: center;\">Erro ao preparar a consulta</p>");
            }
    
            $stmt->execute();
    
            $result = $stmt->get_result();
    ?>

    <main class="container">
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
                    <td><?= htmlspecialchars($row['user_country']); ?></td>
                    <td><?= htmlspecialchars($row['total_sales']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <?php 
        } catch (Exception $e) { 
            echo "<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>"; 
        } finally {
            $stmt->close();
            $db_connection->close(); 
        }
      }
      include('../include/footer.php');
    ?>
</body>

</html>