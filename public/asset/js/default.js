String.prototype.toTextareaString = function() {
    return this.replace(/(\\n|\n)/g,'&#10;')
}
