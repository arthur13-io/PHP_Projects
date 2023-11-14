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
function getTransactions($fileName, ?callable $transactionHandler = null ):array
{
    if (!file_exists($fileName))
    {
        trigger_error("File " . $fileName . " does not exist", E_USER_ERROR);
    }
    $file = fopen($fileName, "r");
    fgetcsv($file); //This discards the first line in the file
    $transactions = [];
    while(($transaction = fgetcsv($file)) !== false)
    {
        if ($transactionHandler !== null)
        {
            $transaction = $transactionHandler($transaction);
        }
        $transactions[] = $transaction;
    }
    return $transactions;
}
function extractTransaction(array $transactionRow):array{
    [$date, $checkNumber, $description, $amount] = $transactionRow;
    $amount = (float) str_replace(['$', ','], '', $amount);

    return [
        "date" => $date,
        "checkNumber" => $checkNumber,
        "description" => $description,
        "amount" => $amount
    ];
}
function calculateTotals(array $transactions):array
{
    $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];
    foreach ($transactions as $transaction)
    {
        $totals['netTotal'] += $transaction['amount'];
        if ($transaction['amount'] >= 0){
            $totals['totalIncome'] += $transaction['amount'];
        }
        else{
            $totals['totalExpense'] += $transaction['amount'];
        }
    }
    return $totals;
}