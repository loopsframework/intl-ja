#!/usr/bin/env php
<?php
/**
 * This file is part of the loops framework.
 *
 * @author Lukas <lukas@m-t.com>
 * @package loops
 * @license https://raw.githubusercontent.com/loopsframework/base/master/LICENSE
 * @link https://github.com/loopsframework/base
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;

$filename_ja = "http://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip";
$filename_en = "http://www.post.japanpost.jp/zipcode/dl/roman/ken_all_rome.zip";

//default values
$apppath   = "app";
$cachepath = "cache";
$config    = "config/config.php";
$boot      = "boot.php";
$tablename = "loops_intl_ja_postal";

//include autoloader
require_once(dirname(__FILE__)."/../../../autoload.php");

//check for user parameter
if(!empty($argv[1])) $apppath = $argv[1];
if(!empty($argv[2])) $cachepath = $argv[2];
if(!empty($argv[3])) $config = $argv[3];
if(!empty($argv[4])) $boot = $argv[4];

//quick and dirty errorchecking
if(!is_dir($apppath) || !is_dir($cachepath) || !file_exists("$apppath/$config")) {
    echo "Initializing the postal database table failed.\n";
    echo "\n";
    echo "Usage: ./loops-intl-ja-createziptable.sh [apppath [cachepath [ config [bootscript]]]]\n";
    echo "  Apppath:    '$apppath'\n";
    echo "  Cachepath:  '$cachepath'\n";
    echo "  Config:     '$config'\n";
    echo "  Bootscript: '$boot'\n";
    exit;
}

//get the phalcon di + some services
$di = Loops::run(realpath($apppath), $cachepath, $config, $boot, TRUE);

$db          = $di->get('db');
$config      = $di->get('config');


//present database setting to user
echo "Your database connection settings:\n";
foreach($config->database as $key => $value) {
    echo "  $key: $value\n";
}
echo "\n";

//ask to continue
echo "Do you want to insert/update japanese postal schema and data into the database now?
Type 'y' to continue: ";
$fh = fopen ("php://stdin", "r");
$line = fgets($fh);
if(trim($line) != 'y') exit;

echo "\n";

//create table + index
if(!$db->tableExists($tablename)) {
    $db->createTable($tablename, NULL, [
        'columns' => [
            new Column("hash",              [ "type" => Column::TYPE_VARCHAR, "size" => 32, "notNull" => TRUE, 'primary' => TRUE ]),
            new Column("regioncode",        [ "type" => Column::TYPE_VARCHAR, "size" => 5, "notNull" => TRUE ]),
            new Column("old_zip",           [ "type" => Column::TYPE_VARCHAR, "size" => 5, "notNull" => TRUE ]),
            new Column("zip",               [ "type" => Column::TYPE_VARCHAR, "size" => 7, "notNull" => TRUE ]),
            new Column("zip1",              [ "type" => Column::TYPE_VARCHAR, "size" => 3, "notNull" => TRUE ]),
            new Column("zip2",              [ "type" => Column::TYPE_VARCHAR, "size" => 4, "notNull" => TRUE ]),
            new Column("prefecture",        [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("prefecture_kana",   [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("prefecture_en",     [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("city",              [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("city_kana",         [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("city_en",           [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("district",          [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("district_kana",     [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("district_en",       [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("districtextra",     [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("districtextra_kana",[ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("districtextra_en",  [ "type" => Column::TYPE_TEXT, "notNull" => TRUE ]),
            new Column("more",              [ "type" => Column::TYPE_BOOLEAN, "notNull" => TRUE ]),
            new Column("detailed",          [ "type" => Column::TYPE_BOOLEAN, "notNull" => TRUE ]),
            new Column("block",             [ "type" => Column::TYPE_BOOLEAN, "notNull" => TRUE ]),
            new Column("multiple",          [ "type" => Column::TYPE_BOOLEAN, "notNull" => TRUE ]),
            new Column("renewed",           [ "type" => Column::TYPE_BOOLEAN, "notNull" => TRUE ]),
            new Column("renewed_reason",    [ "type" => Column::TYPE_INTEGER, "notNull" => TRUE ])
            ]
        ]
    );
    
    $db->addIndex($tablename, NULL, new Index($tablename."_index_zip", ['zip']));
}

//download csv file
echo "Downloading csv files...\n";
$zip_ja = "{$config->paths->cache}/intl_ja_postal_zip_ja.zip";
$zip_en = "{$config->paths->cache}/intl_ja_postal_zip_en.zip";
file_put_contents($zip_ja, file_get_contents($filename_ja));
file_put_contents($zip_en, file_get_contents($filename_en));
echo "done.\n\n";

//open zip file 
$result = array();
if(!is_resource($zip = zip_open($zip_ja))) return FALSE;
if(!$entry = zip_read($zip)) return FALSE;
if(!zip_entry_open($zip, $entry)) return FALSE;
if(!$content = zip_entry_read($entry, zip_entry_filesize($entry))) return FALSE;
$lines = explode("\n",$content);

//prepare japanese data and create a database friendly row
$max = count($lines);
$i = 0;
$time = microtime(TRUE);
echo "Preparing '$zip_ja'\n";
foreach($lines as $line) {
    $i++;
    
    if(!$line) continue;
    
    $parts = explode(",", trim($line));
    $parts = array_map(function($p) { return trim(mb_convert_kana(mb_convert_encoding($p, "UTF-8", "Shift-JIS"), "KV", "UTF-8"), '"'); }, $parts);
    
    $parts[7] = str_replace('　', '', $parts[7]);
    $parts[8] = str_replace('　', '', $parts[8]);
    
    if($parts[8] == '以下に掲載がない場合') {
        $parts[8] = '';
    }
    
    if($parts[5] == 'イカニケイサイガナイバアイ') {
        $parts[5] = '';
    }
    
    $key = md5($parts[2].$parts[6].$parts[7].$parts[8]);
    
    if(!$parts) continue;
    
    $parts[] = substr($parts[2], 0, 3);
    $parts[] = substr($parts[2], 3);
    $parts[] = preg_match("/（(.*?)）/", $parts[8], $match) ? $match[1] : "";
    if(!empty($match[0])) {
        $parts[8] = str_replace($match[0], "", $parts[8]);
    }
    
    $parts[] = preg_match("/\((.*?)\)/", $parts[5], $match) ? $match[1] : "";
    if(!empty($match[0])) {
        $parts[5] = str_replace($match[0], "", $parts[5]);
    }
    
    $parts[] = "";
    $parts[] = "";
    $parts[] = "";
    $parts[] = "";
    
    $result[$key] = $parts;
    
    if(microtime(TRUE) > $time) {
        $time = microtime(TRUE) + 1;
        echo "\r[".sprintf("%5.2f", $i*100/$max)."%]";
    }
}
echo "\r[100.00%]\n\n";

//open english zip file
if(!is_resource($zip = zip_open($zip_en))) return FALSE;
if(!$entry = zip_read($zip)) return FALSE;
if(!zip_entry_open($zip, $entry)) return FALSE;
if(!$content = zip_entry_read($entry, zip_entry_filesize($entry))) return FALSE;
$lines = explode("\n",$content);

//prepare/append english data to records
$max = count($lines);
$i = 0;
$time = microtime(TRUE);
echo "Preparing '$zip_en'\n";
foreach($lines as $line) {
    $i++;
    
    if(!$line) continue;
    
    $parts = explode(",", trim($line));
    $parts = array_map(function($p) { return trim(mb_convert_kana(mb_convert_encoding($p, "UTF-8", "Shift-JIS"), "KV", "UTF-8"), '"'); }, $parts);
    if($parts[6] == 'IKANIKEISAIGANAIBAAI') {
        $parts[6] = '';
    }
    
    $parts[2] = str_replace('　', '', $parts[2]);
    $parts[3] = str_replace('　', '', $parts[3]);
    
    $key = md5($parts[0].$parts[1].$parts[2].$parts[3]);
    
    if(empty($result[$key])) {
        $result[$key] = [
            "", "", $parts[0], "", "", "", $parts[1], $parts[2], $parts[3],
            "", "", "", "", "", "",
            substr($parts[0], 0, 3), substr($parts[0], 3) , "", ""
        ];
    }

    $result[$key][19] = $parts[4];
    $result[$key][20] = $parts[5];
    $result[$key][21] = $parts[6];
    $result[$key][22] = preg_match("/\((.*?)\)/", $parts[6], $match) ? $match[1] : "";
    
    if(!empty($match[0])) {
        $result[$key][21] = str_replace($match[0], "", $result[$key][21]);
    }
    
    if(microtime(TRUE) > $time) {
        $time = microtime(TRUE) + 1;
        echo "\r[".sprintf("%5.2f", $i*100/$max)."%]";
    }
}
echo "\r[100.00%]\n\n";


//prepare query functions for inserting/updating
$insertmode = TRUE;
$insert = function($hash, $row) use ($tablename, $db) {
    return $db->insert($tablename, array_merge([$hash], $row),
        [ 'hash',
          'regioncode', 'old_zip', 'zip', 'prefecture_kana', 'city_kana', 'district_kana', 'prefecture', 'city', 'district',
          'more', 'detailed', 'block', 'multiple', 'renewed', 'renewed_reason',
          'zip1', 'zip2', 'districtextra', 'districtextra_kana', 'prefecture_en', 'city_en', 'district_en', 'districtextra_en' ] );
};

$update = function($hash, $row) use ($tablename, $db) {
    return $db->update($tablename,
        [ 'regioncode', 'old_zip', 'zip', 'prefecture_kana', 'city_kana', 'district_kana', 'prefecture', 'city', 'district',
          'more', 'detailed', 'block', 'multiple', 'renewed', 'renewed_reason',
          'zip1', 'zip2', 'districtextra', 'districtextra_kana', 'prefecture_en', 'city_en', 'district_en', 'districtextra_en' ],
        $row, "hash = '$hash'" );
};


//insert/update records
echo "Inserting/Updating (This might take a while)...\n";

$max = count($result);
$i = 0;
$time = microtime(TRUE);

foreach($result as $hash => $row) {
    $i++;
    if($insertmode) {
        try {
            $success = $insert($hash, $row);
        }
        catch(Exception $e) {
            $insertmode = FALSE;
            
            try {
                $success = $update($hash, $row);
            }
            catch(Exception $e) {
            }
        }
    }
    else {
        try {
            $success = $update($hash, $row);
        }
        catch(Exception $e) {
            $insertmode = TRUE;
            
            try {
                $success = $insert($hash, $row);
            }
            catch(Exception $e) {
            }
        }
    }
    
    if(microtime(TRUE) > $time) {
        $time = microtime(TRUE) + 1;
        echo "\r[".sprintf("%5.2f", $i*100/$max)."%]";
    }
}

echo "\r[100.00%]\n\n;
All done.\n";
