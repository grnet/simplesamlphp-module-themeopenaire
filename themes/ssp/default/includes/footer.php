
<?php
if(!empty($this->data['htmlinject']['htmlContentPost'])) {
  foreach($this->data['htmlinject']['htmlContentPost'] AS $c) {
    echo $c;
  }
}
?>
  </div><!-- /ssp-container -->

<?php if(strpos($this->t('{themeopenaire:discopower:cookies_text}'), 'not translated') === false || strpos($this->t('{themeopenaire:discopower:cookies_accept_btn_text}'), 'not translated') === false): ?>
  <!-- cookies popup -->
  <div id="cookies">
    <div id="cookies-wrapper">
      <p>
          <?php print $this->t('{themeopenaire:discopower:cookies_text}'); ?>
          <?php if(strpos($this->t('{themeopenaire:discopower:cookies_link_text}'), 'not translated') === false): ?>
            <a href="<?php print $this->t('{themeopenaire:discopower:cookies_link_url}'); ?>" target="_blank"><?php print $this->t('{themeopenaire:discopower:cookies_link_text}'); ?></a>
          <?php endif; ?>
      </p>
      <a id="js-accept-cookies" class="cookies-ok" href="#">
          <?php print $this->t('{themeopenaire:discopower:cookies_accept_btn_text}'); ?>
      </a>
    </div>
  </div>
  <!-- /cookies popup -->
<?php endif; ?>

  <footer class="ssp-footer text-center">
    <div class="container-fluid ssp-footer--container">

      <div class="copy">
        Powered by <a href="https://github.com/rciam">RCIAM</a>
        | Service provided by <a href="https://grnet.gr/">GRNET</a>
        | <a href="https://www.openaire.eu/privacy-policy"
             target="_blank">Privacy Policy</a>
      </div>
      </div> <!-- /container-fluid -->
  </footer>
  <script type="text/javascript"
          src="<?php echo htmlspecialchars(SimpleSAML_Module::getModuleURL('themeopenaire/resources/js/dropdown.js')); ?>">
  </script>
  <script type="text/javascript"
          src="<?php echo htmlspecialchars(SimpleSAML_Module::getModuleURL('themeopenaire/resources/js/modal.js')); ?>">
  </script>
  <script type="text/javascript"
          src="<?php echo htmlspecialchars(SimpleSAML_Module::getModuleURL('themeopenaire/resources/js/theme.js')); ?>">
  </script>
  <script type="text/javascript"
          src="<?php echo htmlspecialchars(SimpleSAML_Module::getModuleURL('themeopenaire/resources/js/tooltip.js')); ?>">
  </script>

</body>
</html>
