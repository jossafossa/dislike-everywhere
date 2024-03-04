<?php

/**
 * Get app configuration
 */
function AppConfig($setting = null)
{
    return MvcConfig('app', $setting);
}

/**
 * Get paths configuration
 */
function PathsConfig($setting = null)
{
    $config = require dirname(__DIR__, 5) . '/config/paths.php';
    return !$setting ? $config : $config[$setting];
}

/**
 * Get an auth configuration
 */
function AuthConfig($setting = null)
{
    return MvcConfig('auth', $setting);
}

/**
 * Get view configuration
 */
function ViewConfig($setting = null)
{
    $config = \Leaf\Core::config('view');
    return !$setting ? $config : $config[$setting] ?? null;
}

/**
 * Get database configuration
 */
function DatabaseConfig($setting = null)
{
    return MvcConfig('database', $setting);
}

/**
 * Get an auth configuration
 */
function CorsConfig($setting = null)
{
    return MvcConfig('cors', $setting);
}

/**
 * Get mail configuration
 */
function MailConfig($setting = null)
{
    return MvcConfig('mail', $setting);
}

/**
 * Get an application configuration
 */
function MvcConfig($appConfig, $setting = null)
{
    $config = \Leaf\Core::config($appConfig);
    return !$setting ? $config : $config[$setting];
}
