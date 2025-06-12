<?php
class Prvek
{
    public string|int $hodnota;
    public ?Prvek $dalsi;

    public function __construct(int|string $hodnota)
    {
        $this->hodnota = $hodnota;
        $this->dalsi = null;
    }
}

class LinkedSeznam
{
    protected string $typ;
    protected ?Prvek $pointer = null;

    public function __construct(string $typ)
    {
        $this->nastavTyp($typ);
    }

    private function nastavTyp(string $typ): void
    {
        if ($typ === "int" || $typ === "string") {
            $this->typ = $typ;
        } else {
            throw new InvalidArgumentException("Povolené typy jsou 'int' nebo 'string'.");
        }
    }

    public function zkontrolujTyp(string|int $hodnota)
    {
        if (($this->typ == "int" && is_int($hodnota)) || ($this->typ == "string" && is_string($hodnota))) {
            return;
        } else {
            throw new InvalidArgumentException("Seznam je: {$this->typ}");
        }
    }

    public function vloz(string|int $hodnota): void
    {
        $this->zkontrolujTyp($hodnota);

        $novyPrvek = new Prvek($hodnota);

        if ($this->pointer === null) {
            //první prvek
            $this->pointer = $novyPrvek;
            return;
        }

        if ($this->porovnej($hodnota, $this->pointer->hodnota) < 0) {
            $novyPrvek->dalsi = $this->pointer;
            $this->pointer = $novyPrvek;
            return;
        }

        $aktualni = $this->pointer;
        while ($aktualni->dalsi !== null && $this->porovnej($hodnota, $aktualni->dalsi->hodnota) >= 0) {
            $aktualni = $aktualni->dalsi;
        }

        $novyPrvek->dalsi = $aktualni->dalsi;
        $aktualni->dalsi = $novyPrvek;
    }

    public function porovnej($novaHodnota, $predchozi): int
    {
        if ($this->typ == 'int') {
            return $novaHodnota <=> $predchozi;
        } else {
            return strcmp($novaHodnota, $predchozi);
        }
    }


    public function vypisSeznam(): void
    {
        $aktualni = $this->pointer;
        while ($aktualni !== null) {
            echo $aktualni->hodnota . PHP_EOL;
            $aktualni = $aktualni->dalsi;
        }
    }
}




$novySeznam = new LinkedSeznam("int");
$novySeznam->vloz(2);
$novySeznam->vloz(5);
$novySeznam->vloz(1);
$novySeznam->vloz(10); 
$novySeznam->vloz(1);
$novySeznam->vloz(100);
$novySeznam->vloz(99);
$novySeznam->vloz(2);

echo $novySeznam->vypisSeznam();
