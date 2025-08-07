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
 * Main file
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('id', PARAM_INT);
$coursecontext = context_course::instance($courseid);

require_login($courseid);
require_capability('tool/yerairogo:view', $coursecontext);

$url = new moodle_url('/admin/tool/yerairogo/index.php', ['id' => $courseid]);
$PAGE->set_context($coursecontext);
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title('Hello to yerairogo plugin');
$PAGE->set_heading(get_string('pluginname', 'tool_yerairogo'));

$userscount = $DB->count_records('user');
$course = $DB->get_record('course', ['id' => $courseid]);

echo $OUTPUT->header();

echo html_writer::tag('h1', get_string('helloworld', 'tool_yerairogo'));
echo html_writer::div(get_string('courseid', 'tool_yerairogo', $courseid));

echo html_writer::div(get_string('userscount', 'tool_yerairogo', $userscount));
echo html_writer::div(get_string(
    'coursename',
    'tool_yerairogo',
    format_string($course->fullname, true, ['context' => $coursecontext])
));

// Display the table.
$table = new tool_yerairogo_table('uniqueid', $courseid);
$table->out(0, true);

echo $OUTPUT->footer();
