<?php

// Habilitar CORS
header("Access-Control-Allow-Origin: *"); // Permitir todos los orígenes (puedes especificar un dominio si lo prefieres)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos


// "importacion" el archivo del repositorio de correos
require_once "../repositories/EmailRepository.php";

// "importacion" el archivo del repositorio de contactos
require_once "../repositories/ContactRepository.php";

// "importacion" el archivo del modelo de correos
require_once "../models/emailModel.php";

// Se determina el método de la solicitud HTTP y se maneja según el caso.
switch ($_SERVER['REQUEST_METHOD']) {

       // Manejo de la solicitud GET para obtener correos.
       case 'GET':

        // Si se proporciona un ID, se obtiene un correo específico, de lo contrario se obtienen todos los correo
        if (isset($_GET['id'])) {
            $email = EmailRepository::getOne($_GET['id']);
            if ($email) {

                // Se devuelve el correo encontrado
                echo json_encode($email);
                http_response_code(200);
            } else {
                // Si no se encuentra el correo con la id ingresada, se devuelve un error 404.
                http_response_code(404);
                echo json_encode(['error' => 'Contacto no encontrado']);
            }
        } elseif (isset($_GET['contact_id'])) {
            // Si se proporciona un contact_id, se obtienen todos los correos asociados a ese contacto
            $emails = EmailRepository::getAllByContactId($_GET['contact_id']);
            echo json_encode($emails);
            http_response_code(200);
        } else {

            // se obtienen todos los telefonos
            $emails = EmailRepository::getAll();
            echo json_encode($emails);
            http_response_code(200);
        }
        break;

    // Manejo de la solicitud POST para crear un nuevo correo
    case 'POST':

        // Se obtienen los datos del cuerpo de la solicitud.
        $data = json_decode(file_get_contents('php://input'), true);

        // Se verifica si los datos existen y son válidos.
        if ($data && isset($data['email'], $data['contact_id'])) {

            $newEmail = new EmailModel($data['email'], $data['contact_id']);

            // Se valida que el email tenga mas de 6 caracteres
            if (strlen($newEmail->getEmail() <= 6)) {
                http_response_code(400);
                echo json_encode(['error' => 'El email debe tener mas de 6 numeros']);
                exit();
            }

            // Se valida que la direccion de correo ingresado no exista previamente en la base de datos
            if (EmailRepository::emailExists($newEmail->getEmail())) {
                http_response_code(409);
                echo json_encode(['error' => 'Ya esta siendo usada esta direccion de correo']);
                exit();
            }

            if (!ContactRepository::getOne($newEmail->getContact_id())) {
                http_response_code(404);
                echo json_encode(['error' => 'No existe un contacto con esta id']);
                exit();
            }


            // Se intenta crear el nuevo el correo
            if (EmailRepository::insert($newEmail->getEmail(), $newEmail->getContact_id())) {
                http_response_code(201);
                echo json_encode(['message' => 'Nuevo correo creado con exito']);
            } else {

                // Si hay un error al crear el correo, se devuelve un error
                http_response_code(500);
                echo json_encode(['error' => 'Sucedio un error al crear el correo']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Nombre de atributos invalidos: email | contact_id']);
        }
        break;

    // Manejo de la solicitud PUT para actualizar un correo existente.
    case 'PUT':

        // Se valida que en la URL se ingrese la id del correo
        if (isset($_GET['id'])) {
            $email = EmailRepository::getOne($_GET['id']);

            // Se valida que exista un correo con la id ingresada
            if ($email) {
                // Se obtienen los datos del cuerpo de la solicitud.
                $data = json_decode(file_get_contents('php://input'), true);

                // Se verifica si los datos existen y son válidos.
                if ($data && isset($data['email'], $data['contact_id'])) {

                    $updatedEmail = new EmailModel($data['email'], $data['contact_id']);

                    // Se valida que la direccion de correo tenga mas de 6 caracteres
                    if (strlen($updatedEmail->getEmail()) <= 6) {
                        http_response_code(400);
                        echo json_encode(['error' => 'La direccion de correo debe tener mas de 6 caracteres']);
                        exit();
                    }

                    // Se valida que el la direccion de correo ingresada no exista previamente en la base de datos
                    if (EmailRepository::emailExists($updatedEmail->getEmail())) {
                        http_response_code(409);
                        echo json_encode(['error' => 'Ya esta en uso esta direccion de correo']);
                        exit();
                    }

                    if (!ContactRepository::getOne($updatedEmail->getContact_id())) {
                        http_response_code(404);
                        echo json_encode(['error' => 'No existe un contacto con esta id']);
                        exit();
                    }


                    // Se intenta actualizar el registro
                    if (EmailRepository::update($_GET['id'], $updatedEmail->getEmail(), $updatedEmail->getContact_id())) {
                        echo json_encode(['message' => 'Correo editado con exito']);
                        http_response_code(200);
                    } else {
                        http_response_code(500);
                    }
                } else {
                    // Si los atributos ingresados son invalidos, se retorna un error 404
                    http_response_code(404);
                    echo json_encode(['error' => 'Nombre de atributos invalidos: email | contact_id']);
                }
            } else {
                // Si no existe un correo con la id ingresada, se retorna un error 404
                http_response_code(404);
                echo json_encode(['error' => 'Correo no encontrado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Se debe ingresar la id del correo']);
        }
        break;

    // Manejo de la solicitud DELETE para eliminar un correo.
    case 'DELETE':

        // Se valida que en la URL se ingrese la id del correo
        if (isset($_GET['id'])) {
            $email = EmailRepository::getOne($_GET['id']);

            // Se valida que exista un correo con la id ingresada
            if ($email) {

                // Se intenta eliminar el registro
                if (EmailRepository::delete($_GET['id'])) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Correo eliminado correctamente']);
                } else {
                    // Si hay un error al eliminar el correo, se devuelve un error 500.
                    http_response_code(500);
                }
            } else {

                // Si el correo no se encuentra, se devuelve un error 404.
                http_response_code(404);
                echo json_encode(['error' => 'Correo no encontrado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Se debe ingresar la id del correo']);
        }

        break;

}


?>