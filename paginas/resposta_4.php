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
        <button type="button" id="loadData">Executar Consulta</button>
    </form>

    <main class="container">
        <table id="dataTable">
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
            </tbody>
        </table>
    </main>

    <script>
        document.getElementById('loadData').addEventListener('click', () => {
            fetch('resposta_4_load.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'loadData=true'
            })
            .then(response => response.text())
            .then(data => {
                document.querySelector('#dataTable tbody').innerHTML = data;
            })
            .catch(error => console.error(error));
        });
    </script>

    <?php include("../include/footer.php"); ?>
</body>

</html>