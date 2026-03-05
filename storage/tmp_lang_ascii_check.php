<?php
$root=dirname(__DIR__);
$en=include $root.'/lang/en/site.php';
$ru=include $root.'/lang/ru/site.php';
$ar=include $root.'/lang/ar/site.php';
function flat(array $a,string $p=''): array { $o=[]; foreach($a as $k=>$v){$key=$p===''?$k:$p.'.'.$k; if(is_array($v)){$o+=flat($v,$key);} else {$o[$key]=(string)$v;} } return $o; }
$fen=flat($en); $fru=flat($ru); $far=flat($ar);
$ruAscii=[]; $arAscii=[];
foreach($fen as $k=>$v){
    if(!isset($fru[$k])) continue;
    if($fru[$k]!==$v && preg_match('/^[\x00-\x7F]+$/', $fru[$k])) $ruAscii[$k]=$fru[$k];
    if(isset($far[$k]) && $far[$k]!==$v && preg_match('/^[\x00-\x7F]+$/', $far[$k])) $arAscii[$k]=$far[$k];
}
echo "ru_ascii_non_en=".count($ruAscii)."\n";
foreach($ruAscii as $k=>$v){echo "RU|$k|$v\n";}
echo "ar_ascii_non_en=".count($arAscii)."\n";
foreach($arAscii as $k=>$v){echo "AR|$k|$v\n";}
