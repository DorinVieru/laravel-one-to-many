<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderByDesc('id')->get();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $form_projects = $request->all();

        // CREO LA NUOVA ISTANZA PER COMICS PER SALVARLO NEL DATABASE
        $project = new Project();

        // VERIFICO SE LA RICHIESTA CONTIENE IL CAMPO cover_image
        if($request->hasFile('cover_image')) {
            // Eseguo l'upload del file e recupero il path
            $path = Storage::disk('public')->put('project_image', $form_projects['cover_image']);
            $form_projects['cover_image'] = $path;
        }

        // LO SLUG LO RECUPERO IN UN SECONDO MOMENTO, IN QUANTO NON LO PASSO NEL FORM
        $form_projects['slug'] = Str::slug($form_projects['title'], '-');
        // RECUPERO I DATI TRAMITE IL FILL
        $project->fill($form_projects);

        // SALVO I DATI
        $project->save();

        // FACCIO IL REDIRECT ALLA PAGINA SHOW 
        return redirect()->route('admin.projects.show', ['project' => $project]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Request $request)
    {
        // Genero una condizione per mostrarmi nell'edit e nel create un messaggio di errore personalizzato per la duplicazione di un titolo
        $error_message= '';
        if(!empty($request->all())) {
            $messages = $request->all();
            $error_message = $messages['error_message'];
        }

        return view('admin.projects.edit', compact('project', 'error_message'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $form_projects = $request->all();

        // Creare una query per la modifica di un post con lo stesso titolo
        $exists = Project::where('title', 'LIKE', $form_projects['title'])->where('id', '!=', $project->id)->get();
        // Condizione che mi permette di modificare un post mantenendo lo stesso titolo. Ma se cambio titolo e ne inserisco uno già presente in un altro progetto, mi mostra l'errore impostato.
        if(count($exists) > 0) {
            $error_message = 'Hai inserito un titolo già presente in un altro progetto.';
            return redirect()->route('admin.projects.edit', compact('project', 'error_message'));
        }
        
        // VERIFICO SE LA RICHIESTA CONTIENE IL CAMPO cover_image
        if ($request->hasFile('cover_image')) {
            // Se il post ha un'immagine
            if ($project->cover_image != null) {
                Storage::disk('public')->delete($project->cover_image);
            }

            // Eseguo l'upload del file e recupero il path
            $path = Storage::disk('public')->put('project_image', $form_projects['cover_image']);
            $form_projects['cover_image'] = $path;
        }

        // LO SLUG LO RECUPERO IN UN SECONDO MOMENTO, IN QUANTO NON LO PASSO NEL FORM
        $form_projects['slug'] = Str::slug($form_projects['title'], '-');

        // SALVO I DATI
        $project->update($form_projects);

        // FACCIO IL REDIRECT ALLA PAGINA SHOW 
        return redirect()->route('admin.projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        // CANCELLO L'IMMAGINE
        if($project->cover_image != null){
            Storage::disk('public')->delete($project->cover_image);
        }
        
        $project->delete();

        return redirect()->route('admin.projects.index');
    }
}
