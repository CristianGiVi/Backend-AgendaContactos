<?php

// Definición de la clase CellPhoneModel
class EmailModel {
    
    // Constructor de la clase, inicializa los atributos de la clase
    public function __construct(
        private $email, 
        private $contact_id        
    ) {}

    // Método para obtener el email
    public function getEmail() {
        return $this->email;
    }

    // Método para obtener el ID del contacto asociado
    public function getContact_id() {
        return $this->contact_id;
    }

    // Método para modificar el email
    public function setEmail($email) {
        $this->email = $email;
    }

    // Método para modificar el ID del contacto asociado
    public function setContactId($contact_id) {
        $this->contact_id = $contact_id;
    }
}
?>