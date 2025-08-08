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
 * Inserts new entry or edits existing one
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');

$id = optional_param('id', 0, PARAM_INT);

// We're editing an existing entry.
if ($id) {
    $entry = tool_yerairogo\actions::get($id, 0, MUST_EXIST);
    $courseid = $entry->courseid;
    $params = ['id' => $id];
    $title = get_string('editentry', 'tool_yerairogo');
} else { // We're adding a new entry.
    $courseid = required_param('courseid', PARAM_INT);
    $entry = (object) ['courseid' => $courseid];
    $params = ['courseid' => $courseid];
    $title = get_string('newentry', 'tool_yerairogo');
}

$url = new moodle_url('/admin/tool/yerairogo/edit.php', $params);
$returnurl = new moodle_url('/admin/tool/yerairogo/index.php', ['id' => $courseid]);

$coursecontext = context_course::instance($courseid);

require_login($courseid);
require_capability('tool/yerairogo:edit', $coursecontext);

$PAGE->set_url($url);
$PAGE->set_context($coursecontext);
$PAGE->set_heading($SITE->fullname);

$form = new tool_yerairogo_form();
$form->set_data($entry);

if ($form->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $form->get_data()) {
    if ($data->id) {
        tool_yerairogo\actions::update($data);
    } else {
        tool_yerairogo\actions::insert($data);
    }

    redirect($returnurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($title);

echo $form->display();

echo $OUTPUT->footer();
