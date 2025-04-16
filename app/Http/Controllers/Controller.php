<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yajra\DataTables\DataTableAbstract;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $userCount = User::count();
        view()->share('userCount', $userCount);
    }

    protected function applyCustomSorting(Request $request, DataTableAbstract $dataTable, array $customSortMap): DataTableAbstract
    {
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = $request->input('columns');

        if (!$columns || !isset($columns[$orderColumnIndex]['data'])) {
            return $dataTable;
        }

        $columnName = $columns[$orderColumnIndex]['data'];

        if (isset($customSortMap[$columnName])) {
            $sortCallback = $customSortMap[$columnName];
            $dataTable->order(function ($query) use ($sortCallback, $orderDir) {
                $sortCallback($query, $orderDir);
            });
        } else {
            $dataTable->order(function ($query) use ($columnName, $orderDir) {
                $query->orderBy($columnName, $orderDir);
            });
        }

        return $dataTable;
    }
}
