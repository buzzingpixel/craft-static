<?php

declare(strict_types=1);

$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
$protocol = $secure ? 'https://' : 'http://';

$siteUrl = getenv('SITE_URL');

if (PHP_SAPI !== 'cli' &&
    $_SERVER['HTTP_HOST'] &&
    getenv('USE_HTTP_HOST_FOR_SITE_URL') === 'true'
) {
    $siteUrl = $protocol . $_SERVER['HTTP_HOST'];
}

return [
    '*' => [
        'allowUpdates' => false,
        'appId' => 'fcsc',
        'backupOnUpdate' => false,
        'cacheDuration' => 0,
        'cacheMethod' => 'apc',
        'basePath' => CRAFT_BASE_PATH,
        'cpTrigger' => 'cms',
        'devMode' => true,
        'errorTemplatePrefix' => '_errors/',
        'generateTransformsBeforePageLoad' => true,
        'isSystemLive' => true,
        'maxUploadFileSize' => 512000000,
        'omitScriptNameInUrls' => true,
        'postCpLoginRedirect' => 'entries',
        'projectPath' => CRAFT_BASE_PATH,
        'rememberedUserSessionDuration' => 'P100Y', // 100 years
        'runQueueAutomatically' => true,
        'securityKey' => 'qu999NBvHTk2HVnwbek6urkf36zFKgN',
        'sendPoweredByHeader' => false,
        'siteName' => 'Craft Static Testing',
        'siteUrl' => $siteUrl,
        'suppressTemplateErrors' => false,
        'timezone' => 'America/Chicago',
        'useEmailAsUsername' => true,
        'userSessionDuration' => false, // As long as browser stays open
    ],
];
