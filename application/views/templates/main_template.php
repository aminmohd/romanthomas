<?php
    $this->load->view('templates/header');
    $this->load->view('templates/navigation');
    $count = count($main_content);
    for ($i = 0; $i < $count; $i++) {
        $this->load->view($main_content[$i]);
    }
    $this->load->view('templates/footer');
?>