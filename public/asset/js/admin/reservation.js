/**
 * @param id
 */
function openReservationBoardPopup(id, is_time_select = 1) {
    apiRequest({
        type: 'GET',
        url: `/api/reservation/get/${id}`,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success)
                return;
            let data = response.data;
            let className = 'popup-reservation';
            let style = `
                <style>
                .${className} .row {
                    margin: 10px;
                    line-height: 35px;
                }

                .${className} .row.bold {
                    font-weight: 400;
                }

                .${className} .row.user {
                    line-height: 30px;
                }

                .${className} .row.user a {
                    font-size: 0;
                }

                .${className} .row.user * {
                    font-size: 18px;
                    line-height: 30px;
                    vertical-align: middle;
                }

                .${className} .row-text-wrap {
                    margin: 5px;
                    text-align: left;
                    line-height: 25px;
                }

                .${className} .row-text-wrap * {
                    vertical-align: middle;
                }

                .${className} .text-wrap .comment {
                    margin: 10px 20px;
                    min-height: 100px;
                    font-size: 18px;
                    text-align: left;
                    white-space: pre-wrap;
                }
                </style>`
            let html = ``
            if (!isEmpty(data['questioner_name']) && !isEmpty(data['questioner_id'])) {
                html += `
                <div class= "row-box line-after black">
                    <div class="row bold user link">
                        <a href="javascript:openUserPopup(${data['questioner_id']});" class="button out-line">
                            <img src="/asset/images/icon/user.png"/>
                            <span>${data['questioner_name']}</span>
                        </a>
                    </div>
                </div>`
            } else if (!isEmpty(data['temp_name'])) {
                html += `
                <div class= "row-box line-after black">
                    <div class="row bold user link">
                        <img src="/asset/images/icon/user.png"/>
                        <span>${data['temp_name']}</span>
                    </div>
                </div>`
            }
            if (!isEmpty(data['temp_phone_number'])) {
                html += `
                    <div class="line-after">
                        <div class="row-text-wrap">
                            <img src="/asset/images/icon/message.png"/>
                            <span>${data['temp_phone_number']}</span>
                        </div>
                    </div>`
            }
            let hasDate = data['expect_date'] && data['expect_date'].length > 0;
            let hasTime = data['expect_time'] && data['expect_time'].length > 0;
            if (hasDate || hasTime) {
                html += `
                    <div class="line-after">
                        <div class="row-text-wrap">
                            <img src="/asset/images/icon/time.png"/>
                            <span>${hasDate ? data['expect_date'] : ''}${hasDate && hasTime ? ' ' : ''}${hasTime ? data['expect_time'] : ''}</span>
                        </div>
                    </div>`
            }
            if (data['question_comment'] && data['question_comment'].length > 0) {
                html += `
                    <div class="text-wrap">
                        <div class="comment">${data['question_comment']}</div>
                    </div>`
            }

            if (data['status'] && data['status'] != 'requested') {

                if (!isEmpty(data['respondent_id']) && !isEmpty(data['respondent_name'])) {
                    html += `
                        <div class= "row-box line-after black" style="margin-top: 20px;">
                            <div class="row bold user link">
                                <a href="javascript:openUserPopup(${data['respondent_id']});" class="button out-line">
                                    <img src="/asset/images/icon/user.png"/>
                                    <span>${data['respondent_name']}</span>
                                </a>
                            </div>
                        </div>`
                }
                if (data['status'] == 'accepted') {
                    let hasDate = data['confirm_date'] && data['confirm_date'].length > 0;
                    let hasTime = data['confirm_time'] && data['confirm_time'].length > 0;
                    if (hasDate || hasTime) {
                        html += `
                            <div class="line-after">
                                <div class="row-text-wrap">
                                    <img src="/asset/images/icon/time.png"/>
                                    <span>${hasDate ? data['confirm_date'] : ''}${hasDate && hasTime ? ' ' : ''}${hasTime ? data['confirm_time'] : ''}</span>
                                </div>
                            </div>`
                    }
                }
                if (data['respond_comment'] && data['respond_comment'].length > 0) {
                    html += `
                    <div class="text-wrap">
                        <div class="comment">${data['respond_comment']}</div>
                    </div>`
                }
            } else {
                let parameterString = '';
                if (hasDate) {
                    parameterString += `, '${data['expect_date']}'`
                } else {
                    parameterString += `, null`
                }
                if (hasTime) {
                    parameterString += `, '${data['expect_time']}'`
                } else {
                    parameterString += `, null`
                }
                html += `
                <div class="control-button-wrap absolute line-before">
                    <div class="control-button-box">
                        <a href="javascript:openReservationPopupRefuse(${data['id']});"
                            class="button under-line refuse">
                            <img src="/asset/images/icon/cancel.png"/>
                            <span>Refuse</span>
                        </a>
                        <a href="javascript:openReservationPopupAccept(${data['id']}, ${is_time_select}${parameterString})"
                            class="button under-line accept">
                            <img src="/asset/images/icon/check.png"/>
                            <span>Accept</span>
                        </a>
                    </div>
                </div>`;
            }
            openPopup({
                className: className,
                style: style,
                html: html,
            })
        },
        error: function (response, status, error) {
        },
    });
}

/**
 * 반려 시 다시한번 묻는 popup 을 여는 기능
 * @requires openPopup
 * @requires closePopup
 * @param id
 */
function openReservationPopupRefuse(id) {
    let className = 'popup-reservation-refuse';
    let style = `
        <style>
        .${className} .popup-title {
            margin: 10px 0;
        }

        body .${className} .popup {
            width: 500px;
        }

        .${className} .popup-inner .text-wrap {
            padding: 20px 0;
        }
        .${className} input, .${className} textarea {
            border: none;
            margin: 0;
            font-weight: 200;
        }

        .${className} .form-wrap .comment {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            font-size: 18px;
            font-weight: 200;
            white-space: pre-wrap;
        }

        .${className} .popup-inner .button-wrap {
            margin-top: 20px;
        }

        .${className} .popup-inner .button-wrap .button {
            min-width: 100px;
            padding: 10px 20px;
            margin: 0 10px;
        }
        </style>`
    let html = `
        <h3 class="popup-title">
            Refuse
        </h3>
        <div class="text-wrap">
            Are you sure to refuse reservation?
        </div>
        <div class="form-wrap">
            <div class="input-wrap line-before">
                <textarea placeholder="Reasons for refusals" name="respond_comment" class="comment"></textarea>
            </div>
        </div>
        <div class="button-wrap controls">
            <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
            <a href="javascript:confirmReservationRefuse('${className}', ${id})" class="button confirm black">Refuse</a>
        </div>`;
    openPopup({
        className: className,
        style: style,
        html: html,
    })
}

function openReservationPopupAccept(id, is_time_select = 1, expect_date = null, expect_time = null) {
    let className = 'popup-reservation-accept';
    let style = `
    <style>
    .${className} .popup-title {
        margin: 10px 0;
    }

    .${className} input, .${className} textarea,
    .${className} input[readonly], .${className} textarea[readonly]{
        border: none;
        margin: 0;
        font-weight: 200;
    }

    .${className} .comment {
        width: 100%;
        padding: 10px 20px;
        font-size: 18px;
        font-weight: 200;
        white-space: pre-wrap;
    }

    .${className} .title {
        line-height: 30px;
        font-size: 18px;
        font-weight: 400;
    }

    .${className} .time-selector-wrap {
        display: inline-block;
        width: 450px;
        margin-bottom: 40px;
    }

    .${className} .form-wrap .input-wrap.checkbox {
        text-align: left;
        line-height: 30px;
        margin: 5px 10px;
    }

    .${className} .form-wrap .input-wrap.checkbox * {
        vertical-align: middle;
        font-weight: 400;
    }
    </style>`
    let html = `
    <h3 class="popup-title">
        Accept
    </h3>
    <div class="form-wrap">`
    let minHeight = 150;
    if (is_time_select == 1) {
        minHeight = 100;
        html += `
            <div class= "calendar-wrap">
                <p class="title">Select Date</p>
                <div class= "calendar"></div>
            </div>
            <div class= "time-selector-wrap">
                <p class="title">Select Time</p>
                <div class= "time-selector"></div>
            </div>`;
    }
    html += `
        <div class="input-wrap checkbox">
            <input type="checkbox" id="use_default_comment" name="use_default_comment" checked onchange="onInputValueChanged(this, '${className}')"/>
            <label for="use_default_comment" class="input-title selector">Use default comment</label>
        </div>
        <div class="input-wrap line-before">
            <textarea placeholder="Comment" name="respond_comment" class="comment" readonly style="min-height: ${minHeight}px"></textarea>
        </div>
    </div>
    <div class="control-button-wrap absolute line-before">
        <div class="control-button-box">
            <a href="javascript:closePopup('${className}');"
                class="button under-line cancel">
                <img src="/asset/images/icon/cancel.png"/>
                <span>Cancel</span>
            </a>
            <a href="javascript:confirmReservationAccept('${className}', ${id})" class="button confirm">
                <img src="/asset/images/icon/check.png"/>
                <span>Confirm</span>
            </a>
        </div>
    </div>`;
    openPopup({
        className: className,
        style: style,
        html: html,
        callback: function () {
            $(`.${className} .calendar`).initCalendar({
                cellSize: 60,
                selectedDate: expect_date ?? null,
                standardDate: expect_date ?? null,
                limitStandard: false,
                limitPrevious: false,
            })
            $(`.${className} .time-selector`).initTimeSelector({
                selectedTime: expect_time ?? null,
            })
        },
    })
}

function openReservationPopupRequest(board_id, is_time_select = 1) {
    let className = 'popup-reservation-request';
    let style = `
    <style>
    .${className} .popup-title {
        margin: 10px 0;
    }

    .${className} input, .${className} textarea {
        border: none;
        margin: 0;
        font-weight: 200;
    }

    .${className} .comment {
        width: 100%;
        padding: 10px 20px;
        font-size: 18px;
        font-weight: 200;
        white-space: pre-wrap;
        white-space: pre-wrap;
    }

    .${className} .title {
        line-height: 30px;
        font-size: 18px;
        font-weight: 400;
    }

    .${className} .time-selector-wrap {
        display: inline-block;
        width: 450px;
        margin-bottom: 40px;
    }

    </style>`
    let html = `
    <h3 class="popup-title">
        Request
    </h3>
    <div class="form-wrap">`
    let minHeight = 150;
    if (is_time_select == 1) {
        minHeight = 100;
        html += `
        <div class= "calendar-wrap">
            <p class="title">Select Date</p>
            <div class= "calendar"></div>
        </div>
        <div class= "time-selector-wrap">
            <p class="title">Select Time</p>
            <div class= "time-selector"></div>
        </div>`;
    }
    html += `
        <div class="input-wrap line-before">
            <textarea placeholder="Comment" name="question_comment" class="comment" style="min-height: ${minHeight}px"></textarea>
        </div>
        <input hidden type="text" name="reservation_board_id" class="editable" value="${board_id}"/>
        <input hidden type="text" name="questioner_id" class="editable" value="${getCookie('user_id')}"/>
    </div>
    <div class="control-button-wrap absolute line-before">
        <div class="control-button-box">
            <a href="javascript:closePopup('${className}');"
                class="button under-line cancel">
                <img src="/asset/images/icon/cancel.png"/>
                <span>Cancel</span>
            </a>
            <a href="javascript:confirmReservationRequest('${className}')" class="button confirm">
                <img src="/asset/images/icon/check.png"/>
                <span>Confirm</span>
            </a>
        </div>
    </div>`;
    openPopup({
        className: className,
        style: style,
        html: html,
        callback: function () {
            $(`.${className} .calendar`).initCalendar({
                cellSize: 60,
            })
            $(`.${className} .time-selector`).initTimeSelector()
        },
    })
}

function confirmReservationRequest(className) {
    clearErrorsByClassName(className);
    let data = parseInputToData($(`.${className} input, .${className} textarea`))

    data['expect_date'] = data['date'];
    data['expect_time'] = data['time'];

    delete data['date'];
    delete data['time'];

    apiRequest({
        type: 'POST',
        url: `/api/reservation/request`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                openPopupErrors('popup-error', response, status, request);
                return;
            }
            location.reload();
        },
        error: function (response, status, error) {
            openPopupErrors('popup-error', response, status, error);
        },
    });
}

function confirmReservationAccept(className, id) {
    let data = parseInputToData($(`.${className} input, .${className} textarea`))

    data['confirm_date'] = data['date'];
    data['confirm_time'] = data['time'];

    delete data['date'];
    delete data['time'];

    apiRequest({
        type: 'POST',
        url: `/api/reservation/accept/${id}`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                openPopupErrors('popup-error', response, status, request);
                return;
            }
            location.reload();
        },
        error: function (response, status, error) {
            openPopupErrors('popup-error', response, status, error);
        },
    });
}

/**
 * 예약 반려 기능
 * @param {string}id
 */
function confirmReservationRefuse(className, id) {
    let data = parseInputToData($(`.${className} input, .${className} textarea`))
    apiRequest({
        type: 'POST',
        url: `/api/reservation/refuse/${id}`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                openPopupErrors('popup-error', response, status, request);
                return;
            }
            location.reload();
        },
        error: function (response, status, error) {
            openPopupErrors('popup-error', response, status, error);
        },
    });
}

function onInputValueChanged(element, className) {
    let elementTextarea = $(`.${className} textarea`).get(0);
    if (elementTextarea) {
        if (!element.checked) {
            elementTextarea.readOnly = false;
            elementTextarea.focus();
        } else {
            elementTextarea.readOnly = true;
            elementTextarea.value = "";
        }
    }
}
