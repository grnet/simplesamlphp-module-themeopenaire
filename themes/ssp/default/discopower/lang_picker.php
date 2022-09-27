<?php

$languages = $this->getLanguageList();

if ((isset($this->data['hideLanguageBar'])
    && $this->data['hideLanguageBar'] === true)
  || count($languages) < 2) {
  return;
}

$self_url     = \SimpleSAML\Utils\HTTP::getSelfURL();
$lang_current = "";
$langnames    = [
  'no'    => 'Bokmål',                // Norwegian Bokmål
  'nn'    => 'Nynorsk',               // Norwegian Nynorsk
  'se'    => 'Sámegiella',            // Northern Sami
  'sam'   => 'Åarjelh-saemien giele', // Southern Sami
  'da'    => 'Dansk',                 // Danish
  'en'    => 'English',
  'de'    => 'Deutsch',                  // German
  'sv'    => 'Svenska',                  // Swedish
  'fi'    => 'Suomeksi',                 // Finnish
  'es'    => 'Español',                  // Spanish
  'fr'    => 'Français',                 // French
  'it'    => 'Italiano',                 // Italian
  'nl'    => 'Nederlands',               // Dutch
  'lb'    => 'Lëtzebuergesch',           // Luxembourgish
  'cs'    => 'Čeština',                  // Czech
  'sl'    => 'Slovenščina',              // Slovensk
  'lt'    => 'Lietuvių kalba',           // Lithuanian
  'hr'    => 'Hrvatski',                 // Croatian
  'hu'    => 'Magyar',                   // Hungarian
  'pl'    => 'Język polski',             // Polish
  'pt'    => 'Português',                // Portuguese
  'pt-br' => 'Português brasileiro',     // Portuguese
  'ru'    => 'русский язык',             // Russian
  'et'    => 'eesti keel',               // Estonian
  'tr'    => 'Türkçe',                   // Turkish
  'el'    => 'ελληνικά',                 // Greek
  'ja'    => '日本語',                   // Japanese
  'zh'    => '简体中文',                 // Chinese (simplified)
  'zh-tw' => '繁體中文',                 // Chinese (traditional)
  'ar'    => 'العربية',                  // Arabic
  'fa'    => 'پارسی',                    // Persian
  'ur'    => 'اردو',                     // Urdu
  'he'    => 'עִבְרִית',                 // Hebrew
  'id'    => 'Bahasa Indonesia',         // Indonesian
  'sr'    => 'Srpski',                   // Serbian
  'lv'    => 'Latviešu',                 // Latvian
  'ro'    => 'Românește',                // Romanian
  'eu'    => 'Euskara',                  // Basque
];

$languages_html_list = '';
foreach ($languages as $lang => $current) {
  $lang = strtolower($lang);
  if ($current) {
    $lang_current = $langnames[$lang];
  } else {
    $href                = htmlspecialchars(
      \SimpleSAML\Utils\HTTP::addURLParameters($self_url, array($this->languageParameterName => $lang))
    );
    $languages_html_list .= '<li class="ssp-dropdown__two_cols--item language-item">';
    $languages_html_list .= "<a class=\"js-pick-language\" href=\"{$href}\">{$langnames[$lang]}</a>";
    $languages_html_list .= '</li>';
  }
}

?>

<div class="ssp-lang-container">
  <div class="dropup">
    <button id="dropDownMenuLanguages"
            class="btn ssp-btn__footer dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="true">
      <?= $lang_current ?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-left ssp-dropdown__two_cols" aria-labelledby="dropDownMenuLanguages">
      <?= $languages_html_list ?>
    </ul>
  </div>
</div>
