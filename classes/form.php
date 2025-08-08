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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Class tool_yerairogo_form
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_yerairogo_form extends moodleform {

    /**
     * Form definition
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('name', 'tool_yerairogo'));
        $mform->setType('name', PARAM_NOTAGS);

        $mform->addElement('advcheckbox', 'completed', get_string('completed', 'tool_yerairogo'));

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();
    }

    /**
     * Form validation
     * @param mixed $data
     * @param mixed $files
     * @return array
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);

        if ($DB->record_exists_select('tool_yerairogo',
            'name = :name AND id <> :id AND courseid = :courseid',
            ['name' => $data['name'], 'id' => $data['id'], 'courseid' => $data['courseid']])) {
            $errors['name'] = get_string('errornameexists', 'tool_yerairogo');
        }

        return $errors;
    }

}
