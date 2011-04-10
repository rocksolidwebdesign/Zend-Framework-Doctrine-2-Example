<?php
// always use array cache on the CLI
if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
    $cacheType = '\Doctrine\Common\Cache\ArrayCache';
} else {
    $cacheType = $zfConfigArray['doctrine']['settings']['cacheType'];
}
$dbLogins      = $zfConfigArray['doctrine']['connection'];
$logPath       = $zfConfigArray['doctrine']['settings']['logPath'];
$proxiesPath   = $zfConfigArray['doctrine']['settings']['proxiesPath'];
$entitiesPath  = $zfConfigArray['doctrine']['settings']['entitiesPath'];
$entitiesPath  = is_array($entitiesPath) ? $entitiesPath : array($entitiesPath);

// Setup Autloading
// ****************************************************************
$requiredLibs = array(
    'Application' => '',
    'Doctrine'    => '',
    'Symfony'     => 'Doctrine',
    'Proxies'     => $proxiesPath,
);

require_once 'Doctrine/Common/ClassLoader.php';
foreach ($requiredLibs as $name => $path) {
    if ($path) {
        $classLoader = new \Doctrine\Common\ClassLoader($name, $path);
    } else {
        $classLoader = new \Doctrine\Common\ClassLoader($name);
    }
    $classLoader->register();
}

foreach ($entitiesPath as $name => $path) {
    $classLoader = new \Doctrine\Common\ClassLoader($name, $path);
    $classLoader->register();
}

// Setup Entity Manager
// ****************************************************************
$ormConfig = new \Doctrine\ORM\Configuration();

// custom zend form annotations
$driverImpl = $ormConfig->newDefaultAnnotationDriver($entitiesPath);
$ormConfig->setMetadataDriverImpl($driverImpl);

// logging
//$ormConfig->setSQLLogger(new \Doctrine\DBAL\Logging\FileSQLLogger($logPath));
$ormConfig->setSQLLogger(new \Doctrine\DBAL\Logging\DebugStack());

// caching
if (is_array($cacheType)) {
    $ormConfig->setQueryCacheImpl(new $cacheType['query']);
    $ormConfig->setMetadataCacheImpl(new $cacheType['metadata']);
} else {
    $ormConfig->setQueryCacheImpl(new $cacheType);
    $ormConfig->setMetadataCacheImpl(new $cacheType);
}

// proxies
$ormConfig->setAutoGenerateProxyClasses(true);
$ormConfig->setProxyDir($proxiesPath . '/Proxies');
$ormConfig->setProxyNamespace('Proxies');

// Start Doctrine
$em = \Doctrine\ORM\EntityManager::create($dbLogins, $ormConfig);
