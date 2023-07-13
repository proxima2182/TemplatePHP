String.prototype.toTextareaString = function() {
    return this.replace(/(\\n|\n)/g,'&#10;')
}
String.prototype.toRawString = function() {
    return this.replace(/(\&\#10\;)/g,'\n')
}
