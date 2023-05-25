<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RequestExport implements FromCollection, WithHeadings
{
    private $adres;
    private $miasto;
    private $name;
    private $phone;
    private $mail;

    public function __construct($adres, $miasto, $name, $phone, $mail)
    {
        $this->adres = $adres;
        $this->miasto = $miasto;
        $this->name = $name;
        $this->phone = $phone;
        $this->mail = $mail;
    }

    public function collection()
    {
        $data = [];
        $count = max(count($this->adres), count($this->miasto), count($this->name), count($this->phone), count($this->mail));

        for ($i = 0; $i < $count; $i++) {
            $adres = isset($this->adres[$i]) ? $this->adres[$i] : '';
            $miasto = isset($this->miasto[$i]) ? $this->miasto[$i] : '';
            $name = isset($this->name[$i]) ? $this->name[$i] : '';
            $phone = isset($this->phone[$i]) ? $this->phone[$i] : '';
            $mail = isset($this->mail[$i]) ? $this->mail[$i] : '';


            $asdBeforesAtFromMail = $this->extractDigitsBeforeAt($mail);
            if ($name === 'Toyota Motor Manufacturing Poland' || $name === 'Toyota Professional Wrocław Siechnice') {
                $mail = '';
                $phone = '';

                $mail = isset($this->mail[$i + 1]) ? $this->mail[$i + 1] : '';
                $phone = isset($this->phone[$i + 1]) ? $this->phone[$i + 1] : '';
            }

            if ($name === 'Toyota Central Europe') {
                $phone = '';

                $phone = isset($this->phone[$i + 1]) ? $this->phone[$i + 1] : '';
            }

            $prevPhone = $phone;
            $data[] = [
                'LP.' => $i + 1,
                'ASD' =>     $asdBeforesAtFromMail,
                'Name' => $name,
                'Post Code' => '51-354',
                'City' => $miasto,
                'Street' => $adres,
                'Phone' => $phone,
                'e-mail' => $mail
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return ['LP.', 'ASD', 'Name', 'Post Code', 'City', 'Street', 'Phone', 'e-mail'];
    }
    private function extractDigitsBeforeAt($email)
    {
        $digitsBeforeAt = '';
        if (!empty($email)) {
            $parts = explode('@', $email, 2);
            if (count($parts) === 2) {
                $digitsBeforeAt = preg_replace('/[^0-9]/', '', $parts[0]);
            }
        }
        return $digitsBeforeAt;
    }
}


