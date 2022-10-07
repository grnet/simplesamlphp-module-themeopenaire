<?php
// Some helper functions require the instance of a template
// As a result we create one here even if it is not needed
$globalConfig = \SimpleSAML\Configuration::getInstance();
$t            = new \SimpleSAML\XHTML\Template($globalConfig, 'consent:consentform.php');

$nameRaw = $this->data['nameRaw'];
$name = $this->data['name'];
$attributeValueList = $this->data['attributeValueList'];

$isHidden = in_array($nameRaw, $this->data['hiddenAttributes'], true);
$enable_hide = '';
if ($isHidden) {
  $hiddenId = \SimpleSAML\Utils\Random::generateID();
  $enable_hide = ' style="display: none;" id="hidden_' . $hiddenId . '"';
}

?>

<div class="attrname ssp-table--attrname">
  <div class="attrvalue ssp-table--attrvalue<?= $enable_hide ?>">
    <ul class="list-unstyled ssp-table--attrvalue--list">
      <?php foreach ($attributeValueList as $value): ?>
        <li class="ssp-table--attrvalue--list--item">
          <?php if ($nameRaw === 'jpegPhoto'): ?>
            <img src="data:image/jpeg;base64,<?=htmlspecialchars($value) ?>" alt="User photo" />
          <?php else: ?>
            <?= htmlspecialchars($value) ?>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php if($isHidden): ?>
  <div class="attrvalue consent_showattribute" id="visible_<?= $hiddenId ?>">
    <a class="consent_showattributelink ssp-btn__show-more"
       href="javascript:SimpleSAML_show('hidden_<?= $hiddenId ?>');SimpleSAML_hide('visible_<?= $hiddenId ?>');"
       data-toggle="tooltip"
       data-placement="right"
       title="<?= $this->t('{consent:consent:show_attribute}') ?>"
    >
      <span class="glyphicon glyphicon-eye-open ssp-show-more" aria-hidden="true"></span>
    </a>
  </div>
<?php endif; ?>