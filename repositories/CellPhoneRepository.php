<?php

require_once "../connection/Connection.php";

// Clase CellPhoneRepository que contiene métodos para interactuar con la base de datos de telefonos
class CellPhoneRepository
{

    /**
     * Obtiene todos los telefonos de la base de datos.
     *
     * @return array Un array de contactos
     */
    public static function getAll()
    {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM telefonos";

        // Se ejecuta la consulta.
        $response = $db->query($query);
        $data = [];

        // Se verifica si la consulta devolvió resultados.
        if ($response->num_rows) {

            // Se itera sobre cada fila del resultado y se almacena cada telefono en la variable $data
            while ($row = $response->fetch_assoc()) {
                $data[] = [
                    'id' => $row['id'],
                    'cellphoneNumber' => $row['numero_telefonico'],
                    'contact_id' => $row['contacto_id']
                ];
            } // end while

            // Se devuelve el array con todos los telefonos.
            return $data;
        } // end if

        // Si no hay telefonos, se devuelve un array vacío.
        return $data;
    }


    /**
     * Obtiene un telefono específico por su ID.
     *
     * @param int $id El ID del telefono.
     * @return array Un array con el telefono
     */
    public static function getOne($id)
    {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM telefonos WHERE id=$id";

        // Se ejecuta la consulta.
        $response = $db->query($query);
        $data = [];

        // Se verifica si la consulta devolvió resultados.
        if ($response->num_rows) {

            // Se itera sobre cada fila del resultado y se almacena el telefono en la variable $data
            while ($row = $response->fetch_assoc()) {
                $data[] = [
                    'id' => $row['id'],
                    'cellphoneNumber' => $row['numero_telefonico'],
                    'contact_id' => $row['contacto_id']
                ];
            } // end while

            // Se devuelve el array con el telefono
            return $data;
        } // end if

        // Si no hay telefono, se devuelve un array vacío.
        return $data;
    }



    /**
     * Inserta un nuevo telefono en la base de datos.
     *
     * @param string $cellphoneNumber el numero del telefono
     * @param int $contact_id el id del contacto al que pertenece el telefono
     * @return bool TRUE si la inserción fue exitosa, FALSE en caso contrario.
     */
    public static function insert($cellphoneNumber, $contact_id)
    {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "INSERT INTO telefonos (numero_telefonico, contacto_id) 
                VALUES ('" . $cellphoneNumber . "', '" . $contact_id . "')";

        // Se ejecuta la consulta.
        $db->query($query);
        if ($db->affected_rows) {

            // Devuelve TRUE si la inserción fue exitosa.
            return TRUE;
        } // end if

        // Devuelve FALSE si la inserción falló.
        return FALSE;
    }


    /**
     * Actualiza un telefono existente en la base de datos.
     *
     * @param int $id El ID del telefono a actualizar
     * @param string $cellphoneNumber el numero del telefono
     * @param int $contact_id el id del contacto al que pertenece el telefono
     * @return bool TRUE si la actualización fue exitosa, FALSE en caso contrario.
     */
    public static function update($id, $cellphoneNumber, $contact_id)
    {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "UPDATE telefonos SET
        numero_telefonico='" . $cellphoneNumber . "' , contacto_id='" . $contact_id . "' WHERE id= $id";

        // Se ejecuta la consulta.
        $db->query($query);
        if ($db->affected_rows) {

            // Devuelve TRUE si la actualización fue exitosa.
            return TRUE;
        }

        // Devuelve FALSE si la actualización falló.
        return FALSE;
    }

    /**
     * Elimina un telefono de la base de datos por su ID.
     *
     * @param int $id El ID del telefono a eliminar.
     * @return bool TRUE si la eliminación fue exitosa, FALSE en caso contrario.
     */
    public static function delete($id)
    {
        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "DELETE FROM telefonos WHERE id=$id";

        // Se ejecuta la consulta.
        $db->query($query);
        if ($db->affected_rows) {
            return TRUE; // Devuelve TRUE si la eliminación fue exitosa.
        }
        return FALSE; // Devuelve FALSE si la eliminación falló.
    }

    /**
     * Verifica si un numero de celular ya existe en la base de datos.
     *
     * @param string $cellphoneNumber el numero del telefono
     * @return bool TRUE si el número del telefono existe, FALSE en caso contrario.
     */
    public static function cellPhoneNumberExists($cellphoneNumber)
    {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM telefonos WHERE numero_telefonico='$cellphoneNumber'";

        // Se ejecuta la consulta.
        $response = $db->query($query);

        // Devuelve TRUE si se encontró al menos un contacto, FALSE en caso contrario.
        return $response->num_rows > 0;
    }

    /**
     * Obtiene todos los teléfonos asociados a un contacto específico por su ID.
     *
     * @param int $contact_id El ID del contacto.
     * @return array Un array con los teléfonos asociados al contacto.
     */
    public static function getAllByContactId($contact_id)
    {
        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM telefonos WHERE contacto_id=$contact_id";

        // Se ejecuta la consulta.
        $response = $db->query($query);
        $data = [];

        // Se verifica si la consulta devolvió resultados.
        if ($response->num_rows) {
            // Se itera sobre cada fila del resultado y se almacena el teléfono en la variable $data
            while ($row = $response->fetch_assoc()) {
                $data[] = [
                    'id' => $row['id'],
                    'cellphoneNumber' => $row['numero_telefonico'],
                    'contact_id' => $row['contacto_id']
                ];
            } // end while

            // Se devuelve el array con los teléfonos
            return $data;
        } // end if

        // Si no hay teléfonos, se devuelve un array vacío.
        return $data;
    }
}
