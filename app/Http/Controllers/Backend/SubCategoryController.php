<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SubCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use Str;

class SubCategoryController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(SubCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.sub-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.sub-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => ['required'],
            'name' => ['required', 'max:200', 'unique:sub_categories,name'],
            'status' => ['required']
        ]);

        $imagePath = $this->uploadImage($request, 'image', 'uploads/sub-category');
        $subCategory = new SubCategory();

        $subCategory->category_id = $request->category;
        $subCategory->name = $request->name;
        $subCategory->image = $imagePath;
        $subCategory->slug = Str::slug($request->name);
        $subCategory->status = $request->status;
        $subCategory->save();

        toastr('Created Successfully!', 'success');

        return redirect()->route('admin.sub-category.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $subCategory = SubCategory::findOrFail($id);
        return view('admin.sub-category.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category' => ['required'],
            'name' => ['required', 'max:200', 'unique:sub_categories,name,'.$id],
            'status' => ['required']
        ]);

        $subCategory = SubCategory::findOrFail($id);

        $subCategory->category_id = $request->category;
        $subCategory->name = $request->name;
        if ($request->hasFile('image')) {
            $imagePath = $this->updateImage($request, 'image', 'uploads/sub-category', $subCategory->image);
            $subCategory->image = $imagePath;
        }
        $subCategory->slug = Str::slug($request->name);
        $subCategory->status = $request->status;
        $subCategory->save();

        toastr('Updated Successfully!', 'success');

        return redirect()->route('admin.sub-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $childCategory = ChildCategory::where('sub_category_id', $subCategory->id)->count();

        if($childCategory > 0){
            return response(['status' => 'error', 'message' => 'This items contain, sub items for delete this you have to delete the sub items first!']);
        }
        $subCategory->delete();

        if(!empty($subCategory->image)){
            $this->deleteImage($subCategory->image);
        }

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function changeStatus(Request $request)
    {
        $category = SubCategory::findOrFail($request->id);
        $category->status = $request->status == 'true' ? 1 : 0;
        $category->save();

        return response(['message' => 'Status has been updated!']);
    }
}
