<?php
$this->data['header'] = $this->t('{themeopenaire:attrauthcomanage:exception_header}');

$this->includeAtTemplateBase('includes/header.php');
?>
<h1><?= $this->t('{themeopenaire:attrauthcomanage:exception_title}') ?></h1>

<?= $this->t('{themeopenaire:attrauthcomanage:exception_description}') ?>
<pre>
<?php
$tag = preg_replace('/attrauthcomanage:/', 'themeopenaire:', $this->data['e'], 1);
echo !empty($this->getTag('{' . $tag . '}'))
? $this->t('{' . $tag . '}', $this->data['parameters'])
: $this->data['e'];
?>
</pre>

<?php
$this->includeAtTemplateBase('includes/footer.php');
