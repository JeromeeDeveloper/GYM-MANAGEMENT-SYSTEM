<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payments;
use Backpack\CRUD\app\Library\Widget;
use App\Http\Requests\PaymentsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PaymentsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PaymentsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Payments::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/payments');
        CRUD::setEntityNameStrings('payments', 'payments');
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
            'name' => 'full_name',
            'label' => 'Member Full Name',
            'entity' => 'member',
            'attribute' => 'fullname',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('member', function ($query) use ($searchTerm) {
                    $query->where('firstname', 'like', '%' . $searchTerm . '%')
                          ->orWhere('lastname', 'like', '%' . $searchTerm . '%');
                });
            },
        ]);

        CRUD::addColumn([
            'name' => 'amount',
            'label' => 'Paid Amount',
            'prefix' => '₱',
        ]);

        CRUD::addColumn([
            'name' => 'type',
            'label' => 'Payment Method',
        ]);

        CRUD::addColumn([
            'name' => 'payment_for',
            'label' => 'Gym Plan',
        ]);

        CRUD::addColumn([
            'name' => 'transaction_code',
            'label' => 'Transaction Code',
            'value' => function($entry) {
                return $entry->transaction_code ?? 'N/A';
            }

        ]);

        $payments = Payments::all();

        // Calculate total amount
        $total = $payments->sum('amount');

        // Append total as the last entry
        $totalEntry = [
            'full_name' => 'Total:',
            'amount' => $total,
            // Add other columns as needed
        ];

        // Append total entry to entries array
        $entries = $this->crud->get('entries');
        $entries[] = $totalEntry;
        $this->crud->set('entries', $entries);




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
        CRUD::setValidation(PaymentsRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        CRUD::addField([
            'name' => 'member_id',
            'label' => 'Member Full Name',
            'type' => 'select',
            'entity' => 'member',
            'attribute' => 'fullname', // Assuming 'lastname' is the attribute you want to display
        ]);

        // CRUD::addField([
        //     'name' => 'annual_fee',
        //     'label' => 'Annual Fee (New Members)',
        //     'type' => 'number',
        //     'attributes' => [
        //         'step' => 'any',
        //         'placeholder' => 'Enter amount',
        //     ],
        // ]);

        CRUD::addField([
            'name' => 'payment_for',
            'label' => 'Subscription Plan',
            'type' => 'select_from_array',
            'options' => [
                'monthly' => 'Monthly Subscription',
                'bi-monthly' => 'Bi Monthly Subscription',
                '6-months' => '6 Months Subscription',
                '1-year' => '1 Year Subscription',
                'Annual-Fee' => 'Annual Fee',
            ],
            'attributes' => [
                'placeholder' => 'Gym Subscription',
            ],
        ]);



        CRUD::addField([
            'name' => 'payment_date',
            'label' => 'Payment Date',
            'type' => 'date',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'Enter Date',
            ],
        ]);

        CRUD::addField([
            'name' => 'type',
            'label' => 'Payment Option',
            'type' => 'select_from_array',
            'options' => ['cash' => 'cash', 'gcash' => 'gcash'],
            'attributes' => [
                'placeholder' => 'Payment Method',
                'id' => 'type', // Add id for JavaScript targeting
            ],
        ]);

        CRUD::addField([
            'name' => 'transaction_code',
            'label' => 'Transaction Code',
            'type' => 'text',
            'attributes' => [
                'placeholder' => 'Enter Transaction Code',
            ],
        ]);

        CRUD::addField([
            'name' => 'amount',
            'label' => 'Amount',
            'type' => 'number',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'Enter amount',
            ],
        ]);


        Widget::add()->type('script')->content(asset('assets/js/field.js'));
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
}
