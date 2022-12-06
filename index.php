<?php
/**
 * install方法
 * composerを利用しています
 * composer require symfony/finder
 * 
 * 事前に以下のように、wpxml2md を実行しておきます
 * node_modules/.bin/wpxml2md -i wordpress.2022-12-05.xml -o ./dist2 -r -with-metadata
 */
require_once __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Finder\Finder;

// ここを都度修正
$basedir = dirname(dirname(__FILE__)). '/donyu/20221206-111811/';
$orgdir= $basedir .'posts'; //wordpressから吐き出されたファイルからMDに変換した状態
$outputdir = $basedir .'doku';  //一箇所に平面的にtxt化させた状態
// ここまで

function getMDList($dir) {
    $finder = new Finder();
    $iterator = $finder
        ->in($dir) // ディレクトリを指定
        ->name('*.md') // ファイル名を指定（ワイルドカードを使用できる）
        ->files(); // ディレクトリは除外し、ファイルのみ取得
    $list = array();
    foreach ($iterator as $fileinfo) { // $fileinfoはSplFiIeInfoオブジェクト
        $list[] = $fileinfo->getPathname();
    }
    return $list;
}

// ファイルから特定の一行のみを取得
function getSpecificLineFromFile($filename, $lineNum=1) {
  $fp = fopen($filename, 'r');
  $targetLine = fgets($fp);
  fclose($fp);
  return trim($targetLine, "# ");
}
function mysort($array, $sortkey){
  foreach($array as $key=>$value){
    $id[$key] = $value[$sortkey];
  }
  var_dump($id);
  array_multisort($id, SORT_ASC, $array);
  return $array;
}

$filelist= getMDList($orgdir);
$filearray =[];

foreach ($filelist as $key => $file ){
    $filearray[$key]['title'] = getSpecificLineFromFile($file);
    $filearray[$key]['file'] = $file;
}
var_dump($filearray);

$filearray = mysort($filearray, "title");
//ファイル書き出し

$i = 1;
foreach($filearray as $file){
  copy($file['file'], $outputdir. '/'.$i.".md");
  $i++;
}

//exec('markdown-backup '. $outputdir .'"/*.md"'); 
//exec('find ./ -type f -name "*.md" -exec sh -c \'mv "$0" "${0%..md}.txt"\' {} \;');

var_dump($filearray);
