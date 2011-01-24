<?php

return array(
    'phpSettings' => array(
        'display_startup_errors' => false,
        'display_errors' => false,
        'date' => array(
            'timezone' => 'America/New_York'
        )
    ),
    'bootstrap' => array(
        'path' => sprintf('%s/Bootstrap.php', APPLICATION_PATH)
    ),
    'resources' => array(
        'frontController' => array(
            'moduleDirectory' => APPLICATION_PATH,
            'env' => APPLICATION_ENV,
            'prefixDefaultModule' => true,
            'actionHelperPaths' => array(
                'Epixa\\Controller\\Helper\\' => 'Epixa/Controller/Helper'
            )
        ),
        'modules' => array(),
        'view' => array()
    )
);