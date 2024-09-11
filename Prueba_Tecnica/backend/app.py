from flask import Flask, send_file
from flask_cors import CORS  # Importar CORS
import pandas as pd
import io
import mysql.connector

app = Flask(__name__)
CORS(app)  # Habilitar CORS en la aplicación Flask

@app.route('/descargar_excel', methods=['GET'])
def descargar_excel():
    # Configuración de la conexión a la base de datos
    db_config = {
        'host': 'localhost',
        'user': 'root',
        'password': '',
        'database': 'pedidos'
    }
    
    # Conectar a la base de datos
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)  # Obtener resultados como diccionario

    # Consulta SQL para obtener los datos
    query = "SELECT * FROM tb_pedidos_eliminados"
    cursor.execute(query)
    rows = cursor.fetchall()

    # Convertir los datos en un DataFrame de Pandas
    df = pd.DataFrame(rows)
    
    # Crear un archivo Excel en memoria
    output = io.BytesIO()
    with pd.ExcelWriter(output, engine='openpyxl') as writer:
        df.to_excel(writer, index=False, sheet_name='Datos')
    output.seek(0)

    # Cerrar la conexión
    cursor.close()
    conn.close()

    # Enviar el archivo Excel como respuesta
    return send_file(output, as_attachment=True, download_name='pedidos.xlsx', mimetype='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')

if __name__ == '__main__':
    app.run(debug=True)
