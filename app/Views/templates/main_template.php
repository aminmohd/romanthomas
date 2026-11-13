<?php
echo view('templates/header');
echo view('templates/navigation');
$count = count($main_content);
for ($i = 0; $i < $count; $i++) {
    echo view($main_content[$i]);
}
echo view('templates/footer');
