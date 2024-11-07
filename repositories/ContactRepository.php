<?php

// "importacion" el archivo de conexión a la base de datos.
require_once "../connection/Connection.php";

// Clase ContactRepository que contiene métodos para interactuar con la base de datos de contactos.
class ContactRepository
{

    /**
     * Obtiene todos los contactos de la base de datos.
     *
     * @return array Un array de contactos con su nombre y cédula.
     */
    public static function getAll() {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM contactos";

        // Se ejecuta la consulta.
        $response = $db->query($query);
        $data = [];

        // Se verifica si la consulta devolvió resultados.
        if ($response->num_rows) {

            // Se itera sobre cada fila del resultado y se almacena cada contacto en la variable $data
            while ($row = $response->fetch_assoc()) {
                $data[] = [
                    'id' => $row['id'],
                    'name' => $row['nombre_completo'],
                    'cedula' => $row['cedula']
                ];
            } // end while

            // Se devuelve el array con todos los contactos.
            return $data;
        } // end if

        // Si no hay contactos, se devuelve un array vacío.
        return $data;
    }

    /**
     * Obtiene un contacto específico por su ID.
     *
     * @param int $id El ID del contacto.
     * @return array Un array con el nombre y cédula del contacto o un array vacío si no se encuentra.
     */
    public static function getOne($id) {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM contactos WHERE id=$id";

        // Se ejecuta la consulta.
        $response = $db->query($query);
        $data = [];

        // Se verifica si la consulta devolvió resultados.
        if ($response->num_rows) {

            // Se itera sobre cada fila del resultado y se almacena cada contacto en la variable $data
            while ($row = $response->fetch_assoc()) {
                $data[] = [
                    'name' => $row['nombre_completo'],
                    'cedula' => $row['cedula']
                ];
            } // end while

            // Se devuelve el array con el contacto.
            return $data;
        } // end if

        // Si no hay contacto, se devuelve un array vacío.
        return $data;
    }

    /**
     * Inserta un nuevo contacto en la base de datos.
     *
     * @param string $name El nombre completo del contacto.
     * @param string $idNumber la cedula del contacto.
     * @return bool TRUE si la inserción fue exitosa, FALSE en caso contrario.
     */
    public static function insert($name, $idNumber) {
        
        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "INSERT INTO contactos (nombre_completo, cedula) 
                VALUES ('" . $name . "', '" . $idNumber . "')";

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
     * Actualiza un contacto existente en la base de datos.
     *
     * @param int $id El ID del contacto a actualizar.
     * @param string $name El nuevo nombre del contacto.
     * @param string $idNumber la cedula del contacto.
     * @return bool TRUE si la actualización fue exitosa, FALSE en caso contrario.
     */
    public static function update($id, $name, $idNumber) {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "UPDATE contactos SET
        nombre_completo='" . $name . "' , cedula='" . $idNumber . "' WHERE id= $id";

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
     * Elimina un contacto de la base de datos por su ID.
     *
     * @param int $id El ID del contacto a eliminar.
     * @return bool TRUE si la eliminación fue exitosa, FALSE en caso contrario.
     */
    public static function delete($id) {
        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "DELETE FROM contactos WHERE id=$id";
        
        // Se ejecuta la consulta.
        $db->query($query);
        if ($db->affected_rows) {
            return TRUE; // Devuelve TRUE si la eliminación fue exitosa.
        }
        return FALSE; // Devuelve FALSE si la eliminación falló.
    }

    /**
     * Verifica si una cedula ya existe en la base de datos.
     *
     * @param string $idNumber El número de identificación a verificar.
     * @return bool TRUE si el número de identificación existe, FALSE en caso contrario.
     */
    public static function idNumberExists($idNumber) {

        // Se crea una nueva conexión a la base de datos.
        $db = new Connection();
        $query = "SELECT * FROM contactos WHERE cedula='$idNumber'";

        // Se ejecuta la consulta.
        $response = $db->query($query);

        // Devuelve TRUE si se encontró al menos un contacto, FALSE en caso contrario.
        return $response->num_rows > 0;
    }
}
