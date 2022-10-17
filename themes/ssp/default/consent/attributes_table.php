<?php
// Some helper functions require the instance of a template
// As a result we create one here even if it is not needed
$globalConfig = \SimpleSAML\Configuration::getInstance();
$t            = new \SimpleSAML\XHTML\Template($globalConfig, 'consent:consentform.php');
$translator = $t->getTranslator();

$summary   = 'summary="' . $translator->t('{consent:consent:table_summary}') . '"';
?>

<div class="ssp-attrs--container js-spread">
  <table id="table_with_attributes"  class="table" <?= $summary ?> >
    <?php foreach ($this->data['attributes'] as $name => $value): ?>
    <tr>
      <td>
        <div class="attrname ssp-table--attrname">
          <?php
          if($name == 'eduPersonScopedAffiliation') {
            print htmlspecialchars($this->t('{themeopenaire:consent:affiliation_input_label}')) . ":";
          } else {
              print $translator->getAttributeTranslation(htmlspecialchars($name)) . ":"; // translate
          }
          ?>
        </div>
      <?php
      $nameRaw = $name;
      $name    = $translator->getAttributeTranslation($nameRaw);
      $this->data['nameRaw'] = $nameRaw;
      $this->data['name'] = $name;
      $this->data['attributeValueList'] = $value;
      $this->includeAtTemplateBase('consent/attribute_value_row.php');
      ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
