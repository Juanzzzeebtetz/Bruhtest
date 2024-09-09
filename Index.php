<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificador de BINs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .container input[type="text"] {
            padding: 10px;
            width: 80%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .container input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verificador de BINs</h1>
        <form id="binForm">
            <input type="text" id="binInput" placeholder="Ingrese un BIN de 6 dígitos" required maxlength="6" pattern="\d{6}">
            <br>
            <input type="submit" value="Verificar BIN">
        </form>
        <div id="result" class="result"></div>
    </div>

    <script>
        document.getElementById('binForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const bin = document.getElementById('binInput').value;

            fetch(`api.php?bin_number=${bin}`)
                .then(response => response.json())
                .then(data => {
                    let resultDiv = document.getElementById('result');
                    if (data.error) {
                        resultDiv.innerHTML = `<strong>Error:</strong> ${data.error}`;
                    } else {
                        resultDiv.innerHTML = `
                            <strong>País:</strong> ${data.country_name} <img src="${data.country_flag}" alt="-"><br>
                            <strong>Banco:</strong> ${data.bank}<br>
                            <strong>Nivel:</strong> ${data.level}<br>
                            <strong>Tipo:</strong> ${data.type}<br>
                            <strong>Marca:</strong> ${data.brand}
                        `;
                    }
                })
                .catch(error => {
                    document.getElementById('result').innerHTML = `<strong>Error:</strong> ${error.message}`;
                });
        });
    </script>
</body>
</html>
