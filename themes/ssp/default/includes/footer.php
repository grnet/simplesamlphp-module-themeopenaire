
<?php
if(!empty($this->data['htmlinject']['htmlContentPost'])) {
  foreach($this->data['htmlinject']['htmlContentPost'] AS $c) {
    echo $c;
  }
}
?>
  </div><!-- /ssp-container -->
  <footer class="ssp-footer text-center">
    <div class="container-fluid ssp-footer--container">

        <div class="copy">Powered by <a href="https://github.com/rciam">RCIAM</a> | Service provided by <a href="https://grnet.gr/">GRNET</a></div>
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
