<?php
/**
 * Api routes
 */

if (!defined('ABSPATH')) {
    die('No direct access.');
}

use Extendify\Library\ApiRouter;
use Extendify\Library\Controllers\AuthController;
use Extendify\Library\Controllers\MetaController;
use Extendify\Library\Controllers\PingController;
use Extendify\Library\Controllers\UserController;
use Extendify\Library\Controllers\PluginController;
use Extendify\Library\Controllers\SiteSettingsController;
use Extendify\Library\Controllers\TaxonomyController;
use Extendify\Library\Controllers\TemplateController;

\add_action(
    'rest_api_init',
    function () {
        ApiRouter::get('/active-plugins', [PluginController::class, 'active']);
        ApiRouter::get('/plugins', [PluginController::class, 'index']);
        ApiRouter::post('/plugins', [PluginController::class, 'install']);

        ApiRouter::get('/taxonomies', [TaxonomyController::class, 'index']);

        ApiRouter::post('/templates', [TemplateController::class, 'index']);
        ApiRouter::post('/templates/(?P<template_id>[a-zA-Z0-9-]+)', [TemplateController::class, 'ping']);

        ApiRouter::get('/user', [UserController::class, 'show']);
        ApiRouter::post('/user', [UserController::class, 'store']);
        ApiRouter::post('/clear-user', [UserController::class, 'delete']);
        ApiRouter::get('/user-meta', [UserController::class, 'meta']);
        ApiRouter::get('/max-free-imports', [UserController::class, 'maxImports']);

        ApiRouter::post('/register-mailing-list', [UserController::class, 'mailingList']);

        ApiRouter::post('/register', [AuthController::class, 'register']);
        ApiRouter::post('/login', [AuthController::class, 'login']);

        ApiRouter::get('/meta-data', [MetaController::class, 'getAll']);
        ApiRouter::post('/simple-ping', [PingController::class, 'ping']);

        ApiRouter::get('/site-settings', [SiteSettingsController::class, 'show']);
        ApiRouter::post('/site-settings', [SiteSettingsController::class, 'store']);
    }
);
