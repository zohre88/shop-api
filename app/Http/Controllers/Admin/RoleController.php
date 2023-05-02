<?php

namespace App\Http\Controllers\Admin;

use App\Models\role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\RoleCreateRequest;

class RoleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateRequest $request)
    {
        if(Gate::denies('view-dashboard')){
            return $this->errorResponse(403,'not permission user by gates');
        }
        $role=Role::query()->create([
            'title' => $request->title
        ]);

        $role->permissions()->attach($request->permissions);
        return $this->successResponse(200, $role->title,'role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, role $role)
    {
        $role->update([
            'title' => $request->title
        ]);

        $role->permissions()->sync($request->permissions);

        return $this->successResponse(200, $role->title, 'role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(role $role)
    {
        $role->permissions()->detach();
        $role->delete();
        return $this->successResponse(201, $role->title, 'role deleted successfully');
    }
}
