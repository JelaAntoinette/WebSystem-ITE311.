<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeacherIdToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'teacher_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
            ],
        ];

        // Add column if it doesn't already exist
        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'teacher_id');
    }
}
