<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    

        {
            $courses = Course::paginate(10);
            $tr = "Some value"; 
            return view('courses.index', compact('courses'));
        }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:courses|min:2',
            'description'=>"required|unique:courses|min:10|max:30"
           ],[
               'name.required'=>"course name is required",
               'name.unique'=>"course name already exist",
               'name.min'=>"course name should be more than or equal 2 characters ",
               'description.required'=>"course description is required",
               'description.unique'=>"course description already exist",
               'description.min'=>"course description should be more than or equal 10 characters ",
           ]
       );
           $requestData=$request->all();
           // dump($requestData);
           Course::create($requestData);
           return to_route('courses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return view('courses.show',compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        // $course = Course::findOrFail($id);
    return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {

        
            // التحقق من البيانات المدخلة
            $validatedData = $request->validate([
                'name' => 'required|unique:courses,name,' . $course->id, // السماح بتكرار الاسم إذا كان نفس السجل
                'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // إذا كنت تتعامل مع صورة
            ]);
        
            // إذا تم تحميل صورة جديدة
            if ($request->hasFile('logo')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($course->logo && Storage::disk('public')->exists($course->logo)) {
                    Storage::disk('public')->delete($course->logo);
                }
        
                // حفظ الصورة الجديدة
                $validatedData['logo'] = $request->file('logo')->store('logos', 'public');
            }
        
            // تحديث البيانات
            $course->update($validatedData);
        
            return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
        

        // $requestData=request()->all();
        // // dd($requestData);
        // $course->update($requestData);
        // return to_route('courses.index');

        
            // $validatedData = $request->validate([
            //     'name' => 'required|unique:courses,name,' , // السماح بتكرار الاسم إذا كان نفس السطر
            //     'description' => 'nullable',
            //     'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // إذا كنت تتعامل مع صورة
            // ]);
        
            // // $course = Course::findOrFail($id);
        
            // if ($request->hasFile('logo')) {
            //     $validatedData['logo'] = $request->file('logo')->store('logos', 'public'); // حفظ الصورة
            // }
        
            // $course->update($validatedData);
        
            // return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
        
        


    //     $request->validate([
    //         'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);
    
    //     // $course = Course::findOrFail($id);
    
    
    //     if ($request->hasFile('logo')) {
    //         if ($course->logo) {
    //             Storage::delete($course->logo);
    //         }
    
    //         $logoPath = $request->file('logo')->store('images/courses');
    //         $course->logo = $logoPath;
    //     }
    
    //     $course->save();
    //     return redirect()->route('courses.index')->with('success', 'Logo updated successfully');
    
    // }




    
    // تحقق من صحة المدخلات
    // $validatedData = $request->validate([
    //     'name' => 'required|unique:courses,name,', // اسم فريد باستثناء السطر الحالي
    //     'description' => 'nullable',
    //     'logo' => 'nullable|url', // تحقق من أن الرابط نصي وصالح
    // ]);

    // // تحديث البيانات
    // $course->update($validatedData);

    // // إعادة التوجيه مع رسالة نجاح
    // return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        // $course->delete();
        // return to_route('courses.index');


        // $course = Course::findOrFail($id);

   
    if ($course->logo) {
        Storage::delete($course->logo);
        $course->logo = null; 
        $course->save(); 
    }

    return redirect()->route('courses.index')->with('success', 'Logo deleted successfully');
}
    
}