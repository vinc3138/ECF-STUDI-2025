<?php
require_once '../models/Admin.php';

header('Content-Type: application/json');
echo json_encode(Admin::getStats());
