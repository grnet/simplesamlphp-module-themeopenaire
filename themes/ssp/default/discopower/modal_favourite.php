<?php
$favEntry = null;
foreach ($this->data['idplist'] as $tab => $sList) {
  if (!empty($this->data['preferredidp']) && array_key_exists($this->data['preferredidp'], $sList)) {
    $favEntry = $sList[$this->data['preferredidp']];
  }
}
if(empty($favEntry)) {
  return;
}
?>

<div class="modal fade" id="favourite-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
         <span class="modal-button">
          <button type="button" class="js-close-custom close"><span aria-hidden="true">&times;</span></button>
         </span>
        <h2 class="modal-title"><?= $this->t('{themeopenaire:discopower:header}') ?></h2>
      </div>
      <div class="modal-body ssp-modal-body">
        <div class="row text-center">
          <form id="idpselectform" method="get" action="<?=$this->data['urlpattern'] ?>" class="ssp-form-favourite">
            <input type="hidden" name="entityID" value="<?=htmlspecialchars($this->data['entityID']) ?>"/>
            <input type="hidden" name="return" value="<?=htmlspecialchars($this->data['return']) ?>"/>
            <input type="hidden" name="returnIDParam" value="<?=htmlspecialchars($this->data['returnIDParam']) ?>"/>
            <input type="hidden" name="idpentityid" value="<?=htmlspecialchars($favEntry['entityid']) ?>"/>
            <input type="submit" name="formsubmit" id="favouritesubmit"
                   class="ssp-btn btn ssp-btn__btn-lg ssp-btn__lg text-uppercase remember-me"
                   value="<?= htmlspecialchars(getTranslatedName($this, $favEntry)) ?>"/>
          </form>
        </div>
        <div class="row text-center ssp-or"><?= $this->t('{themeopenaire:discopower_tabs:or_with}') ?></div>
        <div class="row text-center">
          <button class="ssp-btn btn ssp-btn__btn-lg ssp-btn__lg text-uppercase remember-me js-close-custom">
            <?= $this->t('{themeopenaire:discopower_tabs:change_account}') ?>
          </button>
        </div>
      </div> <!-- /modal-body -->
    </div> <!-- /modal-content -->
  </div> <!-- /modal-dialog -->
</div> <!-- /modal -->
