<?php

namespace Controlpanel\Vouchers\Controllers;

use App\Http\Controllers\Controller;
use Controlpanel\Vouchers\Models\Voucher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Builder;

class VoucherController extends Controller
{
    const READ_PERMISSIONS = 'vouchers.read';
    const WRITE_PERMISSIONS = 'vouchers.write';

    public function index(Request $request)
    {
        $this->checkPermission(self::READ_PERMISSIONS);

        //datatables
        if ($request->ajax()) {
            return $this->dataTableQuery();
        }

        $html = $this->dataTable();

        return view('vouchers::index', compact('html'));
    }

    /**
     * @description create table
     *
     * @return Builder
     */
    public function dataTable(): Builder
    {
        $builder = $this->htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => __('ID')])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('Updated at'), 'searchable' => false])
            ->addAction(['data' => 'actions', 'name' => 'actions', 'title' => __('Actions'), 'searchable' => false, 'orderable' => false])
            ->parameters($this->dataTableDefaultParameters());

        if (!$this->can(self::WRITE_PERMISSIONS)) {
            $builder->removeColumn('actions');
        }

        return $builder;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dataTableQuery(): mixed
    {
        $query = Voucher::query();

        return datatables($query)
            ->addColumn('actions', function (Role $role) {
                return Blade::render('
                            <a title="{{__(\'Edit\')}}" href="{{route("controlpanel.vouchers.edit", $role)}}" class="btn btn-sm btn-info"><i
                                    class="fa fas fa-edit"></i></a>
                            <form class="d-inline" method="post" action="{{route("controlpanel.vouchers.destroy", $role)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{__(\'Delete\')}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>'
                    , compact('role'));
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at ? $model->updated_at->diffForHumans() : '';
            })
            ->rawColumns(['actions', 'name'])
            ->make(true);
    }
}
