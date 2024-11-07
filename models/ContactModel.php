<?php 

// Definición de la clase ContactModel
class ContactModel {

     // Constructor que inicializa las propiedades de la clase.
    public function __construct(
        private $name,
        private $idNumber
    ){}

    // Método para obtener el nombre del contacto.
    public function getName() {
        return $this->name;
    }

    // Método para obtener el número de identificación del contacto.
    public function getIdNumber() {
        return $this->idNumber;
    }

    // Método para modificar el nombre del contacto.
    public function setName($name){
        $this->name = $name;
    }

    // Método para modificar el número de identificación del contacto.
    public function setIdNumber($idNumber){
        $this->idNumber = $idNumber;
    }
}
?>
