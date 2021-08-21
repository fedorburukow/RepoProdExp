<? $_SERVER['DOCUMENT_ROOT'] = '/home/bitrix/ext_www/zdgf.ru';
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/zfdfgd.php");
\Bitrix\Main\Loader::IncludeModule("optid.local");
echo time();
$GLOBALS['noSync'] = true;
?>
<? CModule::IncludeModule("iblock");
$arSelect = array("ID", "IBLOCK_ID", 'PROPERTY_E_DATE', 'XML_ID');
$arFilter = array("IBLOCK_ID" => 0, "!PROPERTY_E_DATE" => false, 'XML_ID' => false);
$res = CIBlockElement::GetList(['ID' => 'ASC'], $arFilter, false, ['nTopCount' => 500], $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    // print_r($arFields);
    $arr[] = $arFields['ID'];
}
echo "\n" . count($arr);
// print_r($arr);
$post = [
    'token' => 'hacker_fack_you',
    'data' => $arr
];

$siteList = \Optid\Local\Project\Site::getList('ID', ['filter' => ['UF_ACTIVE' => true, 'ID' => 1]]);
$urlSad = $siteList[1]['UF_URL'];

$url = $urlSad . '/api/ssv.php';
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($post)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
// print_r($result);
if ($result != FALSE && $result != 'no') {
    $el = new CIBlockElement;
    echo "\n" . count(unserialize($result));
    foreach (unserialize($result) as $key => $value) {
        $arLoadProductArray = ['XML_ID' => $value['ID']];
        $el->Update($key, $arLoadProductArray);


        $r = CIBlockElement::SetPropertyValuesEx($key, 2, [
            'ORIGINAL_ID' => $value['ID'],
            'ORIGINAL_URL' => $urlSad . $value['DETAIL_PAGE_URL']
        ]);
    } ?>

<? }
?>

