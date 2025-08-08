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
 * Deletes an existing entry
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');

$id = required_param('id', PARAM_INT);
require_sesskey();

$entry = tool_yerairogo\actions::get($id, 0, MUST_EXIST);
$courseid = $entry->courseid;
$params = ['id' => $id];
$contextcourse = context_course::instance($courseid);

require_login($courseid);
require_capability('tool/yerairogo:edit', $contextcourse);

$url = new moodle_url('/admin/tool/yerairogo/delete.php', $params);
$returnurl = new moodle_url('/admin/tool/yerairogo/index.php', ['id' => $courseid]);
$PAGE->set_url($url);
$PAGE->set_context($contextcourse);

tool_yerairogo\actions::delete($id);
redirect($returnurl, get_string('entrydeleted', 'tool_yerairogo'), null, \core\output\notification::NOTIFY_SUCCESS);
