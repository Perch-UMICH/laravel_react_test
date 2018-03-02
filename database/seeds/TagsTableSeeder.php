<?php
use Illuminate\Database\Seeder;
use App\Tag;

class TagsTableSeeder extends Seeder{
    public function run() {
        DB::table('tags')->delete();

        $tag = new Tag();
        $tag->name = "Chemistry";
        $tag->save();

        $tag = new Tag();
        $tag->name = "Biophysical Chemistry";
        $tag->save();

        $tag = new Tag();
        $tag->name = "Organic Chemistry";
        $tag->save();

        $tag = new Tag();
        $tag->name = "Physical Chemistry";
        $tag->save();

        $tag = new Tag();
        $tag->name = "Biology";
        $tag->save();

        $tag = new Tag();
        $tag->name = "Cell Biology";
        $tag->save();

        $tag = new Tag();
        $tag->name = "Computational Biology";
        $tag->save();

        $tag = new Tag();
        $tag->name = "Biochemistry";
        $tag->save();
    }
}