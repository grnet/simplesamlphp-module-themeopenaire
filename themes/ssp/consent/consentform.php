<?php

// Get Configuration and set the loader
$themeConfig = SimpleSAML\Configuration::getConfig('module_themeopenaire.php');
$loader      = $themeConfig->getValue('loader');
if (!empty($loader)) {
  $this->includeAtTemplateBase('includes/' . $loader . '.php');
}

/**
 * Template form for giving consent.
 *
 * Parameters:
 * - 'yesTarget': Target URL for the yes-button. This URL will receive a POST request.
 * - 'noTarget': Target URL for the no-button. This URL will receive a GET request.
 * - 'sppp': URL to the privacy policy of the destination, or false.
 *
 * @package SimpleSAMLphp
 */

assert(is_string($this->data['yesTarget']));
assert(is_string($this->data['noTarget']));
assert($this->data['sppp'] === false || is_string($this->data['sppp']));

// Parse parameters
$dstName = $this->data['dstName'];
$srcName = $this->data['srcName'];

$id    = $_REQUEST['StateId'];
$state = \SimpleSAML\Auth\State::loadState($id, 'consent:request');

$this->data['hiddenAttributes'] = [];
if (array_key_exists('consent:hiddenAttributes', $state)) {
  $this->data['hiddenAttributes'] = $state['consent:hiddenAttributes'];
}

//$attributes = $this->data['attributes'];
//var_dump($attributes);

$this->data['header'] = $this->t('{consent:consent:consent_header}');
$this->data['jquery'] = ['core' => true];

$this->includeAtTemplateBase('includes/header.php');

?>
  <h2 class="text-center"><?= $this->t('{themeopenaire:consent:consent_accept}') ?></h2>
  <div class="row js-spread">
    <div class="col-sm-12 ssp-content-group js-spread">
      <?php $this->includeAtTemplateBase('consent/attributes_table.php') ?>
      <?php if (isset($this->data['consent_purpose'])): ?>
        <p><?= $this->data['consent_purpose'] ?></p>
      <?php endif; ?>
      <div class="ssp-btns-container">
        <form
          id="consent_yes"
          action="<?= htmlspecialchars($this->data['yesTarget']) ?>"
          style="display:inline-block;"
        >
          <p class="ssp-btns-container--checkbox">
            <?php
            if ($this->data['usestorage']) {
              $checked = ($this->data['checked'] ? 'checked="checked"' : '');
            } // Embed hidden fields...
            ?>
            <input type="checkbox" name="saveconsent" <?= $checked ?> value="1"/> <?= $this->t(
              '{consent:consent:remember}'
            ) ?>
            <input type="hidden" name="StateId" value="<?= htmlspecialchars($this->data['stateId']) ?>"/>
          </p>
          <button
            type="submit"
            name="yes"
            class="ssp-btn btn ssp-btn__action ssp-btns-container--btn__left text-uppercase"
            id="yesbutton"
          >
            <?= htmlspecialchars($this->t('{consent:consent:yes}')) ?>
          </button>
        </form>

        <form
          id="consent_no"
          action="<?= htmlspecialchars($this->data['noTarget']) ?>"
          style="display:inline-block;"
        >
          <input type="hidden" name="StateId" value="<?= htmlspecialchars($this->data['stateId']) ?>"/>
          <button
            type="submit"
            class="ssp-btn ssp-btn__secondary btn ssp-btns-container--btn__right text-uppercase"
            name="no"
            id="nobutton"
          >
            <?= htmlspecialchars($this->t('{consent:consent:no}')) ?>
          </button>
        </form>
      </div> <!-- /ssp-btns-container -->
    </div> <!-- /ssp-content-group -->
  </div> <!-- /row -->

<?php
if ($this->data['sppp'] !== false) : ?>
  <p><?= htmlspecialchars($this->t('{consent:consent:consent_privacypolicy}')) ?>
    <a target="_blank" href="<?= htmlspecialchars($this->data['sppp']) ?>"><?= $dstName ?></a>
  </p>
<?php
endif;

$this->includeAtTemplateBase('includes/footer.php');
