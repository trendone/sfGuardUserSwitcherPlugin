<div id="user-switcher">
  <h5>User Switcher</h5>
  <p>Your are now logged in as <b><?php echo $sf_user->getGuardUser()->getUsername(); ?></b></p>
  <form method="post" action="#">
    <?php echo $form ?>

    <input class="submit" type="submit" name="submit" value="change to user"/>
  </form>
</div>