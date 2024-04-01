<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CheckInsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Models\CheckIns;
use Carbon\Carbon;


/**
 * Class CheckInsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CheckInsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CheckIns::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/check-ins');
        CRUD::setEntityNameStrings('check ins', 'check ins');

        CRUD::setHeading('Daily Check ins');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        CRUD::addColumn([
            'name' => 'code',
            'label' => 'Gym Code',
            'entity' => 'member',
            'attribute' => 'code', 
        ]);

        CRUD::addColumn([
            'name' => 'full_name',
            'label' => 'Member Full Name',
            'entity' => 'member',
            'attribute' => 'fullname',
        ]);

        CRUD::addColumn([
            'name' => 'check_in_time',
            'label' => 'Check In Time',
        ]);

        // CRUD::setFromDb(); // set columns from db columns.



        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CheckInsRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        CRUD::addField([
            'name' => 'member_id',
            'label' => 'Check In',
            'type' => 'select',
            'entity' => 'member',
            'attribute' => 'fullname',
        ]);
        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function filterData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $filterType = $request->input('filterType');


        $parsedStartDate = Carbon::parse($startDate)->startOfDay();
        $parsedEndDate = Carbon::parse($endDate)->endOfDay();


        $filteredCheckIns = CheckIns::whereBetween('check_in_time', [$parsedStartDate, $parsedEndDate])->get();

        $html = '';
        foreach ($filteredCheckIns as $checkIn) {
            $html .= '<tr>';
            $html .= '<td>' . $checkIn->member->code . '</td>';
            $html .= '<td>' . $checkIn->member->firstname . '</td>';
            $html .= '<td>' . $checkIn->member->lastname . '</td>';
            $html .= '<td>' . date('F j, Y g:i A', strtotime($checkIn->check_in_time)) . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
}
