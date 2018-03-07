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
        $str = '<div class="ssp-attrs--container"><table id="table_with_attributes"  class="table ssp-table" '. $summary .'>';
    }

    $mandatoryAttributeNames = array("sn", "mail", "givenName");
    $mandatoryAttributes = array();
    $editableAttributes = array("consentO");
    foreach($mandatoryAttributeNames as $el) {
        $mandatoryAttributes[$el] = array("");
    }

    $attributes = array_merge($mandatoryAttributes, $attributes);

    $attributeOrder = array(
        'sn',
        'givenName',
        'displayName',
        'mail',
        'eduPersonScopedAffiliation',
        'o',
        'consentO',
        'eduPersonEntitlement',
        'Entitlement',
        'eduPersonAssurance',
        'eduPersonUniqueId',
        'termsAccepted',
    );
    $newAttributes = array();
    foreach ($attributeOrder as $attrKey) {
        if (!empty($attributes[$attrKey])) {
            $newAttributes[$attrKey] = $attributes[$attrKey];
            unset($attributes[$attrKey]);
        }
    }
    $attributes = array_merge($newAttributes, $attributes);



    foreach ($attributes as $name => $value) {
        $nameraw = $name;
        $affliation = $name === 'eduPersonScopedAffiliation'; 
        $name = $t->getAttributeTranslation($parentStr . $nameraw);
        $missing = $value[0] === '' && in_array($nameraw, $mandatoryAttributeNames);
        $editable = in_array($nameraw, $editableAttributes);
        $isHidden = in_array($nameraw, $t->data['hiddenAttributes'], true);
        


        if ($isHidden) {
            continue;
        }

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
                    '"><td><div class="attrname ssp-table--attrname">' . $t->t('{themeopenaire:consent:affiliation_input_label}');
            } else {
                $str .= "\n" . '<tr class="' . $alternate[($i++ % 2)] .
                    '"><td><div class="attrname ssp-table--attrname">' . htmlspecialchars($name);
            }
            if ($missing) {
                $str .= ' (*)';
            }
            $str.= '</div>';

            $str .= '<div class="attrvalue ssp-table--attrvalue">';

            if (sizeof($value) > 1) {
                // we hawe several values
                $str .= '<ul class="list-unstyled ssp-table--attrvalue--list">';
                $index = 0;
                foreach ($value as $listitem) {
                    $index++;
                    if ($nameraw === 'jpegPhoto') {
                        $str .= '<li class="ssp-table--attrvalue--list--item"><img src="data:image/jpeg;base64,' .
                            htmlspecialchars($listitem) .
                            '" alt="User photo" /></li>';
                    } elseif ($nameraw === 'mail') { 
                        $str .= '<li class="ssp-table--attrvalue--list--item">';
                        $str .= '<label for="mail'.$index.'">';
                        $str .= '<input type="radio" class="form-control" name="mail" value="'.$listitem.'" id="mail'.$index.'" ';
                        if ($index === 1) {
                            $str .= 'checked';
                        }
                        $str .= ' >';
                        $str .= $listitem;
                        $str .= '</label>';
                    } else {
                        $str .= '<li class="ssp-table--attrvalue--list--item">' . htmlspecialchars($listitem) . '</li>';
                    }
                }
                $str .= '</ul>';
                if ($nameraw === 'mail') {
                    $str .='<i class="ssp-form--hint">';
                    $str .= $t->t('{themeopenminted:consent:multiple_mails_tip}');
                    $str .='</i>';
                }
            } elseif (isset($value[0])) {
                // we hawe only one value
                if ($nameraw === 'jpegPhoto') {
                    $str .= '<img src="data:image/jpeg;base64,' .
                        htmlspecialchars($value[0]) .
                        '" alt="User photo" />';
                } elseif ($nameraw === 'termsAccepted') {
                    $str .='<div><input type="checkbox" value="hasAcceptedTerms" class="form-control" name="'.$nameraw.'" ';
                    if ($value[0] === true ) {
                        $str  .= ' checked ';
                    }
                    $str .=' />';
                    $str .= '<span class="mandatory">'.
                    $t->t('{themeopenminted:consent:terms_field_error}').
                    '</span>';
                    $str .= '</div>';

                } elseif ($editable) {
                    $str .='<div><input name="'.$nameraw.'" class="form-control" value="'.$value[0].'"></div>';
                } elseif ($missing) {
                    $str .='<div><input name="'.$nameraw.'" class="form-control">';
                    $str .='<span class="mandatory">'.
                        $t->t('{themeopenaire:consent:mandatory_field_error}').
                        '</span>';
                    if ($nameraw === 'mail') {
                        $str .= '<span class="mail">'.
                        $t->t('{themeopenaire:consent:mail_field_error}').
                        '</span>';
                    }
                    $str .='</div>';
                } else {
                    $str .= htmlspecialchars($value[0]);
                }

            } // end of if multivalue
            $str .= '</div>';
            $str .= '</td></tr>';
        }	// end else: not child table
    }	// end foreach
    $str .= isset($attributes)? '</table></div>':'';
    return $str;
}

 echo '<h2 class="subtle text-center">' .
      $this->t(
        '{themeopenaire:consent:header}',
          array( 'SPNAME' => $dstName, 'IDPNAME' => $srcName)).
      '<small>' .
      $this->t(
        '{themeopenaire:consent:subheader}',
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
