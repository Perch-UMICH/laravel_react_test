<?php

use Illuminate\Database\Seeder;
use App\Lab;

class LabsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('labs')->delete();

        $lab = new Lab();
        $lab->name = "Nishii Lab";
        $lab->description = <<<DESC
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut ipsum condimentum, eleifend ante a, semper neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras gravida, magna sed finibus ornare, dolor massa sagittis enim, tincidunt luctus risus leo eu ipsum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla enim nibh, mollis sed erat at, euismod sagittis massa. Donec dapibus iaculis lectus, sed lobortis nisl. Integer est ipsum, sodales eu risus ut, condimentum posuere nunc. Nunc et ante magna. Maecenas faucibus finibus massa, ut imperdiet nunc fringilla id.

In consectetur, magna a pharetra aliquam, urna orci bibendum ligula, id vestibulum dui enim et arcu. Vivamus eu diam convallis, pretium mauris ut, varius leo. Morbi pellentesque vel libero eu ultrices. Sed in sem porta, commodo orci vel, pellentesque leo. Curabitur pulvinar blandit mollis. Morbi sed odio eget felis tempus sollicitudin eu ut odio. Quisque diam arcu, sollicitudin ac felis at, dapibus varius eros. Donec facilisis consequat sem, nec posuere quam. Aenean accumsan, libero et fringilla scelerisque, lectus est mollis risus, sit amet consequat neque elit eu mi. Praesent semper nisl quis sodales porttitor. Vivamus sodales laoreet sodales. Pellentesque id viverra mauris, eget pharetra sem.
DESC;
        $lab->publications = "Nishii, A. “Title”. Journal, <b>2017</b>, Volume (issue), Pages.
Nishii, A. “Title”. Journal, <b>2016</b>, Volume (issue), Pages.
Nishii, A. “Title”. Journal, <b>2015</b>, Volume (issue), Pages.";
        $lab->location = "1 Chemistry";
        $lab->save();

        $lab = new Lab();
        $lab->name = "Perch Lab";
        $lab->department = "Biology";
        $lab->description = <<<DESC
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut ipsum condimentum, eleifend ante a, semper neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras gravida, magna sed finibus ornare, dolor massa sagittis enim, tincidunt luctus risus leo eu ipsum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla enim nibh, mollis sed erat at, euismod sagittis massa. Donec dapibus iaculis lectus, sed lobortis nisl. Integer est ipsum, sodales eu risus ut, condimentum posuere nunc. Nunc et ante magna. Maecenas faucibus finibus massa, ut imperdiet nunc fringilla id.

In consectetur, magna a pharetra aliquam, urna orci bibendum ligula, id vestibulum dui enim et arcu. Vivamus eu diam convallis, pretium mauris ut, varius leo. Morbi pellentesque vel libero eu ultrices. Sed in sem porta, commodo orci vel, pellentesque leo. Curabitur pulvinar blandit mollis. Morbi sed odio eget felis tempus sollicitudin eu ut odio. Quisque diam arcu, sollicitudin ac felis at, dapibus varius eros. Donec facilisis consequat sem, nec posuere quam. Aenean accumsan, libero et fringilla scelerisque, lectus est mollis risus, sit amet consequat neque elit eu mi. Praesent semper nisl quis sodales porttitor. Vivamus sodales laoreet sodales. Pellentesque id viverra mauris, eget pharetra sem.
DESC;
        $lab->publications = "Perch, F. “Title”. Journal, <b>2017</b>, Volume (issue), Pages.
Perch, F. “Title”. Journal, <b>2016</b>, Volume (issue), Pages.
Perch, F. “Title”. Journal, <b>2015</b>, Volume (issue), Pages.";
        $lab->location = "1 Biology";
        $lab->save();

    }
}
