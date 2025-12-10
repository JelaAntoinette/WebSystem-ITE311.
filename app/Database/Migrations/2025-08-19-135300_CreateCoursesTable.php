<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'course_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'subject_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'comment'    => 'e.g., ITE311, CS201',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'instructor_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'year_level' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'e.g., 1st Year, 2nd Year, 3rd Year, 4th Year',
            ],
            'date_started' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'date_ended' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'year_started' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => true,
                'comment'    => 'Academic year start (e.g., 2024)',
            ],
            'year_ended' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => true,
                'comment'    => 'Academic year end (e.g., 2025)',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'completed'],
                'default'    => 'active',
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('courses', true);
    }

    public function down()
    {
        $this->forge->dropTable('courses');
    }
}