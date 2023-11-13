<?php

//Code
function getTransactionFiles($dirPath)
{
    $files = [];
    foreach(scandir($dirPath) as $file){
        if (is_dir($file))
        {
            continue;
        }
        else
        {
            $files[] = $dirPath.$file;
        }

    }
    return $files;
}
function getTransactions($fileName)
{
    if (!file_exists($fileName))
    {
        trigger_error("File " . $fileName . " does not exist", E_USER_ERROR);
    }
    $file = fopen($fileName, "r");
    fgetcsv($file);
    $transactions = [];
    while(($transaction = fgetcsv($file)) !== false)
    {
        $transactions[] = $transaction;
    }
    return $transactions;

}