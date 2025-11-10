<?php

//ErrorContainer.php [Class to store and output error messages.]
//pathologicalplay [MMXXV]

class ErrorContainer {
private $errors = [];


//Add error.

public function add_error($error) {
$this->errors[] = $error;
}

//Add error.

//Output of errors.

public function get_errors() {
return $this->errors;
}

public function has_errors() {
return ! empty($this->errors);
}

//Output of errors.

}
