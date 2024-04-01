<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\CheckIns;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Requests\MemberRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Log;


/**
 * Class MemberCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MemberCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Http\Controllers\Admin\Operations\PaymentOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    // Inside your controller or service method
public function updateAnnualStatus()
{

    $activeMemberships = Membership::where('annual_status', 'active')->get();

    foreach ($activeMemberships as $membership) {

        if ($membership->start_date->addYear(1)->isPast()) {
            $membership->annual_status = 'expired';
            $membership->save();
        }
    }
}


     public function searchMembers(Request $request)
     {
         $query = $request->input('query');
         Log::info('Search Query: ' . $query);

         $members = Member::where('code', 'like', '%' . $query . '%')->get();
         Log::info('Found Members: ' . $members->pluck('id')->implode(', '));

         if ($members->isNotEmpty()) {
             foreach ($members as $member) {
                 if ($member->code === $query) {
                     $latest_membership = Membership::where('member_id', $member->id)
                                                     ->latest()
                                                     ->first();

                     if ($latest_membership && $latest_membership->status === 'Active' && !$this->isExpired($latest_membership->expiration_date)) {
                         $checkin = new CheckIns();
                         $checkin->member_id = $member->id;
                         $checkin->save();
                         Log::info('Check-in created for Member ID: ' . $member->id);
                     }
                 }
             }
         }

         $latest_memberships = Membership::whereIn('member_id', $members->pluck('id'))
                                          ->latest()
                                          ->get();

         return view('landingpage', compact('members', 'latest_memberships'));
     }

     private function isExpired($expirationDate)
     {
         return $expirationDate && Carbon::parse($expirationDate)->isPast();
     }

     public function check_ins()
{
    return $this->hasMany(CheckIns::class, 'member_id');
}

    public function setup()
    {
        CRUD::setModel(\App\Models\Member::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/member');
        CRUD::setEntityNameStrings('member', 'members');
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
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'firstname',
            'label' => 'First Name',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'lastname',
            'label' => 'Last Name',
            'type' => 'text',
        ]);


        CRUD::addColumn([
        'name' => 'status',
        'label' => 'Membership Status',
        'type' => 'select',
        'entity' => 'membership',
        'attribute' => 'status',
    ]);

    CRUD::addColumn([
        'name' => 'annual_status',
        'label' => 'Annual Status',
        'type' => 'select',
        'entity' => 'membership',
        'attribute' => 'annual_status',
    ]);



   CRUD::addColumn([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'phone',
            'label' => 'Phone',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'label' => 'Created At',
            'type' => 'datetime',
        ]);

        CRUD::addColumn([
            'name' => 'updated_at',
            'label' => 'Updated At',
            'type' => 'datetime',
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


        CRUD::setValidation(MemberRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.
        // CRUD::setValidation(MemberRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        CRUD::addField([
            'name' => 'firstname',
            'label' => 'First Name',
        ]);

        CRUD::addField([
            'name' => 'lastname',
            'label' => 'Last Name',
        ]);

        CRUD::addField([
            'name' => 'phone',
            'label' => 'Phone Number',
            'type' => 'text',
            'attributes' => [
                'placeholder' => 'Enter 11-digit mobile number',
                'maxlength' => 11,
            ],
            'validation' => [
                'rules' => 'required|digits:11',
            ],
        ]);

        CRUD::addField([
            'name' => 'email',
            'label' => 'Email',
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
}
