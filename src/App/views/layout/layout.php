<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?php echo escapeInjection($title); ?> - Positive News</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/main.css" />
</head>
<body>
<!-- Start Header -->
<?php include $this->resolve("partials/_header.php"); ?>
<!-- End Header -->

<!-- Start Main Content -->
<?php include $this->resolve($template); ?>
<!-- End Main Content -->

<!-- Start Footer -->
<?php include $this->resolve("partials/_footer.php"); ?>
<!-- End Footer -->

<!--<script src="--><?php //echo $this->resolvePathToScripts('js/jquery-3.7.1.min.js'); ?><!--"></script>-->
<!--<script src="--><?php //echo $this->resolvePathToScripts('js/scripts.js'); ?><!--"></script>-->
<script src="/js/jquery-3.7.1.min.js"></script>
<script src="/js/scripts.js"></script>
</body>
</html>
