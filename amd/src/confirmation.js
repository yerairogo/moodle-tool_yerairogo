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

import * as str from "core/str";
import * as notification from 'core/notification';

export const confirmDeletion = (url) => {
    str.get_strings([
            {key: 'delete'},
            {key: 'confirmdeletion', component: 'tool_yerairogo'},
            {key: 'yes'},
            {key: 'no'}
        ])
        .done(function(strings) {
            notification.confirm(strings[0], strings[1], strings[2], strings[3], function() {
                window.location.href = url;
            });
        })
        .fail(notification.exception);
};

export const onClickHandler = (selector) => {
    const items = document.querySelectorAll(selector);

    items.forEach((item) => {
        item.addEventListener("click", (e) => {
            e.preventDefault();
            const href = item.getAttribute("href");
            confirmDeletion(href);
        });
    });
};

export const init = (selector) => {
    onClickHandler(selector);
};
