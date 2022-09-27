<?php

use Webmozart\Assert\Assert;


// HELPER FUNCTIONS
function getTranslatedName($t, $metadata)
{
  if (isset($metadata['UIInfo']['DisplayName'])) {
    $displayName = $metadata['UIInfo']['DisplayName'];
    Assert::isArray($displayName); // Should always be an array of language code -> translation
    if (!empty($displayName)) {
      return $t->getTranslator()->getPreferredTranslation($displayName);
    }
  }

  if (array_key_exists('name', $metadata)) {
    if (is_array($metadata['name'])) {
      return $t->getTranslator()->getPreferredTranslation($metadata['name']);
    } else {
      return $metadata['name'];
    }
  }

  return $metadata['entityid'];
}


// Get Configuration and set the loader
$themeConfig = SimpleSAML\Configuration::getConfig('module_themeopenaire.php');
$loader      = $themeConfig->getValue('loader');
if (!empty($loader)) {
  $this->includeAtTemplateBase('includes/' . $loader . '.php');
}

$languageBar = $themeConfig->getValue('hideLanguageBar');
if (!empty($languageBar)) {
  $this->data['hideLanguageBar'] = true;
}

$this->data['header'] = $this->t('selectidp');
$this->data['jquery'] = ['core' => true, 'ui' => false, 'css' => false];

$this->data['head'] = '<link rel="stylesheet" media="screen" type="text/css" href="'
  . SimpleSAML\Module::getModuleURL('discopower/assets/css/uitheme1.12.1/jquery-ui.min.css')
  . '" />';


$this->includeAtTemplateBase('includes/header.php');

$this->data['htmlinject']['htmlContentPost'][] = '<script type="text/javascript" src="' .
  SimpleSAML\Module::getModuleURL('discopower/assets/js/jquery-1.12.4.min.js') . '"></script>' . "\n";
$this->data['htmlinject']['htmlContentPost'][] = '<script type="text/javascript" src="' .
  SimpleSAML\Module::getModuleURL('discopower/assets/js/jquery-ui-1.12.1.min.js') . '"></script>' . "\n";
$this->data['htmlinject']['htmlContentPost'][] = '<script type="text/javascript" src="' .
  SimpleSAML\Module::getModuleURL('discopower/assets/js/jquery.livesearch.js') . '"></script>' . "\n";
$this->data['htmlinject']['htmlContentPost'][] = '<script type="text/javascript" src="' .
  SimpleSAML\Module::getModuleURL('discopower/assets/js/tablist.js') . '"></script>' . "\n";
$this->data['htmlinject']['htmlContentPost'][] = '<script type="text/javascript" src="' .
  SimpleSAML\Module::getModuleURL('discopower/assets/js/' . $this->data['score'] . '.js') . '"></script>' . "\n";

?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6 col-md-4 col-lg-3 happy-login">
        <div class="ssp-container-small">
          <div class="text-center ssp-logo">
            <a href="https://www.openaire.eu/">
              <img src="<?php
              echo SimpleSAML\Module::getModuleURL('themeopenaire/resources/images/logo.jpg'); ?>" alt="OpenAIRE"/>
            </a>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-8 col-lg-9 choose-idp">
        <div class="ssp-container-small">
          <h1 class="text-center disco"><?= $this->t('{themeopenaire:discopower:header}') ?></h1>
          <?php

          // FAVOURITE MODAL
          // We place the modal here since we want to place it relative to the ssp-container-small
          $this->data['autofocus'] = 'favouritesubmit';
          $this->includeAtTemplateBase('discopower/modal_favourite.php');

          foreach ($this->data['idplist'] as $tab => $slist) {
            if ($tab === 'all' || empty($slist)) {
              continue;
            }

            if ($tab == 'edugain') { // Edugain
              // EDUGAIN MODAL
              // Set the included variable and load the edugain modal
              $this->data['tab']   = $tab;
              $this->data['slist'] = $slist;
              $this->includeAtTemplateBase('discopower/modal_edugain.php');
            } elseif ($tab == "idps_with_logos") { // IDPS with logos
              $idp_with_logo_providers = [];
              foreach ($slist as $idpentry) {
                $idp_with_logo_providers[] = $idpentry;
                if (!empty($this->data['preferredidp'])
                    && $idpentry['entityid'] == $this->data['preferredidp']) {
                  // Put the preferred icon at the top of my list
                  array_unshift($idp_with_logo_providers, $idpentry);
                  // Remove the preferred icon from the end of the list
                  array_pop($idp_with_logo_providers);
                }
              }
            } elseif ($tab == "local") { // LOCAL
              $local_providers = array_values($slist);
            }
          }

          ?>
          <?php
          if (!empty($idp_with_logo_providers)): ?>
            <div class="row ssp-content-group">
              <div class="col-sm-12 text-center">
                <?php
                foreach ($idp_with_logo_providers as $idp_entry) {
                  $this->data['metadata'] = $idp_entry;
                  $this->includeAtTemplateBase('discopower/show_entry.php');
                }
                ?>
              </div>
            </div>
          <?php
          endif; ?>
          <?php
          if (!empty($idp_with_logo_providers) && !empty($local_providers)): ?>
            <div class="row ssp-content-group">
              <div class="col-sm-12 text-center ssp-or">or with your OpenAIRE account</div>
            </div>
          <?php
          endif; ?>
          <?php
          if (!empty($local_providers)): ?>
            <div class="row ssp-content-group">
              <div class="col-sm-12 text-center">
                <?php
                foreach ($local_providers as $idp_entry) {
                  $this->data['metadata'] = $idp_entry;
                  $this->includeAtTemplateBase('discopower/show_entry.php');
                }
                ?>
              </div>
              <div class="col-sm-12 text-center">
                <a class="no-account" href="https://services.openaire.eu/uoa-user-management/register.jsp"><?= $this->t('{themeopenaire:discopower:no_account}') ?></a>
              </div>
            </div>
          <?php
          endif; ?>
        </div> <!-- /ssp-container-small -->
      </div> <!-- row -->
    </div>
  </div>

<?php
$this->includeAtTemplateBase('includes/footer.php');
