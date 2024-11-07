<?php

// Habilitar CORS
header("Access-Control-Allow-Origin: *"); // Permitir todos los orígenes (puedes especificar un dominio si lo prefieres)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos


// "importacion" el archivo del repositorio de telefonos
require_once "../repositories/CellPhoneRepository.php";

// "importacion" el archivo del repositorio de contactos
require_once "../repositories/ContactRepository.php";

// "importacion" el archivo del modelo de telefono.
require_once "../models/cellPhoneModel.php";

// Se determina el método de la solicitud HTTP y se maneja según el caso.
switch ($_SERVER['REQUEST_METHOD']) {

    // Manejo de la solicitud GET para obtener telefonos.
    case 'GET':
        // Si se proporciona un ID, se obtiene un telefono específico.
        if (isset($_GET['id'])) {
            $cellPhone = CellPhoneRepository::getOne($_GET['id']);
            if ($cellPhone) {
                // Se devuelve el telefono encontrado
                echo json_encode($cellPhone);
                http_response_code(200);
            } else {
                // Si no se encuentra el telefono con la id ingresada, se devuelve un error 404.
                http_response_code(404);
                echo json_encode(['error' => 'Contacto no encontrado']);
            }
        } elseif (isset($_GET['contact_id'])) {
            // Si se proporciona un contact_id, se obtienen todos los telefonos asociados a ese contacto
            $cellPhones = CellPhoneRepository::getAllByContactId($_GET['contact_id']);
            echo json_encode($cellPhones);
            http_response_code(200);
        } else {
            // Si no se proporciona ID ni contact_id, se obtienen todos los telefonos
            $cellPhones = CellPhoneRepository::getAll();
            echo json_encode($cellPhones);
            http_response_code(200);
        }
        break;

    // Manejo de la solicitud POST para crear un nuevo telefono
    case 'POST':

        // Se obtienen los datos del cuerpo de la solicitud.
        $data = json_decode(file_get_contents('php://input'), true);

        // Se verifica si los datos existen y son válidos.
        if ($data && isset($data['cellphoneNumber'], $data['contact_id'])) {

            $cellPhone = new CellPhoneModel($data['cellphoneNumber'], $data['contact_id']);

            // Se valida que el nombre tenga mas de 6 caracteres
            if (strlen($cellPhone->getCellphoneNumber()) <= 6) {
                http_response_code(400);
                echo json_encode(['error' => 'El telefono debe tener mas de 6 numeros']);
                exit();
            }

            // Se valida que el numero de telefono ingresado no exista previamente en la base de datos
            if (CellPhoneRepository::cellPhoneNumberExists($cellPhone->getCellphoneNumber())) {
                http_response_code(409);
                echo json_encode(['error' => 'Ya existe un telefono con este numero']);
                exit();
            }

            if (!ContactRepository::getOne($cellPhone->getContact_id())) {
                http_response_code(404);
                echo json_encode(['error' => 'No existe un contacto con esta id']);
                exit();
            }


            // Se intenta crear el nuevo telefono
            if (CellPhoneRepository::insert($cellPhone->getCellphoneNumber(), $cellPhone->getContact_id())) {
                http_response_code(201);
                echo json_encode(['message' => 'Nuevo telefono creado con exito']);
            } else {

                // Si hay un error al crear el telefono, se devuelve un error
                http_response_code(500);
                echo json_encode(['error' => 'Sucedio un error al crear el telefono']);
            }
        } else {
            http_response_code(422);
            echo json_encode(['error' => 'Nombre de atributos invalidos: cellphoneNumber | contact_id']);
        }
        break;




    // Manejo de la solicitud PUT para actualizar un telefono existente.
    case 'PUT':

        // Se valida que en la URL se ingrese la id del telefono
        if (isset($_GET['id'])) {
            $cellPhone = CellPhoneRepository::getOne($_GET['id']);

            // Se valida que exista un telefono con la id ingresada
            if ($cellPhone) {
                // Se obtienen los datos del cuerpo de la solicitud.
                $data = json_decode(file_get_contents('php://input'), true);

                // Se verifica si los datos existen y son válidos.
                if ($data && isset($data['cellphoneNumber'], $data['contact_id'])) {

                    $updatedCellPhone = new CellPhoneModel($data['cellphoneNumber'], $data['contact_id']);

                    // Se valida que el nombre tenga mas de 6 caracteres
                    if (strlen($updatedCellPhone->getCellphoneNumber()) <= 6) {
                        http_response_code(400);
                        echo json_encode(['error' => 'El telefono debe tener mas de 6 numeros']);
                        exit();
                    }

                    // Se valida que el numero de telefono ingresado no exista previamente en la base de datos
                    if (CellPhoneRepository::cellPhoneNumberExists($updatedCellPhone->getCellphoneNumber())) {
                        http_response_code(409);
                        echo json_encode(['error' => 'Ya existe un telefono con este numero']);
                        exit();
                    }

                    if (!ContactRepository::getOne($updatedCellPhone->getContact_id())) {
                        http_response_code(404);
                        echo json_encode(['error' => 'No existe un contacto con esta id']);
                        exit();
                    }


                    // Se intenta actualizar el registro
                    if (CellPhoneRepository::update($_GET['id'], $updatedCellPhone->getCellphoneNumber(), $updatedCellPhone->getContact_id())) {
                        echo json_encode(['message' => 'Telefono editado con exito']);
                        http_response_code(200);
                    } else {
                        http_response_code(500);
                    }
                } else {
                    // Si los atributos ingresados son invalidos, se retorna un error 404
                    http_response_code(404);
                    echo json_encode(['error' => 'Nombre de atributos invalidos: cellphoneNumber | contact_id']);
                }
            } else {
                // Si no existe un telefono con la id ingresada, se retorna un error 404
                http_response_code(404);
                echo json_encode(['error' => 'Telefono no encontrado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Se debe ingresar la id del telefono']);
        }
        break;

    // Manejo de la solicitud DELETE para eliminar un telefono.
    case 'DELETE':

        // Se valida que en la URL se ingrese la id del telefono
        if (isset($_GET['id'])) {
            $cellPhone = CellPhoneRepository::getOne($_GET['id']);

            // Se valida que exista un telefono con la id ingresada
            if ($cellPhone) {

                // Se intenta eliminar el registro
                if (CellPhoneRepository::delete($_GET['id'])) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Telefono eliminado correctamente']);
                } else {
                    // Si hay un error al eliminar el telefono, se devuelve un error 500.
                    http_response_code(500);
                }
            } else {

                // Si el telefono no se encuentra, se devuelve un error 404.
                http_response_code(404);
                echo json_encode(['error' => 'Telefono no encontrado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Se debe ingresar la id del telefono']);
        }

        break;
}
