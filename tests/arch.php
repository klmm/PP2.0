<?php
    $old_path = getcwd();
    chdir($_SERVER['DOCUMENT_ROOT'] . '/admin/scripts/');

    $message=shell_exec('./archive_classements.sh ' . $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/2015/tour-de-france/classements etapeYZ');
    print_r($message);

    chdir($old_path);
?> 