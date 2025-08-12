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

/**
 * Restore support for tool_yerairogo plugin.
 *
 * @package    tool_yerairogo
 * @category   backup
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/restore_tool_plugin.class.php');

/**
 * Class restore_tool_yerairogo_plugin
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_tool_yerairogo_plugin extends restore_tool_plugin {
    /**
     * Return the paths
     *
     * @return restore_path_element[]
     */
    protected function define_course_plugin_structure() {
        $paths = [
            new restore_path_element('entry', $this->get_pathfor('/entry')),
        ];
        return $paths;
    }

    /**
     * Process a course entry
     *
     * @param array $data
     */
    public function process_entry($data) {
        global $DB;

        $data           = (object) $data;
        $courseid       = $this->task->get_courseid();
        $data->courseid = $courseid;

        tool_yerairogo\actions::insert((object) $data);
    }
}
