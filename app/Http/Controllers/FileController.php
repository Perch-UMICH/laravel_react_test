<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Lab;
use App\File;
use App\ResumeFileType;
use App\ProfilePicFileType;
use App\LabPicFileType;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    // Types:
    // propic
    // resume
    // labpic

    public function create_filename() {

    }

    public function add_resume_to_user(Request $request) {
        $user = $request->user();

        $request->validate([
            'file' => 'mimes:doc,pdf,docx,zip',
        ]);

        // Delete old resume
        $resume = $user->resume()->first();
        if ($resume) {
            $file = $resume->file;
            Storage::disk('s3')->delete($file->path);
            $file->delete();
        }

        $file = $request->file('file');

        if ($file->isValid()) {
            $path = Storage::disk('s3')->put('users/' . $user->id . '/doc', $file, 'public');
            $url = Storage::disk('s3')->url($path);
            $file = new File(['path' => $path, 'url' => $url, 'user_id' => $user->id]);
            $user->files()->save($file);
            $type = new ResumeFileType(['current' => true, 'file_id' => $file->id]);
            $file->resume_type()->save($type);

            return $this->outputJSON($file, 'Resume added to user');
        }
        else {
            return $this->outputJSON(null, 'Error: invalid file', 401);
        }
    }

    public function get_user_resume(Request $request) {
        $input = $request->all();
        $u_id = $input['user_id'];
        $user = User::find($u_id);
        $resume = $user->resume()->first();
        $resume->file;

        return $this->outputJSON($resume, 'Retrieved user resume');
    }

//    public function set_user_resume_to_current(Request $request) {
//        $user = $request->user();
//        $files = $user->files()->has('resume_type')->get();
//
//        $file_id = $request->get('file_id');
//        foreach ($files as $f) {
//            if ($f == $file_id) {
//                $f->resume_type->current = true;
//            }
//            else {
//                $f->resume_type->current = false;
//            }
//        }
//
//        return $this->outputJSON($files, 'Set resume to current');
//    }

    public function add_profile_pic_to_user(Request $request) {
        $user = $request->user();
        $input = $request->all();

        $request->validate([
            'file' => 'image',
        ]);

        // Delete old propic
        $pic = $user->profile_pic()->first();
        if ($pic) $pic->delete();

        $file = $request->file('file');


        if ($file->isValid()) {
            // Crop
            $img = Image::make($file->getRealPath());
            $img->crop($input['width'], $input['height'], $input['x'], $input['y']);
            $img->save();

            // Save
            $path = Storage::disk('s3')->put('users/' . $user->id . '/img', $file, 'public');
            $url = Storage::disk('s3')->url($path);

            $file = new File(['path' => $path, 'url' => $url, 'user_id' => $user->id]);
            $user->files()->save($file);
            $type = new ProfilePicFileType(['current' => true, 'file_id' => $file->id]);
            $file->resume_type()->save($type);

            return $this->outputJSON($file, 'Profile pic added to user');
        }
        else {
            return $this->outputJSON(null, 'Error: invalid file', 401);
        }
    }

    public function get_user_profile_pic(Request $request) {
        $input = $request->all();
        $u_id = $input['user_id'];
        $user = User::find($u_id);
        $pic = $user->profile_pic()->first();
        $pic->file;

        return $this->outputJSON($pic, 'Retrieved user profile pic');
    }

//    public function add_image_to_lab(Request $request, Lab $lab) {
//        $user = $request->user();
//        $path = Storage::disk('s3')->putFile('users/' . $lab->id . '/img', $request->file('file'));
//
//        $file = File::create(['path' => $path]);
//
//        $lab->files()->syncWithoutDetaching($file->id);
//
//        return $this->outputJSON($file, 'File added to user');
//    }
}
