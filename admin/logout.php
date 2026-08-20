<?php
require_once __DIR__ . '/../api/auth.php';
header("Location: ../api/auth.php?action=logout");
exit;
