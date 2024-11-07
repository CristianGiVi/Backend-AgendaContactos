<?php 

require_once "../connection/Connection.php";

// Clase CellPhoneRepository que contiene métodos para interactuar con la base de datos de correos
class EmailRepository
{

    /**
     * Obtiene todos los correos de la base de datos.
     *
     * @return array Un array de correos
     */
    public static function getAll() {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM correos";

        // Se ejecuta la consulta.
        $response = $db->query($query);
        $data = [];

        // Se verifica si la consulta devolvió resultados.
        if ($response->num_rows) {

            // Se itera sobre cada fila del resultado y se almacena cada correo en la variable $data
            while ($row = $response->fetch_assoc()) {
                $data[] = [
                    'email' => $row['correo_electronico'],
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
     * Obtiene un correo específico por su ID.
     *
     * @param int $id El ID del correo.
     * @return array Un array con el correo
     */
    public static function getOne($id) {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM correos WHERE id=$id";

        // Se ejecuta la consulta.
        $response = $db->query($query);
        $data = [];

        // Se verifica si la consulta devolvió resultados.
        if ($response->num_rows) {

            // Se itera sobre cada fila del resultado y se almacena el correo en la variable $data
            while ($row = $response->fetch_assoc()) {
                $data[] = [
                    'id' => $row['id'],
                    'email' => $row['correo_electronico'],
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
     * Inserta un nuevo correo en la base de datos.
     *
     * @param string $email la direccion de correo
     * @param int $contact_id el id del contacto al que pertenece el correo
     * @return bool TRUE si la inserción fue exitosa, FALSE en caso contrario.
     */
    public static function insert($email, $contact_id) {
        
        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "INSERT INTO correos (correo_electronico, contacto_id) 
                VALUES ('" . $email . "', '" . $contact_id . "')";

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
     * Actualiza un correo existente en la base de datos.
     *
     * @param int $id El ID del correo a actualizar
     * @param string $email la direccion de correo
     * @param int $contact_id el id del contacto al que pertenece el correo
     * @return bool TRUE si la actualización fue exitosa, FALSE en caso contrario.
     */
    public static function update($id, $email, $contact_id) {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "UPDATE correos SET
        correo_electronico='" . $email . "' , contacto_id='" . $contact_id . "' WHERE id= $id";

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
     * Elimina un correo de la base de datos por su ID.
     *
     * @param int $id El ID del correo a eliminar.
     * @return bool TRUE si la eliminación fue exitosa, FALSE en caso contrario.
     */
    public static function delete($id) {
        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "DELETE FROM correos WHERE id=$id";
        
        // Se ejecuta la consulta.
        $db->query($query);
        if ($db->affected_rows) {
            return TRUE; // Devuelve TRUE si la eliminación fue exitosa.
        }
        return FALSE; // Devuelve FALSE si la eliminación falló.
    }


    /**
     * Verifica si la direccion ya existe en la base de datos.
     *
     * @param string $email la direccion de correo
     * @return bool TRUE si la direccion de correo existe, FALSE en caso contrario.
     */
    public static function emailExists($email) {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM correos WHERE correo_electronico='$email'";

        // Se ejecuta la consulta.
        $response = $db->query($query);

        // Devuelve TRUE si se encontró al menos una de direccion de correo, FALSE en caso contrario.
        return $response->num_rows > 0;
    }

    
    /**
     * Obtiene todos los emails asociados a un contacto específico por su ID.
     *
     * @param int $contact_id El ID del contacto.
     * @return array Un array con los emails asociados al contacto.
     */
    public static function getAllByContactId($contact_id)
    {
        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM correos WHERE contacto_id=$contact_id";

        // Se ejecuta la consulta.
        $response = $db->query($query);
        $data = [];

        // Se verifica si la consulta devolvió resultados.
        if ($response->num_rows) {
            // Se itera sobre cada fila del resultado y se almacena el teléfono en la variable $data
            while ($row = $response->fetch_assoc()) {
                $data[] = [
                    'id' => $row['id'],
                    'email' => $row['correo_electronico'],
                    'contact_id' => $row['contacto_id']
                ];
            } // end while

            // Se devuelve el array con los correos
            return $data;
        } // end if

        // Si no hay correos, se devuelve un array vacío.
        return $data;
    }

}


?>