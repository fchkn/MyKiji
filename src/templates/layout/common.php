<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
    $this->assign('title', 'MyKiji');
?>
<!DOCTYPE html>
<html>
<head>
    <link rel ="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.css">

    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

</head>
<body>
    <?= $this->element('header') ?>

    <main class="fullheight">
        <div class="container bg-white" style="border: solid 30px #f5f5f5;">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>

    <?= $this->element('footer') ?>
</body>
</html>

<style>
html {
    height: 100%;
}

body {
    height: 100%;
    margin: 0;
}

main {
    background: #f5f5f5;
}

.fullheight {
    height: 100%;
}
</style>