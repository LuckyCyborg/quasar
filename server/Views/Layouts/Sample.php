<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? $title : 'Page'; ?> - <?= $siteName = Config::get('server.name'); ?></title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
    <a style="outline: none;" href="<?= site_url(); ?>"><img src="<?= asset_url('images/nova.png'); ?>" alt="<?= $siteName; ?>"></a>
    <?= $content; ?>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
