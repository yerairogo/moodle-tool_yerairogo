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

namespace tool_yerairogo\output;

use context_course;
use moodle_url;
use stdClass;
use templatable;
use renderable;
use tool_yerairogo_table;

/**
 * Class entries_list
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class entries_list implements templatable, renderable {

    /** @var int */
    protected $courseid;

    /**
     * entries_list constructor
     * @param int $courseid
     */
    public function __construct(int $courseid) {
        $this->courseid = $courseid;
    }

    /**
     * Exports entries list data to a templatable interface
     * @param \core\output\renderer_base $output
     * @return array
     */
    public function export_for_template(\core\output\renderer_base $output): array {
        $course = get_course($this->courseid);
        $context = context_course::instance($this->courseid);

        $data = [];
        $data['tooldescription'] = get_config('tool_yerairogo', 'listdescription');
        $data['courseid'] = $course->id;
        $data['coursename'] = format_string($course->fullname, true, ['context' => $context]);

        // Display the table.
        ob_start();
        $table = new tool_yerairogo_table('uniqueid', $this->courseid);
        $table->out(0, true);
        $data['entriestable'] = ob_get_clean();

        // Display the add button.
        if (has_capability('tool/yerairogo:edit', $context)) {
            $editurl = new moodle_url('/admin/tool/yerairogo/edit.php', ['courseid' => $this->courseid]);
            $data['editurl'] = $editurl->out(false);
        }

        return $data;
    }

}
