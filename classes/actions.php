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
            $params['courseid'] = $courseid;
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
        $data->timecreated = time();
        $data->timemodified = $data->timecreated;
        return $DB->insert_record('tool_yerairogo', $data);
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
        $DB->update_record('tool_yerairogo', $data);
    }

    /**
     * Delete an existing entry.
     * @param int $id Entry ID to delete.
     */
    public static function delete(int $id): void {
        global $DB;
        $DB->delete_records("tool_yerairogo", ['id' => $id]);
    }

}
