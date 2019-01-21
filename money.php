<?php
    $csv = 'costlist.csv';
    if (isset($argv[1]) && isset($argv[2])) {
        $file = fopen($csv, 'a');
        if($file) {
            $row[] = date('Y-m-d');
            $row[] = $argv[1];
            $purchase = implode(' ', array_slice($argv, 2));
            $row[] = $purchase;
            fputcsv($file, $row, ";");
            $expense = implode(', ',$row);
            echo "Добавлена строка: $expense\n";
            fclose($file);
        } else echo "Ошибка! Доступ для записи .файл csv запрещен.\n";
    } elseif(isset($argv[1]) && $argv[1] == '--today') {
        if(is_readable($csv)) {
            $file = fopen($csv, 'r');
            $sum = 0;
            while(($expense = fgetcsv($file, '1000', ";")) !== FALSE)
                if($expense[0] === date('Y-m-d'))
                    $sum += $expense[1];
            echo date('Y-m-d')." текущие расходы $sum\n";
            fclose($file);
        } else echo ".CSV-файл с расходами не существует!\n";
    } else echo "Ошибка! Аргументы не заданы. Укажите флаг --today или запустите скрипт с аргументами {цена} и {описание покупки}\n";
?>
