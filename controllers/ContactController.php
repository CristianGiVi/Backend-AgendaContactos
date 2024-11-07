<?php

// Habilitar CORS
header("Access-Control-Allow-Origin: *"); // Permitir todos los orígenes (puedes especificar un dominio si lo prefieres)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos


// "importacion" el archivo del repositorio de contactos
require_once "../repositories/ContactRepository.php";

// "importacion" el archivo del modelo de contacto.
require_once "../models/ContactModel.php";

// Se determina el método de la solicitud HTTP y se maneja según el caso.
switch ($_SERVER['REQUEST_METHOD']) {

        // Manejo de la solicitud GET para obtener contactos.
    case 'GET':

        // Si se proporciona un ID, se obtiene un contacto específico, de lo contrario se obtienen todos los contactos
        if (isset($_GET['id'])) {
            $contact = ContactRepository::getOne($_GET['id']);
            if ($contact) {

                // Se devuelve el contacto encontrado
                echo json_encode($contact);
                http_response_code(200);
            } else {

                // Si no se encuentra el contacto con la id ingresada, se devuelve un error 404.
                http_response_code(404);
                echo json_encode(['error' => 'Contacto no encontrado']);
            }
        } else {

            // se obtienen todos los contactos
            $contacts = ContactRepository::getAll();
            echo json_encode($contacts);
            http_response_code(200);
        }
        break;

        // Manejo de la solicitud POST para crear un nuevo contacto.
    case 'POST':

        // Se obtienen los datos del cuerpo de la solicitud.
        $data = json_decode(file_get_contents('php://input'), true);

        // Se verifica si los datos existen y son válidos.
        if ($data && isset($data['name'], $data['idNumber'])) {

            $contact = new ContactModel($data['name'], $data['idNumber']);

            // Se valida que el nombre tenga mas de 2 caracteres
            if (strlen($contact->getName()) <= 2) {
                http_response_code(400);
                echo json_encode(['error' => 'El nombre debe tener mas de 2 caracteres']);
                exit();
            }

            // Se valida que la cedula tenga mas de 2 caracteres
            if (strlen($contact->getIdNumber()) <= 2) {
                http_response_code(400);
                echo json_encode(['error' => 'La debe tener mas de 2 caracteres']);
                exit();
            }

            // Se valida que la cedula ingresada no exista previamente en la base de datos
            if (ContactRepository::idNumberExists($contact->getIdNumber())) {
                http_response_code(409);
                echo json_encode(['error' => 'Ya existe un contacto con esta cedula']);
                exit();
            }

            // Se intenta crear el nuevo contacto
            if (ContactRepository::insert($contact->getName(), $contact->getIdNumber())) {
                http_response_code(201);
                echo json_encode(['message' => 'Nuevo contacto creado con exito']);
            } else {

                // Si hay un error al crear el contacto, se devuelve un error
                http_response_code(500);
                echo json_encode(['error' => 'Sucedio un error al crear el contacto']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Nombre de atributos invalidos: name | idNumber']);
        }
        break;

        // Manejo de la solicitud PUT para actualizar un contacto existente.
    case 'PUT':

        // Se valida que en la URL se ingrese la id del contacto
        if (isset($_GET['id'])) {
            $contact = ContactRepository::getOne($_GET['id']);

            // Se valida que exista un contacto con la id ingresada
            if ($contact) {
                // Se obtienen los datos del cuerpo de la solicitud.
                $data = json_decode(file_get_contents('php://input'), true);

                // Se verifica si los datos existen y son válidos.
                if ($data && isset($data['name'], $data['idNumber'])) {

                    $newContact = new ContactModel($data['name'], $data['idNumber']);

                    // Se comprueba si el nombre ingresado tiene mas de dos caracteres
                    if (strlen($newContact->getName()) <= 2) {
                        http_response_code(400);
                        echo json_encode(['error' => 'El nombre debe tener mas de 2 caracteres']);
                        exit();
                    }

                    // Se valida que la cedula ingresada no exista previamente en la base de datos
                    if ($contact[0]['cedula'] != $newContact->getIdNumber()) {
                        if (ContactRepository::idNumberExists($newContact->getIdNumber())) {
                            http_response_code(409);
                            echo json_encode(['error' => 'Ya existe un contacto con esta cedula']);
                            exit();
                        }
                    }

                    // Se intenta actualizar el registro
                    if (ContactRepository::update($_GET['id'], $newContact->getName(), $newContact->getIdNumber())) {
                        echo json_encode(['message' => 'Contacto editado con exito']);
                        http_response_code(200);
                    } else {
                        http_response_code(500);
                    }
                } else {
                    // Si los atributos ingresados son invalidos, se retorna un error 404
                    http_response_code(400);
                    echo json_encode(['error' => 'Nombre de atributos invalidos: name | idNumber']);
                }
            } else {
                // Si no existe un contacto con la id ingresada, se retorna un error 404
                http_response_code(404);
                echo json_encode(['error' => 'Contacto no encontrado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Se debe ingresar la id del contacto']);
        }
        break;

        // Manejo de la solicitud DELETE para eliminar un contacto.
    case 'DELETE':

        // Se valida que en la URL se ingrese la id del contacto
        if (isset($_GET['id'])) {
            $contact = ContactRepository::getOne($_GET['id']);

            // Se valida que exista un contacto con la id ingresada
            if ($contact) {

                // Se intenta eliminar el registro
                if (ContactRepository::delete($_GET['id'])) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Contacto eliminado correctamente']);
                } else {
                    // Si hay un error al eliminar el contacto, se devuelve un error 500.
                    http_response_code(500);
                }
            } else {

                // Si el contacto no se encuentra, se devuelve un error 404.
                http_response_code(404);
                echo json_encode(['error' => 'Contacto no encontrado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Se debe ingresar la id del contacto']);
        }

        break;
}
