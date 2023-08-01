let onDragFinished;
let dragged = null;
let dropped = null;

function initializeDraggable(input) {
    onDragFinished = input.onDragFinished;
}

function handleDragStart(event) {
    // wait for async function in drop handler
    if (dropped) return;
    dragged = this;

    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.dropEffect = 'move';
    event.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragEnd(event) {
    // wait for async function in drop handler
    if (dropped) return;
    dragged = null;
}

async function handleDrop(event) {
    event.preventDefault();

    if (dragged !== this) {
        dropped = this;
        // callback (onDragFinished) 가 비동기 작업일 경우 event.dataTransfer.getData 에서 값이 바르게 전달이 안되므로
        // 이벤트 발생 시 미리 변수에 값을 담아둔다
        let transferredData = event.dataTransfer.getData('text/html');
        let isExchanged;

        if (onDragFinished && typeof onDragFinished == 'function') {
            isExchanged = await onDragFinished(dragged, dropped)
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

function handleDragOver(event) {
    // prevent default to allow drop
    event.preventDefault();
}

$(document).ready(function () {
    let elements = document.querySelectorAll('.draggable');
    for (let element of elements) {
        element.addEventListener('dragstart', handleDragStart)
        element.addEventListener('dragend', handleDragEnd)
        element.addEventListener('dragover', handleDragOver)
        element.addEventListener('drop', handleDrop)
    }
})
