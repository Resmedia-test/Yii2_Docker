<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%auth_items}}',
            'itemChildTable' => '{{%auth_items_childs}}',
            'assignmentTable' => '{{%auth_assignments}}',
            'ruleTable' => '{{%auth_rules}}',
            'defaultRoles' => ['guest'],
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'keyPrefix' => 'cache:',
        ],
    ],
];
