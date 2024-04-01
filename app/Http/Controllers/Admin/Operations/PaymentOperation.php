<?php

namespace App\Http\Controllers\Admin\Operations;
use Illuminate\Http\Request; // Import Request class
use Illuminate\Support\Facades\Validator;
use App\Models\Payments;
use App\Models\Membership;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;

trait PaymentOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupPaymentRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/payment', [
            'as'        => $routeName.'.payment',
            'uses'      => $controller.'@payment',
            'operation' => 'payment',
        ]);

        Route::post($segment.'/{id}/payment', [
            'as'        => $routeName.'.payment-add',
            'uses'      => $controller.'@postPaymentForm',
            'operation' => 'payment',
        ]);
    }

    public function postPaymentForm(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'transaction_code' => $request->type === 'cash' ? 'nullable' : 'required|string',
            'payment_for' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entry = new Payments();
            $entry->amount = $request->input('amount');
            $entry->type = $request->input('type');
            $entry->transaction_code = $request->input('transaction_code');
            $entry->payment_for = $request->input('payment_for');
            $entry->member_id = $id;

            $entry->save();

            return redirect()->back()->with('success', 'Payment added successfully!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }




    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupPaymentDefaults()
    {
        CRUD::allowAccess('payment');

        CRUD::operation('payment', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('top', 'payment', 'view', 'crud::buttons.payment');
            CRUD::addButton('line', 'payment', 'view', 'crud::buttons.payment');

            $this->crud->addButton('line', 'payment', 'view', 'crud::buttons.payment');

        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */

    public function payment()
    {

        CRUD::hasAccessOrFail('payment');


        $memberId = request()->route('id');
        $membership = Membership::where('member_id', $memberId)->first();
        $payments = Payments::where('member_id', $memberId)->get();


        $data = [
            'entry' => $this->crud->getCurrentEntry(),
            'crud' => $this->crud,
            'title' => CRUD::getTitle() ?? 'Payment ' . $this->crud->entity_name,
            'membership' => $membership,
            'payments' => $payments,
        ];

    
        return view('crud::operations.payment_form', $data);


    }





}



