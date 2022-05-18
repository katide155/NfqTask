<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::all();
		return view('groups.index', ['groups'=>$groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
		if(!$group)
			$group = new Group;
		$group->group_title = $request->group_title;
		$group->max_number_students_in_group = $request->max_number_students_in_group;
		$group->group_project_id = $request->group_project_id;
		
		$group->save();
		return redirect()->route('group.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return view('groups.show', ['group'=>$group]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
		
        return view('groups.edit', ['group'=>$group]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
       	$group->group_title = $request->group_title;
		$group->max_number_students_in_group = $request->max_number_students_in_group;
		$group->group_project_id = $request->group_project_id;
		
		$group->save();
		return redirect()->route('group.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, $page)
    {
        $group->delete();
		return redirect()->route('group.index')->with('success_message', 'Sėkmingai ištrinta');
    }
}
