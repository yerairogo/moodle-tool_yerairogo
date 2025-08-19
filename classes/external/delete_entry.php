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
use core_external\external_api;
use core_external\external_value;
use tool_yerairogo\actions;

/**
 * Implementation of web service tool_yerairogo_delete_entry
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delete_entry extends external_api {

    /**
     * Describes the parameters for tool_yerairogo_delete_entry
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Entry ID', VALUE_REQUIRED),
        ]);
    }

    /**
     * Implementation of web service tool_yerairogo_delete_entry
     *
     * @param int $id
     */
    public static function execute(int $id) {
        // Parameter validation.
        $params = self::validate_parameters(self::execute_parameters(), ['id' => $id]);

        $entry = actions::get($params['id']);

        // From web services we don't call require_login(), but rather validate_context.
        $context = \context_course::instance($entry->courseid);
        self::validate_context($context);
        require_capability('tool/yerairogo:edit', $context);

        actions::delete($params['id']);
    }

    /**
     * Describe the return structure for tool_yerairogo_delete_entry
     */
    public static function execute_returns(): null {
        return null;
    }

}
