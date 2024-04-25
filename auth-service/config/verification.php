<?php

return [
    'attempts' => env('VERIFICATION_PHONE_ATTEMPTS', 3),
    'decay_time' => env('VERIFICATION_PHONE_DECAY_TIME', 5),
];
