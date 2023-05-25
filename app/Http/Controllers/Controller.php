<?php

namespace App\Http\Controllers;


use App\Exports\RequestExport;
use App\Exports\UsersExport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request)
    {
        $address = $request->address;
        $parts = [];
        foreach ($address as $ad) {
            $parts[] = explode('-', $ad, 2);
        }
        $adres = [];
        $miasto = [];
        foreach ($parts as $p) {
            $adres[] = $p[0];
            $miasto[] = $p[1];
        }
        $name = $request->name;
        $phone = $request->phone;
        $mail = $request->mail;
        $export = new RequestExport($adres, $miasto, $name, $phone, $mail);

        $excelFile = Excel::store($export, 'nazwa_pliku.xlsx', 'public');

    }
}
