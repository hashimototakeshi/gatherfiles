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
$basedir = dirname(dirname(__FILE__)). '/basic-codey/20221216-184214/';
$orgdir= $basedir .'posts'; //wordpressから吐き出されたファイルからMDに変換した状態
$outputdir = $basedir .'dist';  //一箇所に平面的にtxt化させたものを置く場所（予めフォルダを作成）
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
  $targetLine = trim($targetLine);
  return trim($targetLine, "# ");
}
function mysort($array, $sortkey){
    foreach($array as $key=>$value){
        preg_match('/(.*)第(\d+)回(.*)/u', $value[$sortkey], $m);
        if (isset($m[2])){
            $id[$key] = $m[2];
        }else{
            $id[$key] = 100;
        }
        //preg_match('/(.*)第(\d+)回(.*)/u', 'あいうえお第2回ddddd', $m);
        //$m[0] = あいうえお第2回ddddd
        //$m[1] = あいうえお
        //$m[2] = 2
    //$id[$key] = $value[$sortkey];
  }
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
