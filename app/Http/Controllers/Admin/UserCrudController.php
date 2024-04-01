<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Library\Widget;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');

        // CRUD::field('id');
        // CRUD::field('username');
        // CRUD::field('email');
        // CRUD::field('phone');
        // CRUD::field('password');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.

        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Username',
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'label' => 'Email',
        ]);

        CRUD::addColumn([
            'name' => 'phone',
            'label' => 'Phone Number',
        ]);

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        CRUD::addField([
            'name' => 'capabilities',
            'label' => 'Capabilities',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'capabilities-hidden'
            ]
        ]);

        $labels = [
            'Add New Users',     // 1
            'Accept Payments',   // 2
            'View Payments',     // 3
            'View Reports',      // 4
            'Checkins',          // 5
            'Members',           // 6
            'Payments',          // 7
            'Cash Flow',         // 8
        ];

        CRUD::addField([
            'name' => 'capabilities_label',
            'type' => 'custom_html',
            'value' => '<div>User Capabilities</div>',
        ]);

        for ($i = 0; $i < count($labels); $i++) {

            CRUD::addField([
                'name' => 'capabilities'.$i,
                'label' => $labels[$i],
                'type' => 'checkbox',
                'attributes' => [
                    'class' => 'capability-checkbox',
                    'data-value' => $i + 1
                ]
            ]);

            Widget::add()->type('script')->content(asset('assets/js/field.js'));


        }

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
}
