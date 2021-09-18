<?php

$header = <<<EOF
+----------------------------------------------------------------------
| do-tool工具库
+----------------------------------------------------------------------
| Author: Domino184 <m18434900825@163.com>
+----------------------------------------------------------------------
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__);

// vendor/bin/php-cs-fixer fix 要格式化的目录
$config = new PhpCsFixer\Config();
return $config->setRules([
    'header_comment' => ['header' => $header, 'location' => 'after_open',]
])->setFinder($finder);