<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Subdepartment;
use Illuminate\Http\Request;

class SubdepartmentController extends Controller
{
    public function index()
    {
       
         
     
        if(\Auth::user()->can('Manage Subdepartment'))
        {
            
            $subdepartments = Subdepartment::where('created_by', '=', \Auth::user()->creatorId())->get();
            
            return view('subdepartment.index', compact('subdepartments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('Create Subdepartment'))
        {
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('subdepartment.create', compact('branches'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        
       
        if(\Auth::user()->can('Create Subdepartment'))
        {
            
            $validator = \Validator::make(
                $request->all(), 
                [
                    'branch_id' =>'required',
                    'department_id' => 'required',
                       
                    'name' => 'required|max:20',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $subdepartment             = new Subdepartment();
            $subdepartment->branch_id = $request->branch_id;
            $subdepartment->department_id  = $request->department_id;
            $subdepartment->name       = $request->name;
            $subdepartment->created_by = \Auth::user()->creatorId();
            $subdepartment->save();

            return redirect()->route('subdepartment.index')->with('success', __('Subdepartment successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit(Subdepartment $subdepartment)
    {
       
        if(\Auth::user()->can('Edit Subdepartment'))
        {
            if($subdepartment->created_by == \Auth::user()->creatorId())
            
            {
               
                $branch = Branch::where('created_by', \Auth::user()->creatorId())->where('id', $subdepartment->branch_id)->get()->pluck('name', 'id');
                $department = Department::where('created_by', \Auth::user()->creatorId())->where('id', $subdepartment->department_id)->get()->pluck('name', 'id');
               
                return view('subdepartment.edit', compact('subdepartment', 'department', 'branch'));
             
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Subdepartment $subdepartment)
    {
        if(\Auth::user()->can('Edit Subdepartment'))
        {
            if($subdepartment->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'department_id' => 'required',
                                       'name' => 'required|max:20',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $subdepartment->name      = $request->name;
                $subdepartment->save();

                return redirect()->route('subdepartment.index')->with('success', __('Subdepartment successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Subdepartment $subdepartment)
    {
        if(\Auth::user()->can('Delete Subdepartment'))
        {
            if($subdepartment->created_by == \Auth::user()->creatorId())
            {
                $subdepartment->delete();

                return redirect()->route('subdepartment.index')->with('success', __('Subdepartment successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    public function json(Request $request){
        $subdepartments = Subdepartment::where('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();

       return response()->json($subdepartments);
    }
}
