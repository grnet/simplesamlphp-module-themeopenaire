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

function showEntry($t, $metadata, $favourite = FALSE) {

  $basequerystring = '?' .
    'entityID=' . urlencode($t->data['entityID']) . '&amp;' .
    'return=' . urlencode($t->data['return']) . '&amp;' .
    'returnIDParam=' . urlencode($t->data['returnIDParam']) . '&amp;idpentityid=';

  $providersOnlyIcon = array("google", "linkedin", "facebook", "orcid");
  $namelower = strtolower(getTranslatedName($t, $metadata));


  if(in_array($namelower, $providersOnlyIcon)) {
    $html = '<a class="metaentry ssp-btn--round-icon" href="' . $basequerystring . urlencode($metadata['entityid']) . '">';
    $html .= '<img alt="Identity Provider" class="entryicon" src="' . SimpleSAML_Module::getModuleURL('themeopenaire/resources/images/' . $namelower . '.jpg') . '" />';
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

$top = '<div class="row ssp-content-group">
      <div class="col-sm-12">';
$title = '';
$title_html = '';
$list_open = '<div class="metalist ssp-content-group__provider-list ssp-content-group__provider-list--other text-center" id="list_other">';
$providers = '';
$close = '</div></div></div>'; // /metalist /ssp-content-group /row

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
  $buttonOpenEdugain = '<div class="row ssp-content-group"><div class="col-sm-12 text-center"><button type="button" class="ssp-btn btn ssp-btn__open-edugain ssp-btn__lg text-uppercase" data-toggle="modal" data-target="#edugain-modal"><img src="'
    . SimpleSAML_Module::getModuleURL('themeopenminted/resources/images/edugain.png') . '">Login with edugain</button></div></div>';
  echo('
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
        </div> <!-- /modal-content -->
      </div> <!-- /modal-dialog -->
    </div> <!-- /modal -->
    ');
  echo($buttonOpenEdugain);
      }
      else {
        if($tab == "social") {
          $title = $this->t('{discopower:tabs:' . $tab . '}') . ' / ';
          if (!empty($this->data['preferredidp']) && array_key_exists($this->data['preferredidp'], $slist)) {
            $idpentry = $slist[$this->data['preferredidp']];
            $providers .=  (showEntry($this, $idpentry, TRUE));
          }

          foreach ($slist AS $idpentry) {
            if ($idpentry['entityid'] != $this->data['preferredidp']) {
              $providers .= (showEntry($this, $idpentry));
            }
          }
        }
        else if ($tab == "misc") {
          $title .= $this->t('{discopower:tabs:' . $tab . '}');
          if (!empty($this->data['preferredidp']) && array_key_exists($this->data['preferredidp'], $slist)) {
            $idpentry = $slist[$this->data['preferredidp']];
            $providers .=  (showEntry($this, $idpentry, TRUE));
          }

          foreach ($slist AS $idpentry) {
            if ($idpentry['entityid'] != $this->data['preferredidp']) {
              $providers .= (showEntry($this, $idpentry));
            }
          }
          echo $top . $top_close . $list_open . $providers . $close;
        }
      }
    }
  }
}
?>


<div class="row ssp-content-group">
  <div class="col-sm-12 text-center ssp-or">or</div>
</div>
<div class="row ssp-content-group">
  <div class="col-sm-12">
    <form>
      <div class="form-group">
        <input type="email" class="form-control" placeholder="Username / E-mail">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" placeholder="Password">
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox"> Remember me
        </label>
      </div>
      <button type="submit" class="ssp-btn btn  ssp-btn__action text-uppercase ssp-btn__lg ssp-btn__login">Login</button>
    </form>
  </div>
</div>
<div class="row ssp-content-group">
  <div class="col-sm-12">
    <a href="#" class="pull-left ssp-link-forgot">Forgot your password?</a>
    <a href="#" class="pull-right ssp-link-forgot">Forgot your username?</a>
  </div>
</div>
</div> <!-- /ssp-container-small -->

<?php $this->includeAtTemplateBase('includes/footer.php');
