<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace tool_yerairogo;
use advanced_testcase;

/**
 * Tests for the class actions of tool_yerairogo plugin
 *
 * @package    tool_yerairogo
 * @category   test
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class actions_test extends advanced_testcase {

    /**
     * Reset the database before each execution.
     */
    public function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
    }

    /**
     * Test insert and get actions
     *
     * @covers \tool_yerairogo\actions::insert
     * @covers \tool_yerairogo\actions::get
     */
    public function test_insert(): void {
        $course = $this->getDataGenerator()->create_course();
        $entryid = actions::insert((object) [
            'courseid'  => $course->id,
            'name' => 'Test entry',
            'completed' => 0,
            'priority' => 0,
            'description' => 'Entry description',
        ]);
        $entry = actions::get($entryid);
        $this->assertEquals($course->id, $entry->courseid);
        $this->assertEquals('Test entry', $entry->name);
        $this->assertEquals('Entry description', $entry->description);
    }

    /**
     * Test existing entry update
     *
     * @covers \tool_yerairogo\actions::update
     */
    public function test_update(): void {
        $course = $this->getDataGenerator()->create_course();
        $entryid = actions::insert((object) [
            'courseid'  => $course->id,
            'name' => 'Test entry',
            'description' => 'Entry description',
        ]);
        actions::update((object) [
            'id' => $entryid,
            'name' => 'Updated entry',
            'description' => 'Updated description',
        ]);
        $entry = actions::get($entryid);
        $this->assertEquals($course->id, $entry->courseid);
        $this->assertEquals('Updated entry', $entry->name);
        $this->assertEquals('Updated description', $entry->description);
    }

    /**
     * Test entry deletion
     *
     * @covers \tool_yerairogo\actions::delete
     */
    public function test_delete(): void {
        $course = $this->getDataGenerator()->create_course();
        $entryid = actions::insert((object) [
            'courseid'  => $course->id,
            'name' => 'Test entry',
        ]);
        actions::delete($entryid);
        $entry = actions::get($entryid, 0, IGNORE_MISSING);
        $this->assertEmpty($entry);
    }

    /**
     * Test description editor
     *
     * @covers \tool_yerairogo\actions::insert
     * @covers \tool_yerairogo\actions::update
     * @covers \tool_yerairogo\actions::get
     */
    public function test_description_editor(): void {
        $this->setAdminUser();
        $course = $this->getDataGenerator()->create_course();
        $entryid = actions::insert((object) [
            'courseid' => $course->id,
            'name' => 'Test entry 1',
            'description_editor' => [
                'text' => 'Description in HTML',
                'format' => FORMAT_HTML,
                'itemid' => file_get_unused_draft_itemid(),
            ],
        ]);
        $entry = actions::get($entryid);
        $this->assertEquals('Description in HTML', $entry->description);

        actions::update((object) [
            'id' => $entryid,
            'name' => 'Test entry 2',
            'description_editor' => [
                'text' => 'Updated description',
                'format' => FORMAT_HTML,
                'itemid' => file_get_unused_draft_itemid(),
            ],
        ]);
        $entry = actions::get($entryid);

        $this->assertEquals($course->id, $entry->courseid);
        $this->assertEquals('Test entry 2', $entry->name);
        $this->assertEquals('Updated description', $entry->description);

    }

}
