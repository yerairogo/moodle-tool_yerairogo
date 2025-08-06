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
 * Callback implementations for My first Moodle plugin
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Adds this plugin to the course administration menu
 * @param navigation_node $parentnode
 * @param stdClass $course
 * @param context_course $context
 * @return void
 */
function tool_yerairogo_extend_navigation_course(navigation_node $parentnode, stdClass $course, context_course $context) {
    $parentnode->add(
        get_string('pluginname', 'tool_yerairogo'),
        new moodle_url('/admin/tool/yerairogo/index.php', ['id' => $course->id]),
        navigation_node::TYPE_SETTING,
        get_string('pluginname', 'tool_yerairogo'),
        'tool_yerairogo',
        new pix_icon('icon', '', 'tool_yerairogo')
    );
}
