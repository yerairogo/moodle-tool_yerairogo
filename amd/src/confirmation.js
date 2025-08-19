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
 * Confirms the deletion of an entry
 *
 * @module     tool_yerairogo/confirmation
 * @copyright  2025 Yerai Rodríguez <yerai.rodriguez@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import * as str from 'core/str';
import * as notification from 'core/notification';
import * as templates from 'core/templates';
import * as ajax from 'core/ajax';

/**
 * Displays the confirmation to delete the entry
 *
 * @method confirmDeletion
 * @param {Number} id
 * @param {Object} entriesList
 */
export const confirmDeletion = (id, entriesList) => {
    str.get_strings([
            {key: 'delete'},
            {key: 'confirmdeletion', component: 'tool_yerairogo'},
            {key: 'yes'},
            {key: 'no'}
        ])
        .then(function(strings) {
            return notification.confirm(strings[0], strings[1], strings[2], strings[3], function() {
                processDelete(id, entriesList);
            });
        })
        .catch(notification.exception);
};

/**
 * Calls the services to delete the entry and retrieve the entries list
 *
 * @method processDelete
 * @param {Number} id
 * @param {Object} entriesList
 */
export const processDelete = (id, entriesList) => {
    const courseid = entriesList.dataset.courseid;
    const requests = ajax.call([{
        methodname: 'tool_yerairogo_delete_entry',
        args: {id: id}
    }, {
        methodname: 'tool_yerairogo_list_entries',
        args: {courseid: courseid}
    }]);
    requests[1].then(function(data) {
        return loadList(data, entriesList);
    }).catch(notification.exception);
};

/**
 * Loads and renders the entries list
 *
 * @method loadList
 * @param {Object} data
 * @param {Object} entriesList
 */
export const loadList = (data, entriesList) => {
    templates.render('tool_yerairogo/entries_list', data).then(function(html, js) {
        return templates.replaceNodeContents(entriesList, html, js);
    }).catch(notification.exception);
};

/**
 * Binds click event to the selector
 *
 * @method onClickHandler
 * @param {String} selector
 */
export const onClickHandler = (selector) => {
    const items = document.querySelectorAll(selector);

    items.forEach((item) => {
        item.addEventListener("click", (e) => {
            e.preventDefault();
            const id = item.dataset.entryid;
            const entriesList = item.closest('.entries_list');
            confirmDeletion(id, entriesList);
        });
    });
};

/**
 * Initialises the confirmation to delete the entry
 *
 * @method init
 * @param {String} selector
 */
export const init = (selector) => {
    onClickHandler(selector);
};
