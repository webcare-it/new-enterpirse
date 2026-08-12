<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = \App\Models\Admin\Team::oldest('position')->paginate(10);
        return view('backend.team.index', compact('teams'));
    }

    public function create()
    {
        return view('backend.team.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'avatar' => 'nullable',
        ]);

        $team = new \App\Models\Admin\Team();
        $team->name = $request->name;
        $team->position = 1;
        $team->designation = $request->designation;

        $team->avatar = $request->avatar;

        $team->save();

        flash(translate('Team member created successfully.'))->success();
        return redirect()->route('team.index');
    }


    public function edit(Team $team)
    {
        return view('backend.team.edit', compact('team'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'avatar'      => 'nullable',
        ]);

        $team = \App\Models\Admin\Team::findOrFail($id);

        $team->name = $request->name;
        $team->designation = $request->designation;

        if ($request->avatar) {
            $team->avatar = $request->avatar;
        }

        $team->save();

        flash(translate('Team member updated successfully.'))->success();
        return redirect()->route('team.index');
    }


    public function sort(Request $request)
    {
        foreach ($request->positions as $item) {
            \App\Models\Admin\Team::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $team = \App\Models\Admin\Team::findOrFail($id);
        $team->delete();

        flash(translate('Team member deleted successfully.'))->success();
        return redirect()->route('team.index');
    }
}
