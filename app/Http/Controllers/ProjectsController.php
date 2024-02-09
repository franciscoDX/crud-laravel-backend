<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Models\Project;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function handleGetProjectsRequest(Request $request)
    {
        $filters = $request->only(['pageNumber', 'projectId', 'pageSize', 'dateFrom', 'dateTo']);
        $pedidos = self::getProjects($filters);
        return response()->json($pedidos);
    }

    public function getProjects($filters)
    {
        $pageNumber = $filters['pageNumber'] ?? 1;
        $pageSize = $filters['pageSize'] ?? 10;
        $number = $pageSize * $pageNumber - $pageSize;

        $query = Project::query();
        
        $this->applyFilters($query, $filters);

        $totalRecords = $query->count();
        $totalPages = ceil($totalRecords / $pageSize);

        $projectList = $query->skip($number)
                              ->take($pageSize)
                              ->orderBy('id', 'DESC')
                              ->get();

        return ['totalPages' => $totalPages, 
                'totalRecords' => $totalRecords, 
                'projectList' => $projectList];
    }

    public function addProject(CreateProjectRequest $request)
    {
        try {
            $project = new Project();
            $project->project_name = $request->projectName;
            $project->client_name = $request->clientName;
            $project->status = $request->status;
            $project->progress = $request->progress;
            $project->project_description = isset($request->description) ? $request->description : '';
            $project->deadline = isset($request->deadline) ? $request->deadline : null;
            $project->user_id = 1; // Auth::user()->id;  <--- after configure the authentication
            $project->save();
    
            return response()->json(['success' => true, 'message' => 'Project created']);
        } catch (QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Error creating project']);
        }
    }

    public function deleteProject($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Project not found']);
        }

        $project->delete();
        return response()->json(['success' => true, 'message' => 'Project deleted']);
    }

    private function applyFilters($query, $filters) {
        if (isset($filters['projectId'])) {
            $query->projectId($filters['projectId']);
        }
        if (isset($filters['dateFrom'])) {
            $query->dateFrom(date($filters['dateFrom']));
        }
        if (isset($filters['dateTo'])) {
            $query->dateTo(date($filters['dateTo']));
        }
    }
}