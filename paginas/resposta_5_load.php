<?php
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_POST['loadData'])) {
        ini_set('display_errors', '0');
        /** @var mysqli $db_connection */
        include('../conexao.php');

        if (!($db_connection instanceof mysqli)) {
            die("<p style=\"text-align: center;\">Erro de conexão: Não foi possível conectar ao banco de dados</p>");
        }

        try {
            $stmt = $db_connection->prepare("UPDATE user SET user_country = 'Canada' WHERE user_id = 4");

            if ($stmt === false) {
                die("<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>");
            }

            $stmt->execute();

            $stmt = $db_connection->prepare("SELECT user_id, user_name, user_city, user_country FROM User WHERE user_id = 4");

            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_id']); ?></td>
                    <td><?= htmlspecialchars($row['user_name']); ?></td>
                    <td><?= htmlspecialchars($row['user_city']); ?></td>
                    <td><?= htmlspecialchars($row['user_country']); ?></td>
                </tr>
            <?php endwhile;
        } catch (Exception $e) {
            $logMessage = date('Y-m-d H:i:s') . ' - Erro: ' . $e->getMessage() . "\n";
            file_put_contents('error_log_resposta_5.txt', $logMessage, FILE_APPEND);
            echo "<p style=\"text-align: center;\">Ocorreu um erro. Por favor, tente novamente mais tarde.</p>";
        } finally {
            if ($stmt !== null) {
                $stmt->close();
            }
            $db_connection->close();
        }
    }
?>