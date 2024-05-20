<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'My App'; ?></title>
</head>

<body>
    <h1>I am in main</h1>
    <?php $this->template->show('content'); ?>
</body>

</html>