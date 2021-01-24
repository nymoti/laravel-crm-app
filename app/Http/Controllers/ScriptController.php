<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Script;
use Auth;
use Session;
use Toastr;
class ScriptController extends Controller
{
    

    public function __construct() {
        // $this->middleware(['auth', 'clearance'])->except('index', 'show');
        parent::__construct();

        $this->middleware('permission:script-list');

        $this->middleware('permission:script-create', ['only' => ['create','store']]);

        $this->middleware('permission:script-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:script-delete', ['only' => ['destroy']]);
    }
    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()
    {
 
        $scripts_agents = Script::where('type', 'Agent')->get();
        $scripts_closers = Script::where('type', 'Closer')->get();
        $scripts_side_bar = "Scripts side bar";
        $scripts_side_bar_list = "Scripts side bar new list"; 
        return view('app.scripts.index')
        ->with('scripts_agents', $scripts_agents)
        ->with('scripts_closers', $scripts_closers)
        ->with('scripts_side_bar', $scripts_side_bar)
        ->with('scripts_side_bar_list', $scripts_side_bar_list); 

    }

    
    public function getAgentsAndClosersScripts()
    { 
        $type = '';
        if (Auth()->user()->hasRole('Agent')){
            $type = 'Agent';
        }else{
            $type = 'Closer';
        }
        $scripts = Script::where('type', $type)->get(); 
        $scripts_side_bar = "Scripts side bar"; 
        return view('app.scripts.listscript')
        ->with('scripts', $scripts)  
        ->with('scripts_side_bar', $scripts_side_bar) ; 
 
    }




    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()
    {

        $scripts_side_bar = "Scripts side bar"; 
        $scripts_side_bar_newone = "Scripts side bar new one"; 
        return view('app.scripts.create')
        ->with('scripts_side_bar', $scripts_side_bar)
        ->with('scripts_side_bar_newone', $scripts_side_bar_newone); 

    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'title' => 'required',
            'body' => 'required'
        ]);
        // dd($request->all());
        $script = new Script;
        $script->type = $request->type;
        $script->title = $request->title;
        $script->body = $request->body;
        $script->save();
        // Script::create($request->all()); 
        Toastr::success('Votre script  a étè ajoutée avec succès', 'Ajouter un  script', ["positionClass" => "toast-top-right"]);
        return redirect()->route('scripts.index');  

    } 

    public function show(Script $script)
    {

        return view('app.scripts.show',compact('script'));

    } 

    public function edit($id)
    {
        $script = Script::findOrFail($id);
        $scripts_side_bar = "Scripts side bar";
        return view('app.scripts.edit')
        ->with('script', $script)
        ->with('scripts_side_bar', $scripts_side_bar); 
    } 

    public function update(Request $request, $id)
    { 
        $script = Script::findOrFail($id);
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);
        // dd($request->all());
        $script->update($request->all()); 
        Toastr::success('Votre script  a étè modifié avec succès', 'Modifier un  script', ["positionClass" => "toast-top-right"]);
        return redirect()->route('scripts.index');  

    } 

    public function destroy($id)
    {
        $script = Script::findOrFail($id);
        $script->delete();
        Toastr::success('Script a étè supprimée avec succès', 'Supprimer un Script', ["positionClass" => "toast-top-right"]);
        return redirect()->route('scripts.index'); 
    }
}
