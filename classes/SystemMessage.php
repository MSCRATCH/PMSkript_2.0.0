<?php

//SystemMessage.php [Class to render messages.]
//pathologicalplay [MMXXV]

class SystemMessage {

private $message_text;
private $message_wrapper;
private $message_url;
private $message_button_text;


function __construct($message_text, $message_wrapper = null, $message_url = null, $message_button_text = null)  {
$this->message_text = $message_text;
$this->message_wrapper = $message_wrapper;
$this->message_url = $message_url;
$this->message_button_text = $message_button_text;
}

//Render message.

public function render_message() {
$output = '';
$output .= '<div class="'. sanitize($this->message_wrapper). '">';
$output .= '<span class="msg_span">';
$output .= "System message";
$output .= '</span>';
$output .= '<div class="msg_default">';
$output .= '<p>'. sanitize($this->message_text). '</p>';
if ($this->message_url && $this->message_button_text) {
$output .= '<a href="'. sanitize($this->message_url). '">'. '<button class="msg_btn">'. sanitize($this->message_button_text). '</button>'. '</a>';
}
$output .= '</div>';
$output .= '</div>';
return $output;
}

//Render message.

}
