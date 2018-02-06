<div id="loader">
  <div class="sk-circle">
    <div class="sk-circle1 sk-child"></div>
    <div class="sk-circle2 sk-child"></div>
    <div class="sk-circle3 sk-child"></div>
    <div class="sk-circle4 sk-child"></div>
    <div class="sk-circle5 sk-child"></div>
    <div class="sk-circle6 sk-child"></div>
    <div class="sk-circle7 sk-child"></div>
    <div class="sk-circle8 sk-child"></div>
    <div class="sk-circle9 sk-child"></div>
    <div class="sk-circle10 sk-child"></div>
    <div class="sk-circle11 sk-child"></div>
    <div class="sk-circle12 sk-child"></div>
  </div>
</div>
<?php

$faventry = NULL;
foreach( $this->data['idplist'] AS $tab => $slist) {
  if (!empty($this->data['preferredidp']) && array_key_exists($this->data['preferredidp'], $slist))
    $faventry = $slist[$this->data['preferredidp']];
}


if(!array_key_exists('header', $this->data)) {
  $this->data['header'] = 'selectidp';
}
$this->data['header'] = $this->t($this->data['header']);
$this->data['jquery'] = array('core' => TRUE, 'ui' => TRUE, 'css' => TRUE);


$this->data['head'] .= '<script type="text/javascript" src="' . SimpleSAML_Module::getModuleUrl('discopower/js/jquery.livesearch.js')  . '"></script>';
$this->data['head'] .= '<script type="text/javascript" src="' . SimpleSAML_Module::getModuleUrl('discopower/js/' . $this->data['score'] . '.js')  . '"></script>';

if (!empty($faventry)) $this->data['autofocus'] = 'favouritesubmit';

$this->includeAtTemplateBase('includes/header.php');

$languages_html = '';
$includeLanguageBar = TRUE;
if (!empty($_POST))
  $includeLanguageBar = FALSE;
if (isset($this->data['hideLanguageBar']) && $this->data['hideLanguageBar'] === TRUE)
  $includeLanguageBar = FALSE;

if ($includeLanguageBar) {

  $languages = $this->getLanguageList();
  if ( count($languages) > 1 ) {
    $languages_html .= '<div class="ssp-lang-container">
      <div class="dropup">';
    $langnames = array(
      'no' => 'Bokmål', // Norwegian Bokmål
      'nn' => 'Nynorsk', // Norwegian Nynorsk
      'se' => 'Sámegiella', // Northern Sami
      'sam' => 'Åarjelh-saemien giele', // Southern Sami
      'da' => 'Dansk', // Danish
      'en' => 'English',
      'de' => 'Deutsch', // German
      'sv' => 'Svenska', // Swedish
      'fi' => 'Suomeksi', // Finnish
      'es' => 'Español', // Spanish
      'fr' => 'Français', // French
      'it' => 'Italiano', // Italian
      'nl' => 'Nederlands', // Dutch
      'lb' => 'Lëtzebuergesch', // Luxembourgish
      'cs' => 'Čeština', // Czech
      'sl' => 'Slovenščina', // Slovensk
      'lt' => 'Lietuvių kalba', // Lithuanian
      'hr' => 'Hrvatski', // Croatian
      'hu' => 'Magyar', // Hungarian
      'pl' => 'Język polski', // Polish
      'pt' => 'Português', // Portuguese
      'pt-br' => 'Português brasileiro', // Portuguese
      'ru' => 'русский язык', // Russian
      'et' => 'eesti keel', // Estonian
      'tr' => 'Türkçe', // Turkish
      'el' => 'ελληνικά', // Greek
      'ja' => '日本語', // Japanese
      'zh' => '简体中文', // Chinese (simplified)
      'zh-tw' => '繁體中文', // Chinese (traditional)
      'ar' => 'العربية', // Arabic
      'fa' => 'پارسی', // Persian
      'ur' => 'اردو', // Urdu
      'he' => 'עִבְרִית', // Hebrew
      'id' => 'Bahasa Indonesia', // Indonesian
      'sr' => 'Srpski', // Serbian
      'lv' => 'Latviešu', // Latvian
      'ro' => 'Românește', // Romanian
      'eu' => 'Euskara', // Basque
    );

    $textarray = array();
    foreach ($languages AS $lang => $current) {
      $lang = strtolower($lang);
      if ($current) {
        $lang_current = $langnames[$lang];
      } else {
        $textarray[] = '<li class="ssp-dropdown__two_cols--item"><a class="js-pick-language" data-language="' . htmlspecialchars(\SimpleSAML\Utils\HTTP::addURLParameters(\SimpleSAML\Utils\HTTP::getSelfURL(), array($this->languageParameterName => $lang))) . '">' .
          $langnames[$lang] . '</a></li>';
      }
    }
    $languages_html .= '<button class="ssp-btn btn ssp-btn__footer dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'
      . $lang_current
      . '<span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-left ssp-dropdown__two_cols" aria-labelledby="Languages">';
    $languages_html .= join(' ', $textarray);
    $languages_html .= '</ul></div></div>'; // /dropup /ssp-lang-container
  }
}



function showEntry($t, $metadata, $favourite = FALSE) {

  $basequerystring = '?' .
    'entityID=' . urlencode($t->data['entityID']) . '&amp;' .
    'return=' . urlencode($t->data['return']) . '&amp;' .
    'returnIDParam=' . urlencode($t->data['returnIDParam']) . '&amp;idpentityid=';

  $providersOnlyIcon = array("google", "linkedin", "facebook", "orcid", "igtf_certificate_proxy");
  $providerLocal = "openaire";
  $namelower_dasherize = str_replace(' ', '_', strtolower(getTranslatedName($t, $metadata)));


  if(in_array($namelower_dasherize, $providersOnlyIcon)) {
    $html = '<a class="metaentry ssp-btn--round-icon" href="' . $basequerystring . urlencode($metadata['entityid']) . '">';
    $html .= '<img alt="Identity Provider" class="entryicon" src="' . SimpleSAML_Module::getModuleURL('themeopenaire/resources/images/' . $namelower_dasherize . '.jpg') . '" />';
    $html .= '</a>';
  }
  else if($namelower_dasherize == $providerLocal) {
    $html = '<a class="ssp-btn btn ssp-btn__open-edugain ssp-btn__lg text-uppercase" title="OpenAIRE log in" href="' . $basequerystring . urlencode($metadata['entityid']) . '">';
    $html .= '<img alt="Identity Provider" class="entryicon" src="' . SimpleSAML_Module::getModuleURL('themeopenaire/resources/images/' . $namelower_dasherize . '.png') . '" />';
    $html .= 'log in with your OpenAIRE account';
    $html .= '</a>';
  }
  else {
    $html = '<a class="metaentry " href="' . $basequerystring . urlencode($metadata['entityid']) . '">';
    $html .= htmlspecialchars(getTranslatedName($t, $metadata)) . '';

    if(array_key_exists('icon', $metadata) && $metadata['icon'] !== NULL) {
      $iconUrl = \SimpleSAML\Utils\HTTP::resolveURL($metadata['icon']);
      $html .= '<img alt="Identity Provider" class="entryicon" src="' . htmlspecialchars($iconUrl) . '" />';
    }

    $html .= '</a>';
  }



  return $html;
}

?>



<?php

function getTranslatedName($t, $metadata) {
  if (isset($metadata['UIInfo']['DisplayName'])) {
    $displayName = $metadata['UIInfo']['DisplayName'];
    assert('is_array($displayName)'); // Should always be an array of language code -> translation
    if (!empty($displayName)) {
      return $t->getTranslation($displayName);
    }
  }

  if (array_key_exists('name', $metadata)) {
    if (is_array($metadata['name'])) {
      return $t->getTranslation($metadata['name']);
    } else {
      return $metadata['name'];
    }
  }
  return $metadata['entityid'];
}


echo('<div class="ssp-container-small">');

$or_html = '<div class="row ssp-content-group">
  <div class="col-sm-12 text-center ssp-or">or</div>
</div>';

$edugain_html = '';
$local_html ='';
$idps_with_logo_html = '';

if (!empty($faventry)) {
  echo('
    <div class="modal fade" id="favourite-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="js-close-custom close"><span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">Login</h2>
          </div>
          <div class="modal-body ssp-modal-body">
            <div class="row text-center">
              <form id="idpselectform" method="get" action="' . $this->data['urlpattern'] . '" class="ssp-form-favourite">
                <input type="hidden" name="entityID" value="' . htmlspecialchars($this->data['entityID']) . '" />
                <input type="hidden" name="return" value="' . htmlspecialchars($this->data['return']) . '" />
                <input type="hidden" name="returnIDParam" value="' . htmlspecialchars($this->data['returnIDParam']) . '" />
                <input type="hidden" name="idpentityid" value="' . htmlspecialchars($faventry['entityid']) . '" />
                <input type="submit" name="formsubmit" id="favouritesubmit" class="ssp-btn ssp-btn__action btn text-uppercase" value="'
                  . $this->t('login_at') . ' ' . htmlspecialchars(getTranslatedName($this, $faventry)) . '" />
              </form>
            </div>
            <div class="row text-center ssp-or">or</div>
            <div class="row text-center"><button class="btn ssp-btn text-uppercase ssp-btn ssp-btn__secondary js-close-custom">Choose an other account</button></div>
          </div> <!-- /modal-body -->
        </div> <!-- /modal-content -->
      </div> <!-- /modal-dialog -->
    </div> <!-- /modal -->
  ');
}


foreach( $this->data['idplist'] AS $tab => $slist) {
  if ($tab !== 'all') {
    if (!empty($slist)) {
      if($tab == 'edugain') {
  $edugainList = '<div class="metalist ssp-content-group__provider-list ssp-content-group__provider-list--edugain js-spread" id="list_' .$tab . '">';
  if (!empty($this->data['preferredidp']) && array_key_exists($this->data['preferredidp'], $slist)) {
    $idpentry = $slist[$this->data['preferredidp']];
    $edugainList .= (showEntry($this, $idpentry, TRUE));
  }

  foreach ($slist AS $idpentry) {
    if ($idpentry['entityid'] != $this->data['preferredidp']) {
      $edugainList .= (showEntry($this, $idpentry));
    }
  }
  $edugainList .= '</div>'; // /metalist
  $buttonOpenEdugain = '<div class="row ssp-content-group"><div class="col-sm-12 text-center"><button type="button" class="ssp-btn btn ssp-btn__btn-lg ssp-btn__lg text-uppercase" data-toggle="modal" data-target="#edugain-modal"><img class="round" src="'
    . SimpleSAML_Module::getModuleURL('themeopenaire/resources/images/edugain.png') . '">log in with edugain</button></div></div>';
  $edugain_html .= '
    <div class="modal fade" id="edugain-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="js-close-custom close"><span aria-hidden="true">&times;</span></button>
            <h2 class="modal-title">' . $this->t('{discopower:tabs:' . $tab . '}') . '</h2>
          </div>
          <div class="modal-body ssp-modal-body">
            <div class="row">
                <div class="input-group">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                  <form id="idpselectform" action="?" method="get"><input class="form-control" aria-describedby="search institutions" placeholder="Search..." type="text" value="" name="query_'
                  . $tab
                  . '" id="query_' . $tab . '" /></form>'
                  . '</div> <!-- /input-group -->'
                  . $edugainList
                . '</div> <!-- /row -->
          </div> <!-- /modal-body -->
          <div class="modal-footer ssp-text-left">'
          . $languages_html .
          '</div>
        </div> <!-- /modal-content -->
      </div> <!-- /modal-dialog -->
    </div> <!-- /modal -->';
  $edugain_html .= $buttonOpenEdugain;
      }
      else if($tab == "idps_with_logos") {
        $providers = '';
        if (!empty($this->data['preferredidp']) && array_key_exists($this->data['preferredidp'], $slist)) {
          $idpentry = $slist[$this->data['preferredidp']];
          $providers .=  (showEntry($this, $idpentry, TRUE));
        }

        foreach ($slist AS $idpentry) {
          if ($idpentry['entityid'] != $this->data['preferredidp']) {
            $providers .= (showEntry($this, $idpentry));
          }
        }
          $idps_with_logo_html .= '<div class="row ssp-content-group"><div class="col-sm-12 text-center">'
          . $providers .
          '</div></div>';
      }
      else if($tab == "local") {
        $providers = '';
        // Should be 1 provider in the list
        foreach ($slist AS $idpentry) {
          $providers .= (showEntry($this, $idpentry));
        }
          $local_html .= '<div class="row ssp-content-group"><div class="col-sm-12 text-center">'
          . $providers .
          '</div></div>';
      }
    }
  }
}
    echo $edugain_html . $idps_with_logo_html . $or_html . $local_html;
?>


</div> <!-- /ssp-container-small -->

<?php $this->includeAtTemplateBase('includes/footer.php');
