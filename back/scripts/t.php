<?php
$personJSON = '{"name":"Johny Carson","title":"CTO"},{"name":"test","title":"CTO"}';
$persons = json_decode('[' . $personJSON . ']');

// Accédez au premier objet JSON
$name1 = $persons[0]->name;
echo $name1; // Johny Carson

// Accédez au deuxième objet JSON
$name2 = $persons[1]->name;
echo $name2;

