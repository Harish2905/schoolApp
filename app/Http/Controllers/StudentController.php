<?php

namespace App\Http\Controllers;

use App\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Student::latest()->get())
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm">Edit</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="view" id="' . $data->id . '" class="view btn btn-success btn-sm">View</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm">Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('student', ['studentcount' => Student::count()]);
    }


    public function create()
    {
    }

    public function store(Request $request)
    {
        $rules = array(
            'first_name'    =>  'required',
            'last_name'     =>  'required',
            'image'         =>  'required|image|max:2048',
            'parent_name'         =>  'required',
            'phone'         =>  'required|numeric',
            'email'         =>  'required|email',
            'address'    =>  'required|max:200',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('image');

        $new_name = rand() . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('images'), $new_name);




        $form_data = array(
            'first_name'        =>  $request->first_name,
            'last_name'         =>  $request->last_name,
            'image'             =>  $new_name,
            'parent_name'         =>  $request->parent_name,
            'mobile'         =>  $request->phone,
            'email'         =>  $request->email,
            'address'    =>  $request->address,
        );



        Student::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        if (request()->ajax()) {
            $data = Student::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    public function update(Request $request)
    {

        $image_name = $request->hidden_image;
        $image = $request->file('image');
        if ($image != '') {
            $rules = array(
                'first_name'    =>  'required',
                'last_name'     =>  'required',
                'image'         =>  'image|max:2048',
                'parent_name'         =>  $request->parent_name,
                'mobile'         =>  $request->phone,
                'email'         =>  $request->email,
                'address'    =>  $request->address,


            );
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }


            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
        } else {
            $rules = array(
                'first_name'    =>  'required',
                'last_name'     =>  'required'
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }

        $form_data = array(
            'first_name'       =>   $request->first_name,
            'last_name'        =>   $request->last_name,
            'image'            =>   $image_name,
            'parent_name'         =>  $request->parent_name,
            'mobile'         =>  $request->phone,
            'email'         =>  $request->email,
            'address'    =>  $request->address,
        );
        Student::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Student::findOrFail($id);

        $data->delete();
    }
}
