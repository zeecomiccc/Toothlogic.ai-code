<?php

namespace Modules\World\Http\Controllers\Backend;

use App\Models\User;
use App\Authorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\World\Models\State;
use Yajra\DataTables\DataTables;
use Modules\CustomField\Models\CustomField;
use Modules\CustomField\Models\CustomFieldGroup;
use Modules\World\Models\Country;

class StateController extends Controller
{
    // use Authorizable;
    protected string $exportClass = '\App\Exports\StateExport';

    public function __construct()
    {
        // Page Title
        $this->module_title = 'state.title';
        // module name
        $this->module_name = 'state';

        // module icon
        $this->module_icon = 'fa-solid fa-clipboard-list';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => $this->module_icon,
            'module_name' => $this->module_name,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $country = Country::get();
        $module_action = 'List';
        $columns = CustomFieldGroup::columnJsonValues(new User());

        $customefield = CustomField::exportCustomFields(new User());
        $export_import = true;
        $export_columns = [
            [
                'value' => 'name',
                'text' => __('service.lbl_name'),
            ],
            [
                'value' => 'country_name',
                'text' => __('service.lbl_state_column_name'),
            ],
            [
                'value' => 'status',
                'text' => __('service.lbl_status'),
            ],
        ];
        $export_url = route('backend.state.export');
        return view('world::backend.state.index_datatable', compact('module_action', 'filter', 'columns', 'customefield', 'export_import', 'export_columns', 'export_url', 'country'));
    }

    /**
     * Select Options for Select 2 Request/ Response.
     *
     * @return Response
     */
    public function index_list(Request $request)
    {
        $term = trim($request->search);
        $country_id = $request->country_id;

        $query = State::query();

        if (isset($country_id)) {
            $query->where('country_id', $country_id);
        }

        if (!empty($term)) {
            $query->where(function ($query) use ($term) {
                $query->where('name', 'like', "%$term%");
            });
        }

        $query_data = $query->get();

        $data = $query_data->map(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->name,
                'country_id' => $row->country_id,
            ];
        });

        if ($request->is('api/*')) {
            return response()->json(['status' => true, 'data' => $data, 'message' => __('state_list')]);
        }

        return response()->json($data);
    }


    public function index_data(Request $request)
    {
        $query = State::query();

        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
            if (isset($filter['country_id'])) {
                $query->where('country_id', $filter['country_id']);
            }
        }

        return Datatables::of($query)
            ->addColumn('check', function ($data) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-' . $data->id . '"  name="datatable_ids[]" value="' . $data->id . '" onclick="dataTableRowCheck(' . $data->id . ')">';
            })
            ->addColumn('action', function ($data) {
                return view('world::backend.state.action_column', compact('data'));
            })
            ->editColumn('country_id', function ($data) {
                return $data->country->name ?? '-';
            })

            ->editColumn('status', function ($data) {
                // return $data->getStatusLabelAttribute();
                $checked = '';
                if ($data->status) {
                    $checked = 'checked="checked"';
                }

                return '
                                <div class="form-check form-switch ">
                                    <input type="checkbox" data-url="' . route('backend.state.update_status', $data->id) . '" data-token="' . csrf_token() . '" class="switch-status-change form-check-input"  id="datatable-row-' . $data->id . '"  name="status" value="' . $data->id . '" ' . $checked . '>
                                </div>
                            ';
            })
            ->editColumn('updated_at', function ($data) {
                $module_name = $this->module_name;

                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('llll');
                }
            })
            ->rawColumns(['action', 'status', 'check'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
        //custom field for export
        $customFieldColumns = CustomField::customFieldData($datatable, User::CUSTOM_FIELD_MODEL, null);

        return $datatable->rawColumns(array_merge(['action', 'status', 'name', 'country_name', 'check', 'image'], $customFieldColumns))
            ->toJson();
    }

    public function update_status(Request $request, State $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => 'Status Updated']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = State::create($request->all());

        $message = 'New State Added';

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $module_action = 'Edit';

        $data = State::findOrFail($id);

        return response()->json(['data' => $data, 'status' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = State::findOrFail($id);

        $data->update($request->all());

        $message = 'State Updated Successfully';

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $data = State::findOrFail($id);

        $data->delete();

        $message = 'State Deleted Successfully';

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = __('messages.bulk_update');

        switch ($actionType) {
            case 'change-status':
                $customer = State::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = __('messages.bulk_state_update');
                break;

            case 'delete':
                if (env('IS_DEMO')) {
                    return response()->json(['message' => __('messages.permission_denied'), 'status' => false], 200);
                }
                State::whereIn('id', $ids)->delete();
                $message = __('messages.bulk_state_delete');
                break;

            default:
                return response()->json(['status' => false, 'message' => __('service_providers.invalid_action')]);
                break;
        }

        return response()->json(['status' => true, 'message' => __('messages.bulk_update')]);
    }
}
