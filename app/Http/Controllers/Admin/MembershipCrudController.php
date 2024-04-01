<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MembershipRequest;
use App\Models\Membership; // Import Membership model
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon; // Import Carbon

class MembershipCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Membership::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/membership');
        CRUD::setEntityNameStrings('membership', 'memberships');
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
            'name' => 'code', // column name in the members table
            'label' => 'Gym Code', // label to display in the list view
            'entity' => 'member',
            'attribute' => 'code', // column to display from the related subscription plans table
        ]);


        CRUD::addColumn([
            'name' => 'phone', // column name in the members table
            'label' => 'Phone number', // label to display in the list view
            'entity' => 'member',
            'attribute' => 'phone', // column to display from the related subscription plans table
        ]);

        CRUD::addColumn([
            'name' => 'age', // column name in the members table
            'label' => 'Age', // label to display in the list view
            'entity' => 'member',
            'attribute' => 'age', // column to display from the related subscription plans table
        ]);

        CRUD::addColumn([
            'name' => 'status',
            'label' => 'Status',
        ]);
        CRUD::addColumn([
            'name' => 'start_date',
            'label' => 'Start Date',
        ]);
        CRUD::addColumn([
            'name' => 'end_date',
            'label' => 'End Date',
        ]);

        // CRUD::setFromDb(); // set columns from db columns.


    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MembershipRequest::class);

        CRUD::addField([
            'name' => 'member_id',
            'label' => 'Member Full Name',
            'type' => 'select',
            'entity' => 'member',
            'validation' => [
                'required',
            ],
            'attribute' => 'fullname', // Assuming 'lastname' is the attribute you want to display
        ]);

        // Set fields manually
        // CRUD::addField([
        //     'name' => 'status',
        //     'label' => 'Status',
        //     'type' => 'select_from_array',
        //     'validation' => [
        //         'required',
        //     ],
        //     'options' => ['active' => 'active', 'expired' => 'expired', 'canceled' => 'canceled'],
        //     'attributes' => [
        //         'placeholder' => 'Select Status',
        //     ],
        // ]);

        // Add field for member selection


        // Add field for subscription plan selection

        CRUD::addField([
            'name' => 'start_date',
            'label' => 'Start Date',
            'type' => 'date',
            'validation' => [
                'required',
            ],
        ]);

        // Add field for end date
        CRUD::addField([
            'name' => 'end_date',
            'label' => 'End Date',
            'type' => 'date',
            'validation' => [
                'required',
            ],
        ]);
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

    public function postPaymentForm(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'transaction_code' => $request->type === 'cash' ? 'nullable' : 'required|string',
            'payment_for' => 'required|string', // Ensure payment_for is required
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entry = new Payments();
            $entry->amount = $request->input('amount');
            $entry->type = $request->input('type');
            $entry->transaction_code = $request->input('transaction_code');
            $entry->payment_for = $request->input('payment_for'); // Assign the correct payment_for value
            $entry->member_id = $id;

            $entry->save();

            return redirect()->back()->with('success', 'Payment added successfully!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function payment()
    {
        CRUD::hasAccessOrFail('payment');

        // Fetch the membership details based on the $id parameter
        $id = request()->route('id');
        $membership = Membership::find($id);

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = CRUD::getTitle() ?? 'Payment '.$this->crud->entity_name;
        $this->data['entry'] = $this->crud->getCurrentEntry();
        $this->data['membership'] = $membership; // Pass the membership variable to the view

        // load the view
        return view('crud::operations.payment_form', $this->data);
    }



}
