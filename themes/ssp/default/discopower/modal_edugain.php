<div class="modal fade" id="edugain-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-button">
          <button type="button" class="js-close-custom close"><span aria-hidden="true">&times;</span></button>
        </span>
        <h2 class="modal-title"><?= $this->t('{themeopenaire:discopower_tabs:' . $this->data['tab'] . '}') ?></h2>
      </div>
      <div class="modal-body ssp-modal-body">
        <div class="row">
          <div class="input-group">
            <form id="idpselectform" action="?" method="get">
              <input class="form-control"
                     aria-describedby="search institutions"
                     placeholder="Search..." type="text" value=""
                     name="query_<?= $this->data['tab'] ?>"
                     id="query_<?= $this->data['tab'] ?>"/>
            </form>
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            </span>
          </div> <!-- /input-group -->
          <div class="metalist ssp-content-group__provider-list ssp-content-group__provider-list--edugain js-spread"
               id="list_<?= $this->data['tab'] ?>">
            <?php
            if (!empty($this->data['preferredidp'])
                && array_key_exists($this->data['preferredidp'], $this->data['slist'])) {
              $this->data['metadata'] = $this->data['slist'][$this->data['preferredidp']];
              $this->includeAtTemplateBase('discopower/show_entry.php');
            }

            foreach ($this->data['slist'] as $idpentry) {
              if ($idpentry['entityid'] != $this->data['preferredidp']) {
                $this->data['metadata'] = $idpentry;
                $this->includeAtTemplateBase('discopower/show_entry.php');
              }
            }
            ?>
          </div> <!-- /metalist -->
        </div> <!-- /row -->
      </div> <!-- /modal-body -->
      <div class="modal-footer ssp-text-left">
        <?php print $this->includeAtTemplateBase('discopower/lang_picker.php'); ?>
      </div>
    </div> <!-- /modal-content -->
  </div> <!-- /modal-dialog -->
</div> <!-- /modal -->
<div class="row ssp-content-group <?php print !empty($this->data['preferredidp']) ? 'hidden' : ''; ?>">
  <div class="col-sm-12 text-center">
    <h3 class="disco"><?= $this->t('{themeopenaire:discopower:connect_with}') ?></h3>
    <button type="button" class="ssp-btn btn ssp-btn__btn-lg ssp-btn__lg text-uppercase" data-toggle="modal"
            data-target="#edugain-modal">
      <img class="round"
           src="<?= SimpleSAML\Module::getModuleURL('themeopenaire/resources/images/edugain.png') ?>">
      <?= $this->t('{themeopenaire:discopower_tabs:' . $this->data['tab'] . '}') ?>
    </button>
  </div>
</div>