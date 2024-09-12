<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla con Descarga de Excel</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        // Función para realizar la solicitud a la API en Python
        function descargarExcel() {
            fetch('http://localhost:5000/descargar_excel')
            .then(response => {
                if (response.ok) {
                    return response.blob();
                } else {
                    throw new Error('Error al descargar el archivo');
                }
            })
            .then(blob => {
                // Crear una URL para el archivo descargado y forzar la descarga
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'pedidos.xlsx'; // Nombre del archivo
                document.body.appendChild(a);
                a.click();
                a.remove();
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <h1>Datos en Tabla</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>CODIGO_PK</th>
                <th>TB_PEDIDOS_NIT</th>
                <th>TB_PEDIDOS_CLIENTE</th>
                <th>TB_PEDIDOS_MARCA</th>
                <th>TB_PEDIDOS_CODIGO_ZONA</th>
                <th>TB_PEDIDOS_NOMBRE_ZONA</th>
                <th>TB_PEDIDOS_CIUDAD</th>
                <th>TB_PEDIDOS_CAMPANA</th>
                <th>TB_PEDIDOS_FECHA</th>
                <th>TB_PEDIDOS_NRO_FACTURA</th>
                <th>TB_PEDIDOS_NRO_PEDIDO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include '../backend/config.php';

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM tb_pedidos_eliminados";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Imprimir cada fila de los resultados
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["CODIGO_PK"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_NIT"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_CLIENTE"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_MARCA"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_CODIGO_ZONA"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_NOMBRE_ZONA"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_CIUDAD"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_CAMPANA"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_FECHA"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_NRO_FACTURA"] . "</td>";
                    echo "<td>" . $row["TB_PEDIDOS_NRO_PEDIDO"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No hay datos disponibles</td></tr>";
            }

            // Cerrar la conexión
            $conn->close();
            ?>
        </tbody>
    </table>

    <br>
    <button onclick="descargarExcel()">Descargar Excel</button>
</body>
</html>
