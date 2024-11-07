<?php

// Definición de la clase CellPhoneModel
class CellPhoneModel {
    
    // Constructor de la clase, inicializa los atributos de la clase
    public function __construct(
        private $cellphoneNumber, 
        private $contact_id        
    ) {}

    // Método para obtener el número de celular
    public function getCellphoneNumber() {
        return $this->cellphoneNumber;
    }

    // Método para obtener el ID del contacto asociado
    public function getContact_id() {
        return $this->contact_id;
    }

    // Método para modificar número de celular
    public function setCellphoneNumber($cellphoneNumber) {
        $this->cellphoneNumber = $cellphoneNumber;
    }

    // Método para modificar el ID del contacto asociado
    public function setContactId($contact_id) {
        $this->contact_id = $contact_id;
    }
}
?>
