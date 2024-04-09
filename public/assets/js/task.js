(function () {
    
    window.addEventListener('load', windowLoadHandler, false);
    function windowLoadHandler() {
        console.log("load");
        reloadList();
        document.getElementById('toggle-all').addEventListener('change', toggleAllHandler, false);
        //console.log(document.getElementById('undo-all'));
        //document.getElementById('undo-all').addEventListener('change', undocheckboxHandler, false);
        document.getElementById('new-todo').addEventListener('keypress', newTodoKeyPressHandler, false);
    }
}());
