<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/quranmemorizer:view' => array(
        'riskbitmask' => RISK_SPAM,
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW,
        ),
    ),
);