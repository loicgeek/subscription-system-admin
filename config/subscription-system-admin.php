<?php

return [
    'enable_api_routes' => true,
    'enable_web_routes' => false,
    'api_prefix' => 'api/ntech-subscription',
    'web_prefix' => 'ntech-subscription',
    'api_middleware' => ['api'],
    'web_middleware' => ['web', 'auth'],
];