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
use core\exception\coding_exception;
use stdClass;

/**
 * Class api
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class actions {

    /**
     * Retrieve an existing entry.
     * @param int $id
     * @param int $courseid
     * @param int $strictness
     * @return stdClass|bool Object containing the entry data or false if not found.
     */
    public static function get(int $id, int $courseid = 0, int $strictness = MUST_EXIST) {
        global $DB;
        $conditions = ['id' => $id];
        if ($courseid) {
            $conditions['courseid'] = $courseid;
        }
        return $DB->get_record('tool_yerairogo', $conditions, '*', $strictness);
    }

    /**
     * Insert a new entry.
     * @param stdClass $data Entry data to insert.
     * @return int The ID of the newly inserted entry.
     */
    public static function insert(stdClass $data): int {
        global $DB;

        if (empty($data->courseid)) {
            throw new coding_exception('Object data must contain property courseid');
        }

        $context = context_course::instance($data->courseid);

        $insertdata = array_intersect_key((array) $data, [
            'courseid' => 1, 'name' => 1, 'completed' => 1, 'priority' => 1, 'description' => 1, 'descriptionformat' => 1,
        ]);
        $insertdata['timemodified'] = $insertdata['timecreated'] = time();
        $entryid = $DB->insert_record('tool_yerairogo', $insertdata);

        if (!empty($data->description_editor)) {
            $data = file_postupdate_standard_editor($data, 'description', self::editor_options(),
            $context, 'tool_yerairogo', 'entry', $entryid);
            $updatedata = ['id' => $entryid, 'description' => $data->description, 'descriptionformat' => $data->descriptionformat];
            $DB->update_record('tool_yerairogo', $updatedata);
        }

        // Trigger event.
        $event = \tool_yerairogo\event\entry_created::create([
            'context' => $context,
            'objectid' => $entryid,
        ]);
        $event->trigger();

        return $entryid;
    }

    /**
     * Update an existing entry.
     * @param stdClass $data
     */
    public static function update(stdClass $data): void {
        global $DB;

        if (empty($data->id)) {
            throw new coding_exception('Object data must contain property id');
        }

        $data->timemodified = time();

        if (!empty($data->description_editor)) {
            $options = self::editor_options();
            $editordata = file_postupdate_standard_editor($data, 'description', self::editor_options(),
            $options['context'], 'tool_yerairogo', 'entry', $data->id);
            $data->description = $editordata->description;
            $data->descriptionformat = $editordata->descriptionformat;
        }

        $DB->update_record('tool_yerairogo', $data);

        // Trigger event.
        $entry = self::get($data->id);
        $event = \tool_yerairogo\event\entry_updated::create([
            'context' => context_course::instance($entry->courseid),
            'objectid' => $entry->id,
        ]);
        $event->trigger();
    }

    /**
     * Delete an existing entry.
     * @param int $id Entry ID to delete.
     */
    public static function delete(int $id): void {
        global $DB;
        if (!$entry = self::get($id, 0, IGNORE_MISSING)) {
            return;
        }
        $DB->delete_records("tool_yerairogo", ['id' => $id]);

        // Trigger event.
        $event = \tool_yerairogo\event\entry_deleted::create([
            'context' => context_course::instance($entry->courseid),
            'objectid' => $entry->id,
        ]);
        $event->add_record_snapshot('tool_yerairogo', $entry);
        $event->trigger();
    }

    /**
     * Options for the description editor
     * @return array
     */
    public static function editor_options() {
        global $PAGE;
        return [
            'context' => $PAGE->context,
        ];
    }

    /**
     * Delete entries when course is deleted
     * @param \core\event\course_deleted $event
     */
    public static function course_deleted_observer(\core\event\course_deleted $event): void {
        global $DB;
        $DB->delete_records('tool_yerairogo', ['courseid' => $event->objectid]);
    }

}
