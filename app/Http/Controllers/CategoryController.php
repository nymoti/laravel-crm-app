<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\SheetCategory;
use Toastr;
class CategoryController extends Controller
{
    public function index() {    
        $categories =  Category::all();   
        $category_list_side_bar = "category_list_side_bar";  
        $rserv_form_side_bar = "rserv_form_side_bar"; 

        return view('app.categories.index')
        ->with('categories', $categories) 
        ->with('rserv_form_side_bar', $rserv_form_side_bar)
        ->with('category_list_side_bar', $category_list_side_bar);  
    }
 
    public function edit(Request $request) { 
        $id = $request->category_id;
        $category = Category::findOrFail($id);
        $category->title = $request->title;
        $category->update();  
        Toastr::success('Votre libellé étè modifié avec succès', 'Modifier libellé', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    } 
    public function delete($id) {  
        $category = Category::findOrFail($id);        
         $category->delete(); 
        // $sheets_categories = SheetCategory::where('category_id',$id)->get();   
        
        // foreach ($sheets_categories as $sc) {  
        //     // dd($sc )   ;       
        //     $sc->delete(); 
        // }  
        Toastr::success('Votre libellé étè supprimée avec succès', 'Supprimer libellé', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }
 
}
