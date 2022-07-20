<?php
/**
 * Api routes
 */

if (!defined('ABSPATH')) {
    die('No direct access.');
}

use Extendify\ApiRouter;
use Extendify\Library\Controllers\AuthController;
use Extendify\Library\Controllers\MetaController;
use Extendify\Library\Controllers\PingController;
use Extendify\Library\Controllers\UserController;
use Extendify\Onboarding\Controllers\WPController;
use Extendify\Library\Controllers\PluginController;
use Extendify\Onboarding\Controllers\DataController;
use Extendify\Library\Controllers\TaxonomyController;
use Extendify\Library\Controllers\TemplateController;
use Extendify\Onboarding\Controllers\LibraryController;
use Extendify\Library\Controllers\SiteSettingsController;

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
        ApiRouter::post('/site-settings/options', [SiteSettingsController::class, 'updateOption']);

        // Onboarding.
        ApiRouter::post('/onboarding/options', [WPController::class, 'updateOption']);
        ApiRouter::get('/onboarding/options', [WPController::class, 'getOption']);
        ApiRouter::post('/onboarding/parse-theme-json', [WPController::class, 'parseThemeJson']);

        ApiRouter::get('/onboarding/site-types', [DataController::class, 'getSiteTypes']);
        ApiRouter::get('/onboarding/styles', [DataController::class, 'getStyles']);
        ApiRouter::get('/onboarding/layout-types', [DataController::class, 'getLayoutTypes']);
        ApiRouter::get('/onboarding/goals', [DataController::class, 'getGoals']);
        ApiRouter::get('/onboarding/suggested-plugins', [DataController::class, 'getSuggestedPlugins']);
        ApiRouter::get('/onboarding/template', [DataController::class, 'getTemplate']);
        ApiRouter::post('/onboarding/create-order', [DataController::class, 'createOrder']);

        // TODO: consider merging this route into the library.
        ApiRouter::post('/library/site-type', [LibraryController::class, 'updateSiteType']);
    }
);
