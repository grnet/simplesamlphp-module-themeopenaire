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

<div class='attrvalue ssp-table--attrvalue'<?= $enable_hide ?>'>
  <?php foreach ($attributeValueList as $value): ?>
    <?php if ($nameRaw === 'jpegPhoto'): ?>
      <span class="attrvalue-list"><img src="data:image/jpeg;base64,<?=htmlspecialchars($value) ?>" alt="User photo" />,</span>
    <?php else: ?>
      <span class="attrvalue-list"><?= htmlspecialchars($value) ?></span>
    <?php endif; ?>
  <?php endforeach; ?>
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