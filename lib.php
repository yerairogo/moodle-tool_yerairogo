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
 * @param navigation_node $parentnode The extended navigation node
 * @param stdClass $course The course object
 * @param context_course $context The context of the course
 * @return void|null Return null if we don't want to display the node
 */
function tool_yerairogo_extend_navigation_course(navigation_node $parentnode, stdClass $course, context_course $context) {
    if (has_capability('tool/yerairogo:view', $context)) {
        $parentnode->add(
            get_string('pluginname', 'tool_yerairogo'),
            new moodle_url('/admin/tool/yerairogo/index.php', ['id' => $course->id]),
            navigation_node::TYPE_SETTING,
            get_string('pluginname', 'tool_yerairogo'),
            'tool_yerairogo',
            new pix_icon('icon', '', 'tool_yerairogo')
        );
    }
}

/**
 * Checks appropiate access and permissions before serving a file.
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param context $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function tool_yerairogo_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    if ($filearea != 'entry') {
        return false;
    }

    require_login($course);
    require_capability('tool/yerairogo:edit', $context);

    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = (!$args) ? '/' : '/' . implode('/', $args) . '/';

    $fs = get_file_storage();
    if (!$file = $fs->get_file($context->id, 'tool_yerairogo', $filearea, $itemid, $filepath, $filename)) {
        return false;
    }

    send_stored_file($file, null, 0, $forcedownload, $options);
}
