@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center align-items-center">
            <h1 class="fw-bold">Modifica il progetto {{ $project->id }}</h1>  
        </div>
        <div class="col-10 mt-5">
            {{-- Condizione per ciclare gli errori da mostrare --}}
             @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
             @endif
             {{-- Condizione per l'errore della duplicazione del titolo --}}
             @if ($error_message != '')
                <div class="alert alert-danger">
                    {{ $error_message }}
                </div> 
             @endif
             {{-- FORM --}}
            <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <input type="text" name="title" class="form-control @error ('title') is-invalid @enderror" id="title" placeholder="Titolo" required value="{{ old('title') ?? $project->title }}">
                    @error ('title')
                        <div class="text-danger fw-semibold">{{ $message }}</div>
                    @enderror
                </div>
                    <div class="mb-3">
                    <textarea name="description" class="form-control @error ('description') is-invalid @enderror" id="description" rows="5" placeholder="Descrizione" required>{{ old('description') ?? $project->description }}</textarea>
                    @error ('description')
                        <div class="text-danger fw-semibold">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    @if ($project->cover_image != null)
                        <img class="rounded-3 mb-3" src="{{ asset('/storage/' . $project->cover_image) }}" alt="{{ $project->title }}" width="250">
                    @else
                    <h6 class="fw-bold">Immagine di copertina non impostata</h6>
                    @endif

                    <input type="file" name="cover_image" class="form-control @error ('cover_image') is-invalid @enderror" id="cover_image" placeholder="Immagine di copertina" value="{{ old('cover_image') ?? $project->cover_image }}">
                    @error ('cover_image')
                        <div class="text-danger fw-semibold">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-center mb-5">
                    <button type="submit" class="btn btn-warning px-5 fs-4">Modifica ora il progetto</button>
                </div>
            </form>
        </div>
        <div class="col-10 text-center mt-5">
            <a href="/admin/projects" > <button class="btn btn-success">Torna indietro</button></a>
        </div>
    </div>
</div>
@endsection
