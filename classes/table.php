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

require_once($CFG->libdir . '/tablelib.php');

/**
 * Class table
 *
 * @package    tool_yerairogo
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_yerairogo_table extends table_sql {

    /** @var context_course */
    protected $context;

    /**
     * Sets up the table_log parameters
     * @param string $uniqueid unique id of form.
     * @param int $courseid
     */
    public function __construct($uniqueid, $courseid) {
        global $PAGE;

        parent::__construct($uniqueid);

        $columns = ['name', 'description', 'completed', 'priority', 'timecreated', 'timemodified'];
        $headers = [
            get_string('name', 'tool_yerairogo'),
            get_string('description', 'tool_yerairogo'),
            get_string('completed', 'tool_yerairogo'),
            get_string('priority', 'tool_yerairogo'),
            get_string('timecreated', 'tool_yerairogo'),
            get_string('timemodified', 'tool_yerairogo'),
        ];

        $this->context = context_course::instance($courseid);
        if (has_capability('tool/yerairogo:edit', $this->context)) {
            $columns[] = 'edit';
            $headers[] = '';
        }

        $this->set_attribute('id', 'tool_yerairogo_overview');
        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->pageable(true);
        $this->collapsible(false);
        $this->sortable(false);
        $this->is_downloadable(false);

        $this->define_baseurl($PAGE->url);

        $this->set_sql('id, description, descriptionformat, name, completed, priority, timecreated, timemodified',
        '{tool_yerairogo}', 'courseid = ?', [$courseid]);
    }

    /**
     * Displays column name
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_name($row) {
        return format_string($row->name, true, ['context' => $this->context]);
    }

    /**
     * Displays columns description
     * @param mixed $row
     * @return string
     */
    protected function col_description($row) {
        global $PAGE;
        $options = tool_yerairogo\actions::editor_options();
        $description = file_rewrite_pluginfile_urls($row->description, 'pluginfile.php', $PAGE->context->id, 'tool_yerairogo',
        'entry', $row->id);
        return format_text($description, $row->descriptionformat, $options);
    }

    /**
     * Displays column completed
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_completed($row) {
        return $row->completed ? get_string('yes') : get_string('no');
    }

    /**
     * Displays column priority
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_priority($row) {
        return $row->priority ? get_string('yes') : get_string('no');
    }

    /**
     * Displays column timecreated
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timecreated($row) {
        return userdate($row->timecreated, get_string('strftimedatetime'));
    }

    /**
     * Displays column timemodified
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_timemodified($row) {
        return userdate($row->timemodified, get_string('strftimedatetime'));
    }

    /**
     * Displays column edit
     *
     * @param stdClass $row
     * @return string
     */
    protected function col_edit($row) {
        $editurl = new moodle_url('/admin/tool/yerairogo/edit.php', ['id' => $row->id]);
        $deleteurl = new moodle_url('/admin/tool/yerairogo/delete.php', ['id' => $row->id, 'sesskey' => sesskey()]);
        $output = html_writer::link($editurl,
            get_string('edit') . '<br>',
            ['title' => get_string('editentrytitle', 'tool_yerairogo', format_string($row->name))]);
        $output .= html_writer::link($deleteurl, get_string('delete'), ['data-action' => 'deleteentry']);
        return $output;
    }

}
