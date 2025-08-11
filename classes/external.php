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

use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_api;
use core_external\external_value;

/**
 * Implementation of web services for My first Moodle plugin
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * Describes the parameters for tool_yerairogo_delete_entry
     *
     * @return external_function_parameters
     */
    public static function delete_entry_parameters(): external_function_parameters {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Entry ID', VALUE_REQUIRED),
        ]);
    }

    /**
     * Implementation of web service tool_yerairogo_delete_entry
     *
     * @param int $id ID of the entry
     */
    public static function delete_entry(int $id) {
        // Parameter validation.
        $params = self::validate_parameters(self::delete_entry_parameters(), ['id' => $id]);

        $entry = actions::get($params['id']);

        // From web services we don't call require_login(), but rather validate_context.
        $context = \context_course::instance($entry->courseid);
        self::validate_context($context);
        require_capability('tool/yerairogo:edit', $context);

        actions::delete($params['id']);
    }

    /**
     * Describe the return structure for tool_yerairogo_delete_entry
     *
     * @return external_single_structure
     */
    public static function delete_entry_returns(): null {
        return null;
    }

    /**
     * Describes the parameters for tool_yerairogo_list_entries
     *
     * @return external_function_parameters
     */
    public static function list_entries_parameters(): external_function_parameters {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID', VALUE_REQUIRED),
        ]);
    }

    /**
     * Implementation of web service tool_yerairogo_list_entries
     *
     * @param int $courseid ID of the course
     * @return array
     */
    public static function list_entries(int $courseid): array {
        global $PAGE;

        // Parameter validation.
        $params = self::validate_parameters(self::list_entries_parameters(), ['courseid' => $courseid]);

        // From web services we don't call require_login(), but rather validate_context.
        $context = \context_course::instance($params['courseid']);
        self::validate_context($context);
        require_capability('tool/yerairogo:view', $context);

        $output = new \tool_yerairogo\output\entries_list($params['courseid']);
        $renderer = $PAGE->get_renderer('tool_yerairogo');
        return $output->export_for_template($renderer);
    }

    /**
     * Describe the return structure for tool_yerairogo_list_entries
     *
     * @return external_single_structure
     */
    public static function list_entries_returns(): external_single_structure {
        return new external_single_structure([
            'courseid' => new external_value(PARAM_INT, 'Course id'),
            'coursename' => new external_value(PARAM_NOTAGS, 'Course name'),
            'entriestable' => new external_value(PARAM_RAW, 'Entries table contents'),
            'editurl' => new external_value(PARAM_URL, 'Link to the entry edition form', VALUE_OPTIONAL),
        ]);
    }

}
