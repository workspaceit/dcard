<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\AdminLogin;
use App\Models\Member;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CSVController extends Controller {
    public function __construct() {
        if (!Session::get('admin')) {
            return redirect("admin/login")->send();
        }
    }

    public function getExport($model) {
        $table = NULL;

        switch ($model) {
            case 'store':
                $table = Store::all()->toArray();
                break;
            case 'member':
                $table = Member::all()->toArray();
                break;
            case 'admin':
                $table = AdminLogin::all()->toArray();
                break;
        }

        if (!$table) {
            return redirect('app/store-list');
        }

        $file     = "test.csv";
        $fileName = Carbon::now() . " $model.csv";
        $handle   = fopen($file, 'w');
        fputcsv($handle, array_keys($table[1]));

        foreach ($table as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        return response()->download(public_path($file), $fileName, $headers);
    }
}