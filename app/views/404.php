<?php $this->view("inc/header", $data); ?>
<section class="notFound">
  <div class="img">
  </div>
  <div class="text">
    <h1>404</h1>
    <h2>PAGE NOT FOUND</h2>
    <h3>BACK TO HOME?</h3>
    <a href="<?= ROOT ?>home" class="yes">YES</a>
    <a href="https://www.youtube.com/watch?v=6Mx1dTN6qBc&ab_channel=MIbrahim">NO</a>
  </div>
</section>
<?php $this->view("inc/footer", $data); ?>