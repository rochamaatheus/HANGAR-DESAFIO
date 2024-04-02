<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Pedidos</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/tabela.css">
    <style>
    #printButton {
        margin: 20px auto;
        padding: .5rem .75rem;
        padding: .5rem 1rem;
        font-size: 1rem;
        color: #fff;
        background-color: #234;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color .3s ease;
    }

    #printButton:hover {
        background-color: #4b8;
    }
    </style>
</head>

<body>
    <?php include("../include/header-paginas.php"); ?>

    <main class="container">
        <button id="printButton" onclick="window.print()">Imprimir</button>

        <table>
            <thead>
                <tr>
                    <th>ID do Pedido</th>
                    <th>ID do Pedido do Usuário</th>
                    <th>Data do Pedido</th>
                    <th>Total do Pedido</th>
                </tr>
            </thead>
            <tbody>
                <?php
                  ini_set('display_errors', '0');
                  /** @var mysqli $db_connection */
                  include('../conexao.php');
                  if (!($db_connection instanceof mysqli)) {
                    die("<p style=\"text-align: center;\">Erro de conexão: Não foi possível conectar ao banco de dados</p>");
                  }
                  try {
                    $stmt = $db_connection->prepare("SELECT * FROM orders");
                    if ($stmt === false) {
                      die("<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>");
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']); ?></td>
                    <td><?= htmlspecialchars($row['order_user_id']); ?></td>
                    <td><?= htmlspecialchars($row['order_date']); ?></td>
                    <td><?= htmlspecialchars($row['order_total']); ?></td>
                </tr>
                <?php
                  endwhile; } catch (Exception $e) {
                    $logMessage = date('Y-m-d H:i:s') . ' - Erro: ' . $e->getMessage() . "\n";
                    file_put_contents('error_log_resposta_2.txt', $logMessage, FILE_APPEND);
                    echo "<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>";
                  } finally {
                      if ($stmt !== null) {
                          $stmt->close();
                      }
                      $db_connection->close();
                  }
                ?>
            </tbody>
        </table>
    </main>

    <?php include("../include/footer.php"); ?>

</body>

</html>