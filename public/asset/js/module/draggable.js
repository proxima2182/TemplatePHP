/**
 * @file "draggable" 클래스를 추가하여 상호간에 자리교환이 가능하도록 하는 기능 스크립트
 */
let dragged = null;
let dropped = null;

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


jQuery.prototype.initDraggable = async function (input) {
    this.onDragFinished = input.onDragFinished;
    let items = this.find('.draggable-item');
    items.unbind('dragstart')
    items.unbind('dragend')
    items.unbind('dragover')
    items.unbind('drop')
    items.bind('dragstart', handleDragStart);
    items.bind('dragend', handleDragEnd);
    items.bind('dragover', handleDragOver);
    items.bind('drop',  handleDrop(this));
    items.css({
        cursor: 'grab'
    })
}

