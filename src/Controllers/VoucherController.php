<?php

namespace Controlpanel\Vouchers\Controllers;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use Controlpanel\Vouchers\Models\Voucher;
use Controlpanel\Vouchers\Requests\VoucherStoreRequest;
use Controlpanel\Vouchers\Requests\VoucherUpdateRequest;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Builder;

class VoucherController extends Controller
{
    const READ_PERMISSIONS = 'controlpanel.vouchers.read';
    const WRITE_PERMISSIONS = 'controlpanel.vouchers.write';

    /**
     * @param Request $request
     * @return Application|Factory|View|mixed
     * @throws Exception
     */
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
     * @param GeneralSettings $settings
     * @return Application|Factory|View
     */
    public function create(GeneralSettings $settings): View|Factory|Application
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $random = Str::random(32);

        return view('vouchers::edit', compact('settings', 'random'));
    }

    /**
     * @param VoucherStoreRequest $request
     * @return RedirectResponse
     */
    public function store(VoucherStoreRequest $request)
    {
        Voucher::create($request->all());

        return redirect()
            ->route('controlpanel.vouchers.index')
            ->with('success', __('Voucher saved'));
    }

    /**
     * @param Voucher $voucher
     * @param GeneralSettings $settings
     * @return Application|Factory|View
     */
    public function edit(Voucher $voucher, GeneralSettings $settings): View|Factory|Application
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        return view('vouchers::edit', compact('settings', 'voucher'));
    }

    /**
     * @param VoucherUpdateRequest $request
     * @param Voucher $voucher
     * @return RedirectResponse
     */
    public function update(VoucherUpdateRequest $request, Voucher $voucher): RedirectResponse
    {
        $voucher->update($request->all());

        return redirect()
            ->route('controlpanel.vouchers.index')
            ->with('success', __('Voucher saved'));
    }

    /**
     * @description create table
     *
     * @return Builder
     */
    public function dataTable(): Builder
    {
        $builder = $this->htmlBuilder
            ->addColumn(['data' => 'status', 'name' => 'status', 'title' => __('Status')])
            ->addColumn(['data' => 'code', 'name' => 'code', 'title' => __('Code')])
            ->addColumn(['data' => 'memo', 'name' => 'memo', 'title' => __('Memo')])
            ->addColumn(['data' => 'credits', 'name' => 'credits', 'title' => __('Credits')])
            ->addColumn(['data' => 'uses', 'name' => 'uses', 'title' => __('Used / Uses')])
            ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('Updated at'), 'searchable' => false])
            ->addAction(['data' => 'actions', 'name' => 'actions', 'title' => __('Actions'), 'searchable' => false, 'orderable' => false])
            ->parameters($this->dataTableDefaultParameters());

        if (!$this->can(self::WRITE_PERMISSIONS)) {
            $builder->removeColumn('actions');
        }

        return $builder;
    }

    /**
     * @param Voucher $voucher
     * @return RedirectResponse
     */
    public function destroy(Voucher $voucher): RedirectResponse
    {
        $this->checkPermission(self::WRITE_PERMISSIONS);

        $voucher->delete();

        return redirect()
            ->route('controlpanel.vouchers.index')
            ->with('success', __('Voucher removed'));
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dataTableQuery(): mixed
    {
        $query = Voucher::query();

        return datatables($query)
            ->addColumn('status', function (Voucher $voucher) {
                return $voucher->status;
            })
            ->addColumn('actions', function (Voucher $voucher) {
                return Blade::render('
                            <a title="{{__(\'Edit\')}}" href="{{route("controlpanel.vouchers.edit", $voucher)}}" class="btn btn-sm btn-info"><i
                                    class="fa fas fa-edit"></i></a>
                            <form class="d-inline" method="post" action="{{route("controlpanel.vouchers.destroy", $voucher)}}">
                                @csrf
                                @method("DELETE")
                                <button title="{{__(\'Delete\')}}" type="submit" class="btn btn-sm btn-danger confirm"><i
                                        class="fa fas fa-trash"></i></button>
                            </form>'
                    , compact('voucher'));
            })
            ->editColumn('code', function (Voucher $voucher) {
                return "<code>$voucher->code</code>";
            })
            ->editColumn('uses', function (Voucher $voucher) {
                return $voucher->used . '/' . $voucher->uses;
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at ? $model->updated_at->diffForHumans() : '';
            })
            ->rawColumns(['actions', 'status', 'code'])
            ->make(true);
    }
}
