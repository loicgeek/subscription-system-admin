<?php

return [
    'enable_api_routes' => true,
    'enable_web_routes' => false,
    'api_prefix' => 'api/subscription',
    'web_prefix' => 'subscription',
    'api_middleware' => ['api'],
    'web_middleware' => ['web', 'auth'],
];