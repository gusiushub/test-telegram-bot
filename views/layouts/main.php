<?php

$this->registerCsrfMetaTags();
$this->beginPage() 

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
