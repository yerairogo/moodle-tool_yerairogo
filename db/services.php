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
 * External functions and service declaration for My first Moodle plugin
 *
 * Documentation: {@link https://moodledev.io/docs/apis/subsystems/external/description}
 *
 * @package    tool_yerairogo
 * @category   webservice
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'tool_yerairogo_delete_entry' => [
        'classname' => 'tool_yerairogo\external\delete_entry',
        'description' => 'Delete entry',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'tool/yerairogo:edit',
    ],
     'tool_yerairogo_list_entries' => [
        'classname' => 'tool_yerairogo\external\list_entries',
        'description' => 'List entries',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => 'tool/yerairogo:view',
    ],
];
