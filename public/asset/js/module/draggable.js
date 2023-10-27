/**
 * @file "draggable" 클래스를 추가하여 상호간에 자리교환이 가능하도록 하는 기능 스크립트
 */
let dragged = null;
let dropped = null;
let $handle = null;
let $draggableItems = [];

/**
 * drag 시작 handler
 * @param event
 */
function handleDragStart(event) {
    // wait for async function in drop handler
    if (dropped) return;
    dragged = this;

    let e = (event.originalEvent || event);
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.dropEffect = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
}

/**
 * drag 종료 handler
 * drop 이 안일어 나는 경우 전역변수 dragged 를 null 로 만들기 위해 추가
 * @param event
 */
function handleDragEnd(event) {
    // wait for async function in drop handler
    if (dropped) return;
    dragged = null;
}

/**
 * drop handler
 * 데이터 변경 및 onDragFinished callback 호출
 * API 호출 후 결과에 따라 값을 반영/미반영 할 수 있으므로 await 로 호출함
 * @param event
 * @returns {Promise<function(*): boolean>}
 */
function handleDrop(parentElement) {
    return async function (event) {
        $(event.currentTarget).removeClass('active')
        let e = (event.originalEvent || event);
        e.preventDefault();

        if (dragged !== this) {
            dropped = this;
            // callback (onDragFinished) 가 비동기 작업일 경우
            // e.dataTransfer.getData 에서 값이 바르게 전달이 안되므로
            // 이벤트 발생 시 미리 변수에 값을 담아둔다
            let transferredData = e.dataTransfer.getData('text/html');
            let isExchanged;

            if (parentElement.onDragFinished && typeof parentElement.onDragFinished == 'function') {
                isExchanged = await parentElement.onDragFinished(dragged, dropped)
            } else {
                isExchanged = true;
            }
            if (isExchanged) {
                dragged.innerHTML = this.innerHTML;
                dropped.innerHTML = transferredData;
            }
            dropped = null;
            dragged = null;
        }

        return false;
    }
}

/**
 * drag over handler
 * - 예제에서 preventDefault 를 추가해 줘야 정상 작동 한다고 하여 추가
 * @param event
 */
function handleDragOver(event) {
    // prevent default to allow drop
    $(event.currentTarget).addClass('active')
    let e = (event.originalEvent || event);
    e.preventDefault();
}

function handleTouchStart(event) {
    $('.draggable-handle').remove();
    if (dropped) return;
    dragged = this;
    let targetPoint = event.originalEvent.targetTouches[0];
    $handle = $(this.cloneNode(true));

    let bounds = dragged.getBoundingClientRect();
    let offsetX = targetPoint.clientX - bounds.x;
    let offsetY = targetPoint.clientY - bounds.y;
    $handle.addClass('draggable-handle')
    $handle.attr({
        'offset-x': offsetX,
        'offset-y': offsetY,
    })
    $handle.css({
        position: 'fixed',
        width: bounds + 'px',
        left: (targetPoint.clientX - offsetX) + 'px',
        top: (targetPoint.clientY - offsetY) + 'px',
        opacity: '0.5'
    })
    $(this).parent().append($handle)
}

function handleTouchMove(event) {
    if (!dragged) return;
    if ($handle) {
        let targetPoint = event.originalEvent.targetTouches[0];
        $handle.css({
            left: (targetPoint.clientX - $handle.attr('offset-x')) + 'px',
            top: (targetPoint.clientY - $handle.attr('offset-y')) + 'px',
        })
    }
    event.preventDefault();
}

function handleTouchEnd(parentElement) {
    return async function (event) {
        $('.draggable-handle').remove();
        if (dropped) return;
        if ($handle) $handle.remove()

        let touchPoint = event.originalEvent.changedTouches[0];
        let touchX = touchPoint.clientX;
        let touchY = touchPoint.clientY;
        for (let i = 0; i < $draggableItems.length; ++i) {
            let item = $draggableItems.eq(i);
            if (item.attr('draggable-index') != dragged.getAttribute('draggable-index')) {
                let offset = item.offset();
                offset = {
                    ...offset,
                    right: offset.left + item.width(),
                    bottom: offset.top + item.height(),
                }
                offset.top -= window.scrollY;
                offset.bottom -= window.scrollY;
                offset.right -= window.scrollX;
                offset.left -= window.scrollX;
                if (offset.left < touchX && offset.right > touchX &&
                    offset.top < touchY && offset.bottom > touchY) {

                    dropped = item.get(0);
                    // callback (onDragFinished) 가 비동기 작업일 경우
                    // e.dataTransfer.getData 에서 값이 바르게 전달이 안되므로
                    // 이벤트 발생 시 미리 변수에 값을 담아둔다
                    let transferredData = dragged.innerHTML;
                    let isExchanged;

                    if (parentElement.onDragFinished && typeof parentElement.onDragFinished == 'function') {
                        isExchanged = await parentElement.onDragFinished(dragged, dropped)
                    } else {
                        isExchanged = true;
                    }
                    if (isExchanged) {
                        dragged.innerHTML = dropped.innerHTML;
                        dropped.innerHTML = transferredData;
                    }
                    dropped = null;
                    dragged = null;
                    return;
                }
            }
        }
        dragged = null;
    }
}

jQuery.prototype.initDraggable = async function (input) {
    this.onDragFinished = input.onDragFinished;
    $draggableItems = this.find('.draggable-item');
    for (let i = 0; i < $draggableItems.length; ++i) {
        $draggableItems.eq(i).attr({
            'draggable-index': i
        });
    }
    $draggableItems.unbind('dragstart')
    $draggableItems.unbind('dragend')
    $draggableItems.unbind('dragover')
    $draggableItems.unbind('drop')
    $draggableItems.unbind('touchstart')
    $draggableItems.unbind('touchmove')
    $draggableItems.unbind('touchend')
    $draggableItems.bind('dragstart', handleDragStart);
    $draggableItems.bind('dragend', handleDragEnd);
    $draggableItems.bind('dragover', handleDragOver);
    $draggableItems.bind('drop', handleDrop(this));
    $draggableItems.bind('touchstart', handleTouchStart);
    $draggableItems.bind('touchmove', handleTouchMove);
    $draggableItems.bind('touchend', handleTouchEnd(this));
    $draggableItems.css({
        'cursor': 'grab',
    })
}
