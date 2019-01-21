<?php
    if(isset($argv[1])) {
        $input = urlencode($argv[1]);
        $query = 'https://www.googleapis.com/books/v1/volumes?q='.$input;
        $file = file_get_contents($query, TRUE);
        $found = json_decode($file, TRUE);
        if (!function_exists('json_last_error_msg')) {
            function json_last_error_msg() {
                static $ERRORS = array(
                    JSON_ERROR_NONE => 'Ошибок нет',
                    JSON_ERROR_DEPTH => 'Превышена максимальная глубина стека',
                    JSON_ERROR_STATE_MISMATCH => 'Несоответствие состояний (недопустимый или неправильный JSON)',
                    JSON_ERROR_CTRL_CHAR => 'Ошибка символа, возможно, неверная кодировка',
                    JSON_ERROR_SYNTAX => 'Синтаксическая ошибка',
                    JSON_ERROR_UTF8 => 'Искаженные символы UTF-8, возможно, неправильно закодирован'
                );
                $error = json_last_error();
                return isset($ERRORS[$error]) ? $ERRORS[$error] : 'Неизвестная ошибка';
            }
        }
        foreach($found['items'] as $item) {
            $book[] = $item['id'];
            $book[] = $item['volumeInfo']['title'];
            if(!empty($item['volumeInfo']['authors']))
                foreach($item['volumeInfo']['authors'] as $author)
                    $book[] = $author;
            $books[] = $book;
            unset($book);
        }
        if(!empty($file = fopen('books.csv', 'a'))) {
            foreach ($books as $book)
                fputcsv($file, $book, ',');
        }
        fclose($file);
        echo "Пожалуйста, смотрите результаты поиска в разделе 'books.csv'.\n";
    } else echo "Пожалуйста, введите поисковый запрос.\n"
?>
