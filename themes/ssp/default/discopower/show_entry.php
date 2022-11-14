<?php

$basequerystring       = '?' .
  'entityID=' . urlencode($this->data['entityID']) . '&amp;' .
  'return=' . urlencode($this->data['return']) . '&amp;' .
  'returnIDParam=' . urlencode($this->data['returnIDParam']) . '&amp;idpentityid=';
$providersOnlyIcon     = array(
  "google"                 => "svg",
  "linkedin"               => "jpg",
  "github"                 => "png",
  "orcid"                  => "jpg",
  "igtf_certificate_proxy" => "jpg",
);
$providerLocal         = "openaire";
$providersOnlyIconName = array_keys($providersOnlyIcon);
$namelower_dasherize   = str_replace(' ', '_', strtolower(getTranslatedName($this, $this->data['metadata'])));

$href = $basequerystring . urlencode($this->data['metadata']['entityid']);
$extra_classes = "";
if (in_array($namelower_dasherize, $providersOnlyIconName)) {
  $extra_classes = "ssp-btn--round-icon  " . $namelower_dasherize;
  $iconUrl       = $namelower_dasherize . '.' . $providersOnlyIcon[$namelower_dasherize];
  $entry_icon    = SimpleSAML_Module::getModuleURL('themeopenaire/resources/images/' . $iconUrl);
  $a_title       = "";
} elseif ($namelower_dasherize == $providerLocal) {
  $extra_classes = "ssp-btn btn ssp-btn__open-edugain ssp-btn__lg text-uppercase " . $namelower_dasherize;
  $iconUrl       = $namelower_dasherize . '.png';
  $entry_icon    = SimpleSAML_Module::getModuleURL('themeopenaire/resources/images/' . $iconUrl);
  $a_title       = 'OpenAIRE Account';
} else {
  $a_title = htmlspecialchars(getTranslatedName($this, $this->data['metadata']));
  if (array_key_exists('icon', $this->data['metadata'])
    && $this->data['metadata']['icon'] !== null) {
    $iconUrl    = \SimpleSAML\Utils\HTTP::resolveURL($this->data['metadata']['icon']);
    $entry_icon = htmlspecialchars($iconUrl);
  }
}

?>

<a class="metaentry <?= $extra_classes ?>"
   href="<?= $href ?>">
  <?php
  if (!empty($entry_icon)): ?>
    <img alt="Identity Provider"
         class="entryicon"
         src="<?= $entry_icon ?>"/>
  <?php
  endif; ?>
  <?= $a_title ?>
</a>