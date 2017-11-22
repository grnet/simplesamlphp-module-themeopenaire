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
/**
 * Template form for giving consent.
 *
 * Parameters:
 * - 'srcMetadata': Metadata/configuration for the source.
 * - 'dstMetadata': Metadata/configuration for the destination.
 * - 'yesTarget': Target URL for the yes-button. This URL will receive a POST request.
 * - 'yesData': Parameters which should be included in the yes-request.
 * - 'noTarget': Target URL for the no-button. This URL will receive a GET request.
 * - 'noData': Parameters which should be included in the no-request.
 * - 'attributes': The attributes which are about to be released.
 * - 'sppp': URL to the privacy policy of the destination, or FALSE.
 *
 * @package SimpleSAMLphp
 */
assert('is_array($this->data["srcMetadata"])');
assert('is_array($this->data["dstMetadata"])');
assert('is_string($this->data["yesTarget"])');
assert('is_array($this->data["yesData"])');
assert('is_string($this->data["noTarget"])');
assert('is_array($this->data["noData"])');
assert('is_array($this->data["attributes"])');
assert('is_array($this->data["hiddenAttributes"])');
assert('$this->data["sppp"] === false || is_string($this->data["sppp"])');

// Parse parameters
if (array_key_exists('name', $this->data['srcMetadata'])) {
    $srcName = $this->data['srcMetadata']['name'];
} elseif (array_key_exists('OrganizationDisplayName', $this->data['srcMetadata'])) {
    $srcName = $this->data['srcMetadata']['OrganizationDisplayName'];
} else {
    $srcName = $this->data['srcMetadata']['entityid'];
}

if (is_array($srcName)) {
    $srcName = $this->t($srcName);
}

if (array_key_exists('name', $this->data['dstMetadata'])) {
    $dstName = $this->data['dstMetadata']['name'];
} elseif (array_key_exists('OrganizationDisplayName', $this->data['dstMetadata'])) {
    $dstName = $this->data['dstMetadata']['OrganizationDisplayName'];
} else {
    $dstName = $this->data['dstMetadata']['entityid'];
}

if (is_array($dstName)) {
    $dstName = $this->t($dstName);
}

$srcName = htmlspecialchars($srcName);
$dstName = htmlspecialchars($dstName);

$attributes = $this->data['attributes'];

$this->data['header'] = $this->t('{consent:consent:consent_header}');
$this->data['jquery'] = array('core' => TRUE);

$this->includeAtTemplateBase('includes/header.php');
?>

<?php
if ($this->data['sppp'] !== false) {
    echo "<p>" . htmlspecialchars($this->t('{consent:consent:consent_privacypolicy}')) . " ";
    echo "<a target='_blank' href='" . htmlspecialchars($this->data['sppp']) . "'>" . $dstName . "</a>";
    echo "</p>";
}

/**
 * Recursive attribute array listing function
 *
 * @param SimpleSAML_XHTML_Template $t          Template object
 * @param array                     $attributes Attributes to be presented
 * @param string                    $nameParent Name of parent element
 *
 * @return string HTML representation of the attributes
 */
function present_attributes($t, $attributes, $nameParent)
{
    $alternate = array('ssp-table--tr__odd', 'ssp-table--tr__even');
    $i = 0;
    $summary = 'summary="' . $t->t('{consent:consent:table_summary}') . '"';

    if (strlen($nameParent) > 0) {
        $parentStr = strtolower($nameParent) . '_';
        $str = '<div class="ssp-attrs--container"><table class="table" ' . $summary . '>';
    } else {
        $parentStr = '';
        $str = '<div class="ssp-attrs--container js-spread"><table id="table_with_attributes"  class="table ssp-table" '. $summary .'>';
    }

    $mandatoryAttributeNames = array("sn", "mail", "givenName", "eduPersonScopedAffiliation");
    $mandatoryAttributes = array();
    foreach($mandatoryAttributeNames as $el) {
        $mandatoryAttributes[$el] = array("");
    }

    $attributes = array_merge($mandatoryAttributes, $attributes);

    foreach ($attributes as $name => $value) {
        $nameraw = $name;
        $affliation = $name === 'eduPersonScopedAffiliation'; 
        $name = $t->getAttributeTranslation($parentStr . $nameraw);
        $missing = $value[0] === '' && in_array($nameraw, $mandatoryAttributeNames);

        if (preg_match('/^child_/', $nameraw)) {
            // insert child table
            $parentName = preg_replace('/^child_/', '', $nameraw);
            foreach ($value as $child) {
                $str .= "\n" . '<tr class="odd ssp--table--tr__odd"><td>' .
                    present_attributes($t, $child, $parentName) . '</td></tr>';
            }
        } else {
            // insert values directly
            
            if ($affliation) {
                $str .= "\n" . '<tr class="' . $alternate[($i++ % 2)] .
                    '"><td><div class="attrname ssp-table--attrname">' . $t->t('{themeopenminted:consent:affiliation_input_label}');
            } else {
                $str .= "\n" . '<tr class="' . $alternate[($i++ % 2)] .
                    '"><td><div class="attrname ssp-table--attrname">' . htmlspecialchars($name);
            }
            if ($missing) {
                $str .= ' (*)';
            }
            $str.= '</div>';

            $isHidden = in_array($nameraw, $t->data['hiddenAttributes'], true);
            if ($isHidden) {
                $hiddenId = SimpleSAML\Utils\Random::generateID();

                $str .= '<div class="attrvalue ssp-table--attrvalue" style="display: none;" id="hidden_' . $hiddenId . '">';
            } else {
                $str .= '<div class="attrvalue ssp-table--attrvalue">';
            }

            if (sizeof($value) > 1) {
                // we hawe several values
                $str .= '<ul class="list-unstyled ssp-table--attrvalue--list">';
                foreach ($value as $listitem) {
                    if ($nameraw === 'jpegPhoto') {
                        $str .= '<li class="ssp-table--attrvalue--list--item"><img src="data:image/jpeg;base64,' .
                            htmlspecialchars($listitem) .
                            '" alt="User photo" /></li>';
                    } else {
                        $str .= '<li class="ssp-table--attrvalue--list--item">' . htmlspecialchars($listitem) . '</li>';
                    }
                }
                $str .= '</ul>';
            } elseif (isset($value[0])) {
                // we hawe only one value
                if ($nameraw === 'jpegPhoto') {
                    $str .= '<img src="data:image/jpeg;base64,' .
                        htmlspecialchars($value[0]) .
                        '" alt="User photo" />';
                } elseif ($missing) {
                    $str .='<div><input name="'.$nameraw.'" class="form-control">';
                    $str .='<span class="mandatory">'.
                        $t->t('{themeopenminted:consent:mandatory_field_error}').
                        '</span>';
                    if ($nameraw === 'mail') {
                        $str .= '<span class="mail">'.
                        $t->t('{themeopenminted:consent:mail_field_error}').
                        '</span>';
                    }
                    $str .='</div>';
                } else {
                    $str .= htmlspecialchars($value[0]);
                }
            } // end of if multivalue
            $str .= '</div>';

            if ($isHidden) {
                $str .= '<div class="attrvalue consent_showattribute" id="visible_' . $hiddenId . '">';
                $str .= '<a class="consent_showattributelink ssp-btn__show-more" href="javascript:SimpleSAML_show(\'hidden_' . $hiddenId;
                $str .= '\'); SimpleSAML_hide(\'visible_' . $hiddenId . '\');"'
                  .' data-toggle="tooltip" data-placement="right" title="'. $t->t('{consent:consent:show_attribute}') .'">';
                $str .= '<span class="glyphicon glyphicon-eye-open ssp-show-more" aria-hidden="true"></span>';
                $str .= '</a>';
                $str .= '</div>';
            }

            $str .= '</td></tr>';
        }	// end else: not child table
    }	// end foreach
    $str .= isset($attributes)? '</table></div>':'';
    return $str;
}

 echo '<h2 class="subtle text-center">' .
      $this->t(
        '{themeopenminted:consent:header}',
          array( 'SPNAME' => $dstName, 'IDPNAME' => $srcName)).
      '<small>' .
      $this->t(
        '{themeopenminted:consent:subheader}',
          array( 'SPNAME' => $dstName, 'IDPNAME' => $srcName)).

      '</small>' .
      '</h2>
      <div class="row js-spread">
          <div class="col-sm-12 ssp-content-group js-spread">';
?>

<?php
echo present_attributes($this, $attributes, '');
?>

<form style="margin-bottom: 32px; padding: 0px"
    action="<?php echo htmlspecialchars($this->data['yesTarget']); ?>">
<div class="ssp-btns-container">
<p class"ssp-btns-container--checkbox>

<?php
if ($this->data['usestorage']) {
  $checked = ($this->data['checked'] ? 'checked="checked"' : '');
  echo '<input type="checkbox" name="saveconsent" ' . $checked .
      ' value="1" /> ' . $this->t('{consent:consent:remember}');
}

// Embed hidden fields...
foreach ($this->data['yesData'] as $name => $value) {
  echo '<input type="hidden" name="' . htmlspecialchars($name) .
      '" value="' . htmlspecialchars($value) . '" />';
}
?>
    <input type="hidden" name="userData"/>
  </p>
  <button type="submit" name="yes" class=" ssp-btn btn ssp-btn__action ssp-btns-container--btn__left text-uppercase" id="yesbutton">
      <?php echo htmlspecialchars($this->t('{consent:consent:yes}')) ?>
  </button>
</form>

<form style="display: inline-block;" action="<?php echo htmlspecialchars($this->data['noTarget']); ?>"
    method="get">

<?php
foreach ($this->data['noData'] as $name => $value) {
  echo('<input type="hidden" name="' . htmlspecialchars($name) .
      '" value="' . htmlspecialchars($value) . '" />');
}
?>
  <button type="submit" class="ssp-btn ssp-btn__secondary btn ssp-btns-container--btn__right text-uppercase" name="no" id="nobutton">
      <?php echo htmlspecialchars($this->t('{consent:consent:no}')) ?>
  </button>
</form>
</div> <!-- /ssp-btns-container -->
</div> <!-- /ssp-content-group -->
</div> <!-- /row -->
<?php
$this->includeAtTemplateBase('includes/footer.php');
?>
