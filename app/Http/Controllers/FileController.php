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

    public function add_resume_to_user(Request $request, User $user) {

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

            $file = new File(['path' => $path, 'url' => $url]);
            $file->save();

            $resume = new ResumeFileType(['current' => true, 'file_id' => $file->id, 'user_id' => $user->id]);
            $resume->save();

            return $this->outputJSON($file, 'Resume added to user');
        }
        else {
            return $this->outputJSON(null, 'Error: invalid file', 401);
        }
    }

    public function edit_user_resume(Request $request, User $user) {
        //
    }

    public function get_resume_from_user(Request $request, User $user) {
        $resume = $user->resume()->first();
        if ($pic) $file = $resume->file()->first();
        else $file = null;

        return $this->outputJSON($file, 'Retrieved user resume');
    }

    public function delete_user_resume(Request $request, User $user) {
        $res = $user->resume()->first();
        if (!$res) return $this->outputJSON(null, 'Error: user does not have a profile pic');
        $file = $res->file()->first();

        Storage::disk('s3')->delete($file->path);
        $file->delete();
        // Should cascade to delete the resume object

        return $this->outputJSON(null, 'Deleted user resume');
    }


    public function add_pic_to_user(Request $request, User $user) {
        $input = $request->all();

        $request->validate([
            'file' => 'image',
        ]);

        // Delete old propic
        $pic = $user->profile_pic()->first();
        if ($pic) {
            $file = $pic->file;
            Storage::disk('s3')->delete($file->path);
            $file->delete();
        }

        $file = $request->file('file');

        if ($file->isValid()) {
            // Crop
            $img = Image::make($file->getRealPath());
            $size = min($img->width(), $img->height());
            $w = $size / $input['scale'];
            $h = $size / $input['scale'];
            $x = ($input['x'] * $img->width()) - (0.5)*($w);
            $y = ($input['y'] * $img->height()) - (0.5)*($h);
            $img->crop(intval($w), intval($h), intval($x), intval($y));
            $img->save();

            // Save
            $path = Storage::disk('s3')->put('users/' . $user->id . '/img', $file, 'public');
            $url = Storage::disk('s3')->url($path);

            $file = new File(['path' => $path, 'url' => $url]);
            $file->save();

            $propic = new ProfilePicFileType(['current' => true, 'file_id' => $file->id, 'user_id' => $user->id]);
            $propic->save();

            return $this->outputJSON($file, 'Profile pic added to user');
        }
        else {
            return $this->outputJSON(null, 'Error: invalid file', 401);
        }
    }

    // TODO Note working (can't edit while in s3 storage)
    public function edit_user_pic(Request $request, User $user) {
        $input = $request->all();

        $pic = $user->profile_pic()->first();
        if (!$pic) return $this->outputJSON(null, 'Error: user does not have a profile pic');
        $file = $pic->file()->first();
        $f_string = Storage::disk('s3')->get($file->path);

        // Crop
        $img = Image::make($f_string);
        $size = min($img->width(), $img->height());
        $w = $size / $input['scale'];
        $h = $size / $input['scale'];
        $x = ($input['x'] * $img->width()) - (0.5)*($w);
        $y = ($input['y'] * $img->height()) - (0.5)*($h);
        $img->crop(intval($w), intval($h), intval($x), intval($y));
        $img->save();

        return $this->outputJSON($file, 'Updated user profile pic');
    }

    public function get_pic_from_user(Request $request, User $user) {
        $pic = $user->profile_pic()->first();
        if ($pic) $file = $pic->file()->first();
        else $file = null;

        return $this->outputJSON($file, 'Retrieved user profile pic');
    }

    public function delete_user_pic(Request $request, User $user) {
        $pic = $user->profile_pic()->first();
        if (!$pic) return $this->outputJSON(null, 'Error: user does not have a profile pic');
        $file = $pic->file()->first();

        Storage::disk('s3')->delete($file->path);
        $file->delete();
        // Should cascade to delete the profile_pic object

        return $this->outputJSON(null, 'Deleted user profile pic');
    }


    public function add_pic_to_lab(Request $request, Lab $lab) {
        $input = $request->all();

        $request->validate([
            'file' => 'image',
        ]);

        // Delete old lab pic
        $pic = $lab->lab_pic()->first();
        if ($pic) {
            $file = $pic->file;
            Storage::disk('s3')->delete($file->path);
            $file->delete();
        }

        $file = $request->file('file');

        if ($file->isValid()) {
            // Crop
            $img = Image::make($file->getRealPath());
            $size = min($img->width(), $img->height());
            $w = $size / $input['scale'];
            $h = $size / $input['scale'];
            $x = ($input['x'] * $img->width()) - (0.5)*($w);
            $y = ($input['y'] * $img->height()) - (0.5)*($h);
            $img->crop(intval($w), intval($h), intval($x), intval($y));
            $img->save();

            // Save
            $path = Storage::disk('s3')->put('labs/' . $lab->id . '/img', $file, 'public');
            $url = Storage::disk('s3')->url($path);


            $file = new File(['path' => $path, 'url' => $url]);
            $file->save();

            $labpic = new LabPicFileType(['file_id' => $file->id, 'lab_id' => $lab->id]);
            $labpic->save();
            return $this->outputJSON($file, 'Lab pic added to lab');
        }
        else {
            return $this->outputJSON(null, 'Error: invalid file', 401);
        }
    }

    public function edit_lab_pic(Request $request, User $user) {
        //
    }

    public function get_pic_from_lab(Request $request, Lab $lab) {
        $pic = $lab->lab_pic()->first();
        if ($pic) $file = $pic->file()->first();
        else $file = null;

        return $this->outputJSON($file, 'Retrieved lab pic');
    }

    public function delete_lab_pic(Request $request, Lab $lab) {
        $pic = $lab->lab_pic()->first();
        $file = $pic->file()->first();

        Storage::disk('s3')->delete($file->path);
        $file->delete();
        // Should cascade to delete the lab_pic object

        return $this->outputJSON(null, 'Deleted lab pic');
    }

}
