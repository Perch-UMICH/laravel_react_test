<?php

namespace App\Http\Controllers;

use App\Http\Middleware\StudentProfileOwner;
use App\Position;
use App\Application;
use App\ApplicationResponse;
use App\AppQuestionResponse;
use App\Student;
use Illuminate\Http\Request;
use App\User;
use App\Skill;
use App\Tag;
use App\Lab;
use Storage;
use App\WorkExperience;
use App\ClassExperience;
use App\EduExperience;
use App\University;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all();

        $student_data = [];

        foreach( $students as $student ) {
            $student->skills;
            $student->tags;
            $student->work_experiences;
            $student->edu_experiences;
            $student->position_list;
            $student_data[$student->id] = $student;
        }
        return $this->outputJSON($student_data, 'Students retrieved');
    }


    // Get Student based on student_id
    public function show(Student $student)
    {
        $s = $student->toArray();
        $s['skills'] = $student->skills;
        $s['tags'] = $student->tags;
        $s['work_experiences'] = $student->work_experiences;
        $s['edu_experiences'] = $student->edu_experiences()->with('classes','majors','university')->get();
        $s['position_list'] = $student->position_list()->with('departments','skills','tags','lab')->get();

        return $this->outputJSON($s,"Student retrieved");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input = array_filter($input);

        $student = Student::where('user_id', $input['user_id'])->count();
        if ($student > 0) {
            $student = Student::where('user_id', $input['user_id'])->get();
            return $this->outputJSON($student, 'Error: this user already has a student profile');
        }
        $user = User::find($input['user_id']);
        if ($user == null) {
            return $this->outputJSON(null, 'Error: user_id is invalid');
        }

        $student = new Student($input);
        $user->is_student = true;
        $user->student()->save($student);
        $user->save();

        // Skills
        if ($request->has('skill_ids')) {
            $this->sync_skills($request, $student);
        }
        // Tags
        if ($request->has('tag_ids')) {
            $this->sync_tags($request, $student);
        }

        return $this->outputJSON($student, 'Student profile created');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        // TODO check for issues with empty arrays
        $input = $request->all();
        $input = array_filter($input);
        $student->update($input);
        $student->save();

        // Skills
        if ($request->has('skill_ids')) {
            $this->sync_skills($request, $student);
        }
        // Tags
        if ($request->has('tag_ids')) {
            $this->sync_tags($request, $student);
        }

        $student->skills;
        $student->tags;
        $student->position_list;
        $student->work_experiences;
        $student->edu_experiences;

        return $this->outputJSON($student, 'Student profile updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return $this->outputJSON(null, 'Student profile deleted');
    }

    // STUDENT ASSOCIATIONS:

    // Skills:

    // Get student skills of a student, based on student_id
    public function skills(Student $student) {

        $skills = $student->skills()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($skills,"Skills retrieved");
    }

    public function add_skill(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $student->skills()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added skills");
    }

    public function sync_skills(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        if ($ids[0] == 0) {
            $student->skills()->detach();
        }
        else {
            $student->skills()->sync($ids);
        }
        return $this->outputJSON(null,"Synced skills");
    }

    public function remove_skill(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['skill_ids'];
        $student->skills()->detach($ids);
        return $this->outputJSON(null,"Removed skills");
    }

    // Tags:

    // Get student tags of a student, based on student_id
    public function tags(Student $student) {
        $tags = $student->tags()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($tags,"Tags retrieved");
    }

    public function add_tag(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $student->tags()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added tags");
    }

    public function sync_tags(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        if ($ids[0] == 0) {
            $student->tags()->detach();
        }
        else {
            $student->tags()->sync($ids);
        }
        return $this->outputJSON(null,"Synced tags");
    }

    public function remove_tag(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['tag_ids'];
        $student->tags()->detach($ids);
        return $this->outputJSON(null,"Removed tags");
    }

    // Courses:

//    public function school_courses(Student $student) {
//        $courses = $student->school_courses()->wherePivot('student_id', $student->id)->get();
//        return $this->outputJSON($courses,"School courses retrieved");
//    }
//
//    public function add_school_courses(Request $request, Student $student) {
//        $input = $request->all();
//        $ids = $input['course_ids'];
//        $student->school_courses()->sync($ids);
//        return $this->outputJSON(null,"Added courses");
//    }
//
//    public function remove_school_courses(Request $request, Student $student) {
//        $input = $request->all();
//        $ids = $input['course_ids'];
//        $student->school_courses()->detach($ids);
//        return $this->outputJSON(null,"Removed courses");
//    }

    // Searched Labs:


    // Get favorited labs of student, based on student_id
    public function position_list(Student $student) {
        $labs = $student->position_list()->wherePivot('student_id', $student->id)->get();
        return $this->outputJSON($labs,"Labs retrieved");
    }

    public function sync_position_list(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['position_ids'];
        if ($ids[0] == 0) {
            $student->position_list()->detach();
        }
        else {
            $student->position_list()->sync($ids);
        }
        return $this->outputJSON(null,"Synced positions");
    }

    public function add_to_position_list(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['position_ids'];
        $student->position_list()->syncWithoutDetaching($ids);
        return $this->outputJSON(null,"Added positions");
    }

    public function remove_from_position_list(Request $request, Student $student)
    {
        $input = $request->all();
        $ids = $input['position_ids'];
        $student->position_list()->detach($ids);
        return $this->outputJSON(null, "Removed positions");
    }


    // App Responses:

    public function app_responses(Student $student) {
        $responses = $student->app_responses;
        return $this->outputJSON($responses,"App responses retrieved");
    }

    public function create_app_response(Request $request, Student $student) {
        $input = $request->all();
        $position = Position::where('id', $input['position_id'])->first();    // Position this is responding to
        $response_strings = $input['answers'];                              // Response strings

        if (!$position) return $this->outputJSON(null, 'Error: Invalid position_id');
        $application = $position->application;

        // Create new response, and associate with application and student
        $response = new ApplicationResponse();
        $response->sent = false;
        $response->save();
        $application->responses()->save($response);
        $student->responses()->save($response);

        // Get application questions
        $questions = $application->questions;

        $count = 0;
        foreach ($questions as $q) {
            $response_string = $response_strings[$count];
            $question_response = new AppQuestionResponse();
            $question_response->response = $response_string;
            $question_response->save();

            // Associate with corresponding AppQuestion and AppResponse
            $q->answers()->save($question_response);
            $response->answers()->save($question_response);
            $count++;
        }

        return $this->outputJSON(['base' => $response, 'answers' => $response->answers], 'Response created for position ' . $position->name);
    }

    public function update_app_response(Request $request, Student $student) {
        $input = $request->all();
        $response_strings = $input['answers'];   // Updated response strings
        $applicationResponse = ApplicationResponse::find($input['application_response_id']);

        if (!$applicationResponse) return $this->outputJSON(null, 'Error: application_response_id is invalid');
        if ($applicationResponse->student->id != $student->id)  return $this->outputJSON(null, 'Error: student of id ' . $student->id . ' does not own this application response');

        $responses = $applicationResponse->responses;

        $count = 0;
        foreach ($responses as $r) {
            $r->response = $response_strings[$count];
            $r->save();
            $count++;
        }

        return $this->outputJSON(['base' => $applicationResponse, 'responses' => $applicationResponse->responses], 'Response updated');
    }

    public function delete_app_response(Request $request, Student $student) {
        $input = $request->all();
        $applicationResponse = ApplicationResponse::find($input['application_response_id']);

        if (!$applicationResponse) return $this->outputJSON(null, 'Error: application_response_id is invalid');
        if ($applicationResponse->student->id != $student->id)  return $this->outputJSON(null, 'Error: student of id ' . $student->id . ' does not own this application response');

        return $this->outputJSON(null, 'Response deleted');
    }

    public function submit_app_response(Request $request, Student $student) {
        $input = $request->all();
        $applicationResponse = ApplicationResponse::find($input['application_response_id']);

        if (!$applicationResponse) return $this->outputJSON(null, 'Error: application_response_id is invalid');
        if ($applicationResponse->student->id != $student->id) return $this->outputJSON(null, 'Error: student of id ' . $student->id . ' does not own this application response');

        $applicationResponse->sent = true;

        return $this->outputJSON(null, 'Response submitted to position ' . $applicationResponse->application->position->name);
    }

    // Resume:

    public function add_resume(Request $request, Student $student) {
        // Delete old resume if exists
        // NOTE: this doesn't seem to work...
        Storage::delete($student->resume_path);
        $path = Storage::putFile('resumes', $request->file('resume'));
        $student->resume_path = $path;

        return $this->outputJSON($student, 'Stored new resume');
    }

    // Experiences

    public function create_and_add_work_experience(Request $request, Student $student) {
        $input = $request->all();
        $e = $input['work_experience'];

        $work = new WorkExperience($e);
        $student->work_experiences()->save($work);

        $student->work_experiences;
        return $this->outputJSON($student, 'Added work experiences to student');
    }

    public function remove_work_experiences(Request $request, Student $student) {
        $input = $request->all();
        $ids = $input['work_experience_ids'];
        foreach ($ids as $id) {
            $work_exp = WorkExperience::find($id);
            if (!$work_exp) return $this->outputJSON(null,"Error: invalid work_experience id",404);
        }
        WorkExperience::destroy($ids);
        return $this->outputJSON($student, 'Deleted work experiences from student');
    }

//    public function create_and_sync_edu_experiences(Request $request, Student $student) {
//        $input = $request->all();
//        $input = array_filter($input);
//
//        $experiences = $input['edu_experiences'];
//
//        foreach ($experiences as $e) {
//            $edu_exp = EduExperience::create($e);
//            $major_ids = $e->major_ids;
//            $class_experience_ids = $e->class_experience_ids;
//            $edu_exp->majors()->syncWithoutDetaching($major_ids);
//            $edu_exp->classes()->syncWithoutDetaching($class_experience_ids);
//        }
//
//        return $this->outputJSON($student,"Added edu experiences",200);
//    }

//    public function add_class_experiences(Request $request, Student $student) {
//        $input = $request->all();
//        $experiences = $input['class_experiences'];
//        foreach ($experiences as $e) {
//            $class = ClassExperience::where('title', $e->title);
//            if ($class == null) {
//                $class = new ClassExperience($e);
//                $class->save();
//            }
//            $student->class_experiences()->attach($class->id);
//        }
//        return $this->outputJSON($student, 'Added class experience to student');
//    }
//
//    public function remove_class_experiences(Request $request, Student $student) {
//        $input = $request->all();
//        $ids = $input['ids'];
//        $student->class_experiences()->detach($ids);
//        return $this->outputJSON($student, 'Deleted class experiences from student');
//    }
}
