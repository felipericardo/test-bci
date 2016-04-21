<?php

// $plates = [
//     'AYA9019',
//     'CEK7969',
//     'OAY7246'
// ];

class SinespThread extends Thread {
    public function __construct($plate) {
        $this->plate = $plate;
    }

    public function run() {
        $resPython = shell_exec("python sinesp.py " . $this->plate);
        $this->data = json_decode($resPython, true);
        // if ($this->plate == 'CEK7969' || $this->plate == 'OAY7246') {
        //     sleep(3);
        // }
        // $this->data = json_decode('{"plate": "' . $this->plate . '", "message": "Message #' . rand(1,5) . '"}', true);
    }
}

if (isset($_GET['plates']) || !empty($_GET['plates'])) {
    $start = microtime(true);

    $plates = explode(',', $_GET['plates']);
    $threads = [];
    foreach ($plates as $p) {
        $threads[] = $thread = new SinespThread($p);
        $thread->start();
    }

    $res = [
        'status' => 'success',
        'plates' => []
    ];
    foreach ($threads as $t) {
        $t->join();
        $res['plates'][] = $t->data;
    }
    $res['time'] = number_format(microtime(true) - $start, 3, '.', '');
} else {
    $res = [
        'status' => 'error',
        'message' => 'Informe pelo menos uma placa'
    ];
}

echo json_encode($res);
