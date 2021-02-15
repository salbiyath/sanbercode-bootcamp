<?php

// buat 2 class hewan dan fight
trait Hewan
{
    public $nama, $darah = 50, $jumlahKaki, $keahlian;

    public function atraksi()
    {
        return "$this->nama sedang $this->keahlian";
    }
}

trait Fight
{
    public $attackPower, $defencePower;

    public function serang($diserang)
    {
        return "$this->nama sedang menyerang $diserang";
    }

    public function diserang($penyerang, $attackPowerPenyerang)
    {
        // rumus darah sekarang - attackPower penyerang / defencePower yang diserang
        $result = $this->darah - $attackPowerPenyerang /  $this->defencePower;
        $this->darah = $result; // set nilai darah setelah diserang

        return "$this->nama sedang diserang $penyerang";
    }
}

class Elang
{
    use Hewan, Fight;

    // function untuk set nilai dari property hewan dan fight untuk class elang
    public function addDetailElang($jumlahKaki, $keahlian, $attackPower, $defencePower)
    {
        $this->nama = "Elang_1";
        $this->jumlahKaki = $jumlahKaki;
        $this->keahlian = $keahlian;
        $this->attackPower = $attackPower;
        $this->defencePower = $defencePower;
    }

    public function getInfoHewan()
    {
        // menampilkan semua informasi harimau
        return $str = "nama $this->nama darah $this->darah jumlah kaki $this->jumlahKaki keahlian $this->keahlian attak power $this->attackPower defence power $this->defencePower jenis elang";
    }
}

class harimau
{
    use hewan, Fight;

    // function untuk set nilai dari property hewan dan fight untuk class harimau
    public function addDetailHarimau($jumlahKaki, $keahlian, $attackPower, $defencePower)
    {
        $this->nama = "harimau_1";
        $this->jumlahKaki = $jumlahKaki;
        $this->keahlian = $keahlian;
        $this->attackPower = $attackPower;
        $this->defencePower = $defencePower;
    }

    public function getInfoHewan()
    {
        // menampilkan semua informasi harimau
        return $str = "nama  $this->nama darah $this->darah jumlah kaki $this->jumlahKaki keahlian $this->keahlian attak power $this->attackPower defence power $this->defencePower jenis harimau";
    }
}


echo "<h4>Elang_1 Gameplay</h4>";
// instansiasi class elang
$elang = new elang();
$elang->addDetailElang(2, "terbang tinggi", 10, 5); // set nilai 
echo $elang->atraksi();
echo "<br> <hr>";
echo $elang->serang('harimau_1');
echo "<br> <hr>";
echo $elang->diserang('harimau_1', 7);
echo "<br> <hr>";
echo $elang->getInfoHewan();
echo "<br> <hr>";

echo "<h4>Harimau_1 Gameplay</h4>";
// instansiasi class harimau
$harimau = new harimau();
$harimau->addDetailHarimau(4, "lari cepat", 7, 8); // set nilai
echo $harimau->atraksi();
echo "<br> <hr>";
echo $harimau->serang("elang_1");
echo "<br> <hr>";
echo $harimau->diserang("elang_1", 10);
echo "<br> <hr>";
echo $harimau->getInfoHewan();
