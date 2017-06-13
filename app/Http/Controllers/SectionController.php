<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Section;

class SectionController extends Controller
{

    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Store new project section
     * @param \Illuminate\Http\Request $request
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project) {
        $section =  new Section();
        /** validate the request data */
        $this->validate(Request(),$section->validation, $section->messages);
        /** create new section */
        $section = Section::create(['name' => $request->get('name'), 'project_id' => $project->id]);
        /** return success and stored section */
        return ['success' => true, 'message' => 'section has been created', 'section' => $section];
    }

    /**
     * get section data
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section) {
        /** If section cant be found return error */
        if(!$section){
            return ['success' => false, 'message' => 'The requested section could not be found'];
        }
        /** return success and requested section */
        return ['success' => true, 'message' => 'section has been found', 'project' => $section];
    }

    /**
     * update section data
     * @param \Illuminate\Http\Request $request
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section) {
        /** If section cant be found return error */
        if(!$section){
            return ['success' => false, 'message' => 'The requested section could not be found'];
        }
        /** validate the section data */
        $this->validate(Request(),$section->validation, $section->messages);
        /** update record */
        $section->name = $request->get('name');
        $section->save();
        /** return success and updated project */
        return ['success' => true, 'message' => 'section has been updated', 'section' => $section];
    }

    /**
     * Delete project section
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section) {
        /** If section cant be found return error */
        if(!$section){
            return ['success' => false, 'message' => 'The requested section could not be found'];
        }
        /** delete project */
        $section->delete();
        /** return success message */
        return ['success' => true, 'message' => 'Section '.$section->name.' has been successfully deleted'];
    }
}
