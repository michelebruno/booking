<?php

use App\User; 

 
$u = auth()->attempt(['email' => 'bm.michelebruno@gmail.com', 'password' => 'password']);

var_dump(auth()->user());