### Documentation for the Excel to PDF Conversion Project

#### Overview
This project allows users to convert an Excel (.xlsx) file into a PDF via a web form. The system consists of two main components:

1. **Frontend (HTML, CSS, and JavaScript)**: A form that allows the user to upload an Excel file and receive the converted PDF file as a response.
2. **Backend (PHP)**: The server processes the Excel file and performs the conversion to PDF using a specialised class.

---

### Involved Files

1. **`process.php`**: This PHP file handles the logic for converting the Excel file to PDF.
2. **`index.html`**: The HTML file containing the form for uploading the Excel file.

---

### Project Structure

```
/project-root
│
├── /src
│   ├── index.html                (Upload form and response display)
│   ├── process.php               (File conversion logic)
│   └── ExcelToPdfConversor.php   (Class that converts Excel to PDF)
├── /vendor                      (Composer dependencies)
└── composer.json                (PHP dependency file)
```

---

### Workflow

#### 1. **Frontend - index.html**

The HTML form allows the user to select an Excel file (.xlsx) and upload it to the server. 

- **Form**:
  - The user selects a `.xlsx` file.
  - Upon clicking "Convert to PDF", the file is sent to the server using a `POST` method.
  
- **Server Interaction**:
  - The form uses `JavaScript` to send the `POST` request with the file attached.
  - The server’s response is displayed below the form, showing either a success or error message.

#### 2. **Backend - process.php**

- **File Reception**:
  - The file is received via `$_FILES['excelFile']`.
  - The file’s extension is checked to ensure it is `.xlsx`.
  
- **PDF Conversion**:
  - If the file extension is valid, it is passed to the `ExcelToPdfConversor` class for conversion.
  - If the conversion is successful, the server returns a URL to download the resulting PDF file.
  - If there is an error during conversion (e.g., issues with the Excel file), an error message is returned.

- **Error Handling**:
  - If no file is uploaded or there is an issue with uploading, an error message is returned.
  - If the file does not have the `.xlsx` extension, the user is informed.

---

### Code Files

#### **process.php**

```php
<?php

namespace src;

use Exception;

require_once '../vendor/autoload.php';
require './ExcelToPdfConversor.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
        $excelFile = $_FILES['excelFile']['tmp_name']; // The uploaded file
        $excelFileName = $_FILES['excelFile']['name']; // Original filename
        $fileExtension = pathinfo($excelFileName, PATHINFO_EXTENSION);

        // Check if the file has the .xlsx extension
        if ($fileExtension !== 'xlsx') {
            print_r(json_encode(['success' => false, 'message' => 'Please upload a valid Excel file (.xlsx).']));
            exit;
        }

        $outputPdf = 'converted_' . time() . '.pdf'; // Output PDF filename

        $converter = new ExcelToPdfConversor();
        
        try {
            // Call the method that converts the Excel file to PDF
            $converter->excelToPdf($excelFile, $outputPdf);

            // If conversion is successful, return the URL of the PDF file
            print_r(json_encode(['success' => true, 'fileUrl' => $outputPdf]));
        } catch (Exception $e) {
            // If there was an error during conversion, return the error message
            print_r(json_encode(['success' => false, 'message' => 'Conversion error: ' . $e->getMessage()]));
        }
    } else {
        print_r(json_encode(['success' => false, 'message' => 'No file uploaded or an error occurred during upload.']));
    }
}
?>
```

#### **index.html**

```html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel to PDF Converter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }

        h1 {
            color: #333;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            max-width: 400px;
            background-color: #f9f9f9;
        }

        input[type="file"] {
            margin: 10px 0;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success,
        .error {
            margin: 20px auto;
            padding: 10px;
            max-width: 400px;
            border-radius: 5px;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <h1>Excel to PDF Converter</h1>
    <form id="uploadForm" enctype="multipart/form-data" method="POST">
        <label for="excelFile">Select an Excel file (.xlsx):</label><br><br>
        <input type="file" name="excelFile" id="excelFile" accept=".xlsx" required><br><br>
        <button type="submit">Convert to PDF</button>
    </form>
    <div id="response"></div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const responseDiv = document.getElementById('response');
            responseDiv.innerHTML = '';

            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    responseDiv.innerHTML = `<div class="success">
                        File successfully converted: 
                        <a href="${result.fileUrl}" target="_blank">Download PDF</a>
                    </div>`;
                } else {
                    responseDiv.innerHTML = `<div class="error">Error: ${result.message}</div>`;
                }
            } catch (error) {
                responseDiv.innerHTML = `<div class="error">Error processing file: ${error.message}</div>`;
            }
        });
    </script>
</body>

</html>
```

---

### Dependencies

This project uses the following dependencies:

- **composer**: Composer is required to manage PHP dependencies.
  
---

### Installation Instructions

1. Install the project dependencies using Composer:
   ```bash
   composer install
   ```

2. Place the Excel `.xlsx` file you wish to convert into the web form.

3. Upon submitting the form, the server will process the file and convert it to PDF.

4. A download link for the converted PDF file will be provided on the webpage.

---

### Security Considerations

- Ensure that the uploaded file is of type `.xlsx` to prevent other file types from being processed.
- Validate the file size and content to mitigate potential security risks.

### Documentación para el proyecto de conversión de Excel a PDF

#### Descripción general
Este proyecto permite convertir un archivo Excel (.xlsx) a PDF mediante un formulario web. El sistema está compuesto por dos partes principales:

1. **Frontend (HTML, CSS y JavaScript)**: Un formulario que permite al usuario cargar un archivo Excel y recibir el archivo PDF convertido como respuesta.
2. **Backend (PHP)**: El servidor procesa el archivo Excel y realiza la conversión a PDF utilizando una clase especializada.

---

### Archivos involucrados

1. **`process.php`**: Este archivo PHP maneja la lógica de la conversión del archivo Excel a PDF.
2. **`index.html`**: El archivo HTML que contiene el formulario para que el usuario cargue el archivo Excel.

---

### Estructura del proyecto

```
/project-root
│
├── /src
│   ├── index.html                (Formulario de carga y respuesta)
│   ├── process.php               (Lógica de conversión de archivos)
│   └── ExcelToPdfConversor.php   (Clase que convierte Excel a PDF)
├── /vendor                       (Dependencias de Composer)
└── composer.json                 (Archivo de dependencias de PHP)
```

---

### Flujo de trabajo

#### 1. **Frontend - index.html**

El formulario HTML permite al usuario seleccionar un archivo Excel (.xlsx) y cargarlo en el servidor. 

- **Formulario**:
  - El usuario selecciona un archivo `.xlsx`.
  - Al hacer clic en "Convertir a PDF", se envía el archivo al servidor usando el método `POST`.
  
- **Interacción con el servidor**:
  - El formulario usa `JavaScript` para realizar la solicitud `POST` con el archivo adjunto.
  - La respuesta del servidor se muestra debajo del formulario, mostrando un mensaje de éxito o error.

#### 2. **Backend - process.php**

- **Recepción del archivo**:
  - El archivo se recibe mediante `$_FILES['excelFile']`.
  - Se verifica que el archivo tenga la extensión `.xlsx`.
  
- **Conversión a PDF**:
  - Si la extensión es válida, el archivo se pasa a la clase `ExcelToPdfConversor` para la conversión.
  - Si la conversión es exitosa, el servidor devuelve una URL para descargar el archivo PDF generado.
  - Si ocurre un error durante la conversión (por ejemplo, problemas con el archivo Excel), se devuelve un mensaje de error.

- **Manejo de errores**:
  - Si no se ha cargado un archivo o si hay un error al subirlo, se envía un mensaje de error.
  - Si el archivo no tiene la extensión `.xlsx`, se informa al usuario.

---

### Archivos de código

#### **process.php**

```php
<?php

namespace src;

use Exception;

require_once '../vendor/autoload.php';
require './ExcelToPdfConversor.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
        $excelFile = $_FILES['excelFile']['tmp_name']; // El archivo cargado
        $excelFileName = $_FILES['excelFile']['name']; // El nombre original del archivo
        $fileExtension = pathinfo($excelFileName, PATHINFO_EXTENSION);

        // Verificar que el archivo tenga la extensión .xlsx
        if ($fileExtension !== 'xlsx') {
            print_r(json_encode(['success' => false, 'message' => 'Por favor, sube un archivo Excel válido (.xlsx).']));
            exit;
        }

        $outputPdf = 'converted_' . time() . '.pdf'; // Nombre de salida del archivo PDF

        $converter = new ExcelToPdfConversor();
        
        try {
            // Llamar al método que convierte el archivo Excel a PDF
            $converter->excelToPdf($excelFile, $outputPdf);

            // Si la conversión fue exitosa, enviar la URL del archivo PDF
            print_r(json_encode(['success' => true, 'fileUrl' => $outputPdf]));
        } catch (Exception $e) {
            // Si hubo un error en la conversión, devolver el error
            print_r(json_encode(['success' => false, 'message' => 'Error en la conversión: ' . $e->getMessage()]));
        }
    } else {
        print_r(json_encode(['success' => false, 'message' => 'No se ha subido ningún archivo o ocurrió un error al subirlo.']));
    }
}
?>
```

#### **index.html**

```html
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertidor de Excel a PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }

        h1 {
            color: #333;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            max-width: 400px;
            background-color: #f9f9f9;
        }

        input[type="file"] {
            margin: 10px 0;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success,
        .error {
            margin: 20px auto;
            padding: 10px;
            max-width: 400px;
            border-radius: 5px;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <h1>Convertidor de Excel a PDF</h1>
    <form id="uploadForm" enctype="multipart/form-data" method="POST">
        <label for="excelFile">Selecciona un archivo Excel (.xlsx):</label><br><br>
        <input type="file" name="excelFile" id="excelFile" accept=".xlsx" required><br><br>
        <button type="submit">Convertir a PDF</button>
    </form>
    <div id="response"></div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const responseDiv = document.getElementById('response');
            responseDiv.innerHTML = '';

            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    responseDiv.innerHTML = `<div class="success">
                        Archivo convertido exitosamente: 
                        <a href="${result.fileUrl}" target="_blank">Descargar PDF</a>
                    </div>`;
                } else {
                    responseDiv.innerHTML = `<div class="error">Error: ${result.message}</div>`;
                }
            } catch (error) {
                responseDiv.innerHTML = `<div class="error">Error al procesar el archivo: ${error.message}</div>`;
            }
        });
    </script>
</body>

</html>
```

---

### Dependencias

Este proyecto utiliza las siguientes dependencias:

- **composer**: Se requiere Composer para gestionar las dependencias de PHP.
  
---

### Instrucciones de instalación

1. Instalar las dependencias del proyecto con Composer:
   ```bash
   composer install
   ```

2. Colocar el archivo Excel `.xlsx` que deseas convertir en el formulario web.

3. Al enviar el formulario, el servidor procesará el archivo y lo convertirá a PDF.

4. El enlace de descarga del archivo PDF convertido se proporcionará en la interfaz web.

---

### Consideraciones de seguridad

- Asegúrese de que el archivo cargado sea de tipo `.xlsx` para evitar que otros tipos de archivo sean procesados.
- Valide el tamaño y contenido del archivo para evitar posibles riesgos de seguridad.