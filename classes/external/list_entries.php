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

namespace tool_yerairogo\external;

use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_api;
use core_external\external_value;

/**
 * Implementation of web service tool_yerairogo_list_entries
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class list_entries extends external_api {

    /**
     * Describes the parameters for tool_yerairogo_list_entries
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID', VALUE_REQUIRED),
        ]);
    }

    /**
     * Implementation of web service tool_yerairogo_list_entries
     *
     * @param int $courseid
     */
    public static function execute(int $courseid) {
        global $PAGE;

        // Parameter validation.
        $params = self::validate_parameters(self::execute_parameters(), ['courseid' => $courseid]);

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
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'courseid' => new external_value(PARAM_INT, 'Course id'),
            'coursename' => new external_value(PARAM_NOTAGS, 'Course name'),
            'entriestable' => new external_value(PARAM_RAW, 'Entries table contents'),
            'editurl' => new external_value(PARAM_URL, 'Link to the entry edition form', VALUE_OPTIONAL),
            'tooldescription' => new external_value(PARAM_NOTAGS, 'Tool description', VALUE_OPTIONAL),
        ]);
    }

}
