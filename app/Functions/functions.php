<?php


$m = new MongoClient();
$collection = $m->selectCollection('test', 'phpmanual');