<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo escapeInjection($title); ?> - Good Mood</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/main.css" />
</head>
<body>
<!-- Start Header -->
<?php require $this->resolve("partials/_header.php"); ?>
<!-- End Header -->

<!-- Start Main Content -->
<div class="container">
  <?php echo $content; ?>
</div>
<!-- End Main Content -->

<!-- Start Footer -->
<?php require $this->resolve("partials/_footer.php"); ?>
<!-- End Footer -->

<script src="../js/jquery-3.7.1.min.js"></script>
<script src="../js/scripts.js"></script>
</body>
</html>
