<?php
    $this->load->view('elements/header');
    $this->load->view('elements/top_header');
    $this->load->view('elements/sidebar_left');
    $this->load->view('elements/content_writer', $page);
    $this->load->view('elements/control_sidebar');
    $this->load->view('elements/footer');
?>