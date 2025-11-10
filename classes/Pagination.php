<?php

//Pagination.php [Class to create and output pagination.]
//pathologicalplay [MMXXV]

class Pagination {

private $entries_per_page;
private $current_page;
private $total_records;

function __construct($entries_per_page, $current_page, $total_records)  {
$this->entries_per_page = $entries_per_page;
$this->current_page = $current_page;
$this->total_records = $total_records;
}

//Calculate offset.

public function get_offset() {
return ($this->current_page - 1) * $this->entries_per_page;
}

//Calculate offset.

//Calculate number of pages.

public function get_number_of_pages() {
if ($this->total_records === 0) {
return 1;
}
return ceil($this->total_records / $this->entries_per_page);
}

//Calculate number of pages.

//Check if the page number is valid.

public function is_valid_page_number() {
if ($this->total_records === 0) {
$this->current_page = 1;
return true;
}
$number_of_pages = $this->get_number_of_pages();
if ($this->current_page < 1 || $this->current_page > $number_of_pages) {
$this->current_page = 1;
}
return true;
}

//Check if the page number is valid.

//Render pagination.

public function render_pagination($url) {
$number_of_pages = $this->get_number_of_pages();
$output = '';
$separator = strpos($url, '?') !== false ? '&' : '?';
$output .= '<ul class="pagination_ul">';
$prev_page = ($this->current_page > 1) ? $this->current_page - 1 : $this->current_page;
$output .= '<li class="pagination_li">'. '<a class="pagination_link" href="'. sanitize($url). sanitize($separator). 'page='. sanitize($prev_page). '">'. "Prev". '</a>'. '</li>';
$output .= '<li class="pagination_li">'. "You are on page". "&nbsp;". sanitize($this->current_page). "&nbsp;". "of". "&nbsp;". sanitize($number_of_pages). '</li>';
$next_page = ($this->current_page < $number_of_pages) ? $this->current_page + 1 : $this->current_page;
$output .= '<li class="pagination_li">'. '<a class="pagination_link" href="'. sanitize($url).  sanitize($separator). 'page='. sanitize($next_page). '">'. "Next". '</a>'. '</li>';
$output .= '</ul>';

return $output;
}

//Render pagination.

}
