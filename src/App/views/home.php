<main>
  <div class="wrapper">
    <div class="container">
      <?php echo $mainArticle; ?>

      <!-- Search Articles -->
      <?php include $this->resolve("partials/_searchBar.php"); ?>

      <?php echo $articlesGrid; ?>
    </div>
  </div>
</main>
<!-- End Main Content Area -->