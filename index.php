<?php
if (file_exists(__DIR__ . '/install.lock')) {
    die("您进错地方了");
} else {
    header('location: web/shoproot.php?r=install');
}
