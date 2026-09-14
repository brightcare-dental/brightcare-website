<?php
require __DIR__ . '/inc/auth.php';
logout();
redirect('/admin/index.php');
