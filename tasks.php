<?php

/**
 * Class MultiTable
 * Задача: заполнить таблицу умножения и вывести в stdout. Столбцы дожлны быть минимально возможной ширины,
 * значения выравнены по правому краю столбцов. Позаботиться о повышении быстродействия при построении таблицы.
 */
class MultiTable
{
    protected array $columnWidths = [];
    protected array $values = [];
    protected int $yAxisWidth;

    protected const Y_DELIMITER = '|';
    protected const X_DELIMITER = '-';

    /**
     * MultiTable constructor.
     * @param int $length - размер таблицы
     * @throws Exception
     */
    public function __construct(
        protected int $length
    )
    {
        if ($this->length < 1) {
            throw new Exception('Parameter must be an integer greater than 0');
        }
        $this->calculateYAxisWidth();
        $this->fillTableValues();
        $this->calculateColumnsWidth();
    }

    /**
     * Отрисовка таблицы в stdout
     */
    public function render()
    {
        echo $this->renderTitle() . "\n";
        for ($i = 1; $i <= $this->length; $i++) {
            echo $this->renderRow($i) . "\n";
        }
    }

    /**
     * Расчет ширины столбца с заголовками строк
     */
    protected function calculateYAxisWidth()
    {
        $this->yAxisWidth = mb_strlen(strval(pow($this->length, 2)));
    }

    /**
     * Расчет ширины столбцов
     */
    protected function calculateColumnsWidth()
    {
        for ($i = 1; $i <= $this->length; $i++) {
            $this->columnWidths[$i] = mb_strlen($this->length * $i);
        }
    }

    /**
     * Отрисовка строки
     * @param $rowNumber
     * @return string
     */
    protected function renderRow($rowNumber): string
    {
        $row = str_pad(strval($rowNumber), $this->yAxisWidth, ' ', STR_PAD_LEFT) .
            ' ' . static::Y_DELIMITER . ' ';
        for ($i = 1; $i <= $this->length; $i++)
        {
            $row .= str_pad(
                strval($this->values[$rowNumber][$i]),
                    $this->columnWidths[$i],
                    ' ',
                    STR_PAD_LEFT
                ) . ' ';
        }
        return $row;
    }

    /**
     * Отрисовка строки с заголовками столбцов
     * @return string
     */
    protected function renderTitle(): string
    {
        $title = str_repeat(' ', $this->yAxisWidth + 3);
        for ($i = 1; $i <= $this->length; $i++) {
            $title .= str_pad(strval($i), $this->columnWidths[$i], ' ', STR_PAD_LEFT) . ' ';
        }
        $title .= "\n" . str_repeat(static::X_DELIMITER, mb_strlen($title) - 1);
        return $title;
    }

    /**
     * Заполнение таблицы значений
     */
    protected function fillTableValues()
    {
        for ($i = 1; $i <= $this->length; $i++) {
            for ($j = $i; $j <= $this->length; $j++) {

                if ($j > 1) {
                    $value = $this->values[$i][$j-1] + $i;
                } elseif ($i > 1) {
                    $value = $this->values[$i-1][$j] + $j;
                }
                else {
                    $value = $i * $j;
                }

                $this->values[$i][$j] = $value;

                if ($i !== $j) {
                    $this->values[$j][$i] = $value;
                }
            }
        }
    }
}

/**
 * Class Endings
 * Задача: написать метод/функцию, возвращающий переданное в него количество и слово "компьютер" в родительском падеже.
 */
class Endings
{
    protected static array $endingVariants = [
        ['','а','ов',],
        ['а','ы',''],
    ];
    protected static array $roots = [
        'компьютер' => [
            'root' => 'компьютер',
            'endings' => 0
        ],
        'машина' => [
            'root' => 'машин',
            'endings' => 1
        ],
    ];
    protected static array $endingsByTail = [2, 0, 1, 1, 1, 2, 2, 2, 2, 2];

    /**
     * @param int $number
     * @param string $word
     * @return string
     * @throws Exception
     */
    public static function getParental(int $number, $word = 'компьютер'): string
    {
        if (!isset(static::$roots[$word])) {
            throw new Exception('I do not know that word');
        }

        $tail = $number % 10;

        $endings = static::$endingVariants[static::$roots[$word]['endings']];

        return strval($number) . ' ' .
            static::$roots[$word]['root'] .
            $endings[static::$endingsByTail[$tail]];
    }
}



try {
    echo "Task #1 output:\n";
    $table = new MultiTable(5);
    $table->render();
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

try {
    echo "Task #2 output:\n" . Endings::getParental(23) . "\n";
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}