<?php
    session_start();
    session_destroy();
    // header('location: ../index.php');
    echo json_encode(['status' => 'sucesso']);
