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

use context_course;

/**
 * Tests for the events of tool_yerairogo plugin
 *
 * @package    tool_yerairogo
 * @category   test
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class events_test extends \advanced_testcase {

    /**
     * Reset the database before each execution.
     */
    public function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
    }

    /**
     * Test for event entry_created
     *
     * @covers \tool_yerairogo\event\entry_created
     */
    public function test_entry_created(): void {
        $course = $this->getDataGenerator()->create_course();

        // Trigger and capture the event.
        $sink = $this->redirectEvents();
        $entryid = actions::insert((object) [
            'courseid'  => $course->id,
            'name' => 'Test entry',
            'completed' => 0,
            'priority' => 0,
            'description' => 'Entry description',
        ]);
        $events = $sink->get_events();
        $event = reset($events);

        $this->assertInstanceOf('\tool_yerairogo\event\entry_created', $event);
        $this->assertEquals(context_course::instance($course->id), $event->get_context());
        $this->assertEquals($entryid, $event->objectid);
    }

    /**
     * Test for event entry_updated
     *
     * @covers \tool_yerairogo\event\entry_updated
     */
    public function test_entry_updated(): void {
        $course = $this->getDataGenerator()->create_course();
        $entryid = actions::insert((object) [
            'courseid'  => $course->id,
            'name' => 'Test entry',
            'completed' => 0,
            'priority' => 0,
            'description' => 'Entry description',
        ]);

        // Trigger and capture the event.
        $sink = $this->redirectEvents();
        actions::update((object) [
            'id' => $entryid,
            'name' => 'Test entry 2',
        ]);
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        $this->assertInstanceOf('\tool_yerairogo\event\entry_updated', $event);
        $this->assertEquals(context_course::instance($course->id), $event->get_context());
        $this->assertEquals($entryid, $event->objectid);
    }

    /**
     * Test for entry entry_deleted
     *
     * @covers \tool_yerairogo\event\entry_deleted
     */
    public function test_entry_deleted(): void {
        $course = $this->getDataGenerator()->create_course();
        $entryid = actions::insert((object) [
            'courseid'  => $course->id,
            'name' => 'Test entry',
            'completed' => 0,
            'priority' => 0,
            'description' => 'Entry description',
        ]);

        // Trigger and capture the event.
        $sink = $this->redirectEvents();
        actions::delete($entryid);
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        $this->assertInstanceOf('\tool_yerairogo\event\entry_deleted', $event);
        $this->assertEquals(context_course::instance($course->id), $event->get_context());
        $this->assertEquals($entryid, $event->objectid);
    }

}
